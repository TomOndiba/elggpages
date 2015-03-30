<?php

global $CONFIG;

$title = elgg_echo('hypeCompanies:everyone');
$header = elgg_view('page_elements/title', array('title' => $title));
$filter = elgg_view('hypeCompanies/filter');
$body = elgg_view('hypeFramework/wrappers/contentwrapper', array('body' => $filter));
$body .= elgg_view('hypeCompanies/list');
$body = elgg_view_layout('two_column_left_sidebar', $area1, $header . $body, $area3);

page_draw($title, $body);

?>
