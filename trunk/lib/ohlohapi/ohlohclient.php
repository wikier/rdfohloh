<?php
include 'ohlohapi_class_inc.php';

$apikey = 'api key available from ohloh.net! Please get your own - its free and easy...';
$projectid = 5263;
$userid = 3608;
$usermail = 'pscott@example.com';
$proxyArray = array (
			'proxy_host'     => 'proxy.myserver.co.za',
			'proxy_protocol' => 'http',
			'proxy_user'     => 'user',
			'proxy_pass'     => 'secretpassword',
			'proxy_port'     => 8080,
			);
			
$ohloh = new ohlohapi($apikey, $projectid, $userid, NULL, $usermail);

$info = $ohloh->getProjectInfo($projectid);

$acc = $ohloh->getSingleAccount($userid);
var_dump($acc);
echo $acc->kudo_score->kudo_rank;
$acc = $ohloh->getAccountByEmail($usermail);

$sizefactsnoanal = $ohloh->sizeFactsNoAnalysis();
var_dump($sizefactsnoanal);

$analysisnoid = $ohloh->getAnalysisLatest();
var_dump($analysisnoid);

$analysisid = $ohloh->getAnalysisById(78207);
var_dump($analysisid);

$activitynoid = $ohloh->getActivityFactsNoId();
var_dump($activitynoid);

$activity = $ohloh->getActivityFactsById(78207);
var_dump($activitynoid);

$contrib = $ohloh->contributorFactProject();
var_dump($contrib);

$contrib = $ohloh->contributorFactById(31210);
var_dump($contrib);

$contrib = $ohloh->contributorLanguageFactById(31210);
var_dump($contrib);

$contrib = $ohloh->getRecievedKudos($userid);
var_dump($contrib);

echo $contrib[0]->kudo->sender_account_name;