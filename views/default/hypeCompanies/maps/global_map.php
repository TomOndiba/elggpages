<?php
$container = 'global_map';

$companies = $vars['companies'];
$branches = $vars['branchcompanies'];
$companies = array_merge($companies, $branches);

$markers = getMarkers($companies);

echo elgg_view('hypeCompanies/maps/js', $vars);
$user = get_loggedin_user();
?>

<script type="text/javascript">
    $(document).ready(function() {
        var markers = <?php echo json_encode($markers) ?>;
        
        var container = '<?php echo $container ?>';
        var newyork = new google.maps.LatLng(40.69847032728747, -73.9514422416687);

        var map = initialize(newyork, markers, container, true);
        
        $('#change_location').click(function(){
            changeLocation(map);
        });
    });
</script>

<div id="change_location" class="right"><a href="javascript:void(0)"><?php echo elgg_echo('hypeCompanies:changelocation') ?></a></div><div class="clearfloat"></div>
<div id="global_map" style="width:100%;height:500px;">
</div>
