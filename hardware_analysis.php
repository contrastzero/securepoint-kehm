<?php

/**
 * Helper function to to decode and parse the device specs of each log entry
 * @param $metadataBase64
 * @return mixed
 */
function parseSpecsMetadata($metadataBase64) 
{
    $metadataGzip = base64_decode($metadataBase64);
    $metadataJson = gzdecode($metadataGzip);
    return json_decode($metadataJson, true);
}


/**
 * Function to categorize hardware to distinguish devices that access the license server
 * @param $entries
 * @return array of the 25 most used hardware classes, sorted by license count
 */
function categorizeHardware($entries)
{
    $hardwareClasses = [];

    foreach ($entries as $entry) 
    {
        $specs = parseSpecsMetadata($entry->specs);

        $hardwareClass = [
            'cpu'           => $specs['cpu'],
            'architecture'  => $specs['architecture'],
            'mem'           => $specs['mem'],
            'disk_root'     => $specs['disk_root'],
            'disk_data'     => $specs['disk_data']
        ];

        $classKey = json_encode($hardwareClass);

        if (!isset($hardwareClasses[$classKey])) 
        {
            $hardwareClasses[$classKey] = [
                'class'         => $hardwareClass,
                'serial_numbers'=> []
            ];
        }

        // Extract the serial number from the entry
        $serialNumber = $entry->serial;

        // Use the serial number to track unique licenses
        if (!in_array($serialNumber, $hardwareClasses[$classKey]['serial_numbers'])) 
        {
            $hardwareClasses[$classKey]['serial_numbers'][] = $serialNumber;
        }
    }

    // Convert serial numbers array length to license count
    foreach ($hardwareClasses as &$hardwareClass) 
    {
        $hardwareClass['licenses'] = count($hardwareClass['serial_numbers']);
        unset($hardwareClass['serial_numbers']);
    }
    // Sort by license count in descending order
    usort($hardwareClasses, function($a, $b) {
        return $b['licenses'] - $a['licenses'];
    });

    return array_slice($hardwareClasses, 0, 25);
}



/**
 * Function to display the hardware classes as an html table
 * @param $hardwareClasses
 * @return void echo of html table for display
 */
function displayHardwareClasses($hardwareClasses) 
{
    echo '<div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>CPU</th>
                        <th>Architecture</th>
                        <th>Memory</th>
                        <th>Disk Root</th>
                        <th>Disk Data</th>
                        <th>License Count</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($hardwareClasses as $info) 
    {
        $class = $info['class'];
        echo '<tr>
                <td>' . htmlspecialchars($class['cpu']) . '</td>
                <td>' . htmlspecialchars($class['architecture']) . '</td>
                <td>' . htmlspecialchars($class['mem']) . '</td>
                <td>' . htmlspecialchars($class['disk_root']) . '</td>
                <td>' . htmlspecialchars($class['disk_data']) . '</td>
                <td>' . htmlspecialchars($info['licenses']) . '</td>
              </tr>';
    }

    echo '    </tbody>
            </table>
        </div>
    </div>';
}
