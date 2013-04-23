<?php

/*****
* Bounceparse.php - Parses through bounce logs
*					Stores current days' entries to db
*					Deletes entries >30 days old
*
* Created - Steve Layman 2013
* 
*/

//Working directory for logs
//$dir = '/var/backups/UnitedLayer_ARTICHOKE/pmta/logs/';
$dir = '/home/slayman/bounceparser/logs/';
//$dir = '/home/steve/Documents/Temp/';

class PMTA_PARSER {
    // Database info
    protected $dbServer = 'localhost';
    //protected $dbUser = 'slayman';
    //protected $dbPass = 'blvh24lfhalsdkfha';
    protected $dbUser = 'root';
    protected $dbPass = 'password1';
    protected $dbName = 'pmta_parser';
    protected $dbTable = 'bounces';
    public $csv;
    var $dataArray;

    public function __construct ($csv){
        $this->csv = $csv;
    }

    public function parse() {
        if ($handle = fopen($this->csv, 'r')) {
            $currentRow = 1;
            while ($data = fgetcsv($handle, ",")) {
                $number_of_fields = count($data);
                for ($c=0; $c < $number_of_fields; $c++) {
                    $this->dataArray[$currentRow] = $data;
                }
                $currentRow ++;
            }
        }
        fclose($handle);
    }

    public function store(){
        $this->link = mysqli_connect($this->dbServer, $this->dbUser, $this->dbPass, $this->dbName);
        foreach ($this->dataArray as $item){
            if ($item[0] == 'b') {
                    //break up X-MRID field and convert array vals to integer
                    $msgInfo = array_map('intval', explode('.',$item[23]));
                    $deliv = strtotime($item[1]);
                    $queued = strtotime($item[2]);
                    if(isset($msgInfo[1])){
                        mysqli_query($this->link, "INSERT INTO bounces (delivered, queued, recipient, dsnstatus, bouncereason, acct, contact, msgid, seqid) VALUES ('$deliv','$queued','$item[4]','$item[7]','$item[8]','$msgInfo[1]','$msgInfo[2]','$msgInfo[4]','$msgInfo[5]')");
                    }
            };
        };
    }
    public function cleanup(){
        mysqli_query($this->link, 'DELETE FROM '.$this->dbTable.' WHERE delivered<'.time()-2592000);
    }
}

//Delete previous days' archives
//exec('rm -rf '.$dir.'/temp/*');

//Current days' archives
$tars = glob($dir.'old_logs_'.date('Ymd').'*');

foreach ($tars as $i) {
    exec('tar -xf '.$i.' -C '.$dir.'temp');
}

$logFiles = glob($dir.'temp/old_logs/*');

foreach ($logFiles as $csv) {
    $bounceLog = new PMTA_PARSER($csv);
    $bounceLog->parse();
    $bounceLog->store();
    $bounceLog->cleanup();
}