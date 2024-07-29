<?php 


/**
 * Functions for parsing the log file
 */

// Create a class for the log entry to have a structured data type
class LogEntry 
{
    public $ip;
    public $hostname;
    public $timestamp;
    public $request;
    public $status;
    public $responseSize;
    public $proxy;
    public $responseTime;
    public $serial;
    public $version;
    public $specs;
    public $notAfter;
    public $remainingDays;
    public $mac_address;

    // Constructor function
    public function __construct($ip, $hostname, $timestamp, $request, $status, $responseSize, $proxy, $responseTime, $serial, $version, $specs, $notAfter, $remainingDays, $mac_address) 
    {
        $this->ip = $ip;
        $this->hostname = $hostname;
        $this->timestamp = $timestamp;
        $this->request = $request;
        $this->status = $status;
        $this->responseSize = $responseSize;
        $this->proxy = $proxy;
        $this->responseTime = $responseTime;
        $this->serial = $serial;
        $this->version = $version;
        $this->specs = $specs;
        $this->notAfter = $notAfter;
        $this->remainingDays = $remainingDays;
        $this->mac_address = $mac_address;
    }
}


/**
 * Function to parse the log file and return an array of LogEntry objects
 * @param $filename
 * @throws \Exception
 * @return LogEntry[]
 */
function parseLogFile($filename) 
{
    $logEntries = [];
    $file = fopen($filename, "r");

    if ($file) 
    {
        while (($line = fgets($file)) !== false) 
        {
            // Regex to extract the log entry values for further use
            if (preg_match('/^(\d+\.\d+\.\d+\.\d+)\s+([\w\.-]+)\s+\[(.+?)\]\s+"(GET .+?)"\s+(\d+)\s+(\d+)\s+proxy=([\w\.-]+)\s+rt=([\d.]+)\s+serial=([\w\d]+)\s+version=([\d\.]+)\s+specs=([\w\d\/=+]+)\s+not_after="(.+?)"\s+remaining_days=(.+)$/', $line, $matches)) 
            {
                $ip             = $matches[1];
                $hostname       = $matches[2];
                $timestamp      = $matches[3];
                $request        = $matches[4];
                $status         = $matches[5];
                $responseSize   = $matches[6];
                $proxy          = $matches[7];
                $responseTime   = $matches[8];
                $serial         = $matches[9];
                $version        = $matches[10];
                $specs          = $matches[11];
                $notAfter       = $matches[12];
                $remainingDays  = $matches[13];
                $mac_address    = getMacAddress($specs);

                // Construct a new LogEntry object and add it to the array for further use
                $logEntries[]   = new LogEntry($ip, $hostname, $timestamp, $request, $status, $responseSize, $proxy, $responseTime, $serial, $version, $specs, $notAfter, $remainingDays, $mac_address);
            }
        }
        fclose($file);
    } 
    else 
    {
        // throw an error if unable to open the file
        throw new Exception("Unable to open the file: $filename");
    }

    return $logEntries;
}