<?php
use API\Configuration\Router\Router;

Router::setEndPointMiddleWare("hello", "Verificator");

Router::setGlobalMiddleWare("Verificator");

Router::serve();
