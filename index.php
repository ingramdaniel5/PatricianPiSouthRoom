<?php
//Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'LightStatus.php';
$lat = "41.626688";
$long = "-81.452380";
if (isset($_GET["GetCurrentSunriseAndSunset"]))//Controls pin 17
{
  if ($_GET["GetCurrentSunriseAndSunset"] = 1)//If it's a 1, update the existing item
  {
    $apiResponse = file_get_contents("https://api.sunrise-sunset.org/json?lat=".$lat."&lng=".$long."&date=today");
    $formattedResponse = json_decode($apiResponse);
    $formattedResponseResults = $formattedResponse->results;
    $newTimeObject = new LightStatus($formattedResponseResults->sunset, $formattedResponseResults->sunrise);
    $newTimeObject->saveSelfToTextFile();
    $newTimeObject->appendToLightsLog();
    echo json_encode($newTimeObject);
  }
  else if ($_GET["GetCurrentSunriseAndSunset"] = 2)
  {
    $existingTimeObject = new LightStatus();
    echo $existingTimeObject->loadSelfFromTextFile();
  }
}
else
{

}

?>
