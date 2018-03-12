var express = require('express'),
	bodyParser = require('body-parser'),
	morgan  = require('morgan'),
	routes = require('./routes/api'),
	fs = require('fs'),
	https = require('https'),
	app     = express();
 
// ssl options
var options = {
	key: fs.readFileSync('ssl/privkey.pem'),
	cert: fs.readFileSync('ssl/fullchain.pem'),
	requestCert: false,
	rejectUnauthorized: false
};


app.use(bodyParser.json());
app.use(morgan('combined'));

// used for testing if the draytek goes wierd with tls
// app.use('/', function(req, res){
// 	res.send('hello\n');
// 	console.log('Time:', Date.now());
// });

app.use('/api', routes);

const port = 7000;
const ip = '0.0.0.0';

// catch stuff that dropes through index.js
app.use(function (req, res, next) {
  res.status(404).send("Sorry can't find that!");
});

// error handling
app.use(function(err, req, res, next){
  console.error(err);
  res.status(500).send('Something bad happened!');
});

// http listner
// app.listen(port, ip);
// console.log('Server running on http://%s:%s', ip, port);

https.createServer(options, app).listen(port, ip, function () {
	   console.log('%s: https server started on %s:%d ...', Date(Date.now() ), ip, port);
});