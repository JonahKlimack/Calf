<?php

namespace app\Calf;

use App\Calf\Mailer;
use App\Calf\Segment;
use App\Calf\BuildsMoosendApiCall;

class SegmentFactory
{
	private $segment;

	/**
	* Initialize by creating a segment
	*
	* @param str @mailingListId
	* @param str $name
	* @param str $matchType
	* @return mixed
	*/
	public function __construct($mailingListId, $name, $matchType='all')
	{
		// dump($mailingListId);
		// dump($name);
		// dump($matchType);
		$segmentId = Segment::create($mailingListId, $name,$matchType);
		// dump('after segment call');
		// dd($segmentId);

		//must set field, comparer, value...

		if (is_int($segmentId))
		{
			$this->segment = Segment::get($segmentId);
			dump($this->segment);
			dump('hi');
			$this->mailingList = MailingList::get($mailingListId);
		}
		else
			Error::handle('We got an error when attempting to create a segment');

	}
	// public function addCriteria($field, $comparer, $value)
	// {

	// }

	/**
	* Add Criteria
	*
	* @param str $field
	* @param str $comparer
	* @param str $value
	* @return str $segmentId
	*/
	public function addCriteria($field, $comparer, $value)
	{
		return Segment::addCriteria($this->mailingList->ID, $this->segment->ID, $field, $comparer, $value);
	}

	/**
	* Create a segment with random subscribers for a mailing
	*
	* @param str @mailingListId
	* @param str $name
	* @param str $matchType
	* @return mixed
	*/
	// public function createRandom($field, $comparer, $value)
	// {

	// }





	/**
	* Get the segment we just created
	*
	* @return mixed
	*/
	public function get()
	{
		return $this->segment;
	}

}
