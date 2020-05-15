<?php
//Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("America/New_York");

include 'Reporter.php';

if (!empty($_POST["pwd"]))
{
  $currentStoredConfig = fgets(fopen('config.txt', 'r'));
  $currentStoredConfig = trim($currentStoredConfig);
  if ($_POST["pwd"] == $currentStoredConfig)
  {
    include 'main.php';
  }
  else {
    echo "ERROR! Incorrect Password Submitted! This event will be logged";
    log::authorizationError();
  }
}
else {
  echo "ERROR! No password passed! This event will be logged";
  log::authorizationError();
}
?>
