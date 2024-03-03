<?php
require "vendor/autoload.php";
use App\Functions\Core;
if (Core::getEnvVariable("APP_ENV") == "local") {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}