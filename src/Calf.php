<?php

namespace app\Calf;

use Auth;
use App\User;
use App\Mailing;
use App\Calf\Segment;
use App\Calf\Campaign;
use App\Calf\Subscriber;
use App\Calf\MailingList;
use App\Calf\CampaignFactory;
use Illuminate\Support\Str;
use App\Jobs\SendsAMailing;


class Calf
{
	private $mailingList;
	private $pointer;

	/**
	* Create an instance
	*
	* @return int
	*/
	public function __construct($mailingListIdentifier=null)
	{
		$this->mailingList = $mailingListIdentifier ? MailingList::getOrCreate($mailingListIdentifier) : null;
	}

	/**
	* Test creating a mailing list
	*
	* @return int
	*/
	public function testSendMailing()
	{
		//init stuff
		$num = 50;
		//create test list
		// return $this->seedList(MailingList::getTest(),200);


		//1. get the next mailing using algo
		$mailing = Mailing::where('status','queued')->get()->all();


		//2. grab all subcrbiers, mind the paging
		$testMailingList = MailingList::getTest();
		$subscribers = Subscriber::getAllFrom($testMailingList->ID);


		//3. create a random subset
		$randomizedSubscribers = array_rand($subscribers, $num);
		foreach($subscribers as $key => $value)
			$randomList[] = $subscribers[$key];
		dump($randomList);


		//4. put into moosend format
		for ($i = 0; $i < $num; $i++)
		{
			$formattedSubscribers[$i]['Name'] = $randomList[$i]->Name;
			$formattedSubscribers[$i]['Email'] = $randomList[$i]->Email;
		}


		//5. create a temp mailing list
		$tempMailingList = MailingList::create(Str::random(12));



		//6.  add subscribers to temp mailinglist
		$addResult = Subscriber::addMany($tempMailingList->ID, $formattedSubscribers);
		dump('adding subscribers to temp list');
		dump($addResult);


		//7. Create a campaign for this temp mailing list
		$campaign = Campaign::create((new CampaignFactory())->createTest());
		dump('camapaign creation result');
		dd($campaign);

		//8. send the campaign
		$sendResult = Campaign::send($campaign->ID);



		//9. delete the temp mailing list  (& campaign?)
		$deleteResult = MailingList::delete($tempMailingList->ID);
		dump('deleting temp mailing list');
		dump($deleteResult);
	}


	/**
	* Let calf know we know are working with a segment
	*
	* @return int
	*/
	public function segment($identifier)
	{
		$this->pointer = 'App\\Helpers\\Mailer\\Segment';
		$this->segment = Segment::get($this->mailingList->ID,$identifier);
		dump('deleting list');
		return $this;
	}




	/**
	* update whatever we are pointing to
	*
	* @return int
	*/
	public function update($arguments=[])
	{
		$arguments = [
			$this->mailingList->ID,'asdfsdfsdfs','segmentname'
		];
		return call_user_func_array($this->pointer.'::update', $arguments);
	}



	/**
	* create test mailing list
	*
	* @return int
	*/
	public function createTestMailingList()
	{
		$mailingList = MailingList::create('test');

	}


	/**
	* seed mailing list with test subscribers
	*
	* @return int
	*/
	public function seedList($mailingList, $num)
	{

		for ($i = 0; $i < $num; $i++)
		{
			$seedSubscribers[$i]['name'] = 'Jonah'.Str::random(5);
			$seedSubscribers[$i]['email'] = 'jonah'.Str::random(5).'@blahblah.com';
		}
			// dd($seedSubscribers);


		return Subscriber::addMany($mailingList->ID, $seedSubscribers);

	}



	/** * create a test list with num seed subscribers * * @return int */ public function buildAList($num)
	{
		// $mailingList = $this->createTestMailingList();

		// if ($mailingList)
		$seedSubscribers = $this->seedList($mailingList->ID, $num);

		return $seedSubscribers;
	}

}


