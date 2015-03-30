<?php

global $CONFIG;
gatekeeper();

$user_guid = get_input('user_guid');
$user = get_entity($user_guid);

$object_guid = (int) get_input('object_guid');
if ($object_guid == '') {
    $object_guid = NULL;
} else {
    $object = get_entity($object_guid);
    $address_string = getAddressString($object_guid);
}

$parent_guid = get_input('parent_guid');
$parent = get_entity($parent_guid);

$access_id = (int) get_input('access_id');

$company = new ElggObject($object_guid);
$company->subtype = 'branchcompany';
$company->owner_guid = $user_guid;
$company->access_id = get_input('access_id');
$company->container_guid = $parent_guid;

$fields = getCompanyBranchFields();

foreach ($fields as $ref => $value) {
    if (get_input($ref)) {
        $company->$ref = get_input($ref);
    } else {
        $company->$ref = '';
    }
}

if ($object_guid == NULL) {
    $action = 'create';
} else {
    $action = 'update';
}
if ($object_guid == NULL or $company->canEdit()) {
    $result = $company->save();
} else {
    $result = false;
}


if ($result) {
    system_message(elgg_echo('hypeCompanies:savesuccess'));
    //add_to_river('river/object/company/' . $action, $action, $user->guid, $company->guid);
    if ($address_string !== getAddressString($company)) {
        remove_metadata($company->guid, 'latitude');
        remove_metadata($company->guid, 'longitude');
    }
    if ($parent->isEnabled())
        forward('pg/companies/view/' . $parent->guid);
    
    forward('pg/companies/all');
} else {
    register_error(elgg_echo('hypeCompanies:noprivileges'));
    forward($_SERVER['HTTP_REFERER']);
}
?>