<?php
include 'LightStatus.php';
$currentStoredConfig = fgets(fopen('config.txt', 'r'));
$currentStoredConfig = trim($currentStoredConfig);
if (!empty($_POST["pwd"]) && $_POST["pwd"] == $currentStoredConfig)
{
  if (!empty($_POST["UpdateTime"]))
  {
    //LightStatus::printAndSaveBSLightStatus();
    LightStatus::update($_POST["UpdateTime"]);
    log::savedTimeUpdated();
  }
  else if (!empty($_POST["GetCurrentTime"]))
  {
    echo json_encode(LightStatus::loadFromTextFile());
    log::currentStatusRequestMade();
  }
  else if (!empty($_POST["GetLogs"]))
  {
    echo json_encode(log::GetAllLogs());
    log::logRequestMade();
  }
  else {
    echo "ERROR! No command recieved! Please pass an actual command.";
    log::nullCommandError();
  }
}
else
{
  echo "ERROR! Incorrect Password Submitted! This event will be logged";
  log::authorizationError();
}

?>
