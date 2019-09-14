<?php

	/**
	 * Format a number into money
	 * @return formatted dollar amount
	 */
	function formatMoney($unformat) {

		$format = number_format($unformat, 2,'.', ',');

		//return product
		return '$' . $format;
	}

	function format_date($date)	{
		
	    return date('n\/j\/Y', strtotime($date));
	
	}

	function format_short_date($date)	{

	    return date('n\/j', strtotime($date));

	}

	function format_datetime($datetime)	{
		
	    return date('n\/j\/Y g\:i A', strtotime($datetime));
	
	}

	function format_short_notes($notes) {
		if (strlen($notes) > 20) {
			$mod = substr($notes, 0, 20);
			$mod = $mod . '... ';
			$mod = $mod . '<div class="note_popup" title="Notes" data-placement="left" data-content="' . $notes . '"><span class="glyphicon glyphicon-info-sign" style="color:#428bca;"></span></div>';
			return $mod;
		} else {
			return $notes;
		}
	}
	
	function time_difference ($time_1, $time_2) {   

	    $val_1 = new DateTime($time_1);
	    $val_2 = new DateTime($time_2);

	    $interval = $val_1->diff($val_2);
	    $year     = $interval->y;
	    $month    = $interval->m;
	    $day      = $interval->d;
	    $hour     = $interval->h;
	    $minute   = $interval->i;
	    $second   = $interval->s;

	    $output   = '';

	    if($year > 0){
	        if ($year > 1){
	            $output .= $year." years ";     
	        } else {
	            $output .= $year." year ";
	        }
	    }

	    if($month > 0){
	        if ($month > 1){
	            $output .= $month." months ";       
	        } else {
	            $output .= $month." month ";
	        }
	    }

	    if($day > 0){
	        if ($day > 1){
	            $output .= $day." days ";       
	        } else {
	            $output .= $day." day ";
	        }
	    }

	    if($hour > 0){
	        if ($hour > 1){
	            $output .= $hour." hours ";     
	        } else {
	            $output .= $hour." hour ";
	        }
	    }

	    if($minute > 0){
	        if ($minute > 1){
	            $output .= $minute." minutes ";     
	        } else {
	            $output .= $minute." minute ";
	        }
	    }

	    if($second > 0){
	        if ($second > 1){
	            $output .= $second." seconds";      
	        } else {
	            $output .= $second." second";
	        }
	    }

	    return $output;
	}	

	function getYears() {
		$start_year = 2013;
		$date = new DateTime();
		$this_year = $date->format('Y');
		$years = [];
		for ($x = $start_year; $x <= $this_year; $x++) {
		    $years []= $x; //add year to array
		} 
		return array_reverse($years);
	}
	
?>