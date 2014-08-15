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
                //mapTypeId: google.maps.MapTypeId.TERRAIN,
                mapTypeId: google.maps.MapTypeId.SATELLITE,
                mapTypeControl: false
            };
            // Time to update with minutes
            var update_inteval = 8;

            // Max execution time until abort the request
            var max_timeout_request = 20;

            // Url with all ads
            var json_url = "<?php echo base_url();?>api/json?";

            // Url for the windows countent
            var iframe_url = "<?php echo base_url();?>api/iframe/";

            // init map
            var map = new google.maps.Map(document.getElementById('map_canvas'), options);


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
            function getFormData() {
                return $( "form" ).serialize();
            }
            function get_data() {
                $.ajax({
                    url: json_url+getFormData(),
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
                        // Prepare images
                        // item.images = jQuery.parseJSON(item.images);
                        // var images_str = ''

                        // $.each( item.images, function( i, image ) {
                        //     if (image != undefined) {
                        //         images_str += '<img src="'+image+'" class="image" /><br />';
                        //     }
                        // });

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
                            $.ajax({
                                    url: iframe_url+item.id,
                                    dataType: 'json',
                                    success:function (data) {
                                        //infowindow.setContent(data.content);
                                        infowindow = new google.maps.InfoWindow({
                                            content: data.content
                                            // content: "<div class='infowindow_content'><iframe src='aulas/show/" + a.aula.id + "'></iframe</div>"
                                            // content: item.title+'<br />'+
                                            // '<b>R$ '+item.price+'</b><br />'+
                                            // images_str+item.description
                                        });
                                    }
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
            html, body, #map-canvas {
            height: 100%;
            margin: 0px;
            padding: 0px
        }
        .image{
            width: 100px;
            height: 100px;
            float: left;
        }
        </style>
    </head>
    <body style="margin:0px; padding:0px;">
        <div id="last-update"></div>
        <button id="update">update</button>
        <form>
            Preço mínimo: <input name="min_price" />
            Preço máximo: <input name="max_price" />
            <!-- <select name="single">
                <option>Single</option>
                <option>Single2</option>
            </select>
            <br>
            <select name="multiple" multiple="multiple">
                <option selected="selected">Multiple</option>
                <option>Multiple2</option>
                <option selected="selected">Multiple3</option>
            </select>
            <br>
            <input type="checkbox" name="check" value="check1" id="ch1">
            <label for="ch1">check1</label>
            <input type="checkbox" name="check" value="check2" checked="checked" id="ch2">
            <label for="ch2">check2</label>
            <br>
            <input type="radio" name="radio" value="radio1" checked="checked" id="r1">
            <label for="r1">radio1</label>
            <input type="radio" name="radio" value="radio2" id="r2">
            <label for="r2">radio2</label> -->
        </form>
        <div id="map_canvas" style="width: 100%; height: 100%;"></div>
    </body>
    <!-- example from: http://stackoverflow.com/questions/3059044/google-maps-js-api-v3-simple-multiple-marker-example
    http://you.arenot.me/2010/06/29/google-maps-api-v3-0-multiple-markers-multiple-infowindows/ -->
</html>