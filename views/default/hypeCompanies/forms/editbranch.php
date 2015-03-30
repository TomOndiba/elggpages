<script type="text/javascript">
    function check_mandatory_fields(form_container) {
        var check = true;
        $('.mandatory', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        if (!check) alert('<?php echo elgg_echo('hypeCompanies:mandatoryfieldmissing') ?>');
        return check;
    }
</script>

<?php
$fields = getCompanyBranchFields();
$parent_guid = $vars['parent_guid'];

if (!$vars['entity']) {
    foreach ($fields as $ref => $value) {
        $vars['entity']->$ref = '';
    }
    $current_category = NULL;
} else {
    if (is_plugin_enabled('hypeCategories')) {
        $current_category = get_item_categories($vars['entity']->guid);
    } else {
        $current_category = NULL;
    }
}
?>

<form action="<?php echo $vars['url']; ?>action/company/savebranch" method="post" enctype="multipart/form-data" onsubmit="return check_mandatory_fields($(this));">
    <?php
    echo elgg_view('input/securitytoken');
    //echo '<p><label>' . elgg_echo('hypeCompanies:icon') . '</label>' . elgg_view('input/file', array('internalname' => 'companyicon')) . '</p>';
    foreach ($fields as $ref => $value) {
        echo '<div><label>' . elgg_echo($value['display_name']) . '</label>' . elgg_view('input/' . $value['type'], array('value' => $vars['entity']->$ref,
            'internalname' => $ref, 'options' => $value['options'], 'class' => $value['class'])) . '</div>';
    }

    if (in_array('branchcompany', string_to_tag_array(get_plugin_setting('allowed_object_types', 'hypeCategories')))) {
        echo elgg_view('hypeCategories/forms/assign', array('current_category' => $current_category)) . '<br>';
    }


    echo '<p><label>' . elgg_echo('hypeCompanies:access') . '</label>' . elgg_view('input/access', array('internalname' => 'access_id', 'value' => $vars['entity']->access_id)) . '</p>';
    echo elgg_view('input/hidden', array('value' => $vars['entity']->guid, 'internalname' => 'object_guid'));
    echo elgg_view('input/hidden', array('value' => $vars['user']->guid, 'internalname' => 'user_guid'));
    echo elgg_view('input/hidden', array('value' => $parent_guid, 'internalname' => 'parent_guid'));
    echo elgg_view('input/submit', array('value' => 'save', 'internalname' => 'save'));
    ?>
</form>