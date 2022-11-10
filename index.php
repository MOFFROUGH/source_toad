<?php
require_once("data.php");

function displayData(array $data)
{

    foreach ($data as $key => $value) {
        if ($key == "guest_id") {
            echo "\n";
        }

        is_array($value) ? displayData($value) : print_r(ucwords(str_replace("_", " ", $key)) . " : " . $value . "\n");

    }
    return $data;
}

print_r(displayData($data));