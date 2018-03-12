<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Jdd
 * @author     mike nunn <bonnie650@gmail.com>
 * @copyright  Copyright (C) 2016. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Model class to post contact, pledge and gift aid declaration to thankQ API
 *
 * @since  3.1
 */

class Jcurl
{

	private function logToFile($logMsg, $level)
	{
		$logConf = array('text_file' => 'jdd.log');
		$this->jLogger = new JLogLoggerFormattedtext( $logConf );
		$this->jLogger->addEntry( new JLogEntry($logMsg, $level) );
	}

	/**
	 * Send post request to API
	 *
	 * @param   string  $apiField  thankQ api field
	 * @param   string  $jsonData  jSon data to post
	 *
	 * @return  The json response object
	 *
	 * @since   1.0
	 *
	 * @throws  Not sure yet.
	 */

	public function postAPI($apiField, $jsonData, $url, $key)
	{
		// Retrun obj, set to false if error
		$resObj = false;

		$logPrefix = "Jcurl " . $apiField . " ";

		$sendHeaders = array(
			"Content-Type: application/json",
			"X-apikey: " . $key,
			"Content-length: " . strlen($jsonData)
		);

		$curlOpts = array(
			CURLOPT_TIMEOUT        		=> 60,
			CURLOPT_DNS_CACHE_TIMEOUT	=> 120,
			CURLOPT_HEADER         		=> 0,
			CURLOPT_VERBOSE        		=> 0,
			CURLOPT_NOPROGRESS     		=> 1, // Switch to false to turn on progress
			CURLOPT_RETURNTRANSFER 		=> 1,
			CURLOPT_HTTPHEADER     		=> $sendHeaders,
			CURLOPT_POSTFIELDS     		=> $jsonData,
			CURLOPT_POST           		=> 1,
		);

		$ch = curl_init($url . $apiField . '/?apikey=' . $key);

		curl_setopt_array($ch, $curlOpts);

		$res = curl_exec($ch);

		if (curl_errno($ch))
		{
			$this->logToFile($logPrefix . "curl_init: " . curl_error($ch), JLog::ERROR);
			$resObj = false;
		}
		else
		{
			$resInfo = curl_getinfo($ch);

			curl_close($ch);

			$codeHttp = $resInfo["http_code"];
			$conT = number_format($resInfo["connect_time"], 3);
			$totT = number_format($resInfo["total_time"], 3);
			$logPrefix .= ( "http: " . $codeHttp . " conT: " . $conT . " totT: " . $totT );

			// Decode into object and check if valid
			$resObj = json_decode($res);
			$jFlag = json_last_error();

			if ($codeHttp === 200)
			{
				if ($jFlag === JSON_ERROR_NONE)
				{
					if ($resObj->Status == "Success")
					{
						// echo $logPrefix . " api status - " . $resObj->Status . "\n";
						$this->logToFile($logPrefix . " api status - " . $resObj->Status, JLog::INFO);
					}
					else
					{
						$this->logToFile($logPrefix . " call ok but api not returning success " . json_encode($resObj), JLog::ERROR);
						// echo $logPrefix . " call ok but api not returning success " . json_encode($resObj) . "\n";
						$resObj = false;
					}
				}
				else
				{
					$this->logToFile($logPrefix . " no json even though we've got an ok?", JLog::ERROR);
					// echo $logPrefix . " no json even though we've got an ok?\n";
					$resObj = false;
				}
			}
			else
			{
				if ($jFlag === JSON_ERROR_NONE)
				{
					$this->logToFile($logPrefix . " valid json but error " . json_encode($resObj), JLog::ERROR);
					// echo $logPrefix . " valid json but error " . json_encode($resObj) . "\n";
					$resObj = false;
				}
				else
				{
					$this->logToFile($logPrefix . " no json", JLog::ERROR);
					// echo $logPrefix . " no json\n";
					$resObj = false;
				}
			}

			return $resObj;
		}
	}
}

/**
 * Class JddFrontendHelper
 *
 * @since  1.6
 */
class JddFrontendHelper
{
	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_jdd/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_jdd/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'JddModel');
		}

		return $model;
	}

	public static function ddDate()
	{

		$count = 0;
		$daysToAdd = 10;
		$endDate = "";
		$startDate = new DateTime();
		$day = new DateInterval("P1D");
		$month = new DateInterval("P1M");
		$d1 = "";
		$d2 = "";
		$dates = array();

		// get working days
		while ($count < $daysToAdd)
		{
			$endDate = $startDate->add($day);
			if ($endDate->format("w") != 0 && $endDate->format("w") != 6)
			{
				$count++;
			}
		}

		if ($endDate->format("d") > 14)
		{
			//increment month by 1 and give 1st and 15th
			$endDate->add($month);
			$d1 = date_create($endDate->format('Y-m-1'));
			$d2 = date_create($endDate->format('Y-m-15'));
		} else {
			//give 15th current month and 1st of next month
			$d1 = date_create($endDate->format('Y-m-15'));
			$endDate->add($month);
			$d2 = date_create($endDate->format('Y-m-1'));

		}

		$dates['d1'] = date("j M Y", $d1->format('U') );
		$dates['d2'] = date("j M Y", $d2->format('U') );

		return $dates;
	}
}
