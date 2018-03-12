var express = require('express');
var router = express.Router();
var dbObj = require('../db.js');
var fs = require('fs');
var file = './sn.json';
var apiKey = 'QZfD3j2Gxz8RFLCR5Vr78Z137593z45FxssPUAsW';
var dateArr = new Date().toISOString().split('T');
var today = dateArr[0];

router.post('/:target/ContactPublic', function(req, res) {
	if (req.query.apikey == apiKey) {

		// set target from url params
		var target = req.params.target;

		// get serial number, increment and save to fs
		var snObj = JSON.parse(fs.readFileSync(file, 'utf8'));
		var newSn = 'W00' + (snObj.sn += 1);
		fs.writeFile(file, JSON.stringify(snObj) , 'utf-8');
		
		var firstName = req.body.firstName;
		var keyName = req.body.keyname;
		var envSal = firstName.charAt(0) + " " + keyName;

		// build the contact data array
		var contactData = [newSn];
		contactData.push(req.body.title);
		contactData.push(firstName);
		contactData.push(keyName);
		contactData.push(firstName);
		contactData.push(envSal);
		contactData.push(req.body.country);
		contactData.push(req.body.postcode);
		contactData.push(req.body.addressLine1);
		contactData.push(req.body.addressLine3);
		contactData.push(req.body.eveningtelephone || "");
		contactData.push(req.body.mobilenumber || "");
		contactData.push(req.body.emailAddress);
		contactData.push(req.body.doNotEmail);
		contactData.push(req.body.doNotMail);
		contactData.push("-1");
		contactData.push(req.body.contactType || "individual");

		if (dbObj.queryDb("insertContact", contactData, target)){
			res.json({ Status: 'Success', Values: {serialNumber: newSn} });
		} else {
			throw ('queryDb failed');
		}
		// console.log(contactData);
	} else {
		res.status(400).json({ error: 'Invalid api key' });
	}
});

router.post('/:target/PledgeInVerificationPublic', function(req, res) {
	if (req.query.apikey == apiKey) {

		// set target from url params
		var target = req.params.target;
		var pledgeData = [];
		var ddData = [];
		var serialNumber = req.body.serialNumber;
		var instVal = req.body.instalmentValue;
		var startDate = req.body.startDate;
		var payFreq = req.body.paymentFrequency;
		
		// build pledge data array
		pledgeData.push(serialNumber);
		pledgeData.push(serialNumber);
		pledgeData.push(req.body.pledgeType);
		pledgeData.push(payFreq);
		pledgeData.push(req.body.paymentType);
		pledgeData.push(instVal);
		pledgeData.push(startDate);
		pledgeData.push(req.body.sourceCode);
		pledgeData.push(req.body.currency);
		pledgeData.push(today);
		pledgeData.push(today);
		pledgeData.push('Karuna Fundraiser');

		// Build DD data
		ddData.push(serialNumber);
		ddData.push(req.body.DDIStatus);
		ddData.push(instVal);
		ddData.push(payFreq);
		ddData.push(startDate);
		ddData.push(req.body.accountName);
		ddData.push(req.body.sortCode);
		ddData.push(req.body.accountNumber);
		ddData.push('');
		ddData.push('');
		ddData.push('');
		ddData.push(today);
		ddData.push('Internet');
		ddData.push('-1');
		ddData.push('0');
		ddData.push('');

		var resPledge = dbObj.queryDb("insertPledge", pledgeData, target);
		var resDD = dbObj.queryDb("insertDD", ddData, target);

		if (resPledge && resDD){
			res.json({ Status: 'Success' });
		} else {
			throw ('queryDb failed');
		}
		// console.log(pledgeData);
		// console.log(ddData);
	} else {
		res.status(400).json({ error: 'Invalid api key' });
	}
});

router.post('/:target/GiftAidDeclarationPublic', function(req, res) {
	if (req.query.apikey == apiKey) {

		// set target from url params
		var target = req.params.target;

		var serialNumber = req.body.serialNumber;
		var gadData = [];

		gadData.push(serialNumber);
		gadData.push(serialNumber);
		gadData.push('Web');
		gadData.push(req.body.title);
		gadData.push(req.body.firstName);
		gadData.push(req.body.keyname);
		gadData.push(today);
		gadData.push('2013-04-01');
		gadData.push(-1);

		if (dbObj.queryDb("insertGAD", gadData, target)){
			res.json({ Status: 'Success', Values: {serialNumber: serialNumber} });
		} else {
			throw ('queryDb failed');
		}
		// console.log(gadData);
		// res.json({ Status: 'Success' });
		
	} else {
		res.status(400).json({ error: 'Invalid api key' });
	}
});

router.post('/wibble', function(req, res) {
	if (req.query.apikey == apiKey) {
		res.json({ Status: 'Success' } );
	} else {
		res.status(400).json({ error: 'Invalid api key' });
	}
});


module.exports = router;