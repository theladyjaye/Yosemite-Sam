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
 *    @package date
 *    @author Adam Venturella <aventurella@gmail.com>
 *    @copyright Copyright (C) 2010 Adam Venturella
 *    @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 *
 **/
class AMDay
{
	const kDayMonday      = 1;
	const kDayTuesday     = 2;
	const kDayWednesday   = 3;
	const kDayThursday    = 4;
	const kDayFriday      = 5;
	const kDaySaturday    = 6;
	const kDaySunday      = 7;
	
	private $year;
	private $month;
	private $day;
	private $day_of_week;
	
	public function __construct($year, $month, $day)
	{
		$this->day         = $day;
		$this->month       = $month;
		$this->year        = $year;
		$this->day_of_week = date("N", mktime(0, 0, 0, $this->month, $this->day, $this->year));
	}
	
	public function __get($key)
	{
		switch($key)
		{
			case 'day':
			case 'month':
			case 'year':
			case 'day_of_week':
				return $this->{$key};
				break;
		}
	}
}

?>