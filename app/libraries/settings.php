<?php

class Settings {

	public static function get($name) {
		$result = Setting::where('name','=',$name)->first();

		if(!empty($result)) {
			return $result->value;
		} else {
			return '';
		}
	}

	public static function put($name, $value) {
		$result = Setting::where('name','=',$name)->first();

		if (empty($result)) {
			DB::table('settings')->insert(array('name' => $name, 'value'=>$value ));
		} else {
			Setting::where('name','=',$name)->update(array('value' => "$value"));
		}
	}

}