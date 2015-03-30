<?php

global $CONFIG;

$company_guid = get_input('company_guid');
$company = get_entity($company_guid);

$title = elgg_echo('hypeCompanies:editlisting');
$header = elgg_view('page_elements/title', array('title' => $title));
if ($company && $company->canEdit()) {
    $body = elgg_view('hypeCompanies/forms/edit', array('entity' => $company, 'user' => get_loggedin_user()));
} else if (!$company && canCreateCompany(get_loggedin_user ())) {
    $body = elgg_view('hypeCompanies/forms/edit', array('user' => get_loggedin_user()));
} else {
    $body = elgg_echo('hypeCompanies:noprivileges');
}
$body = elgg_view('hypeFramework/wrappers/contentwrapper', array('body' => $body));
$body = elgg_view_layout('two_column_left_sidebar', $area1, $header . $body, $area3);

page_draw($title, $body);
?>

