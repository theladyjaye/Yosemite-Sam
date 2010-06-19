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

require 'AMDay.php';

class AMMonth implements Countable, Iterator
{
	const kMonthJanuary   = 1;
	const kMonthFebruary  = 2;
	const kMonthMarch     = 3;
	const kMonthApril     = 4;
	const kMonthMay       = 5;
	const kMonthJune      = 6;
	const kMonthJuly      = 7;
	const kMonthAugust    = 8;
	const kMonthSeptember = 9;
	const kMonthOctober   = 10;
	const kMonthNovember  = 11;
	const kMonthDecember  = 12;
	
	private $month;
	private $year;
	private $days_in_month;
	private $first_day_of_week;
	private $last_day_of_week;
	private $previous_month;
	private $next_month;
	private $start_of_week;
	private $end_of_week;
	private $current_day;
	
	public function __construct($month=null, $year=null, $start_of_week=AMDay::kDaySunday)
	{
		date_default_timezone_set('UTC');
		
		$this->month              = $month ? $month : date('n');
		$this->year               = $year  ? $year  : date('Y');
		$this->days_in_month      = date("t", mktime(0, 0, 0, $this->month, 1, $this->year));
		$this->first_day_of_week  = date("N", mktime(0, 0, 0, $this->month, 1, $this->year));
		$this->last_day_of_week   = date("N", mktime(0, 0, 0, $this->month, $this->days_in_month, $this->year));
		
		$this->start_of_week      = $start_of_week;
		$this->end_of_week        = $start_of_week == AMDay::kDayMonday ? AMDay::kDaySunday :  ($start_of_week - 1);
		
		$this->setPreviousMonthDaysWithinView();
		$this->setNextMonthDaysWithinView();
	}
	
	private function setPreviousMonthDaysWithinView()
	{
		if($this->first_day_of_week != $this->start_of_week)
		{
			$previous_month       = $this->month == AMMonth::kMonthJanuary ? AMMonth::kMonthDecember : ($this->month - 1);
			$previous_year        = $this->month == AMMonth::kMonthJanuary ? ($this->year - 1)       : $this->year;
			$previous_month_days  = date("t", mktime(0, 0, 0, $previous_month, 1, $previous_year));
			$this->previous_month = array();
			
			for($i = 0; $i < $this->first_day_of_week; $i++)
			{
				$day  = new AMDay($previous_year, $previous_month, ($previous_month_days - $i));
				array_unshift($this->previous_month, $day);
			}
		}
	}
	
	private function setNextMonthDaysWithinView()
	{
		if($this->last_day_of_week != $this->end_of_week)
		{
			$next_month       = $this->month == AMMonth::kMonthDecember ? AMMonth::kMonthJanuary : ($this->month + 1);
			$next_year        = $this->month == AMMonth::kMonthDecember ? ($this->year + 1)      : $this->year;
			$this->next_month = array();
			
			$day  = new AMDay($next_year, $next_month, 1);
			while($day->day_of_week != $this->end_of_week)
			{
				array_push($this->next_month, $day);
				$day  = new AMDay($next_year, $next_month, ($day->day+1));
			}
			
			// add the last day
			array_push($this->next_month, $day);
		}
	}
	
	public function count()
	{
		return $this->days_in_month;
	}
	
	function rewind() 
	{
			$this->current_day = 1;
	}

	function current() 
	{
		return new AMDay($this->year, $this->month, $this->current_day);
	}

	function key() 
	{
		return $this->current_day;
	}

	function next() 
	{
		++$this->current_day;
	}

	function valid() 
	{
		return ($this->current_day <= $this->days_in_month) ? true : false;
	}
	
	public function __get($key)
	{
		$value = null;
		
		switch($key)
		{
			case 'first_day_of_week':
			case 'last_day_of_week':
			case 'previous_month':
			case 'next_month':
				$value = $this->{$key};
				break;
		}
		return $value;
	}
	
}
?>