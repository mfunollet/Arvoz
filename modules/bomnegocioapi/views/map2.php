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
            // map options
            var options = {
                zoom: 5,
                //center: new google.maps.LatLng(39.909736, -98.522109), // centered US
                mapTypeId: google.maps.MapTypeId.TERRAIN,
                mapTypeControl: false
            };
            // Time to update with minutes
            var update_inteval = 8;

            // Max execution time until abort the request
            var max_timeout_request = 20;

            // init map
            var map = new google.maps.Map(document.getElementById('map_canvas'), options);

            // NY and CA sample Lat / Lng
            // var southWest = new google.maps.LatLng(40.744656, -74.005966);
            // var northEast = new google.maps.LatLng(34.052234, -118.243685);
            // var lngSpan = northEast.lng() - southWest.lng();
            // var latSpan = northEast.lat() - southWest.lat();
            
            var bounds = new google.maps.LatLngBounds();
            var markersArray = [];
            get_data()
            setInterval(clearOverlays, update_inteval*60000 );

            function date() {
                var now = new Date(),
                    now = now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
                    //(now.getMonth()+ 1) + '/' + now.getDate() + '/' + now.getFullYear();
                $('#last-update').html('Última atualização as '+now);
            }

            function get_data() {
                url = "<?php echo base_url();?>api/json";
                $.ajax({
                    url: url,
                    dataType: 'json',
                    //data: data,
                    success: loadResults,
                    timeout: 1000*max_timeout_request
                });
                //var xhr = $.getJSON(url);
                //xhr.always(loadResults);
                //xhr.done(loadResults);
                //xhr.error(function(jqXHR, textStatus, errorThrown) { alert("error " + textStatus+' response.status '+jqXHR.status + ' errorThrown '+errorThrown); })
            }

            function clearOverlays() {
              for (var i = 0; i < markersArray.length; i++ ) {
                markersArray[i].setMap(null);
              }
              markersArray.length = 0;
              get_data();
            }
            $("#update").click(clearOverlays);

            function loadResults(data) {
                date();
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
                        
                        markersArray.push(marker);
                        //google.maps.event.addListener($("#update"),"click",clearOverlays);

                        google.maps.event.addListener(marker, 'click', function() {
                              var win = window.open(item.url, '_blank');
                              win.focus();
                        });
                        google.maps.event.addListener(marker, 'mouseout', function() {
                            infowindow.close(map, marker);
                        });
                        google.maps.event.addListener(marker, 'mouseover', function() {
                            infowindow = new google.maps.InfoWindow({
                                content: item.title+'<br /><img src="'+item.image+'" class="image" />'
                            });
                            infowindow.open(map, marker);
                        });
                        markersArray.push(marker);
                    })(marker, i);

                    //create a function that will open an InfoWindow for a marker mouseover
                    // var onMarkerClick = function() {
                    //     var marker = this;
                    //     var latLng = marker.getPosition();
                    //     infowindow.setContent(
                    //         '<h3>Marker position is:</h3>' + latLng.lat() + ', ' + latLng.lng());
                    //     infowindow.open(map, marker);
                    // };
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
        <div id="last-update"></div>
        <button id="update">update</button>
        <div id="map_canvas" style="width: 1300px; height: 900px;"></div>
    </body>
    <!-- example from: http://stackoverflow.com/questions/3059044/google-maps-js-api-v3-simple-multiple-marker-example
    http://you.arenot.me/2010/06/29/google-maps-api-v3-0-multiple-markers-multiple-infowindows/ -->
</html>