'use strict'

if (process.env.NODE_ENV !== 'production')
    require('dotenv').config();

const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const path = require('path');
const cors = require('cors');
const requestIp = require('request-ip');
const request = require('request');


const app = express();
const port = process.env.PORT || 3000;

const { getHomeView } = require('./routes/index');


//db connection here
const dbSettings = {
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
    port: process.env.DB_PORT
};

const db = mysql.createConnection(dbSettings);

db.connect((err) => {
    if (err) {
        console.log(`Database Error: ${err}`);
        throw err;
    }
    else {
        console.log(`Connected to Database: ${dbSettings.database}`);
    }
});
global.db = db;


app.set('port', port); //set express to use this port
app.set('views', __dirname + '/views'); //set express to look in this folder to render our view
app.set('view engine', 'ejs'); //configure template engine




//routes for the app
app.get('/', getHomeView);





app.use(function (req, res, next) {
    res.status(404).send("Sorry!!! Can't find that.");
});

app.listen(port, function () {
    console.log(`Reseller Club Application Started. Running on Port: ${port}.`);
});