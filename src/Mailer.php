<?php

namespace app\Calf;

use Auth;
use App\User;
use App\Mailing;
use App\Calf\MailingList;
use App\Calf\Segment;
use App\Calf\Subscriber;
use App\Calf\Campaign;
use App\Helpers\Error;

class Mailer
{

	public $user;
	public $mailing;


/**
	* Construct a new Mailer instance
	*
	* @return int
	*/
	public function __construct(User $user)
	{
		$this->mailing = $this->grabNextInQueue();
		if($this->mailing)
			$this->user = $this->getUser();
		else
			Error::handle('No mailings found when attempting to send a mailing via web');
	}





	/**
	* Get the next mailing in line to be sent
	* grabs pro users before free users
	*
	* @return int
	*/
	public static function getNextMailing()
	{
		$mailings = Mailing::where("status", 'queued')->get()->sortByDesc('id');

		if(isset($mailings) && count($mailings))
		{
			$mailings = $mailings->filter(function ($mailing) {
				$user = User::find($mailing->user_id);
				return isset($user) && $user->isUpgraded() ?: $mailing;
			})->all();

			return count(array_values($mailings)) ? $mailings[0] : false;
		}

		//shouldn't get to here
		return Error::handle('can\'t grab next mailing in queue, problem');

	}




	/**
	* Get Next Mailing In Queue
	*
	* @return int
	*/
	public static function next()
	{
		return self::getNextMailing();
	}




	/**
	* Get mailing(s) currently being processed
	*
	* @return int
	*/
	public static function current()
	{
		return Mailing::where('status', 'processing')->get()->all();
	}



	/**
	* Get last complete In Queue
	*
	* @return int
	*/
	public static function previous()
	{


	}



	/**
	* Get User
	*
	* @return int
	*/
	public function getUser()
	{
		return User::find($this->mailing->user_id);

	}



	/**
	* Actually perform the mailing
	*
	* @return int
	*/
	public function sendmail()
	{
	}
}