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
class AMBitmapDimensions
{
	const kTransformNone               = 0x00;
	const kTransformCropFit            = 0x01;
	const kTransformCropFitWidth       = 0x02;
	const kTransformCropFitHeight      = 0x03;
	const kTransformDynamicFit         = 0x04;
	const kTransformStaticFit          = 0x05;
	const kTransformProportionalWidth  = 0x06;
	const kTransformProportionalHeight = 0x07;
	
	public $width;
	public $height;
	public $quality;
	public $transformationHint;
	
	public function __construct($width = null, $height = null, $transformationHint = AMBitmapDimensions::kTransformNone, $quality = 90)
	{
		$this->width      = $width  ? (int)  $width  : null;
		$this->height     = $height ? (int)  $height : null;
		$this->quality    = (int)  $quality;
		$this->transformationHint = $transformationHint;
	}
}
?>