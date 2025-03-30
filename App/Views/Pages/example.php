<div>
    <p>example page</p>
</div>

<?php

use App\Auth\Auth;

$db = \App\Database\SQLDatabase::getInstance();


$clause = array(
    array(
        "column" => "id",
        "condition" => ">="
    ),
);

// print_r($db->select("tester", ["name", "mail"], $clause,[],["1"])); 

// $db->update("tester", ["name", "mail"], $clause, ["OR"], ["judy", "july", "hum", "hum"]);


$auth = Auth::getAuth($db);

// $auth->signup("yoan","yoanhall@gmail.com", "12345");

var_dump($auth->signin("yoanhall@gmail.com", "12345"));