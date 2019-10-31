/*
Author: Robert Lie (mobilefish.com)
The store_records.js file retrieves sensor data from The Things Network and stores the sensor data in the MySQL database.
See LoRa/LoRaWAN Tutorial 27
https://www.mobilefish.com/download/lora/lora_part27.pdf

Prerequisites:
Install the following applications:
- MySQL version: 5.7.17 (mysql -V)
- Node JS version: v10.6.0 (node -v)
- NPM version: 6.5.0 (npm -v)

Usage:
1) Update file config.js
2) Start the app: node store_records.js
3) Check if the table sensor_data contains sensor data using the webbased tool phpMyAdmin:
   http://localhost/~username/phpmyadmin/index.php

Additional information:
- TTN API, more information:
  https://github.com/TheThingsNetwork/node-app-sdk
- LoRa/LoRaWAN Tutorial 25
  https://youtu.be/lZXiaMFYwfw
  https://www.mobilefish.com/download/lora/lora_part25.pdf
- LoRa/LoRaWAN Tutorial 26
  https://youtu.be/EMoZ9taGZRs
  https://www.mobilefish.com/download/lora/lora_part26.pdf
*/

const ttn = require('ttn');
const mysql = require('mysql');
const moment = require('moment');
const config = require('./config.js');
config.databaseOptions.database = "underdogsPedestrianFrequencyCounter";

const appID = config.TTNOptions.appID;
const accessKey = config.TTNOptions.accessKey;

const con = mysql.createConnection(config.databaseOptions);

con.connect(function(err) {
    if (err) throw err;
    console.log("Connected to database");
});

ttn.data(appID, accessKey)
    .then(function (client) {
        client.on("uplink", async function (devID, payload) {
            console.log("Received uplink from", devID);

            if( payload.counter != undefined) {
                const bytes = payload.payload_raw;
                var pedestrian = (bytes[0]<<[8]) | bytes[1];
                var cyclist = (bytes[2]<<[8])| bytes[3];


                // Do not convert the date time to local timezone.
                // payload.metadata.time: The timezone is zero UTC offset
                // Keep it in UTC format, users can convert the date time to their local time zone.
                //
                // The Things Network, the time is measured with 9 digits fractional-seconds, example: '2018-12-27T14:39:12.420921047Z'
                // There are two issues.
                // - Moment.js is a wrapper for the Date object in JavaScript, and is limited to three decimal places (milliseconds).
                //   This is because that is all that the date object supports.
                // - MySQL 5.7 has fractional seconds support for DATETIME with up to microseconds (6 digits) precision:
                //   See: https://dev.mysql.com/doc/refman/5.7/en/fractional-seconds.html
                //
                // Using this solution will lose some microseconds precision:
                // const time = moment(payload.metadata.time).utc().format('YYYY-MM-DD HH:mm:ss.SSSSSS');
                // Because of this I have decided to store the time as a VARCHAR and NOT as a DATETIME(6)
                //

                const query1 = "INSERT INTO pedestrian SET ?";
                const values = {
                    leftCountP: leftCountP,
                    rightCountP: rightCountP
                };

                const query2 = "INSERT INTO cyclist SET ?";
                const values2 = {
                  leftCountC: leftCountC,
                  rightCountC: rightCountC
                };

                con.query(query1, values1, function (err, results) {
                    if(err) throw err;
                    console.log("Record inserted: ",results.insertId);
                });

                con.query(query2, values2, function (err, results){
                  if(err) throw err;
                  console.log("Record inserted: ",results.insertId)
                });
            }
        })
    })
    .catch(function (error) {
        console.error("Error", error)
        process.exit(1)
    })
