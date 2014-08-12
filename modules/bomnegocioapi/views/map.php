<html>
<head>
<meta charset="utf-8">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

  <script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=true"></script>
<script src="<?php echo base_url();?>cjs/jquery/gmaps.js"></script>

<script type="text/javascript">
	var map;
   function loadResults (data) {
   	 var markers_data = [];
  //  	alert(data.items.length);
		//	alert(i+': '+item.title);
      //alert(JSON.stringify(data));
      //alert(data.)
      /*if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
        	//var item = data.;
        	alert(data[i].title)
        	*/
      //var markers_data = [];
      	//var places = $.parseJSON(data.responseText);
      	alert(JSON.stringify(data.responseText));
      	//alert(places[0].title)
      	//alert(JSON.stringify(places));

      	//places = jQuery.parseJSON(places)
      	
		$.each( places, function( i, item ) {
   		//alert(JSON.stringify(item.title));
			

        //if (item.location.lat != undefined && item.location.lng != undefined) {
        //     var icon = 'https://foursquare.com/img/categories/food/default.png';
        //     if (item.categories.length > 0) {
        //       icon = item.categories[0].icon;
        //     }

	  //       addr = '';
	  //       if(item.zip_code != ''){
	  //       	addr = item.zip_code+' - ';
			// }
			// if(item.district != ''){
	  //       	addr = addr+item.district+' - ';
			// }
			// if(item.city != ''){
	  //       	addr = addr+item.city;
			// }
			
			// GMaps.geocode({
			//   address: addr,
			//   callback: function(results, status) {
			//     if (status == 'OK') {
			//     	//alert(item.title)
			//       var latlng = results[0].geometry.location;

					
			// 		//map.addMarker({
			// 		markers_data.push({
			// 		  lat : latlng.lat(),
			// 		  lng : latlng.lng(),
			// 		  title : item.title,
			// 		  infoWindow: {
			// 		    content: '<p>'+
			// 		    			'<h1>'+item.title+'</h1>'+
			// 		    			'<img src="'+item.image+'" class="image" />'+
			// 		    			item.description+
			// 		    		'</p>'
			// 		  }/*,
			// 		  icon : {
			// 		    size : new google.maps.Size(32, 32),
			// 		    url : item.image
			// 		  }*/
			// 		});
			//     	//alert(JSON.stringify(markers_data));

			// 		// markers_data.lat = latlng.lat()
			// 		// markers_data.lng = latlng.lng()
			// 		// markers_data.title = item.title


			//       //map.setCenter(latlng.lat(), latlng.lng());
			//       // map.addMarker({
			//       //   lat: latlng.lat(),
			//       //   lng: latlng.lng()
			//       // });
			//     }
			//   }
			// });


			markers_data.push({
			  lat : item.lat,
			  lng : item.lon,
			  title : item.title,
			  infoWindow: {
			    content: '<p>'+
			    			'<h1>'+item.title+'</h1>'+
			    			//'<img src="'+item.image+'" class="image" />'+
			    			//item.description+
			    		'</p>'
				  }/*,
				  icon : {
				    size : new google.maps.Size(32, 32),
				    url : item.image
				  }*/
			});

	        //   }
    		map.addMarkers(markers_data);
			});
      //   }
      // }
      

      //map.addMarkers(markers_data);
    }

$(document).ready(function(){
	map = new GMaps({
		div: '#map',
		 lat: -23.1769,
		 lng: -45.8865
	});

	var xhr = $.getJSON('<?php echo base_url();?>api/json');

	xhr.always(loadResults);
	//xhr.done(loadResults);



	// map.addMarker({
	//   lat: -12.043333,
	//   lng: -77.028333,
	//   title: 'Lima',
	//   infoWindow: {
 //  			content: '<p>HTML Content</p>'
	// 	}
	// });
/*
	$('#geocoding_form').submit(function(e){
	    e.preventDefault();

		GMaps.geocode({
		  address: $('#address').val(),
		  callback: function(results, status) {
		    if (status == 'OK') {
		      var latlng = results[0].geometry.location;
		      map.setCenter(latlng.lat(), latlng.lng());
		      map.addMarker({
		        lat: latlng.lat(),
		        lng: latlng.lng()
		      });
		    }
		  }
		});

	});*/

});
</script>

<style type="text/css">
#map,
#panorama {
  height:100%;
  background:#6699cc;
}
.image{
	width: 200px;
	height: 200px;
}
</style>
</head>
<body> 

		<div id="map"></div>

	</body> 
</html>