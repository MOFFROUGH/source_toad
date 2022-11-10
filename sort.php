<?php
require_once("data.php");

function sortNestedArrayByChildKeys(array $array, array $sortKeys): array
{
    $sortKeys = array_reverse($sortKeys);

    foreach ($sortKeys as $key) {
        usort($array, function ($a, $b) use ($key) {
            $a = searchForValueInArray($a, $key);
            $b = searchForValueInArray($b, $key);
            return $a <=> $b;
        });
    }

    return $array;
}

function searchForValueInArray(array $array, string $key)
{
    foreach ($array as $k => $v) {
        if ($k == $key) {
            return $v;
        }

        if (is_array($v)) {
            $result = searchForValueInArray($v, $key);
            if ($result) {
                return $result;
            }
        }
    }

    return false;
}

$data = sortNestedArrayByChildKeys($data, ['account_id', 'first_name', 'last_name']);
print_r($data);