<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include "./config.inc.php";
include "./bounceparse.php";

foreach ($logFiles as $csv) {
    $bounceLog = new PMTA_PARSER($csv);
    $bounceLog->parse();
    $bounceLog->store();
    $bounceLog->cleanup();
}