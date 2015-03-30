<?php

$latitude = get_input('latitude');
$longitude = get_input('longitude');

$company_guid = (int) get_input('company_guid');
$company = get_entity($company_guid);

if ($company instanceof ElggEntity) {
    $company->hypelatitude = $latitude;
    $company->hypelongitude = $longitude;
}

die();
?>
