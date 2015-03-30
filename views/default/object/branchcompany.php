<?php

if (isset($vars['entity']) &&
        $vars['entity'] instanceof ElggObject
        && $vars['entity']->getSubtype() == 'branchcompany') {
    //if (get_context() == "search") {
        echo elgg_view("company/branchlisting", $vars);
    //}
}
?>