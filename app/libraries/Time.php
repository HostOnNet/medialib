<?php

class Time
{
	public static function hms2sec($hms)
	{
		return (strtotime($hms) - strtotime('TODAY'));
	}

	public static function sec2hms($sec, $padHours = true)
	{
		$hms = "";
		$hours = intval(intval($sec) / 3600);
		$hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT) . ':' : $hours . ':';
		$minutes = intval(($sec / 60) % 60);
		$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT) . ':';
		$seconds = intval($sec % 60);
		$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
		return $hms;
	}

	public static function hmsms2ms($hms_ms) {
		$ms_part = explode('.', $hms_ms);
		$milliseconds = 0;
		if (isset($ms_part[1])) {
			$milliseconds = $ms_part[1];
		}
		return ((strtotime($hms_ms) - strtotime('TODAY'))  * 1000) + $milliseconds;
	}

}