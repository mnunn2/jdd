<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Jdd
 * @author     mike nunn <bonnie650@gmail.com>
 * @copyright  Copyright (C) 2016 Karuna trust All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import Joomla modelitem library
jimport('joomla.application.component.modelitem');
jimport('joomla.application.component.helper');
jimport('joomla.log.logger.formattedtext');

jimport('subp.path.jdd', '/helpers/');

// Import sendinblue api
JLoader::register('Mailin', JPATH_COMPONENT . '/helpers/Mailin.php');

/**
 * Jdd Model
 *
 * @since  0.1
 */
class JddModelDonorDb extends JModelItem
{
	// Keyname must be set as TEST as a check to ensure
	// contact is updtated with form data
	private $contact = array(
		"title" => '',
		"firstName" => '',
		"keyname" => 'TEST',
		"addressLine1" => '108 Nirvana Close',
		"addressLine3" => 'Braintree',
		"addressLine4" => 'Essex',
		"postcode" => 'IP31 8JG',
		"country" => 'UK',
		"emailAddress" => 'mike@karuna.org',
		"dayTelephone" => "",
		"eveningTelephone" => '0123 998877',
		"mobileNumber" => '07980 123456',
		"primaryCategory" => "Donor",
		"source" => "src-cont",
		"dmEmailOptIn" => '1', // True opts in to direct marketing
		"dmMailOptIn" => '1', // True opts in to direct marketing
		"doNotContact" => '0',
		"doNotEmail" => '1',
		"doNotMail" => '1',
		"doNotPhone" => '0',
		"doNotSMS" => '0',
		"emailThirdParty" => '0',
		"mailThirdParty" => '0'
	);

	private $bank = array(
		"currency" => 'GBP',
		"bankName" => '',
		"bankAddress" => '',
		"bankPostCode" => '',
		"accountName" => 'T Test',
		"sortCode" => '',
		"accountNumber" => '',
		"instalmentValue" => '12',
		"DDIDateReceived" => '',
		"DDIMethod" => "Internet",
		"DDIStartDate" => '',
		"DDIStatus" => "New - Awaiting Lodgement",
		"paymentDay" => "1",
		"paymentFrequency" => "Monthly",
		"paymentType" => "Direct Debit",
		"startDate" => '',
		"sourceCode" => "GEN",
		"accountVerifiedBy" => "mn",
		"pledgeType" => "Open Ended",
		"taxClaimable" => 'yes',
		"receiptSerialNumber" => "",
		"directDebitInstruction" => "",
		"serialNumber" => "001000"
	);

	/**
	 * Populate contact and bank arrays
	 */
	public function __construct()
	{
		parent::__construct();

		$today = date("d/m/Y");
		$this->bank["DDIDateReceived"] = $today;
		$this->bank["startDate"] = $today;
	}

	/**
	 * Send contact and bank to thankQ api
	 *
	 * @param   array  $frmData  sanitised form data
	 *
	 * @return  boolean
	 * 
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function updateDonor($frmData)
	{
		$logPrefix = "donordb updateDonor ";

		// Append class contact and bandk with sanitized form data
		foreach ($frmData as $key => $val)
		{
			if (array_key_exists($key, $this->contact))
			{
				$this->contact[$key] = $val;
			}
		}

		foreach ($frmData as $key => $val)
		{
			if (array_key_exists($key, $this->bank))
			{
				$this->bank[$key] = $val;
			}
		}

		$this->contact["firstName"] = ucfirst($this->contact["firstName"]);
		$this->contact["keyname"] = ucfirst($this->contact["keyname"]);

		// Check contact and bank have been updated with form data
		if ( $this->contact["keyname"] === "TEST")
		{
			$this->logToFile($logPrefix . "Form data not appended to base contact obj", JLog::CRITICAL);

			return false;
		}

		return true;
	}

	/**
	 * Cache contact and bank data to DB
	 *
	 * @return  boolean
	 */
	public function cacheToDb()
	{
		$logPrefix = "donordb cacheToDb ";

		$insertData = new stdClass;
		$insertData->title = $this->contact['title'];
		$insertData->firstName = $this->contact['firstName'];
		$insertData->keyname = $this->contact['keyname'];
		$insertData->instalmentValue = $this->bank['instalmentValue'];
		$insertData->contactData = json_encode($this->contact);
		$insertData->bankData = json_encode($this->bank);

		try
		{
			$dbo = JFactory::getDbo();
			$dbo->insertObject('#__jdd_donors', $insertData);
			$dbId = $dbo->insertid();
		}
		catch (Exception $e)
		{
			$this->logToFile($logPrefix . "Failed to write to DB " . $e->getMessage(), JLog::CRITICAL);

			return false;
		}

		return $dbId;
	}

	/**
	 * Remove contact and bank data from DB
	 *
	 * @param   int  $dbId  id of donor in DB 
	 *
	 * @return  boolean
	 */
	public function removeFromDb($dbId)
	{
		$logPrefix = "donordb removeFromDb ";

		try
		{
			// Delete obj from DB
			$dbo = JFactory::getDbo();
			$delQuery = $dbo->getQuery(true);

			$conditions = array(
				$dbo->quoteName('id') . ' = ' . $dbo->quote($dbId)
			);

			$delQuery->delete($dbo->quoteName('#__jdd_donors'));
			$delQuery->where($conditions);

			$dbo->setQuery($delQuery);

			$dbo->execute();
		}
		catch (Exception $e)
		{
			$this->logToFile($logPrefix . "Failed to delete from DB $dbId " . $e->getMessage(), JLog::CRITICAL);

			return false;
		}

		return true;
	}

	/**
	 * Retrieve contact and bank data from DB
	 *
	 * @param   int  $dbId  id of donor in DB 
	 *
	 * @return  boolean
	 */
	public function retrieveFromDb($dbId)
	{
		$logPrefix = "donordb getFromDb ";

		try
		{
			$dbo = JFactory::getDbo();
			$query = $dbo->getQuery(true);

			$query->select($dbo->quoteName(array('id', 'contactData', 'bankData')));
			$query->from($dbo->quoteName('#__jdd_donors'));

			if ($dbId)
			{
				$query->where($dbo->quoteName('id') . ' = ' . $dbo->quote($dbId));
			}
			else
			{
				throw new Exception(" invalid dbId ");
			}

			$dbo->setQuery($query);
			$results = $dbo->loadObjectList();

			if ($results)
			{
				// Update class vars contact and bank
				foreach ($results as $donor)
				{
					$this->contact = json_decode($donor->contactData, true);
					$this->bank = json_decode($donor->bankData, true);
				}
			}
			else
			{
				throw new Exception(" Retrieve query failed ");
			}

		return true;
		}
		catch (Exception $e)
		{
			$this->logToFile($logPrefix . "Failed to retrieve from DB $dbId " . $e->getMessage(), JLog::CRITICAL);

			return false;
		}
	}

	/**
	 * Test function to dump contact and bank data arrays
	 *
	 * @return  string
	 */
	public function testToAPI()
	{
		$NEWLINE = '<br />';
		$outStr = $NEWLINE . "Contact data is as follows: ";

		foreach ($this->contact as $key => $value)
		{
			$outStr = $outStr . "$key - $value, ";
		}

		$outStr = $outStr . $NEWLINE . $NEWLINE . "Bank data is as follows: ";

		foreach ($this->bank as $key => $value)
		{
			$outStr = $outStr . "$key - $value, ";
		}

		return $outStr;
	}

	/**
	 * Post contact and bank data arrays to thankQ api 
	 *
	 * @return  boolean
	 */
	public function sendToApi()
	{
		$trainOrProd = JComponentHelper::getParams('com_jdd')->get('trainOrProd');
		$oldOrNew = JComponentHelper::getParams('com_jdd')->get('oldOrNew');
		$logPrefix = "donordb sendToApi ";

		if ($oldOrNew == "new")
		{
			if ($trainOrProd == "prod")
			{
				$key = JComponentHelper::getParams('com_jdd')->get('prodKey');
				$url = JComponentHelper::getParams('com_jdd')->get('prodUrl');
			}
			else
			{
				$key = JComponentHelper::getParams('com_jdd')->get('trainKey');
				$url = JComponentHelper::getParams('com_jdd')->get('trainUrl');
			}
		}
		else
		{
			if ($trainOrProd == "prod")
			{
				$key = JComponentHelper::getParams('com_jdd')->get('oldProdKey');
				$url = JComponentHelper::getParams('com_jdd')->get('oldProdUrl');
			}
			else
			{
				$key = JComponentHelper::getParams('com_jdd')->get('oldTrainKey');
				$url = JComponentHelper::getParams('com_jdd')->get('oldTrainUrl');
			}
		}

		$apiContactF = "ContactPublic";
		$apiBankF = "PledgeInVerificationPublic";
		$apiGadF = "GiftAidDeclarationPublic";
		$serialNumber = "";
		$today = date("d/m/Y");
		$retFlag = false;
		$gadData = array("dateOfDeclaration" => $today,
							"title" => $this->contact["title"],
							"firstName" => $this->contact["firstName"],
							"keyname" => $this->contact["keyname"],
							"serialNumber" => "");

		$jContactData = json_encode($this->contact);

		$curlReq = new Jcurl;

		$contactRes = $curlReq->postAPI($apiContactF, $jContactData, $url, $key);

		if ($contactRes)
		{
			// Prepare and send pledge data
			$serialNumber = $contactRes->Values->serialNumber;
			$this->logToFile($logPrefix . 'new contact created sn: ' . $serialNumber, JLog::INFO);
			$this->bank["directDebitInstruction"] = $serialNumber;
			$this->bank["receiptSerialNumber"] = $serialNumber;
			$this->bank["serialNumber"] = $serialNumber;

			$jBankData = json_encode($this->bank);

			$bankRes = $curlReq->postAPI($apiBankF, $jBankData, $url, $key);

			if ($bankRes && $this->bank["taxClaimable"] === "yes")
			{
				// Prepare and send GAD data
				$gadData["serialNumber"] = $serialNumber;
				$jGadData = json_encode($gadData);
				$gadRes = $curlReq->postAPI($apiGadF, $jGadData, $url, $key);

				if ($gadRes)
				{
					$retFlag = true;
				}
				else
				{
					/* echo "pledge or gad failed check logs\n";
					log sn of db contact
					log pledge and gad on test
					 */
					$retFlag = false;
				}
			}
			elseif ($bankRes && $this->bank["taxClaimable"] === "no")
			{
				$retFlag = true;
			}
		}
		else
		{
			// X echo "create contact failed see log\n";
			$retFlag = false;
		}

		return $retFlag;
	}

	/**
	 * Send email via SendinBlue transactional mail service
	 *
	 * @return  boolean
	 */
	public function sendEmail()
	{
		$mailURL = JComponentHelper::getParams('com_jdd')->get('sendinblueURL');
		$mailKey = JComponentHelper::getParams('com_jdd')->get('sendinblueKey');
		$mailTemplateID = JComponentHelper::getParams('com_jdd')->get('sendinblueID');
		$contact = & $this->contact;
		$bank = & $this->bank;
		$logPrefix = "donordb sendEmail ";
		$accountNumberShort = substr($bank["accountNumber"], -2);
		$sortCodeShort = substr($bank["sortCode"], -2);

		$retFlag = false;

		$data = array( "id" => $mailTemplateID,
			"to" => $contact["emailAddress"],
			"replyto" => "patrick@karuna.org",
			"attr" => array(
				"TITLE" => $contact["title"],
				"FIRSTNAME" => $contact["firstName"],
				"KEYNAME" => $contact["keyname"],
				"FREQUENCY" => $bank["paymentFrequency"],
				"AMOUNT" => $bank["instalmentValue"],
				"ACCOUNTNAME" => $bank["accountName"],
				"ACCOUNTNUMBER" => $accountNumberShort,
				"SORTCODE" => $sortCodeShort,
				"STARTDATE" => $bank["startDate"],
				"SERIALNUMBER" => $bank["serialNumber"]
			),
			"headers" => array("Content-Type" => "text/html;charset=iso-8859-1")
		);

		$mailin = new Mailin($mailURL, $mailKey);

		try
		{
			$mailRes = $mailin->send_transactional_template($data);

			$resMsg = 'api response code: ' . $mailRes["code"] . ' ' . $mailRes["message"];

			if ($mailRes["code"] === "success")
			{
				$this->logToFile($logPrefix . $resMsg, JLog::INFO);
				$retFlag = true;
			}
			else
			{
				$this->logToFile($logPrefix . $resMsg, JLog::ERROR);
			}
		}
		catch (Exception $e)
		{
			$this->logToFile($logPrefix . 'Sendinblue Mailin call failed ' . $e->getMessage(), JLog::ERROR);
		}

		return $retFlag;
	}

	/**
	 * Wrapper to access joomla logging
	 *
	 * @param   string  $logMsg  contact info
	 * @param   string  $level   bank info
	 *
	 * @return  none
	 */
	private function logToFile($logMsg, $level)
	{
		$logConf = array('text_file' => 'jdd.log');
		$this->jLogger = new JLogLoggerFormattedtext($logConf);
		$this->jLogger->addEntry(new JLogEntry($logMsg, $level));
	}

	/**
	 * Getter for private object
	 *
	 * @return  array
	 */
	public function getContact()
	{
		return $this->contact;
	}

	/**
	 * Getter for private object
	 *
	 * @return  array
	 */
	public function getBank()
	{
		return $this->bank;
	}
}
