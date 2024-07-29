<?php


/**
 * Extract the mac address from the decoded device specs
 * @param $specs base64 encoded and gzip compressed
 * @return mixed
 */
function getMacAddress($specs) 
{
    $specsArray = json_decode(decodeSpecs($specs), true);
    if ($specsArray['mac'] == null || $specsArray['mac'] == '') 
    {
        return 'Unknown';
    }
    if (json_last_error() === JSON_ERROR_NONE && is_array($specsArray) && isset($specsArray['mac'])) 
    {
        return $specsArray['mac'];
    }
    return 'Unknown';
}



/**
 * Decode specs
 * @param mixed $encodedSpecs
 * @return string
 */
function decodeSpecs($encodedSpecs) 
{
    // Decode base64
    $decodedData = base64_decode($encodedSpecs);
    if ($decodedData === false) 
    {
        return "Invalid base64 data";
    }

    // Decompress gzip
    $specs = @gzdecode($decodedData);
    if ($specs === false) 
    {
        return "Invalid gzip data";
    }

    return $specs;
}