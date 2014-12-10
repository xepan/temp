<?php

class xDate extends AbstractController{
	public $default_date_start = '1970-01-01 00:00:00';
	public $default_date_end = '1970-01-01 00:00:00';

	function diff($start_date, $end_date){
		
		return (Carbon::createFromFormat("Y-m-d H:i:s",$end_date ?:$this->default_date_end)->diffForHumans(Carbon::createFromFormat("Y-m-d H:i:s",$start_date ?:$this->default_date_start)));
	}
}