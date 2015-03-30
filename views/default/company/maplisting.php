<?php
$entity = $vars['entity'];

$title = $entity->title;
$description = elgg_get_excerpt($vars['entity']->description, 100);

$icon = elgg_view("profile/icon", array('entity' => $entity, 'size' => 'small'));
$address = getAddressString($entity);

?>

<div id="<?php echo $entity->guid ?>" class="search_listing">
    <div class="filter_area">
        <div class="search_listing_icon company_logo"><?php echo $icon; ?></div>
        <div class="search_listing_info">
                    <div class="listing_title">
            <p class="company_title"><a href="<?php echo $entity->getURL() ?>"><?php echo $title; ?></a></p>
        </div>

            <p class="item_address company_address"><?php echo $address ?></p>
            
        </div>

    </div>
</div>