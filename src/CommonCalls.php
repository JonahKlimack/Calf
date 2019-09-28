<?php

namespace app\Calf;

use App\Calf\Error;
use App\Calf\Mailer;
use App\Calf\MoosendApiAdapter;
use App\Calf\BuildsMoosendApiCall;


abstract class CommonCalls
{

	const TEST_NAME = 'test';


	/**
	* Which child class called me?
	*
	* @return str $childClassName
	*/
	public static function getChildName()
	{
		$path = explode('\\', static::class);
		return array_pop($path);
	}

}