<?php

namespace app\Calf;


use App\Calf\Mailer;
use App\Calf\CommonCalls;
use App\Calf\MoosendApiAdapter;
use App\Calf\BuildsMoosendApiCall;

class Subscriber extends CommonCalls
{


	/**
	* Get all subscribers from given list
	*
	* @param str $mailinListId
	* @return mixed
	*/
	public static function getAllFrom($mailingListId, $status='subscribed', $page=1, $perPage=100)
	{
		$url = (new BuildsMoosendApiCall('lists', $status,['subscribers']))->add(['id' => $mailingListId, 'query' => '&Page='.$page.'&PageSize='.$perPage])->build()->get();

		$response = MoosendApiAdapter::call($url);

		return is_null($response->Error) ? $response->Context->Subscribers : false;
	}



	/**
	* Get a subscriber given email address or Id
	*
	* @param string $identifier
	* @param
	* @return stdClass $mailingList
	*/
	public static function get($mailingListId, $identifier)
	{
		return self::getIdType($identifier) == 'id' ? self::getById($mailingListId, $identifier) : self::getByEmail($mailingListId, $identifier);
	}



	/**
	*
	* @param string $identifier
	* @return stdClass $mailingList
	*/
	public static function getIdType($identifier)
	{
		return (substr_count($identifier,"-") === 4 && !substr_count($identifier, " ")) ? 'id' : 'email';
	}






	/**
	* Get a subscriber given email address
	*
	* @param str $mailinListId
	* @return mixed
	*/
	public static function getByEmail($mailingListId, $email)
	{
		$url = (new BuildsMoosendApiCall('subscribers', 'view'))->add(['id' => $mailingListId, 'query' => '&email='.$email])->build()->get();

		$response = MoosendApiAdapter::call($url);

		return is_null($response->Error) ? $response->Context : false;

	}




	/**
	* Get a subscriber given email address
	*
	* @param str $mailinListId
	* @return mixed
	*/
	public static function getById($mailingListId, $subscriberId)
	{
		$url = (new BuildsMoosendApiCall('subscribers', $subscriberId,['find']))->add(['id' => $mailingListId])->build()->get();

		$response = MoosendApiAdapter::call($url);

		return is_null($response->Error) ? $response->Context : false;

	}


	/**
	* Add a subscriber to a mailing list.
	*
	* @param str $mailingListId
	* @param str $name
	* @param str $email
	* @param bool $extDoubleOptIn
	* @param arr $customFields
	* @return mixed
	*/
	public static function add($mailingListId, $name,$email,$extDoubleOptIn=false,$customFields=[])
	{
		$url = (new BuildsMoosendApiCall('subscribers', 'subscribe'))->add(['id' => $mailingListId])->build()->get();

		$params = [
			'Name' => $name,
			'Email' => $email,
			'HasExternalDoubleOptIn' => $extDoubleOptIn,
			"CustomFields" => $customFields
		];
		$response = MoosendApiAdapter::call($url,'POST', $params, 'json');

		return is_null($response->Error) ? $response->Context : false;
	}




	/**
	* add many subscribers at once
	*
	* @param str $mailingListId
	* @param str $email
	* @return mixed
	*/
	public static function addMany($mailingListId, $subscribers, $optIn=false)
	{
		$url = (new BuildsMoosendApiCall('subscribers', 'subscribe_many'))->add(['id' => $mailingListId])->build()->get();

		$params = [
			'HasExternalDoubleOptIn' => $optIn,
			'Subscribers' => $subscribers
		];
		$response = MoosendApiAdapter::call($url,'POST', $params, 'json');


		return is_null($response->Error) ? $response->Context : false;
	}



/**
	* add or update a subscriber
	*
	* @param str $mailingListId
	* @param str $subscriberId
	* @param str $name
	* @param str $email
	* @param bool $extDoubleOptIn
	* @param arr $customFields
	* @return mixed
	*/
	public static function addOrUpdate($mailingListId, $subscriberId, $name,$email,$extDoubleOptIn=false,$customFields=[])
	{
		$url = (new BuildsMoosendApiCall('subscribers', $subscriberId, ['update']))->add(['id' => $mailingListId, 'subscriberId' => $subscriberId])->build()->get();

		$params = [
			'Name' => $name,
			'Email' => $email,
			'HasExternalDoubleOptIn' => $extDoubleOptIn,
			"CustomFields" => $customFields
		];
		$response = MoosendApiAdapter::call($url,'POST', $params, 'json');

		return is_null($response->Error) ? $response->Context : false;
	}




/**
	* unsubscribe someone from all mailing lists
	* given email
	*
	* @param str $email
	* @return mixed
	*/
	public static function unsubFromAll($email)
	{
		$url = (new BuildsMoosendApiCall('subscribers', 'unsubscribe'))->build()->get();

		$params = [
			'Email' => $email,
		];
		$response = MoosendApiAdapter::call($url,'POST', $params, 'json');

		return is_null($response->Error) ? true : false;
	}




/**
	* unsubscribe someone from given list
	* given email
	*
	* @param str $mailingListId
	* @param str $email
	* @return mixed
	*/
	public static function unsubFromList($mailingListId, $email)
	{
		$url = (new BuildsMoosendApiCall('subscribers', 'unsubscribe'))->add(['id' => $mailingListId])->build()->get();

		$params = [
			'Email' => $email,
		];
		$response = MoosendApiAdapter::call($url,'POST', $params, 'json');

		return is_null($response->Error) ? true : false;
	}




/**
	* unsubscribe someone from given campaign
	* given email
	*
	* @param str $mailingListId
	* @param str $CampaignId
	* @param str $email
	* @return mixed
	*/
	public static function unsubFromCampaign($mailingListId, $campaignId, $email)
	{
		$url = (new BuildsMoosendApiCall('subscribers', 'unsubscribe'))->add(['id' => $mailingListId, 'subId' => $campaignId])->build()->get();

		$params = [
			'Email' => $email,
		];
		$response = MoosendApiAdapter::call($url,'POST', $params, 'json');

		//success returns null response->Error with no $response->Context=null
		return is_null($response->Error) ? true : false;
	}





/**
	* Remove subscriber completely (not on suprresion list)
	*
	* @param str $mailingListId
	* @param str $email
	* @return mixed
	*/
	public static function remove($mailingListId, $email)
	{
		$url = (new BuildsMoosendApiCall('subscribers', 'remove'))->add(['id' => $mailingListId])->build()->get();

		$params = [
			'Email' => $email,
		];
		$response = MoosendApiAdapter::call($url,'POST', $params, 'json');

		return is_null($response->Error) ? true : false;
	}






/**
	* remove many subscribers at once completely
	*
	* @param str $mailingListId
	* @param array $emails
	* @return mixed
	*/
	public static function removeMany($mailingListId, $emails)
	{
		$url = (new BuildsMoosendApiCall('subscribers', 'remove_many'))->add(['id' => $mailingListId])->build()->get();

		$params = [
			'emails' => $emails
		];
		$response = MoosendApiAdapter::call($url,'POST', $params, 'json');

		return is_null($response->Error) ? $response->Context : false;
	}

}