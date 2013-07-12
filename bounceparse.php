<?php

/*****
* Bounce Parser
* 
* A utility to parse through the current days' bounce logs and store them to a database
* 
* @author       Steve Layman
* @copyright    Copyright (c) 2013, Ontraport, LLC
*/

// ----------------------------------------------------------------------------------------------

class PMTA_PARSER {

    public $csv;
    var $dataArray;

    /**
    * Constructor
    *
    */
    public function __construct ($csv)
    {
        $this->csv = $csv;
    }

    // ------------------------------------------------------------------------------------------

    /**
    * Parse method
    *
    * Iterate through each row of the csv and casts it as an array
    *
    * @access public
    */ 
    public function parse()
    {
        if ($handle = fopen($this->csv, 'r'))
        {
            while ($data = fgetcsv($handle, ","))
            {
                $this->dataArray[] = $data;
            }
        }
        fclose($handle);
    }

    /**
    * Store method
    *
    * Iterate through each array and store the relevant values to the database
    *
    * @access public
    */
    public function store()
    {
        $this->link = mysqli_connect(DBSERVER, DBUSER, DBPASS, DBNAME);
        foreach ($this->dataArray as $item)
        {
            //break up X-MRID field and convert array vals to integer
            $msgInfo = array_map('intval', explode('.',$item[23]));
            $deliv = strtotime($item[1]);
            $queued = strtotime($item[2]);
            if(isset($msgInfo[1]))
            {
                mysqli_query($this->link, "INSERT INTO bounces (delivered, queued, recipient, dsnstatus, bouncereason, acctid, contactid, msgid, seqid) VALUES ('$deliv','$queued','$item[4]','$item[7]','$item[8]','$msgInfo[1]','$msgInfo[2]','$msgInfo[4]','$msgInfo[5]')");
            }
        }
    }

    //Delete old archives
    public function cleanup()
    {
        $this->link = mysqli_connect(DBSERVER, DBUSER, DBPASS, DBNAME);
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