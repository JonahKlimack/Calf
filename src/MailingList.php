<?php

namespace app\Calf;

use App\Calf\Error;
use App\Calf\Mailer;
use App\Calf\CommonCalls;
use App\Calf\MoosendApiAdapter;
use App\Calf\BuildsMoosendApiCall;

class MailingList extends CommonCalls
{

	/**
	* Get a mailing list given name or Id
	*
	* @param string $identifier
	* @param
	* @return stdClass $mailingList
	*/
	public static function get($identifier)
	{
		return self::getIdType($identifier) == 'id' ? self::getById($identifier) : self::getByName($identifier);
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
	* Get Mailing List using name
	*
	* @param string $listName
	* @return stdClass $mailingList or false
	*/
	// 9/23/19 WARNING only checks first page of paging results
	// so if user has more than 100 mailing lists, this won't work
	public static function getByName($listName)
	{
		$url = (new BuildsMoosendApiCall('lists'))->add(['query'=>'&WithStatistics=true&ShortBy=CreatedOn&SortMethod=ASC&Page=1&PageSize=100'])->build()->get();

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
	public static function getById($listId, $subId=null)
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
	* Get Resource using it's Id
	*
	* @param string $listName
	* @return stdClass $mailingList or false
	*/
	public static function getAll()
	{
		$url = (new BuildsMoosendApiCall('lists'))->add(['query' =>'&WithStatistics=true&ShortBy=CreatedOn&SortMethod=ASC'])->build()->get();

		$response = MoosendApiAdapter::call($url);

		return isset($response) ? $response->Context->MailingLists : Error::handle('could not get all mailing lists got error or null from moosend');
	}



	/**
	* Create a mailing list and return the ID
	* error if somehow error from moose
	*
	* @param string $listName
	* @param string $confirmationPage
	* @param string $unSubRedirectUrl
	* @return string $mailingListId
	*/
	public static function create(
		$name,
		$confirmationPage = 'http://killthespammer.com/confirm',
		$unSubRedirectUrl = 'http://killthespammer.com')
	{
		if (is_object(MailingList::getByName($name)))
			return Error::handle('List with name: "'.$name.'" already exists. Try another name.');

		$params = [
			'Name' => $name,
			'ConfirmationPage' => $confirmationPage,
			'RedirectAfterUnsubscribePage' => $unSubRedirectUrl
		];
		$url = (new BuildsMoosendApiCall('lists', 'create'))->build()->get();

		$result = MoosendApiAdapter::call($url, 'POST', $params, 'json');

		return isset($result) ? self::get($result->Context) : Error::handle('could not create mailing list got error or null from moosend');
	}




	/**
	* Get a mailing list, if doesn't exist, create it
	*
	* @param string $identifier
	* @return stdClass $mailingList
	*/
	public static function getOrCreate($identifier)
	{
		return self::get($identifier) ?: self::create($identifier);
	}







	/**
	* Delete the mailing list using id or name
	*
	* @param $key (type of Id)
	* @param $value (value of id)
	* @return bool
	*/
	public static function delete($identifier)
	{
		$mailingList = self::get($identifier);
		dump('temp mailing list to delete is');
		dump($mailingList);

		$url = (new BuildsMoosendApiCall('lists', 'delete'))->add(['id' => $mailingList->ID])->build()->get();

		$response = MoosendApiAdapter::call($url,'GET',[],'delete');
		dump('response from deleting mailing list');
		dump($response);

		return is_null($response) ? true : false;
	}









	/**
	* Create a custom field for the form that builds mailing list
	*
	* @param string $listName
	* @return stdClass $mailingList or false
	*/
	public static function createCustomField($mailingListIdentifier, $name, $customFieldType, $options=null, $isRequired=false, $isHidden=false)
	{
		$mailingList = self::get($mailingListIdentifier);

		$url = (new BuildsMoosendApiCall('lists', 'create', ['customfields']))->add(['id' => $mailingList->ID])->build()->get();


		$params = [
			'name' => func_get_arg(1),
			'CustomFieldType' => func_get_arg(2),
		];
		if (isset($options))
			$params['options'] = func_get_arg(3);
		if ($isRequired)
			$params['isRequired'] = func_get_arg(4);
		if ($isHidden)
			$params['isHidden'] = func_get_arg(5);


		$result = MoosendApiAdapter::call($url,'POST', $params, 'json');

		return isset($result) ? $result->Context : Error::handle('could not create custom field got error or null from moosend');

	}






	/**
	* Update a custom field for the form that builds mailing list
	*
	* @param string $listName
	* @return stdClass $mailingList or false
	*/
	public static function updateCustomField($mailingListIdentifier, $customFieldId, $name, $customFieldType, $options=null, $isRequired=false, $isHidden=false)
	{
		$mailingList = self::get($mailingListIdentifier);

		$url = (new BuildsMoosendApiCall('lists', 'update', ['customfields']))->add(['id' => $mailingList->ID, 'subId' => $customFieldId])->build()->get();

		$params = [
			'name' => func_get_arg(2),
			'CustomFieldType' => func_get_arg(3),
		];
		if (isset($options))
			$params['options'] = func_get_arg(4);
		if ($isRequired)
			$params['isRequired'] = func_get_arg(5);
		if ($isHidden)
			$params['isHidden'] = func_get_arg(6);


		$result = MoosendApiAdapter::call($url,'POST', $params, 'json');

		return isset($result) ? $result->Context : Error::handle('could not update custom field got error or null from moosend');


	}




	/**
	* Update a custom field for the form that builds mailing list
	*
	* @param string $listName
	* @return stdClass $mailingList or false
	*/
	public static function deleteCustomField($mailingListIdentifier, $customFieldId)
	{
		$mailingList = self::get($mailingListIdentifier);

		$url = (new BuildsMoosendApiCall('lists', 'delete', ['customfields']))->add(['id' => $mailingList->ID, 'subId' => $customFieldId])->build()->get();


		$result = MoosendApiAdapter::call($url,'GET');

		return isset($result) ? $result->Context : Error::handle('could not delete custom field got error or null from moosend');
	}






}