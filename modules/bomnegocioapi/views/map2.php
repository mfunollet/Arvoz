<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Multiple Markers Google Maps</title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.11&sensor=false" type="text/javascript"></script>
        <script type="text/javascript">
        // check DOM Ready
        $(document).ready(function() {
            var xhr = $.getJSON("<?php echo base_url();?>api/json");
            //xhr.always(loadResults);
            xhr.done(loadResults);
            xhr.error(function(jqXHR, textStatus, errorThrown) { alert("error " + textStatus+' response.status '+jqXHR.status + ' errorThrown '+errorThrown); })

            function loadResults(data) {
                // map options
                var options = {
                    zoom: 5,
                    //center: new google.maps.LatLng(39.909736, -98.522109), // centered US
                    mapTypeId: google.maps.MapTypeId.TERRAIN,
                    mapTypeControl: false
                };

                // init map
                var map = new google.maps.Map(document.getElementById('map_canvas'), options);

                // NY and CA sample Lat / Lng
                // var southWest = new google.maps.LatLng(40.744656, -74.005966);
                // var northEast = new google.maps.LatLng(34.052234, -118.243685);
                // var lngSpan = northEast.lng() - southWest.lng();
                // var latSpan = northEast.lat() - southWest.lat();
                
                var bounds = new google.maps.LatLngBounds();

                // set multiple marker
                $.each( data, function( i, item ) {
                //for (var i = 0; i < 250; i++) {
                    // init markers
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(item.lat, item.lon),
                        map: map,
                        title: item.title
                    });
                    bounds.extend(new google.maps.LatLng(item.lat, item.lon));

                    // process multiple info windows
                    (function(marker, i) {
                        item.image = jQuery.parseJSON(item.images)[0];
                        // add click event
                        google.maps.event.addListener(marker, 'click', function() {
                            infowindow = new google.maps.InfoWindow({
                                content: item.title+'<br /><img src="'+item.image+'" class="image" />'
                            });
                            infowindow.open(map, marker);
                        });
                    })(marker, i);
                });
                map.fitBounds(bounds);
            }
        });
        </script>
        <style type="text/css">
        .image{
            width: 100px;
            height: 100px;
        }
        </style>
    </head>
    <body>
        <div id="map_canvas" style="width: 1300px; height: 900px;"></div>
    </body>
    <!-- example from: http://stackoverflow.com/questions/3059044/google-maps-js-api-v3-simple-multiple-marker-example
    http://you.arenot.me/2010/06/29/google-maps-api-v3-0-multiple-markers-multiple-infowindows/ -->
</html>