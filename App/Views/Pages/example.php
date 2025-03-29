<div>
    <p>example page</p>
</div>

<?php

$db = \App\Database\SQLDatabase::getInstance();


$clause = array(
    array(
        "column" => "id",
        "condition" => ">="
    ),
    array(
        "column" => "id",
        "condition" => "="
    ),
);
// print_r($db->select(["name", "mail"],"players", $clause,["OR"],["1", "2"])); 

$db->update("tester", ["name", "mail"], $clause, ["OR"], ["judy", "july", "hum", "hum"]);
