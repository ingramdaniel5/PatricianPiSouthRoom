<?php
  /**
   * A class which helps manage the start and stop times of the outside lights
   */
  class LightStatus
  {
    public $TimeOn;
    public $TimeOff;

    public static $SaveFile = "LightsConfig.txt";

    public static function update($status)
    {
      $passedObject = json_decode($status);
      $newStatus = new LightStatus($passedObject->TimeOn, $passedObject->TimeOff);
      LightStatus::saveToTextFile($newStatus);
    }

    function __construct($TimeOn, $TimeOff)
    {
      $this->TimeOn = $TimeOn;
      $this->TimeOff = $TimeOff;
      return $this;
    }

    public static function printAndSaveBSLightStatus()
    {
      $newStatus = new LightStatus(date("Y-m-d h:i:sa"), date("Y-m-d h:i:sa"));
      LightStatus::saveToTextFile($newStatus);
      echo json_encode($newStatus);
    }

    public static function loadFromTextFile()
    {
      $myOpenFile = fopen(LightStatus::$SaveFile, "r") or die("Unable to open save file!");
      $timeConfiguration = fread($myOpenFile, filesize(LightStatus::$SaveFile));
      fclose($myOpenFile);
      return $timeConfiguration;
    }

    public static function saveToTextFile($config)
    {
      $myOpenFile = fopen(LightStatus::$SaveFile, "w") or die("Unable to open save file!");
      fwrite($myOpenFile, json_encode($config));
      fclose($myOpenFile);
    }
  }


 ?>
