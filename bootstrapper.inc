<?php

    require_once('vendor/autoload.php');
    //load .evn library
    $dotenv = new Dotenv\Dotenv(__DIR__); //configuration for local development
    // $dotenv = new Dotenv\Dotenv(__DIR__, '.env.example'); //Config for sharing and testing
    $dotenv->load();