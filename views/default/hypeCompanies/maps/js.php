<script type="text/javascript">

    function setMarkers(map, locations) {

        for (var i = 0; i < locations.length; i++) {
            var company = locations[i];
            var companyName = company[0];
            var companyLatLng = new google.maps.LatLng(company[1], company[2]);
            var companyIcon = company[3];
            var companyIconLarge = company[8];
            var companyIndex = company[5];
            var companyURL = company[6];
            var companyAddressString = company[4];
            
            var companyInfo = 
                '<b>' + companyName + '</b><br />' + 
                '<div style="width:250px;height:100px;padding:10px;">\n\
                    <div style="float:left;width:100px;padding:5px;">\n\
                        <img src="' + companyIconLarge + '" />\n\
                    </div>' + 
                                        '<div style="float:left;width:140px;padding:25px 0;">\n\
                        <i>' + companyAddressString + '</i><br />\n\
                    <a href="' + companyURL + 'target="_blank">See details</a>\n\
                    </div>\n\
                    <div style="clear:both"></div>\n\
                </div>';
                            


                                    var marker = new google.maps.Marker({
                                        position: companyLatLng,
                                        map: map,
                                        icon: companyIcon,
                                        title: companyName,
                                        zIndex: companyIndex,
                                        companyInfo: companyInfo,
                                        companyURL: companyURL
                                    });

                                    var companyInfoWindow = new google.maps.InfoWindow({
                                        content: 'holding...'
                                    });

                                    google.maps.event.addListener(marker, 'click', function() {
                                        companyInfoWindow.setContent(this.companyInfo);
                                        companyInfoWindow.open(map, this);
                                    });

                                    //google.maps.event.addListener(marker, 'click', function() {
                                    //    window.open(this.companyURL, 'mywindow');
                                    //});
                                }
                                return true;
                            }

                            function setAds(map) {
                                var specDiv = document.createElement('div');
                                var specOptions = {
                                    format: google.maps.adsense.AdFormat.HALF_BANNER,
                                    position: google.maps.ControlPosition.BOTTOM_CENTER,
                                    publisherId: 'pub-8490157954180368',
                                    map: map,
                                    visible: true
                                };
                                var adUnit = new google.maps.adsense.AdUnit(specDiv, specOptions);
                            }

                            function getMarkers(map, markers) {
                                var companiesMapped = new Array();
                                var k = 0;

                                for (var i = 0; i < markers.length; i++) {
                                    var company = markers[i];
                                    var companyLatLng = new google.maps.LatLng(company[1], company[2]);
                                    var mapBounds = new google.maps.LatLngBounds(companyLatLng, companyLatLng);
                                    if (map.getBounds()) {
                                        mapBounds = map.getBounds();
                                    }
                                    if (mapBounds.contains(companyLatLng)) {
                                        companiesMapped[k] = company[7];
                                        k++;
                                    }
                                }

                                companiesMapped = companiesMapped.join(',');

                                var companiesContainer = $('#companiesContainer');
                                var ajax_loader = '<div class="hj-ajax-loader hj-loader-circle"></div>';
                                companiesContainer.html(ajax_loader);
                                $.ajax ({
                                    url: '<?php echo elgg_add_action_tokens_to_url($vars['url'] . 'action/company/get_companies') ?>',
                                    type: 'POST',
                                    contentType: 'application/x-www-form-urlencoded',
                                    dataType: 'html',
                                    data: {companies:companiesMapped},
                                    success: function(data) {
                                        companiesContainer.html(data);
                                    }
                                });
                            }
    
                            function initialize(latlng, markers, container, global) {
                                var myOptions = {
                                    zoom: 15,
                                    center: latlng,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                }

                                var map = new google.maps.Map(document.getElementById(container),
                                myOptions);

                                setMarkers(map, markers);

                                setAds(map);

        
                                if (global == true) {
           
                                    google.maps.event.addListener(map, 'idle', function() {
                                        getMarkers(this, markers);
                                    });
           
                                    setUserPosition(map);
            
                                }
                                return map;

                            }

                            function codeAddress(address, company_guid, map) {
                                var geocoder = new google.maps.Geocoder();
                                geocoder.geocode({'address': address}, function(results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        var latlng = results[0].geometry.location;
                                        $.ajax ({
                                            url: '<?php echo elgg_add_action_tokens_to_url($vars['url'] . 'action/company/setlatlng') ?>',
                                            type: 'POST',
                                            dataType: 'html',
                                            data: {
                                                latitude: latlng.lat(),
                                                longitude: latlng.lng(),
                                                company_guid: company_guid
                                            },
                                            success: function(data) {
                                                map.setCenter(latlng);
                                            }
                                        });
                                    } else {
                                        alert("Geocode was not successful for the following reason: " + status);
                                    }
                                });
                            }

                            function changeLocation(map) {
                                var new_location = prompt('<?php echo elgg_echo('hypeCompanies:nolocationidentified') ?>', '');
                                codeAddress(new_location, '<?php echo get_loggedin_userid() ?>', map);
                            }
    
                            function setUserPosition(map) {
<?php
$user = get_loggedin_user();

if (strlen($user->hypelatitude) > 0 && strlen($user->hypelongitude) > 0) {
    ?>
                var userLat = parseFloat('<?php echo $user->hypelatitude ?>');
                var userLng = parseFloat('<?php echo $user->hypelongitude ?>');
                if (userLat !== 'NaN' && userLng !== 'NaN') {
                    var userLatLng = new google.maps.LatLng(userLat, userLng);
                    map.setCenter(userLatLng);
                } else {
                    var new_location = prompt('<?php echo elgg_echo('hypeCompanies:nolocationidentified') ?>', '');
                    codeAddress(new_location, '<?php echo $user->guid ?>', map);
                }
    <?php
} else {
    ?>
                var new_location = prompt('<?php echo elgg_echo('hypeCompanies:nolocationidentified') ?>', '');
                codeAddress(new_location, '<?php echo $user->guid ?>', map);

<?php } ?>
    }

</script>
