<?php

/*****
* Bounce Parser
* 
* A utility to view bounces
* 
* @author       Steve Layman
* @copyright    Copyright (c) 2011, Ontraport, LLC
*/

// ----------------------------------------------------------------------------------------------

include "./config.inc.php";
include "./bouncetable.php";

if ($_GET['debug'])
{
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

//$buffer = '<html><head><title>Bounce Log Viewer</title><link rel="stylesheet" href="http://app.ontraport.com/js/ontraport/production.css" type="text/css"></head><body>';

$buffer = <<<STRING
	<html>
	<head>
		<title>Bounce Log Viewer</title>
		<!-- <link rel="stylesheet" href="http://app.ontraport.com/js/ontraport/production.css" type="text/css"> -->
		<link rel="stylesheet" href="./css/style.css" type="text/css">
	</head>
	<body>
		<div class = "wrapper">
			<div class="ussr-chrome-panel-panes position-absolute-zero ussr-border-solid-left ussr-border-solid-right">
				<div class="ussr-component ussr-component-collection ussr-pane-sub-component ontraport_components_collection ontraport_event_stop_propagation ussr-component-collection-has-group-actions ussr-component-collection-can-edit-listfields">
					<table class="ussr-table-striped" cellpadding="0">
						<thead class="ussr-component-collection-head">
						<tr class="sem-collection-header-display">
							<th class=" ussr-component-collection-cell ussr-component-collection-cell-type-text ussr-border-solid-bottom" data-header="bounced">
								<a class="ussr-component-collection-cell-data text-decoration-none " href="javascript://">Bounced</a>
							</th>
							<th class=" ussr-component-collection-cell ussr-component-collection-cell-type-text ussr-border-solid-bottom" data-header="queued">
								<a class="ussr-component-collection-cell-data text-decoration-none " href="javascript://">Queued</a>
							</th>
							<th class=" ussr-component-collection-cell ussr-component-collection-cell-type-text ussr-border-solid-bottom" data-header="recipient">
								<a class="ussr-component-collection-cell-data text-decoration-none " href="javascript://">Recipient</a>
							</th>
							<th class=" ussr-component-collection-cell ussr-component-collection-cell-type-text ussr-border-solid-bottom" data-header="dsnstatus">
								<a class="ussr-component-collection-cell-data text-decoration-none " href="javascript://">DSN Status</a>
							</th>
							<th class=" ussr-component-collection-cell ussr-component-collection-cell-type-text ussr-border-solid-bottom" data-header="dsnmta">
								<a class="ussr-component-collection-cell-data text-decoration-none " href="javascript://">DSN MTA</a>
							</th>
							<th class=" ussr-component-collection-cell ussr-component-collection-cell-type-text ussr-border-solid-bottom" data-header="account">
								<a class="ussr-component-collection-cell-data text-decoration-none " href="javascript://">Account</a>
							</th>
							<th class=" ussr-component-collection-cell ussr-component-collection-cell-type-text ussr-border-solid-bottom" data-header="contact">
								<a class="ussr-component-collection-cell-data text-decoration-none " href="javascript://">Contact</a>
							</th>
							<th class=" ussr-component-collection-cell ussr-component-collection-cell-type-text ussr-border-solid-bottom" data-header="message">
								<a class="ussr-component-collection-cell-data text-decoration-none " href="javascript://">Message</a>
							</th>
							<th class=" ussr-component-collection-cell ussr-component-collection-cell-type-text ussr-border-solid-bottom" data-header="sequence">
								<a class="ussr-component-collection-cell-data text-decoration-none " href="javascript://">Sequence</a>
							</th>
							<tbody class="ussr-component-collection-body">
STRING;

$buffer .= build_table();
echo $buffer;
//build_table();
echo "</div></div></div></body></html>";