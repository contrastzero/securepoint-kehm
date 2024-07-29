<?php

include 'log_parser.php';
include 'helper_functions.php';
include 'serial_number_analysis.php';
include 'ip_address_violations.php';
include 'mac_address_violations.php';
include 'hardware_analysis.php';


/**
 * Main log analysis script
 */

 // get the file and run the parser function
$logFile = 'log_file.log';
$entries = parseLogFile($logFile);

echo '<br>
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
<br>
<h2> Most used License Serial Numbers </h2>
<br>';

// Task 1
$serialCount = countSerialNumbers($entries);
displayTopSerialNumbers($serialCount);

echo '<br>
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
<br>
<h2> License Rule violations by MAC-Address </h2>
<br>';

//Task 2
$resultMac = checkSerialMacViolations($entries);
displayMacViolations($resultMac['violations'], $resultMac['serialMacMap']);

echo '<br>
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
<br>
<h2> Distinct Hardware Analysis </h2>
<br>';

//Bonus Task 3
$hardwareClasses = categorizeHardware($entries);
displayHardwareClasses($hardwareClasses);