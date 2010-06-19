<?php
/**
 *    AxisMundi
 * 
 *    Copyright (C) 2010 Adam Venturella
 *
 *    LICENSE:
 *
 *    Licensed under the Apache License, Version 2.0 (the "License"); you may not
 *    use this file except in compliance with the License.  You may obtain a copy
 *    of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 *    This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 *    without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR 
 *    PURPOSE. See the License for the specific language governing permissions and
 *    limitations under the License.
 *
 *    Author: Adam Venturella - aventurella@gmail.com
 *
 *    @package forms
 *    @author Adam Venturella <aventurella@gmail.com>
 *    @copyright Copyright (C) 2010 Adam Venturella
 *    @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 *
 **/

$base = dirname(__FILE__);
require $base.'/validators/AMValidator.php';

interface IAMFormDelegate
{
	public function willValidateForm(&$form);
	public function didValidateForm(&$form);
}

class AMForm
{
	const kDataKey       = 'data';
	const kFilesKey      = 'files';
	const kValidatorsKey = 'validators';
	const kDelegateKey   = 'delegate';
	
	public $validators;
	
	public $formData;
	public $fileData;
	public $needsValidation = false;
	
	protected $delegate;
	protected $_isValid = true;
	
	public static function formWithContext($context)
	{
		$form = new AMForm();
		$form->setContext($context);
		return $form;
	}
	
	public function setContext($context)
	{
		$this->formData   = isset($context[AMForm::kDataKey]) ? array_map(array($this, 'prepare_form_data'), $context[AMForm::kDataKey]) : array();
		$this->fileData   = isset($context[AMForm::kFilesKey]) ? $context[AMForm::kFilesKey] : $_FILES;
		$this->validators = array();
		
		if(isset($context[AMForm::kValidatorsKey]))
		{
			$tmpFlag = true;
			foreach($context[AMForm::kValidatorsKey] as $validator)
			{
				$tmpFlag = ($tmpFlag && $validator->isValid);
				$this->addValidator($validator);
			}
			
			$this->_isValid         = $tmpFlag;
		}
		
		if(isset($context[AMForm::kDelegateKey]))
		{
			$this->setDelegate($context[AMForm::kDelegateKey]);
		}
	}
	
	private function prepare_form_data($item)
	{
		if(is_array($item))
		{
			$item = array_map(array($this, 'prepare_form_data'), $item);
		}
		else
		{
			$item = trim($item);
		}
		
		return $item;
	}
	
	public function setDelegate(IAMFormDelegate &$delegate)
	{
		$this->delegate = $delegate;
	}
	
	public function delegate()
	{
		return $this->delegate;
	}
	
	public function addValidator(AMValidator $validator)
	{
		$this->_isValid        = false;
		$this->needsValidation = true;
		
		$validator->form = $this;
		array_push($this->validators, $validator);
	}
	
	public function validate()
	{
		if($this->delegate)
			$this->delegate->willValidateForm($this);
			
		if(count($this->validators))
		{
			$this->validateForm();
		}
		else
		{
			$this->_isValid = true;
		}
		
		$this->needsValidation = false;
		
		if($this->delegate)
			$this->delegate->didValidateForm($this);
	}
	
	private function formDataForKey($key)
	{
		$value = null;
		
		if(isset($this->formData))
		{
			$keys   = explode('->', $key);
			$value  = $this->formData;
			
			while(count($keys))
			{
				if(array_key_exists($keys[0], $value))
				{
					$value = $value[array_shift($keys)];
				}
			}
		}
		else if(isset($this->fileData) && isset($this->fileData[$key]))
		{
			$value = (object) $this->fileData[$key];
		}
		
		return $value;
	}
	
	protected function validateForm()
	{
		$length  = count($this->validators);
		$tmpFlag = true;
		
		for($i = 0; $i < $length; $i++ )
		{
			$currentValidator =& $this->validators[$i];
			$currentValidator->validate();
			
			if($currentValidator->isRequired == AMValidator::kRequired)
				$tmpFlag = ($tmpFlag && $currentValidator->isValid);
		}
		
		if($tmpFlag != $this->_isValid)
			$this->_isValid = $tmpFlag;
		
		if($this->_isValid)
			$this->formWillSucceed();
		else
			$this->formWillFail();
	}
	
	protected function formWillSucceed() { }
	protected function formWillFail()
	{
		$this->clearInvalidValues();
	}
	
	protected function clearInvalidValues()
	{
		foreach($this->validators as &$validator)
		{
			if(!$validator->isValid)
			{
				if(is_array($validator->key))
				{
					foreach($validator->key as $key)
					{
						$this->formData[$key] = null;
					}
				}
				else
				{
					$this->formData[$validator->key] = null;
				}
			}
		}
	}
	
	public function __get($key)
	{
		$value = null;
		
		switch($key)
		{
			case 'isValid':
				$this->needsValidation ? $this->validate() : null;
				$value = $this->_isValid;
				break;
			
			default:
				$value = $this->formDataForKey($key);
				break;
		}
		
		return $value;
	}
}

?>