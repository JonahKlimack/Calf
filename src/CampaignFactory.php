<?php

namespace app\Calf;

use App\Calf\MailingList;

class CampaignFactory
{

	/**
	* Create a default test campaign
	*
	* @return array
	*/
	public static function createTest()
	{

		return [
			'Name' => 'Test campaign',
			'Subject' => 'Some subject',
			'SenderEmail' => 'something@email.com',
			'ReplyToEmail' => 'something@email.com',
			'ConfirmationToEmail' => 'something@email.com',
			'WebLocation' => 'http://www.mysite.gr/newsletter/index',
			// 'MailingLists' => [
			// 	// [
			// 	// 	'MailingListID' => 'adaf2fe1-55db-42dc-aaf8-56d8f502138d',
			// 	// 	'SegmentID' => '10166'
			// 	// ],
			// 	[
			// 		'MailingListID' => (MailingList::getMainList())->ID
			// 	]
			// ],
			'IsAB' => true,
			'ABCampaignType' => 'Content',
			'WebLocationB' => 'http://www.mysite.gr/newsletter/index',
			'HoursToTest' => 2,
			'ListPercentage' => 20,
			'ABWinnerSelectionType' => 'OpenRate'
		];

	}


	/**
	* Create a default test campaign
	*
	* @return array
	*/
	public static function createMain()
	{
		return self::createTest();
	}



}
