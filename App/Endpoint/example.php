<?php
session_start();

define("ROOT",dirname(dirname(__DIR__)));
require ROOT.'/App/App.php';
App::load();

//whatever you want your endpoint to do