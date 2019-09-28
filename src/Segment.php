<?php

namespace app\Calf;

use App\Calf\Mailer;
use App\Calf\CommonCalls;
use App\Calf\MoosendApiAdapter;;
use App\Calf\BuildsMoosendApiCall;

class Segment extends CommonCalls
{



	/**
	* Get all segments from given list
	*
	* @param str $mailinListId
	* @return mixed
	*/
	public static function getAllFrom($mailingListId)
	{
		$url = (new BuildsMoosendApiCall('lists', 'segments'))->add(['id' => $mailingListId])->build()->get();

		$response = MoosendApiAdapter::call($url);

		return is_null($response->Error) ? $response->Context : false;
	}

	/**
	* Get a segment given name or Id
	*
	* @param string $identifier
	* @param
	* @return stdClass $mailingList
	*/
	public static function get($identifier, $mailingListId)
	{
		return self::getIdType($identifier, $mailingListId) == 'id' ? self::getById($identifier, $mailingListId) : self::getByName($identifier, $mailingListId);
	}


	/**
	* Determine if identifier supplied is name or id
	*
	* @param string $identifier
	* @return stdClass $mailingList
	*/
	public static function getIdType($identifier)
	{
		return (substr_count($identifier,"-") === 4 && !substr_count($identifier, " ")) ? 'id' : 'name';
	}



	/**
	* Get segment using name
	*
	* @param string $listName
	* @return stdClass $mailingList or false
	*/
	public static function getByName($listName)
	{
		$url = (new BuildsMoosendApiCall('lists'))->add(['query'=>'&WithStatistics=true&ShortBy=CreatedOn&SortMethod=ASC'])->build()->get();

		$response = MoosendApiAdapter::call($url);

		$mailingLists = $response->Context->MailingLists;

		if (count($mailingLists))
		{
			foreach($mailingLists as $mailingList)
			{
				if ($mailingList->Name === $listName)
					return $mailingList;
			}
		}

		return false;
	}



	/**
	* Get the test mailing list
	* for debugging and testing
	*
	* @return MailingList
	*/
	public static function getTest()
	{
		return self::getByName(self::TEST_NAME);
	}



	/**
	* Get Resource using it's Id
	*
	* @param string $listName
	* @return stdClass $mailingList or false
	*/
	public static function getById($listId, $mailingListId)
	{
		$url = (new BuildsMoosendApiCall('lists'))->add(['query' =>'&WithStatistics=true&ShortBy=CreatedOn&SortMethod=ASC'])->build()->get();

		$response = MoosendApiAdapter::call($url);

		$mailingLists = $response->Context->MailingLists;

		if (count($mailingLists))
		{
			foreach($mailingLists as $mailingList)
			{
				if ($mailingList->ID === $listId)
					return $mailingList;
			}
		}
		return false;
	}




	/**
	* Creates a new EMPTY segment
	*
	* @param str @mailingListId
	* @param str $name
	* @param str $matchType
	* @return mixed
	*/
	/*
	Match Type can be:
	All : Only subscribers that match all given criteria will be returned by the segment.
	Any : Subscribers that match any of the given criteria will be returned by the segment.
	*/
	public static function create($mailingListId, $name, $matchType='all')
	{
		$url = (new BuildsMoosendApiCall('lists', 'create', ['segments']))->add(['id' => $mailingListId])->build()->get();

		$params = [
			'name' => $name,
			'matchType' => $matchType
		];
		$response = MoosendApiAdapter::call($url,'POST', $params,'json');

		return is_null($response->Error) ? $response->Context : false;
	}



	/**
	* Updates an existing segment.
	*
	* @param str @mailingListId
	* @param str $name
	* @param str $matchType
	* @return mixed
	*/
	public static function update($mailingListId, $segmentId, $name, $matchType='all')
	{
		$url = (new BuildsMoosendApiCall('lists', 'update', ['segments']))->add(['id' => $mailingListId, 'subId' => $segmentId])->build()->get();

		$params = [
			'name' => $name,
			'matchType' => $matchType
		];
		$response = MoosendApiAdapter::call($url,'POST', $params,'json');

		return is_null($response->Error) ? true : false;
	}



	/**
	* Adds criteria to an existing segment
	*
	* @param str @mailingListId
	* @param int $segmentId
	* @param array $criteria
	* @return mixed
	*/
	public static function addCriteria($mailingListId, $segmentId, $field, $comparer, $value, $dateFrom=null, $dateTo=null)
	{
		$segment = Segment::get($mailingList->ID, 'test');

		$url = (new BuildsMoosendApiCall('lists', 'add', ['segments', 'criteria']))->add(['id' => $mailingListId, 'subId' => $segmentId])->build()->get();

		$params = [
			'Field' => $field,
			'Comparer' => $comparer,
			'Value' => $value
		];
		if (!is_null($dateFrom))
			$params['DateFrom'] = $dateFrom;
		if (!is_null($dateTo))
			$params['DateTo'] = $dateTo;


		$response = MoosendApiAdapter::call($url,'POST', $params,'json');

		return is_null($response->Error) ? true : false;
	}






	/**
	* Updates criteria of an existing segment
	*
	* @param str @mailingListId
	* @param int $segmentId
	* @param array $criteria
	* @return mixed
	*/
	public static function updateCriteria($mailingListId, $segmentId, $criteriaId, $criteria)
	{
		$url = (new BuildsMoosendApiCall('lists', 'add', ['segments', 'criteria']))->add([
			'id' => $mailingListId,
			'subId' => $segmentId,
			'subSubId' => $criteriaId
		])->build()->get();

		if (is_array($criteria) && count($criteria) == 3)
			$params = $criteria;
		else
			MoosendApiAdapter::errorHandler('criteria doesn\'t meet requirements.');

		$response = MoosendApiAdapter::call($url,'POST', $params,'json');

		return is_null($response->Error) ? true : false;
	}







	/**
	* Get a segment ID within a mailing list
	*
	* @param str @mailingListId
	* @return mixed
	*/
	public function getSegmentId($mailingListId)
	{


	}












}