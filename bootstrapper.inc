<?php

require_once('vendor/autoload.php');
    //load .evn library
    $dotenv = new Dotenv\Dotenv(__DIR__); //configuration for local development
    // $dotenv = new Dotenv\Dotenv(__DIR__, '.env.example'); //Config for sharing and testing
    $dotenv->load();

    function exception_handler($exception)
    {
        http_response_code(500);
        echo  "Uncaught exception: " , $exception->getMessage(), "\n";
    }
    set_exception_handler('exception_handler');