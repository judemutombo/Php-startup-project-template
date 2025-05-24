<?php
use API\Configuration\Router\RouteParameterValidator;

RouteParameterValidator::set("/:id/:content?start&finish");

var_dump(Request->method);