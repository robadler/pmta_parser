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
//$dbUser = 'slayman';
//$dbPass = 'blvh24lfhalsdkfha';
$dbUser = 'root';
$dbPass = 'password1';
$dbName = 'pmta_parser';
$dbTable = 'bounces';

//Working directory for logs
//$dir = '/var/backups/UnitedLayer_ARTICHOKE/pmta/logs/';
//$dir = '/home/slayman/bounceparser/logs/';
$dir = '/home/steve/Documents/Temp/';
$archives = 'old_logs_'.date('Ymd').'*';

//Current days' archives
$tars = glob($dir.'old_logs_'.date('Ymd').'*');

foreach ($tars as $i) {
	exec('tar -xf '.$i.' -C '.$dir.'temp');
}

$logFiles = glob($dir.'temp/old_logs/*');

foreach ($logFiles as $i) {
	$current_row = 1;
	if (($handle = fopen($i, 'r')) != FALSE) {
		while (($data = fgetcsv($handle, ",")) != FALSE) {
			$number_of_fields = count($data);
    if ($current_row == 1)
    {
    //Header line
        for ($c=0; $c < $number_of_fields; $c++)
        {
            $header_array[$c] = $data[$c];
        }
    }
    else
    {
    //Data line
        for ($c=0; $c < $number_of_fields; $c++)
        {
            $data_array[$header_array[$c]] = $data[$c];
        }
        print_r($data_array);
    }
    $current_row++; 
		}
	}
	fclose($handle);
}

$link = mysql_connect($dbServer, $dbUser, $dbPass, $dbName);



/*
mysqli_query($link, 'DELETE FROM '.$dbTable.' WHERE delivered<'.time()-2592000);*/