<?php

gatekeeper();

$action = get_input('action_type');
$parent_guid = get_input('parent_guid');
$entity_guid = get_input('entity_guid');
$entity = get_entity($entity_guid);

switch ($action) {

    case 'new' :
        echo elgg_view('hypeCompanies/forms/editbranch', array('parent_guid' => $parent_guid));
        break;
    
    case 'edit' :
        echo elgg_view('hypeCompanies/forms/editbranch', array('parent_guid' => $parent_guid, 'entity' => $entity));
        break;
  
}

die();
?>
