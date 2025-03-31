<div>
    <p>example page</p>
</div>

<?php

use App\Auth\Auth;

$db = \App\Database\SQLDatabase::getInstance();


$clause = array(
    array(
        "column" => "id",
        "condition" => "="
    ),
);





$auth = Auth::getInstance($db);

var_dump( $auth->signup("yoan","yoanhall@gmail.com", "12345"));


print_r($db->select("tester", ["name", "mail"], $clause,[],[1])); 

// var_dump($auth->signin("yoanhall@gmail.com", "12345"));

//var_dump($db->update("tester", ["name", "mail"], $clause, [], ["july", "july@gmail.com", 1]));
//var_dump($db->update("tester", ["name"], $clause, [], ["yoan", 3]));