<?php

/**
 * Function to check for license access violations based on MAC addresses
 * @param array $entries
 * @return array of the 10 most occurring license access violations and the violating MAC addresses
 */
function checkSerialMacViolations($entries) 
{
    // Initialize Arrays
    $serialMacMap   = [];
    $violations     = [];

    // Iterate through each entry in the log file
    foreach ($entries as $entry) 
    {
        // Get the serial number and MAC address
        $serial     = $entry->serial;
        $mac        = $entry->mac_address;

        // Filter out values that are empty
        if (empty($mac) ||$mac=='Unknown' ||$mac=='' ||$mac=='null') 
        {
            continue;
        }

        if (!isset($serialMacMap[$serial])) 
        {
            $serialMacMap[$serial] = [];
        }

        // Check if the MAC address is already set in the array
        if (!in_array($mac, $serialMacMap[$serial])) 
        {
            $serialMacMap[$serial][] = $mac;
        }

        // Add the serial number to violations if the serial number is already in use
        if (count($serialMacMap[$serial]) > 1) 
        {
            $violations[$serial] = count($serialMacMap[$serial]);
        }
    }

    arsort($violations); 

    // return the 10 most occurring violations and the corresponding MAC addresses
    return [
        'violations'    => array_slice($violations, 0, 10, true),
        'serialMacMap'  => $serialMacMap,
    ];
}


// 

/**
 * Function to display the License violations as an html table
 * @param array $violations
 * @param array $serialMacMap
 * @return void echo html table for display
 */
function displayMacViolations($violations, $serialMacMap)
{
    echo '<div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>License Serial</th>
                        <th>Violation Count</th>
                        <th>Distinct Devices (MAC Addresses)</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($violations as $serial => $count) 
    {
        echo '<tr>
                <td>' . $serial . '</td>
                <td>' . $count . '</td>
                <td>' . implode(', ', $serialMacMap[$serial]) . '</td>
              </tr>';
    }

    echo '    </tbody>
            </table>
        </div>
    </div>';
}
