# Necessary imports://///////////////////////////////////////////////////////////
# ///////////////////////////////////////////////////////////////////////////////
# Needed to work with date time strings
import datetime
# Needed to get the devices host name
import socket

# Needed to make api requests
import requests
import urllib.request
from requests.auth import HTTPDigestAuth

# Needed to work with the json object
import json

# Needed to access the save files
import sys
import os

# Needed for the relay controls
import RPi.GPIO as GPIO
import time
# ///////////////////////////////////////////////////////////////////////////////
# ///////////////////////////////////////////////////////////////////////////////


# Global variable setup:

# List of GPIO pins being controlled:
pinList = [17, 23]

# Gets the host's name on system boot
hostName = socket.gethostname()
# Testing url:
# UpdateLogURL = "http://northroomoutsidelights/?GetCurrentSunriseAndSunset=1"
UpdateLogURL = "http://" + hostName + "/?GetCurrentSunriseAndSunset=1"

# Path to current log location
CurrentSchedulePath = "/var/www/html/LightsConfig.txt"
# Default on and off times at 0:00
CurrentTimeOn = datetime.time(0, 0, 0)
CurrentTimeOff = datetime.time(0, 0, 0)
GPIO.setmode(GPIO.BOARD)

def jsonStringToFormattedTimeObjects(jsonStringObject):
    FormattedJSON = json.loads(jsonStringObject)
    # Sets up the current on and off time from the api call that was made:
    CurrentTimeOnString = FormattedJSON.get("TimeOn", "9999")
    CurrentTimeOffString = FormattedJSON.get("TimeOff", "9999")
    print("Time to turn on: ")
    print(CurrentTimeOnString)
    print("Time to turn off: ")
    print(CurrentTimeOffString)
    CurrentTimeOn = datetime.datetime.strptime(CurrentTimeOnString, "%H:%M")
    CurrentTimeOff = datetime.datetime.strptime(CurrentTimeOffString, "%H:%M")

def getSetTimes():
    webURL = urllib.request.urlopen(UpdateLogURL)
    data = webURL.read()
    encoding = webURL.info().get_content_charset('utf-8')
    print("Querying PHP Site for response...")
    jsonStringToFormattedTimeObjects(data.decode(encoding));
    return

def activateRelays():
    print("Relays Currently ON")
    for i in pinList:
        GPIO.setup(i, GPIO.OUT)
        GPIO.output(i, GPIO.HIGH)

def deactivateRelays():
    print("Relays Currently Off")
    for i in pinList:
        GPIO.setup(i, GPIO.OUT)
        GPIO.output(i, GPIO.LOW)

def main():
    getSetTimes()
    # Endless loop that runs in background of pi as application:
    while True:
        dt = datetime.datetime.now()
        # At noon, Re check sunrise and sunset times
        if dt.time() == datetime.time(12) or dt.time() == datetime.time(3) or dt.time() == datetime.time(5) or dt.time() == datetime.time(7) or dt.time() == datetime.time(9):
            # Does nothing with response
            print("Reading config from php application...")
            getSetTimes(requests.get(UpdateLogURL))
        else:
            print("Reading config from local file...")
            scheduleFile = open(CurrentSchedulePath, "r")
            jsonStringToFormattedTimeObjects(scheduleFile.read())

        if dt.time() > CurrentTimeOn:
            activateRelays()
        else:
            deactivateRelays()



#   Shows that the main is the main application to the compiler
if __name__ == "__main__":
    main()



# On boot, update log in case today's schedule hasn't been set yet

