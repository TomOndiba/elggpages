<div class="index_box">
    <h2><?php echo elgg_echo("hypeCompanies:latest"); ?></h2>
    <?php
    $entities = elgg_get_entities(array(
                'type' => 'object',
                'subtype' => 'company',
                'limit' => 5
            ));
    echo elgg_view_entity_list($entities, false, 0, 5);
    ?>
</div>

