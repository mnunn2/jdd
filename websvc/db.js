var creds = require('./creds.js');
var db = require('odbc')();

var q = {

	insertContact: 'insert into WEBCONTACT \
					(SERIALNUMBER, TITLE, FIRSTNAME, KEYNAME, LETTERSALUTATION, ENVELOPESALUTATION, \
					COUNTRY, POSTCODE, ADDRESSLINE1, ADDRESSLINE3, EVENINGTELEPHONE, MOBILENUMBER, \
					EMAILADDRESS, DONOTEMAIL, DONOTMAIL, WEBNEW, CONTACTTYPE) \
					values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',

	insertPledge: 'insert into webpledge \
					(PLEDGEID, SERIALNUMBER, PLEDGETYPE, PAYMENTFREQUENCY, PAYMENTTYPE, INSTALMENTVALUE, \
					STARTDATE, SOURCECODE, CURRENCY, CREATED, EXTRACTDATE, WEBSOURCE) \
					values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',

	insertDD: 'insert into webdirectdebit \
					(PLEDGEID, STATUS, INSTALMENTVALUE, PAYMENTFREQUENCY, STARTDATE, ACCOUNTNAME, \
					SORTCODE, ACCOUNTNUMBER, BANKNAME, BANKADDRESS, BANKPOSTCODE, DDIDATERECEIVED, \
					DDIMETHOD, DECRYPTED, ACCOUNTVERIFIEDONLINE, VERIFICATIONCODE) \
					values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',

	insertGAD: 'insert into webgiftaiddeclaration \
					(DECLARATIONID, SERIALNUMBER, TYPEOFDECLARATION, TITLE, FIRSTNAME, KEYNAME, \
					DATEOFDECLARATION, EFFECTIVEFROMDATE, WEBNEW) \
					values (?, ?, ?, ?, ?, ?, ?, ?, ?)'
};

var queryDb = function(sqlQ, params, target){

	var dsn = 'DSN=thankQ' + target + creds.cn;

	try {
		var result = db.openSync(dsn);
	} catch (e) {
		console.log(e.message);
	}

	try {
		var rows = db.querySync(q[sqlQ], params);
		db.closeSync();
		return true;
	} catch (e) {
		db.closeSync();
		console.log("query error " + e.message);
		return false;
	}
};

var getSql = function(sqlQ, params, callback){
  callback(null, q[sqlQ]);
};

module.exports.getSql = getSql;
module.exports.queryDb = queryDb;