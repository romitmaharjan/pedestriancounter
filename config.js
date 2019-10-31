/*
Author: Robert Lie (mobilefish.com)
The config.js file contains the MySQL user credentials and The Things Network (TTN) appID and accessKey.
See LoRa/LoRaWAN Tutorial 27
https://www.mobilefish.com/download/lora/lora_part27.pdf

The config.js file is used by:
- drop_db.js
- create_db.js
- create_table.js
- store_records.js
- read_table.js
- retrieve.js
- send.js
*/
const databaseOptions = {
    host: 'localhost',
    user: 'root'
};

const TTNOptions = {
    appID: 'iot_lora_gateway',
    accessKey: 'ttn-account-v2.yG0Lip0xJU03RCCU9WVauj0S_ASrWUOiPuu8BBEoNyw'
};

module.exports = {databaseOptions: databaseOptions, TTNOptions: TTNOptions};
