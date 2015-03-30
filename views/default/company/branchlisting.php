<?php
$entity = $vars['entity'];
$parent_guid = $vars['entity']->container_guid;
$parent = get_entity($parent_guid);

$fields = getCompanyBranchFields();
$company_address = getAddressString($entity);
$map_container = 'company_map_' . $entity->guid;

$title = $entity->title;
$parent_title = $parent->title;
//$description = elgg_get_excerpt($vars['entity']->description, 100);

$icon = elgg_view("profile/icon", array('entity' => $entity, 'size' => 'small'));
$address = getAddressString($entity);

$map_container = 'company_map_' . $entity->guid;

if (get_context() == 'companycontainer') {
    $map = elgg_view('hypeCompanies/maps/company_map', array(
        'latitude' => $entity->hypelatitude,
        'longitude' => $entity->hypelongitude,
        'address' => friendly_title($address),
        'container' => $map_container,
        'company_guid' => $entity->guid));

    $map .= '<div class="company_icon" id="company_map_' . $entity->guid . '" style="width:100%;height:200px;"></div>';
} else {
    $map = '<div class="search_listing_icon company_logo">' . $icon . '</div>';
}
?>

<div id="<?php echo $entity->guid ?>" class="search_listing">

    <div class="listing_title">
        <p class="company_title"><a href="<?php echo $entity->getURL() ?>"><?php echo $title; ?></a> - 
            <a href="<?php echo $parent->getURL() ?>"><?php echo $parent_title; ?></a></p>
    </div>
    <?php
    echo $map;
    ?>

    <div class="search_listing_info">
        <p class="item_address company_address"><?php echo $address ?><br/>
            <?php
            if ($entity->phone) {
                echo elgg_echo('hypeCompanies:branchphone') . ': ' . $entity->phone;
            }
            if ($entity->www) {
                echo elgg_echo('hypeCompanies:branchwebsite') . ': ' . $entity->www;
            }
            if ($entity->services) {
                echo elgg_echo('hypeCompanies:branchspecialties') . ': ' . elgg_view('output/tags', array('tags' => string_to_tag_array($entity->services)));
            }
            ?>
        </p>
    </div>
    <div class="clearfloat"></div>
    <div class="search_listing_extras">
        <?php 
        if (get_context() == 'companycontainer') : ?>
            <div class="left padded">
                <?php
                if ($parent->canEdit()) {
                    ?>
                    <a id="editbranchform" class="button" branchid="<?php echo $entity->guid ?>" href="javascript:void(0)"><?php echo elgg_echo('edit') ?></a>
                    <?php
                }
                ?>
            </div>
            <div class="left padded">
                <?php
                if ($parent->canEdit()) {
                    echo elgg_view('output/confirmlink', array(
                        'text' => elgg_echo('delete'),
                        'href' => $vars['url'] . 'action/company/delete?company_guid=' . $entity->guid,
                        'confirm' => elgg_echo('confirm')
                    ));
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="clearfloat"></div>

</div>