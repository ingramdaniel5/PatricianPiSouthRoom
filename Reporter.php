<?php
/**
 *
 */
class Log
{
    public $logMessage;
    public $requestIP;
    public $currentDate;
    public $postData = array();
    public $getData = array();

    private static $LogFile = 'ApplicationLog.txt';

    public static function authorizationError(){
      Log::generic("ERROR! Access Denied to attempted client.");
    }

    public static function savedTimeUpdated(){
      Log::generic("External Lights Operating time updated");
    }

    public static function logRequestMade(){
      Log::generic("Log records request made");
    }

    public static function currentStatusRequestMade(){
      Log::generic("Current light config status request made");
    }

    public static function nullCommandError(){
      Log::generic("ERROR! Null/Undefined Command");
    }

    public static function generic($Message)
    {
      // Generates the new item and gets the date and time of the request
      $newLog = new Log();
      $newLog->currentDate = date("Y-m-d h:i:sa");
      $newLog->logMessage = $Message;
      $newLog->postData = array();
      $newLog->getData = array();
      // Appends all of the current requests data:
      foreach ($_POST as $key => $value)
      {
        array_push($newLog->postData, htmlspecialchars($key)."=".htmlspecialchars($value));
      }
      foreach ($_GET as $key => $value)
      {
        array_push($newLog->getData, htmlspecialchars($key)."=".htmlspecialchars($value));
      }

      // Gets the ip address of the client who requested it
      $newLog->requestIP = $_SERVER['REMOTE_ADDR'];
      Log::saveLog($newLog);
    }

    public static function GetAllLogs()
    {
      $logs = array();
      $myOpenFile = fopen(Log::$LogFile, "r") or die("ERROR! Unable to open log file!");
      while(!feof($myOpenFile))
      {
        array_push($logs, json_decode(fgets($myOpenFile)));
      }
      return $logs;
    }

    private static function saveLog($log)
    {
      $myOpenFile = fopen(Log::$LogFile, "a") or die("ERROR! Unable to open log file!");
      fwrite($myOpenFile, json_encode($log));
      fwrite($myOpenFile, PHP_EOL);
      fclose($myOpenFile);
    }
}
?>
