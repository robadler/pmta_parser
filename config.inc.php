<?php

/*****
*
*/

//database info
define("DBSERVER", "localhost");
define("DBUSER", "slayman");
define("DBPASS", "blvh24lfhalsdkfha");
define("DBNAME", "pmta_parser");
define("DBTABLE" , "bounces");

//Working directory for logs
define("DIR", "/var/backups/UnitedLayer_ARTICHOKE/pmta/logs/");

//Days to keep entries in database
define("DAY", 30);
define("DELETE", DAY * 60 * 60 * 24);