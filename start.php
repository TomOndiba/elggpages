<?php

include_once(dirname(__FILE__) . '/models/model.php');

function hypeCompanies_init() {
    global $CONFIG;

    if (!is_plugin_enabled('hypeFramework')) {
        register_error('hypeFramework is not enabled. hypeCompanies will not work properly. Visit www.hypeJunction.com for details');
    }

    register_action('company/get_companies', true, $CONFIG->pluginspath . 'hypeCompanies/actions/get_companies.php', false);
    register_action('company/setlatlng', false, $CONFIG->pluginspath . 'hypeCompanies/actions/setlatlng.php', false);
    register_action('company/save', false, $CONFIG->pluginspath . 'hypeCompanies/actions/save.php', false);
    register_action('company/delete', false, $CONFIG->pluginspath . 'hypeCompanies/actions/delete.php', false);
    register_action('company/savebranch', false, $CONFIG->pluginspath . 'hypeCompanies/actions/savebranch.php', false);
    register_action('company/branchform', false, $CONFIG->pluginspath . 'hypeCompanies/actions/branchform.php', false);

    register_entity_type('object', 'company');
    register_entity_type('object', 'branchcompany');
    
    register_page_handler('companies', 'hypeCompanies_page_handler');
    register_entity_url_handler('hypeCompanies_url_handler', 'object', 'company');
    register_entity_url_handler('hypeCompanies_branch_url_handler', 'object', 'branchcompany');

    elgg_extend_view('profile/icon', 'hypeCompanies/icon');
    register_plugin_hook('entity:icon:url', 'object', 'company_icon_hook');

    elgg_extend_view('css', 'hypeCompanies/css');
    elgg_extend_view('input', 'hypeCompanies/filter');

    elgg_extend_view('page_elements/header', 'hypeCompanies/maps/metatags');
    elgg_extend_view('index/righthandside', 'hypeCompanies/latest');

    add_menu(elgg_echo('hypeCompanies:menu'), $CONFIG->wwwroot . 'pg/companies/all');
}

function hypeCompanies_pagesetup() {
    global $CONFIG;

//add submenu options
    if (get_context() == "companies") {
        add_submenu_item(elgg_echo('hypeCompanies:everyone'), $CONFIG->wwwroot . "pg/companies/all/");
        add_submenu_item(elgg_echo('hypeCompanies:neighbourhood'), $CONFIG->wwwroot . "pg/companies/neighbourhood");

        if (isloggedin ()) {
            add_submenu_item(elgg_echo('hypeCompanies:network'), $CONFIG->wwwroot . "pg/companies/network/" . $_SESSION['user']->username);

            if (canCreateCompany(get_loggedin_user())) {
                add_submenu_item(elgg_echo('hypeCompanies:your'), $CONFIG->wwwroot . "pg/companies/owner/" . $_SESSION['user']->username);
                add_submenu_item(elgg_echo('hypeCompanies:addcompany'), $CONFIG->wwwroot . "pg/companies/new/{$_SESSION['user']->username}/");
            }
        }
    }
}

function hypeCompanies_page_handler($page) {

    global $CONFIG;

    if (isset($page[0])) {

        switch ($page[0]) {
            case 'neighbourhood' :
                include($CONFIG->pluginspath . 'hypeCompanies/views/default/hypeCompanies/pages/neighbourhood.php');
                break;

            case 'network' :
                set_input('username', $page[1]);
                include($CONFIG->pluginspath . 'hypeCompanies/views/default/hypeCompanies/pages/network.php');
                break;

            case 'owner' :
                set_input('username', $page[1]);
                include($CONFIG->pluginspath . 'hypeCompanies/views/default/hypeCompanies/pages/owner.php');
                break;

            case 'new' :
                set_input('username', $page[1]);
                include($CONFIG->pluginspath . 'hypeCompanies/views/default/hypeCompanies/pages/new.php');
                break;

            case 'edit' :
                set_input('company_guid', $page[1]);
                include($CONFIG->pluginspath . 'hypeCompanies/views/default/hypeCompanies/pages/edit.php');
                break;

            case 'icon':
                if (isset($page[1])) {
                    set_input('company_guid', $page[1]);
                }
                if (isset($page[2])) {
                    set_input('size', $page[2]);
                }
                include($CONFIG->pluginspath . "hypeCompanies/graphics/hypeCompanies/icon.php");
                break;

            case 'view' :
                set_input('company_guid', $page[1]);
                include($CONFIG->pluginspath . 'hypeCompanies/views/default/hypeCompanies/pages/company.php');
                break;

            case 'all' :
            default :
                include($CONFIG->pluginspath . 'hypeCompanies/views/default/hypeCompanies/pages/all.php');
                break;
        }
    } else {
        register_error(elgg_echo('hypeCompanies:error'));
        forward($_SERVER['HTTP_REFERER']);
    }
}

function company_icon_hook($hook, $entity_type, $returnvalue, $params) {

    global $CONFIG;
    if ((!$returnvalue) && ($hook == 'entity:icon:url') && ($params['entity'] instanceof ElggObject) && ($params['entity']->getSubtype() == 'company')) {

        $entity = $params['entity'];
        $size = $params['size'];
        $filehandler = new ElggFile();
        $filehandler->owner_guid = $entity->owner_guid;
        $filehandler->setFilename("company/" . $entity->guid . $size . ".jpg");

        $url = $CONFIG->url . "pg/companies/icon/{$entity->guid}/{$size}/company.jpg";
        return $url;
    }
    
    if ((!$returnvalue) && ($hook == 'entity:icon:url') && ($params['entity'] instanceof ElggObject) && ($params['entity']->getSubtype() == 'branchcompany')) {

        $entity = $params['entity'];
        $parent = get_entity($entity->container_guid);
        $size = $params['size'];
        $url = $CONFIG->url . "pg/companies/icon/{$parent->guid}/{$size}/company.jpg";
        return $url;
    }
    
}

function hypeCompanies_url_handler($company) {
    global $CONFIG;
    return $CONFIG->wwwroot . "pg/companies/view/" . $company->guid . '/' . friendly_title($company->title);
}

function hypeCompanies_branch_url_handler($branch) {
    global $CONFIG;
    return $CONFIG->wwwroot . "pg/companies/view/" . $branch->container_guid;
}

function hypeCompanies_user_address($event, $object_type, $object) {
    if ($object instanceof ElggUser) {
        $object->hypelatitude = '';
        $object->hypelongitude = '';
    }
}

register_elgg_event_handler('init', 'system', 'hypeCompanies_init');
register_elgg_event_handler('pagesetup', 'system', 'hypeCompanies_pagesetup');
register_elgg_event_handler('update', 'user', 'hypeCompanies_user_address');
?>
