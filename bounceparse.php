<?php

/*****
* Bounce Parser
* 
* A utility to parse through the current days' bounce logs and store them to a database
* 
* @author       Steve Layman
* @copyright    Copyright (c) 2011, Ontraport, LLC
*/

// ----------------------------------------------------------------------------------------------

include "./config.inc.php";

class PMTA_PARSER {

    public $csv;
    var $dataArray;

    /**
    * Constructor
    *
    */
    public function __construct ($csv){
        $this->csv = $csv;
    }

    // ------------------------------------------------------------------------------------------

    /**
    * Parse method
    *
    * This function iterates through each row of the array and casts it as an array
    *
    * @access public
    */ 
    public function parse() {
            if ($handle = fopen($this->csv, 'r')) {
                while ($data = fgetcsv($handle, ",")) {
                    $number_of_fields = count($data);
                    for ($c=0; $c < $number_of_fields; $c++) {
                        $this->dataArray[] = $data;
                    }
                }
            }
            fclose($handle);
        }

    /**
    * Store method
    *
    * This function iterates through each array and stores the relevant values to a database
    *
    * @access public
    */
    public function store() {
        $this->link = mysqli_connect(DBSERVER, DBUSER, DBPASS, DBNAME);
        foreach ($this->dataArray as $item) {
            //break up X-MRID field and convert array vals to integer
            $msgInfo = array_map('intval', explode('.',$item[23]));
            $deliv = strtotime($item[1]);
            $queued = strtotime($item[2]);
            if(isset($msgInfo[1])){
                mysqli_query($this->link, "INSERT INTO bounces (delivered, queued, recipient, dsnstatus, bouncereason, acct, contact, msgid, seqid) VALUES ('$deliv','$queued','$item[4]','$item[7]','$item[8]','$msgInfo[1]','$msgInfo[2]','$msgInfo[4]','$msgInfo[5]')");
            }
        }
    }

    //Delete old archives
    public function cleanup(){
        mysqli_query($this->link, 'DELETE FROM '.DBTABLE.' WHERE delivered<'.time()-(DELETE));
    }
}


//Delete previous days' archives
exec('rm -rf '.DIR.'/temp/*');

//Current days' archives
$tars = glob(DIR.'old_logs_'.date('Ymd').'*');

foreach ($tars as $i) {
    exec('tar -xf '.$i.' -C '.DIR.'temp');
}

$logFiles = glob(DIR.'temp/old_logs/*');