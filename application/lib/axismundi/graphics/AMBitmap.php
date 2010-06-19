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
 *    @package graphics
 *    @author Adam Venturella <aventurella@gmail.com>
 *    @copyright Copyright (C) 2010 Adam Venturella
 *    @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 *
 **/
require 'AMBitmapDimensions.php';

class AMBitmap
{

	public $bitmapData;
	public $witdh;
	public $height;
	public $type;
	public $quality;
	
	private $temp_file;
	
	public static function bitmapWithUrl($url)
	{
		$instance          = new AMBitmap();
		$instance->quality = 100;
		
		// first we need to get the remote file
		if(strpos($url, 'http://') === 0){
			$url = $instance->getRemoteFile($url);
		}
		
		list($instance->width, $instance->height, $instance->type) = getimagesize($url);
		
		switch ($instance->type)
		{
			case IMAGETYPE_GIF:
				$instance->bitmapData = imagecreatefromgif($url);
				break;
				
			case IMAGETYPE_JPEG:
				$instance->bitmapData = imagecreatefromjpeg($url);
				break;
				
			case IMAGETYPE_PNG:
				$instance->bitmapData = imagecreatefrompng($url);
				break;
		}

		return $instance;
	}
	
	public function __construct($width=null, $height=null, $type=null, $quality=null, $bitmapData=null)
	{
		$this->width      = $width;
		$this->height     = $height;
		$this->type       = $type;
		$this->quality    = $quality;
		$this->bitmapData = $bitmapData;
	}
	
	public function dimensions()
	{
		 return new AMBitmapDimensions($this->width, $this->height, AMBitmapDimensions::kTransformNone, $this->quality);
	}
	
	private function getRemoteFile($url)
	{
		$data            = file_get_contents($url, FILE_BINARY);
		$this->temp_file = tempnam(sys_get_temp_dir(), null);
		$resource        = fopen($this->temp_file, "wb");
		fwrite($resource, $data);
		fclose($resource);
		
		return $this->temp_file;
	}
	
	public function getBounds()
	{
		//(left, upper, right, lower)
		return array(0, 0, $this->width, $this->height);
	}
	
	//                 0     1      2      3
	// array $bounds (left, upper, right, lower)
	public function crop($bounds)
	{
		$width  = $bounds[2] - $bounds[0];
		$height = $bounds[3] - $bounds[1];
		
		$cropped = imagecreatetruecolor($width, $height);
		imagecopyresampled ($cropped, $this->bitmapData, 0, 0, $bounds[0], $bounds[1], $bounds[2], $bounds[3], $bounds[2], $bounds[3]); 

		return new AMBitmap($width, $height, $this->type, 80, $cropped);
	}

	public function resize(AMBitmapDimensions $dimensions)
	{	
		// constrain porportionally to width
		/*if (!$dimensions->height)
		{
			$dimensions->height = $this->calculatePorportion($dimensions->width, $this->width, $this->height); //ceil($a1 / $a * $b);
		}
		// constrain porportionally to height
		else if (! $dimensions->width)
		{
			$dimensions->width = $this->calculatePorportion($dimensions->height, $this->height, $this->width); //ceil($a1 / $a * $b);
		}*/

		//if ($dimensions->transformationHint)
		//{
			$bounds = $this->getBounds();
			switch ($dimensions->transformationHint)
			{
				case AMBitmapDimensions::kTransformCropFitHeight:
					$this->cropToHeight($bounds, $dimensions);
					$this->setValuesWithBitmap($this->crop($bounds));
					break;
				
				case AMBitmapDimensions::kTransformCropFitWidth:
					$this->cropToWidth($bounds, $dimensions);
					$this->setValuesWithBitmap($this->crop($bounds));
					break;
				
				case AMBitmapDimensions::kTransformCropFit:
					$this->cropToFit($bounds, $dimensions);
					$this->setValuesWithBitmap($this->crop($bounds));
					break;
				
				case AMBitmapDimensions::kTransformDynamicFit:
					$this->dynamicResize($bounds, $dimensions);
					break;
				
				case AMBitmapDimensions::kTransformStaticFit:
					break;
				
				case AMBitmapDimensions::kTransformProportionalWidth:
					$dimensions->width = $this->calculatePorportion($dimensions->height, $this->height, $this->width);
					break;
						
				case AMBitmapDimensions::kTransformProportionalHeight:
					$dimensions->height = $this->calculatePorportion($dimensions->width, $this->width, $this->height);
					break;
				
				default:
					break;
			}
		//}
		
		switch ($this->type)
		{
			case IMAGETYPE_GIF:
				return $this->resizeGIF($dimensions);
				break;

			case IMAGETYPE_JPEG:
				return $this->resizeJPG($dimensions);
				break;
			
			case IMAGETYPE_PNG:
				return $this->resizeJPG($dimensions);
				break;
		}
	}
	
	private function setValuesWithBitmap(AMBitmap $a)
	{
		$this->width      = $a->width;
		$this->height     = $a->height;
		$this->bitmapData = $a->bitmapData;
		$this->type       = $a->type;
		$this->quality    = $a->quality;
	}
	
	private function dynamicResize(&$bounds, AMBitmapDimensions &$dimensions)
	{
		if($this->width >= $this->height){
			$dimensions->height = $this->calculatePorportion($dimensions->width, $this->width, $this->height);
		}
		else if ($this->height > $this->width){
			$dimensions->width = $this->calculatePorportion($dimensions->height, $this->height, $this->width);
		}
	}

	private function cropToHeight(&$bounds, AMBitmapDimensions &$dimensions)
	{
		$aspectRatio = $dimensions->width / $dimensions->height;
		$newHeight = $this->width / $aspectRatio;
		
		$left  = 0;
		$upper = ($this->height - $newHeight) / 2;
		$right = $this->width;
		$lower = $this->height - $upper;
		
		$bounds = array(floor($left), floor($upper), floor($right), floor($lower));
	}

	private function cropToWidth(&$bounds, AMBitmapDimensions &$dimensions)
	{
		$aspectRatio = $dimensions->width / $dimensions->height;
		$newWidth = $this->height * $aspectRatio;
		
		$left  = ($this->width - $newWidth) / 2;
		$upper = 0;
		$right = $this->width - $left;
		$lower = $this->height;
		
		$bounds = array(floor($left), floor($upper), floor($right), floor($lower));
	}

	private function cropToFit(&$bounds, AMBitmapDimensions &$dimensions)
	{	
		$aspectRatio = $dimensions->width / $dimensions->height;
		$newHeight = $this->width / $aspectRatio;
		
		if($newHeight > $this->height)
			$this->cropToWidth($bounds, $dimensions);			
		else
			$this->cropToHeight($bounds, $dimensions);			
	}

	private function resizeGIF(AMBitmapDimensions $dimensions)
	{
		$resize = imagecreatetruecolor($dimensions->width, $dimensions->height); 
		imagecopyresampled ($resize, $this->bitmapData, 0, 0, 0, 0, $dimensions->width, $dimensions->height, $this->width, $this->height); 
		
		//imagecopyresized($resize, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 
		
		return new AMBitmap($dimensions->width, $dimensions->height, $this->type, $dimensions->quality, $resize);
		
	}
	
	private function resizeJPG(AMBitmapDimensions $dimensions)
	{
		$resize = imagecreatetruecolor($dimensions->width, $dimensions->height); 
		imagecopyresampled ($resize, $this->bitmapData, 0, 0, 0, 0, $dimensions->width, $dimensions->height, $this->width, $this->height); 
		
		//imagecopyresized($resize, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 
		
		return new AMBitmap($dimensions->width, $dimensions->height, $this->type, $dimensions->quality, $resize);
	}
	
	private function calculatePorportion($a1, $a, $b)
	{	
		return ceil($a1 / $a * $b);
	}
	
	public function draw()
	{
		switch ($this->type)
		{
			case IMAGETYPE_GIF:
				return imagegif($this->bitmapData);
				break;

			case IMAGETYPE_JPEG:
				return imagejpeg($this->bitmapData);
				break;
			
			case IMAGETYPE_PNG:
				return imagepng($this->bitmapData);
				break;
				
		}
	}
	
	public function write($filenameWithPath)
	{
		$status = false;
		switch ($this->type)
		{
			case IMAGETYPE_GIF:
				$status = imagegif($this->bitmapData, $filenameWithPath);
				break;
			
			case IMAGETYPE_JPEG:
				$status = imagejpeg($this->bitmapData, $filenameWithPath, $this->quality);
				break;
			
			case IMAGETYPE_PNG:
				$status = imagepng($this->bitmapData, $filenameWithPath);
				break;
		}
		
		return $status;
	}
	
	public function __destruct()
	{
		if($this->temp_file && 
		   file_exists($this->temp_file) && 
		   strpos($this->temp_file, sys_get_temp_dir()) !== false)
		{
			unlink($this->temp_file);
		}
	}
}
?>
