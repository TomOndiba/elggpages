<?php

global $CONFIG;

$user = $_SESSION['user'];

$title = elgg_echo('hypeCompanies:neighbourhood');
$header = elgg_view_title($title);

$TabsArray = array();
$TabsArray['all'] = array(
    'id' => 'all_companies',
    'name' => elgg_echo('hypeCompanies:tabs:all'),
    'view' => 'hypeCompanies/maps/global_map',
    'vars' => array(
        'companies' => elgg_get_entities(array('types' => 'object', 'subtypes' => 'company', 'limit' => 500)),
        'branchcompanies' => elgg_get_entities(array('types' => 'object', 'subtypes' => 'branchcompany', 'limit' => 500))
    )
);

if (is_plugin_enabled('hypeCategories')) {
    $categories = elgg_get_entities_from_metadata(array(
        'metadata_name' => 'level',
        'metadata_value' => 1,
        'type' => 'object',
        'subtype' => 'category',
        'limit' => 9999,
        'order_by_metadata' => array('name' => 'sort', 'direction' => ASC, 'as' => text)));
    foreach ($categories as $category) {
        $companies = get_filed_items_by_type($category->guid, 'object', 'company');
        if (!empty($companies)) {
            $TabsArray[$category->guid] = array(
                'id' => $category->guid,
                'name' => $category->title,
                'view' => 'hypeCompanies/maps/global_map',
                'vars' => array(
                    'companies' => elgg_get_entities(array('types' => 'object', 'subtypes' => 'company', 'limit' => 500)),
                    'branchcompanies' => elgg_get_entities(array('types' => 'object', 'subtypes' => 'branchcompany', 'limit' => 500))
                )
            );
        }
    }
}

$area1 = '';
$area2 = elgg_view('hypeFramework/tabs', array('tabs' => $TabsArray));
$area3 = '<div id="companiesContainer"></div>';
$module_title = elgg_view_title(elgg_echo('hypeCompanies:nowonthemap'));
$area3 = elgg_view('hypeFramework/wrappers/contentwrapper', array('body' => $module_title . $area3));
$area4 = get_submenu();

$body = $header;
$body .= elgg_view_layout('hypeFramework/two_column', $area1, $area2, $area3, $area4);
$body = elgg_view_layout('one_column', $body);

page_draw($title, $body);
?>
