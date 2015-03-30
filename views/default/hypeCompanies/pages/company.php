<?php

global $CONFIG;

$company_guid = get_input('company_guid');
$company = get_entity($company_guid);

$TabsArray = array(
    'summary' => array('id' => 'company_summary', 'name' => elgg_echo('hypeCompanies:tabs:summary'), 'view' => 'hypeCompanies/summary', 'vars' => array('entity' => $company)),
    'branches' => array('id' => 'company_branches', 'name' => elgg_echo('hypeCompanies:tabs:branches'), 'view' => 'hypeCompanies/branches', 'vars' => array('entity' => $company)),
    'reviews' => array('id' => 'company_reviews', 'name' => elgg_echo('hypeCompanies:tabs:reviews'), 'view' => 'hypeCompanies/reviews', 'vars' => array('entity' => $company)),
);

$hypepluginlist = string_to_tag_array(get_plugin_setting('hypepluginlist', 'hypeFramework'));
foreach ($hypepluginlist as $plugin) {
    $TabsArray = trigger_plugin_hook('hypeCompanies:companytabs:' . $plugin, 'all', array('current' => $TabsArray), $TabsArray);
}
$TabsArray = trigger_plugin_hook('hypeCompanies:companytabs', 'all', array('current' => $TabsArray), $TabsArray);

$body = elgg_view('hypeFramework/tabs', array('tabs' => $TabsArray));
$body = elgg_view('hypeFramework/wrappers/contentwrapper', array('body' => $body));

$area1 = elgg_view_title($company->title);
$area2 = $body;
$area3 = elgg_view('hypeCompanies/profile', array('entity' => $company));
$area3 = elgg_view('hypeFramework/wrappers/contentwrapper', array('body' => $area3));
$area4 = get_submenu() . '<div class="clearfloat"></div>';


$title = elgg_echo('hypeCompanies:companies');
$body = elgg_view_layout('hypeFramework/two_column', $area1, $area2, $area3, $area4);
$body = elgg_view_layout('one_column', $body);

page_draw($title, $body);
?>
