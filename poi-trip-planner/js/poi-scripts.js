jQuery(document).ready(function($){
  //creat map on page load
  map = new_map('.poi-map');

  //when user clicks a dropdown, add markers to map
  $('#poi-nav').on('show.bs.dropdown', function(){
    var markers = [];
    var pois = {};
    $(this).find('.open>.dropdown-menu>li').each(function(index){
      //https://developers.google.com/maps/documentation/javascript/examples/infowindow-simple
      markers['title'] = $(this).find('a').data('poi_title');
      markers['description'] = $(this).find('a').data('poi_description');
      markers['website'] = $(this).find('a').data('poi_website');
      markers['lat'] = $(this).find('a').data('lat');
      markers['lng'] = $(this).find('a').data('lng');

      pois.push(markers);
    });

    add_markers(pois);
  });

  //show the mytrip map
  $('#mytrip-map').on('show.bs.collapse', function(){
    var markers = [];
    var pois = {};
    $('.mytrip-listing').each(function(index){
      markers['title'] = $(this).data('poi_title');
      markers['description'] = $(this).data('poi_description');
      markers['website'] = $(this).data('poi_website');
      markers['lat'] = $(this).data('poi_lat');
      markers['lng'] = $(this).data('poi_lng');

      pois.push(markers);
    });

    add_markers(pois);
  });

  //set header counter and btns for my-trip
  update_my_trip_counter();
  update_my_trip_btns();

  //add to trip
  $('.add-to-trip').on('click', function(e){
    e.preventDefault();
    var poiId = $(this).data('poi_id');
    var poiIds = '';
    var savedPois_cookie = Cookies.get('poi_ids');

    if(savedPois_cookie){
      var savedPois = savedPois_cookie.split(',').map(Number);

      if(savedPois.indexOf(poiId) < 0){
        savedPois.push(poiId);
        poiIds = savedPois.toString();
      }
    }
    else{
      poiIds = poiId;
    }

    Cookies.set('poi_ids', poiIds, { expires:30 });
    update_my_trip_btns();
  });

  //remove from trip
  $('.remove-from-trip').on('click', function(e){
    e.preventDefault();
    var poiId = $(this).data('poi_id');
    var poiIds = '';
    var savedPois_cookie = Cookies.get('poi_ids');

    if(savedPois_cookie){
      var savedPois = savedPois_cookie.split(',').map(Number);

      var poiIdIndex = savedPois.indexOf(poiId);

      if(poiIdIndex > -1){
        savedPois.splice(poiIdIndex, 1);
        poiIds = savedPois.toString();

        Cookies.set('poi_ids', poiIds, { expires:30 });
        location.reload();
      }
    }
  });
});

function update_my_trip_counter(){
  var savedPois_cookie = Cookies.get('poi_ids');
  $('#my-trip-icon').attr('data-count', savedPois_cookie.length);
}

function update_my_trip_btns(){
  var savedPois_cookie = Cookies.get('poi_ids');
  var $tripBtn = $('.add-to-trip');
  var currentPoiId = $tripBtn.data('poi_id');

  if(savedPois_cookie){
    var savedPois = savedPois_cookie.split(',').map(Number);
    if(savedPois.indexOf(currentPoiId) >= 0){
      $tripBtn.removeClass('add-to-trip').addClass('remove-from-trip');
      $tripBtn.text('X Remove From Trip');
    }
  }
}

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
