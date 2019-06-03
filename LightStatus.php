<?php
  /**
   * A class which helps manage the start and stop times of the outside lights
   */
  class LightStatus
  {
    public $TimeOn;
    public $TimeOff;
    public $CurrentDay;

    private $SaveFile = "LightsConfig.txt";
    private $LightLogFile = "LightsConfigLog.txt";

    function __construct($TimeOn, $TimeOff)
    {
      //$compensatedTimeOff = date('m-d-Y H:i', strtotime($TimeOff) - (60 * 60 * 3.5));
      $compensatedTimeOff = date('m-d-Y H:i', strtotime('today midnight') + (60 * 60));
      $compensatedTimeOn =  date('m-d-Y H:i', strtotime($TimeOn) - (60 * 60 * 4.5));
      $this->CurrentDay = date("m-d-Y");
      $this->TimeOn = $compensatedTimeOn;
      $this->TimeOff = $compensatedTimeOff;
      return $this;
    }

    public function appendToLightsLog()
    {
      $myOpenFile = fopen($this->LightLogFile, "a") or die("Unable to open save file!");
      fwrite($myOpenFile, json_encode($this));
      fwrite($myOpenFile, PHP_EOL);
      fclose($myOpenFile);
    }
    //Returns a json object
    public function loadSelfFromTextFile()
    {
      $myOpenFile = fopen($this->SaveFile, "r") or die("Unable to open save file!");
      $jsonObject = fread($myOpenFile, filesize($SaveFile));
      fclose($myOpenFile);
      return $jsonObject;
    }

    public function saveSelfToTextFile()
    {
      $myOpenFile = fopen($this->SaveFile, "w") or die("Unable to open save file!");
      fwrite($myOpenFile, json_encode($this));
      fclose($myOpenFile);
    }
  }


 ?>
