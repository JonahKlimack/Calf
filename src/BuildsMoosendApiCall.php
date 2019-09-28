<?php

namespace app\Calf;

use App\Calf\Error;

class BuildsMoosendApiCall
{

	const FORMAT = 'json';
	const BASE_URL = 'https://api.moosend.com/v3/';
	const API_PARAM = 'apikey';
	private $module;
	private $filename;
	private $commands;
	private $id;
	private $subId;
	private $subSubId;
	private $query;
	private $url;


	/**
	* Construct a new instance
	*
	* @param string $module
	* @param string $command
	* @param integer $
	* @param string $query
	* @return stdClass $mailingList
	*/
	public function __construct($module, $filename = null, $commands = [])
	{
		$this->module = $module;

		if (!is_null($filename))
			$this->filename = $filename;

		if (count($commands))
			$this->commands = $commands;

		$this->format = self::FORMAT;
	}





/**
	* Build the url with the required data
	*
	* @return string $url
	*/
	public function add($data)
	{
		dump('adding data');
		dump($data);
		foreach($data as $key => $value)
			$this->{$key} = $value;

		return $this;
	}



	/**
	* Build the url from parts
	*
	* @return string $url
	*/
	public function build()
	{
		dump('building url');
		$this->constructUrlFromParts();

		return $this;
	}





	/**
	* Get the url we built
	*
	* @return int
	*/
	public function getUrl()
	{
		if (isset($this->url))
		{
			dump('the url we built is');
			dump($this->url);
			return $this->url;
		}
		else
			return Error::handle('url is not set in BuildsMooseApiCall'.__LINE__);
	}



	/**
	* Set the type of url that we'll build
	* internal classification
	*
	* @param string $type
	* @return void
	*/
	// public function type($type)
	// {
	// 	dump('setting type');
	// 	$this->type = strtolower($type);
	// 	return $this;
	// }



	/**
	* Build the url
	*
	* @return string $url
	*/
	public function getApiParam()
	{
		return self::API_PARAM;
	}




	/**
	* Get the url we just built
	*
	* @param string $listName
	* @return stdClass $mailingList
	*/
	public function get()
	{
		return $this->getUrl();
	}


	/**
	* Build the url from all the parts
	*
	* @return string $url
	*/

	// curl_setopt($ch, CURLOPT_URL, "https://api.moosend.com/v3/lists/{MailingListID}/segments/{SegmentID}/criteria/add.{Format}?apikey=");

	public function constructUrlFromParts()
	{
		$this->url = self::BASE_URL.$this->module;

		if (isset($this->id))
			$this->url .= "/".$this->id;

		if (count($this->commands) >= 1)
			$this->url .= "/".$this->commands[0];

		if (isset($this->subId))
			$this->url .= "/".$this->subId;

		if (count($this->commands) >= 2)
			$this->url .= "/".$this->commands[1];

		if (isset($this->subSubId))
			$this->url .= "/".$this->subSubId;

		if (count($this->commands) == 3)
			$this->url .= "/".$this->commands[2];

		if (isset($this->filename))
			$this->url .= "/".$this->filename;

		$this->url .= '.'.self::FORMAT."?".self::API_PARAM.'='.env('MOOSEND_API_KEY');

		if (isset($this->query))
			$this->url .= $this->query;
	}


}