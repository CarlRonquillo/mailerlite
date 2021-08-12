<?php

function get_date_time($timestamp,$isTime = false)
{
    $dateTime =  explode(' ', $timestamp);

    if($isTime) {
        $data = date_format(date_create($dateTime[1]),"H:i:s");
    } else {
        $data = date_format(date_create($dateTime[0]),"d M Y");
    }

    return $data;
}

function get_country($fields)
{
    $country = '';
    foreach ($fields as $field) {
        if ($field->key == 'country' && isset($field->value)) {
            $country = $field->value;
        }
    }
    
    return $country;
}