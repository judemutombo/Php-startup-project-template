<?php
use API\Router\Router;

Router::setEndPointMiddleWare("hello", "Verificator");

Router::setGlobalMiddleWare("Verificator");

Router::serve();
