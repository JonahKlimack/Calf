<?php
namespace app\Helpers\Mailer;

use App\Calf\Mailer;
use App\Calf\CommonCalls;
use App\Calf\MoosendApiAdapter;
use App\Calf\BuildsMoosendApiCall;

class Campaign extends CommonCalls
{

	/**
	* Creates a draft campaign ready to send
	* or split test
	*
	* @param
	* @return mixed
	*/
	public static function create($name, $subject, $senderEmail, $replyToEmail)
	{
		$url = (new BuildsMoosendApiCall('campaigns', 'create'))->build()->get();

		$params = [
			'Name' => $name,
			'Subject' => $subject,
			'SenderEmail' => $senderEmail,
			'ReplyToEmail' => $replyToEmail
		];
		$response = MoosendApiAdapter::call($url,'POST', $params,'json');

		return is_null($response->Error) ? $response->Context : false;
	}






	/**
	* clones an existing campaign
	*
	* @param str $campaignId
	* @return mixed
	*/
	public static function clone($campaignId)
	{
		$url = (new BuildsMoosendApiCall('campaigns', 'clone'))->add(['id' => $campaignId])->build()->get();

		$response = MoosendApiAdapter::call($url) ;

		return is_null($response->Error) ? $response->Context : false;
	}






	/**
	* Get a list of all campaigns wit
	* detailed information
	*
	* @return mixed
	*/
	public static function getAll()
	{
		$url = (new BuildsMoosendApiCall('campaigns'))->build()->get();

		$response = MoosendApiAdapter::call($url);

		return is_null($response->Error) ? $response->Context : false;
	}








	/**
	* Get a list of all campaigns paginated
	*
	* @param int $page
	* @return mixed
	*/
	public static function getAllByPage($page)
	{
		$url = (new BuildsMoosendApiCall('campaigns'))->add(['page' => $page])->build()->get();

		$response = MoosendApiAdapter::call($url);

		return is_null($response->Error) ? $response->Context : false;
	}





	/**
	* Get a list of all campaigns paginated
	* specify limit per page
	*
	* @param int $page
	* @param int $pageSize
	* @return mixed
	*/
	public static function getAllByPageSize($page, $pageSize)
	{
		$url = (new BuildsMoosendApiCall('campaigns'))->add([
			'page' => $page,
			'pageSize' => $pageSize,
			'query' => 'ShortBy=CreatedOn&SortMethod=ASC',
		])->build()->get();

		$response = MoosendApiAdapter::call($url);

		return is_null($response->Error) ? $response->Context : false;
	}





	/**
	* Get detailed info about a campaign
	*
	* @param str $campaignId
	* @return mixed
	*/
	public static function details($campaignId)
	{
		$url = (new BuildsMoosendApiCall('campaigns', 'view'))->add(['id' => $campaignId])->build()->get();

		$response = MoosendApiAdapter::call($url);

		return is_null($response->Error) ? $response->Context : false;

	}





	/**
	* Get the campaign statistics
	*
	* @param str $campaignId
	* @return mixed
	*/
	public static function stats($campaignId)
	{
		$url = (new BuildsMoosendApiCall('campaigns', 'stats', 'Type'))->add(['id' => $campaignId])->build()->get();

		$response = MoosendApiAdapter::call($url);

		return is_null($response->Error) ? $response->Context : false;
	}




	/**
	* Get the campaign statistics
	*
	* @param str $campaignId
	* @return mixed
	*/
	public static function activityBylocation($campaignId)
	{
		$url = (new BuildsMoosendApiCall('campaigns', 'stats', 'count'))->add(['id' => $campaignId])->build()->get();

		$response = Mailer::call($url);

		return is_null($response->Error) ? $response->Context : false;
	}


	/**
	* Get the campaign statistics
	*
	* @param str $campaignId
	* @return mixed
	*/
	public static function linkActivity($campaignId)
	{
		$url = (new BuildsMooseApiCall('campaigns', 'stats', 'links'))->add(['id' => $campaignId])->build()->get();

		$response = Mailer::call($url);

		return is_null($response->Error) ? $response->Context : false;
	}


	/**
	* Schedule a campaign.
	*
	* @param str $campaignId
	* @param arr $params
	* @return mixed
	*/
	public static function schedule($campaignId, $params)
	{
		$url = (new BuildsMooseApiCall('campaigns', 'schedule'))->add(['id' => $campaignId])->build()->get();

		$response = Mailer::call($url,'POST', $params, 'json');

		return is_null($response->Error) ? $response->Context : false;
	}



	/**
	* UnSchedule a campaign.
	*
	* @param str $campaignId
	* @param arr $params
	* @return mixed
	*/
	public static function unSchedule($campaignId, $params)
	{
		$url = (new BuildsMooseApiCall('campaigns', 'unschedule'))->add(['id' => $campaignId])->build()->get();

		$response = Mailer::call($url);

		return is_null($response->Error) ? $response->Context : false;
	}




	/**
	* Get the a/b stats summary for a campaign
	*
	* @param str $campaignId
	* @param arr $params
	* @return mixed
	*/
	public static function getAbSummary($campaignId, $params)
	{
		$url = (new BuildsMooseApiCall('campaigns', 'view_ab_summary'))->add(['id' => $campaignId])->build()->get();

		$response = Mailer::call($url);

		return is_null($response->Error) ? $response->Context : false;
	}




	/**
	* Send the campaign immediately
	*
	* @param str $campaignId
	* @return mixed
	*/
	public static function send($campaignId)
	{
		$url = (new BuildsMooseApiCall('campaigns', 'send'))->add(['id' => $campaignId])->build()->get();

		$response = Mailer::call($url);

		return is_null($response->Error) ? $response->Context : false;
	}


}