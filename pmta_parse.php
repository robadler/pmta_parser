<?php

include "bounceparse.php";

foreach ($logFiles as $csv) {
    $bounceLog = new PMTA_PARSER($csv);
    $bounceLog->parse();
    $bounceLog->store();
    $bounceLog->cleanup();
}