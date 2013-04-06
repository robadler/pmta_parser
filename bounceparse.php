<?php

/*****
* Bounceparse.php - Parses through bounce logs
*					Stores current days' entries to db
*					Deletes entries >30 days old
*
* Created - Steve Layman 2013
* 
*/

// Database info
$dbServer = 'localhost';
$dbUser = 'slayman';
$dbPass = 'blvh24lfhalsdkfha';
$dbName = 'pmta_parser';
$dbTable = 'bounces';

//Working directory for logs
//$dir = '/var/backups/UnitedLayer_ARTICHOKE/pmta/logs/';
$dir = '/home/slayman/bounceparser/logs/';

//Current days' archives
$tars = glob($dir.'old_logs_'.date('Ymd').'*');

foreach ($tars as $i) {
	exec('sudo tar -xf '.$i.' -C temp');
}

$link = mysql_connect($dbServer, $dbUser, $dbPass, $dbName);

mysqli_query($link, 'DELETE FROM '.$dbTable.' WHERE delivered<'.time()-2592000);*/