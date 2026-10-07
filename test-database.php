<?php

require_once "functions.php";

echo "<h1>Database Test</h1>";

echo "<h2>Testing Google Sheets...</h2>";

$result = googleSheetGet([
    "action" => "users"
]);

echo "<pre>";

print_r($result);

echo "</pre>";

?>