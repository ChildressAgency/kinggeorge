jQuery(document).ready(function($){
  //creat map on page load
  map = new_map('#poi-map');

  //when user clicks a dropdown, add markers to map
  $('#poi-nav').on('show.bs.dropdown', function(){
    var markers = [];
    $(this).find('.open>.dropdown-menu>li').each(function(index){
      //https://developers.google.com/maps/documentation/javascript/examples/infowindow-simple
      var poiTitle = $(this).find('a').data('poi_title');
      var poiDescription = $(this).find('a').data('poi_description');
      var poiWebsite = $(this).find('a').data('poi_website');
      var poiLat = $(this).find('a').data('lat');
      var poiLng = $(this).find('a').data('lng');

      var pois = {};
      poi['title'] = poiTitle;
      poi['description'] = poiDescription;
      poi['website'] = poiWebsite;
      poi['lat'] = poiLat;
      poi['lng'] = poiLng;

      pois.push(poi);
    });

    add_markers(pois);
  });

});

function new_map($el) {
  //38.264493,-77.2198848
  var args = {
    zoom: 16,
    center: new google.maps.LatLng(38.264493, -77.2198848),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  // create map	        	
  var map = new google.maps.Map($el[0], args);

  // center map
  center_map(map);

  // return
  return map;
}

function add_markers(pois, map){
  $.each(pois, function(key, poi){
    var latlng = new google.maps.LatLng(poi.lat, poi.lng);
    var infoWindowContent = '';
    
    infoWindowContent += '<h4>' + poi.title + '</h4>';
    infoWindowContent += '<p>' + poi.description + '</p>';
    infoWindowContent += '<a href="' + poi.website + '">Visit Website</a>';


    var marker = new google.maps.Marker({
      position: latlng,
      map: map
    });

    map.markers.push(marker);

    var infoWindow = new google.maps.InfoWindow({
      content: infoWindowContent
    });

    google.maps.event.addListener(marker, 'click', function(){
      infoWindow.open(map, marker);
    });
  });

  center_map(map);
}

function center_map(map) {
  // vars
  var bounds = new google.maps.LatLngBounds();

  // loop through all markers and create bounds
  $.each(map.markers, function (i, marker) {
    var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
    bounds.extend(latlng);
  });

  // only 1 marker?
  if (map.markers.length == 1) {
    // set center of map
    map.setCenter(bounds.getCenter());
    map.setZoom(16);
  }
  else {
    // fit to bounds
    map.fitBounds(bounds);
  }
}

// global var
var map = null;
