<?php

global $CONFIG;

$username = get_input('username');
$user = get_user_by_username($username);

$title = elgg_echo('hypeCompanies:newlisting');
$header = elgg_view('page_elements/title', array('title' => $title));

if (canCreateCompany(get_loggedin_user())) {
    $body = elgg_view('hypeCompanies/forms/edit', array('user' => get_loggedin_user()));
} else {
    $body = elgg_echo('hypeCompanies:noprivileges');
}

$body = elgg_view('hypeFramework/wrappers/contentwrapper', array('body' => $body));
$body = elgg_view_layout('two_column_left_sidebar', $area1, $header . $body, $area3);

page_draw($title, $body);
?>

