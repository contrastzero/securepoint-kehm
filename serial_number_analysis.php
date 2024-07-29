<?php

/**
 * Count the number of accesses per license serial number
 * @param array $entries
 * @return array of the 10 most used license serial numbers
 */
function countSerialNumbers($entries) 
{
    $serialCount = [];

    foreach ($entries as $entry) 
    {
        if (isset($serialCount[$entry->serial])) 
        {
            $serialCount[$entry->serial]++;
        } 
        else
        {
            $serialCount[$entry->serial] = 1;
        }
    }
    // sort the array by value, in descending order, so the IP with the highest count is first
    arsort($serialCount);

    //limit the array to 10 entries
    $serialCount = array_slice($serialCount, 0, 10);

    return $serialCount;
}

/**
 * Display function for the most used serial numbers
 * @param mixed $serialCount
 * @return void echo html table for display
 */
function displayTopSerialNumbers($serialCount) 
{
    echo '<div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>License Serial</th>
                        <th>Usage Count</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($serialCount as $serial => $count) 
    {
        echo '<tr>
                <td>' . $serial . '</td>
                <td>' . $count . '</td>
              </tr>';
    }

    echo '    </tbody>
            </table>
        </div>
    </div>';
}
