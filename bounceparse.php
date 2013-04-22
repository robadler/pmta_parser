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

class PMTA_PARSER {
    public $csv;

    function __construct ($csv){
        $this->csv = $csv;
    }

    function parse() {
        foreach ($logFiles as $i) {
            if ($handle = fopen($i, 'r')) {
                $currentRow = 1;
                while ($data = fgetcsv($handle, ",")) {
                    $number_of_fields = count($data);
                    for ($c=0; $c < $number_of_fields; $c++) {
                        $dataArray[$currentRow] = $data;
                    }
                    $currentRow ++;
                }
            }
        }
        fclose($handle);
    }

    function store(){
        $link = mysqli_connect($dbServer, $dbUser, $dbPass, $dbName);

        foreach ($dataArray as $item){
            if ($item[0] == 'b') {
                    //break up X-MRID field and convert array vals to integer
                    $msgInfo = array_map('intval', explode('.',$item[23]));
                    $deliv = strtotime($item[1]);
                    $queued = strtotime($item[2]);
                    mysqli_query($link, "INSERT INTO bounces (delivered, queued, recipient, dsnstatus, bouncereason, acct, contact, msgid, seqid) VALUES ('$deliv','$queued','$item[4]','$item[7]','$item[8]','$msgInfo[1]','$msgInfo[2]','$msgInfo[4]','$msgInfo[5]')");
            };
        };
    }
//mysqli_query($link, 'DELETE FROM '.$dbTable.' WHERE delivered<'.time()-2592000);
}

//Working directory for logs
//$dir = '/var/backups/UnitedLayer_ARTICHOKE/pmta/logs/';
//$dir = '/home/slayman/bounceparser/logs/';
$dir = '/home/steve/Documents/Temp/';

//Current days' archives
$tars = glob($dir.'old_logs_'.date('Ymd').'*');

foreach ($tars as $i) {
    exec('tar -xf '.$i.' -C ./temp');
}

$logFiles = glob($dir.'temp/old_logs/*');