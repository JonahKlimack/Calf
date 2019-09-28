<?php

namespace app\Calf;


class Error
{

	/**
	* Handle this error
	*
	* @param string $message
	* @return void
	*/
	public static function handle($msg)
	{
		Error::pretty($msg);

		//right now, just dying, no handlers
		// die;
	}


	/**
	* Output pretty message to user
	* (for now just dying )
	*
	* @param string $message
	* @return void
	*/
	public static function pretty($msg)
	{
		echo "<h1>Error</h1>";

		dump($msg);

	}



	/**
	* Output information about the last result
	*
	* @param string $message
	* @return void
	*/
	public static function info($msg)
	{
		echo "<h1>Information</h1>";

		dump($msg);

	}

}