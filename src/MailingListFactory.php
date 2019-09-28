<?php

namespace app\Calf

use App\Calf`\Error;
use App\Calf\MailingList;
use App\Calf\MoosendApiAdapter;
use App\Calf\BuildsMoosendApiCall;

class MailingListFactory
{


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

		return isset($result) ? $result->Context : Error::handle('could not create mailing list got error or null from moosend');
	}

}