<?php

namespace app\Calf;

use App\Calf\Error;
use App\Calf\Mailer;

class MoosendApiAdapter
{


	/**
	* Call the Moosend Api
	* and return the response
	*
	* @param str $url
	* @param str $method
	* @param arr $params
	* @param str $extra
	* @return mixed
	*/
	public static function call($url,$method='GET',$params = [],$extra=null)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		if ($method === 'POST' && count($params))
		{
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($params));
		}

		if($extra == 'delete')
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

		if ($extra == 'json')
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"Accept: application/json"
			));
		}


		// // // TESTING
		// if($method='POST' && strstr($url, 'delete'))
		// {
		// 	dump('stopped in call api');
		// 	dd(curl_exec($ch));
		// }

		$response = json_decode(curl_exec($ch));
		curl_close($ch);


		// // TESTING
		// if($method='POST' && strstr($url, 'delete'))
		// {
		// 	dump($response);
		// 	exit;
		// }

		// return isset($response) && is_null($response->Error) ? $response : Error::info('The last operation returned a null result ');

		// 9/23/2019 doing it this way cuz operatoins like dleete return a nuill response bt could fuck up something else somewhere, use unit tests tos ee wats uip
		return isset($response) && is_null($response->Error) ? $response : null;
	}



}