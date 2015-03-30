<?php
$company = $vars['entity'];

if (!$vars['size']) $size='large';
$company_logo = elgg_view('profile/icon', array('entity' => $company, 'size' => $size));

$fields = getCompanyFields();
$company_address = getAddressString($company);
?>

<div id="company_profile">

    <div class="company_logo"><?php echo $company_logo ?> </div>

    <div><span class="company_address">
            <?php
            echo $company_address;
            ?>
        </span></div>
    <?php
            foreach ($fields as $ref => $value) {
                if ($company->$ref && $value['section'] == 'contacts') {
                    echo '<div><span class="company_' . $ref . '">';
                    echo $value['display_name'] . ': ';
                    echo elgg_view('output/' . $value['type'], array('value' => $company->$ref));
                    echo '</span></div>';
                }
            }
    ?>
            <div class="search_listing_extras">
        <?php echo elgg_view("elggx_fivestar/elggx_fivestar", array('entity' => $vars['entity'])); ?>
        </div>

        <p class="owner_timestamp">
        <?php
            $owner = get_entity($company->owner_guid);
            $imprint = elgg_echo('hypeCompanies:addedby') . ' ';
            $imprint .= '<a href="' . $owner->getURL() . '">' . $owner->name . '</a> ';
            if (is_plugin_enabled('hypeCategories')) {
                $imprint .= elgg_echo('hypeCompanies:incategory') . ' ';
                $imprint .= elgg_view('output/category', array('entity' => $company));
            }
            echo $imprint;
        ?>
        </p>
    <?php
            if ($company->canEdit()) {
    
                echo '<div class="button">';
                echo '<a href="' . $vars['url'] . 'pg/companies/edit/' . $company->guid . '">' . elgg_echo('hypeCompanies:editbutton') . '</a>';
                echo '</div>';
            }
        ?>
</div>
