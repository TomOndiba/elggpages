<?php
$entity = $vars['entity'];

if ($entity instanceof ElggObject && $entity->getSubtype() == 'company') {

    // Get size
    if (!in_array($vars['size'], array('small', 'medium', 'large', 'tiny', 'master', 'topbar')))
        $vars['size'] = "medium";

    // Get any align and js
    if (!empty($vars['align'])) {
        $align = " align=\"{$vars['align']}\" ";
    } else {
        $align = "";
    }
    ?>


    <img src="<?php echo $entity->getIcon($vars['size']); ?>" border="0" <?php echo $align; ?> title="<?php echo $entity->title; ?>" <?php echo $vars['js']; ?> />


    <?php
}

if ($entity instanceof ElggObject && $entity->getSubtype() == 'branchcompany') {

    $parent = get_entity($entity->container_guid);
    // Get size
    if (!in_array($vars['size'], array('small', 'medium', 'large', 'tiny', 'master', 'topbar')))
        $vars['size'] = "medium";

    // Get any align and js
    if (!empty($vars['align'])) {
        $align = " align=\"{$vars['align']}\" ";
    } else {
        $align = "";
    }
    ?>


    <img src="<?php echo $parent->getIcon($vars['size']); ?>" border="0" <?php echo $align; ?> title="<?php echo $parent->title; ?>" <?php echo $vars['js']; ?> />


    <?php
}
?>