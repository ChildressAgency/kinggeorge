jQuery(document).ready(function($){
  //create map on page load
  if($('.poi-map').length){
    map = new_map($('.poi-map'));
  }

  //when user clicks a dropdown, add markers to map
  $('#poi-nav').on('shown.bs.dropdown', function(){
    //var pois = {'markers':[]};
    var pois = [];
    var $poiItems = $(this).find('.open>.dropdown-menu>li');
    
    $poiItems.each(function(index){
      //https://developers.google.com/maps/documentation/javascript/examples/infowindow-simple
  
      var markers = [];
      markers['title'] = $(this).find('a').data('poi_title');
      markers['description'] = $(this).find('a').data('poi_description');
      markers['website'] = $(this).find('a').data('poi_website');
      markers['lat'] = $(this).find('a').data('poi_lat');
      markers['lng'] = $(this).find('a').data('poi_lng');

      pois.push(markers);
    });

    add_markers(pois, map);
  });

  //show the mytrip map
  $('#mytrip-map').on('shown.bs.collapse', function(){
    var pois = [];
    $('.mytrip-listing').each(function(index){
      var markers = [];
      markers['title'] = $(this).data('poi_title');
      markers['description'] = $(this).data('poi_description');
      markers['website'] = $(this).data('poi_website');
      markers['lat'] = $(this).data('poi_lat');
      markers['lng'] = $(this).data('poi_lng');

      pois.push(markers);
    });

    add_markers(pois, map);
    google.maps.event.trigger(map, 'resize');
  });

  //set header counter and btns for my-trip
  update_my_trip_counter();
  update_my_trip_btns();

  //add to trip
  $('.add-remove-trip').on('click', '.add-to-trip', function(e){
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
    update_my_trip_counter();
  });

  //remove from trip
  $('.add-remove-trip').on('click', '.remove-from-trip', function(e){
    e.preventDefault();
    var poiId = $(this).data('poi_id');
    var poiIds = '';
    var savedPois_cookie = Cookies.get('poi_ids');

    if(savedPois_cookie){
      var savedPois = savedPois_cookie.split(',').map(Number);

      var poiIdIndex = savedPois.indexOf(poiId);
console.log(poiIdIndex);
      if(poiIdIndex > -1){
        savedPois.splice(poiIdIndex, 1);
        poiIds = savedPois.toString();

        Cookies.set('poi_ids', poiIds, { expires:30 });
        location.reload();
      }
    }
  });

  /*$('.gallery>a[rel^="prettyPhoto"]').prettyPhoto({
    social_tools: ''
  });*/

  $('.gallery').magnificPopup({
    delegate: 'a',
    type: 'image',
    image: {
      titleSrc: 'title'
    },
    gallery: {
      enabled: true,
      preload: [0,1],
      navigateByImgClick: true
    },
    zoom: {
      enabled: true,
      duration: 300,
      easing: 'ease-in-out'
    }
  });
});

function update_my_trip_counter(){
  var savedPois_cookie = Cookies.get('poi_ids');
  savedPois_count = 0;

  if(savedPois_cookie){
    var savedPois = savedPois_cookie.split(',').map(Number);

    savedPois_count = savedPois.length;
  }
  $('#my-trip-icon').attr('data-count', savedPois_count);
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
  //38.267451,-77.180927
  var args = {
    zoom: 14,
    center: new google.maps.LatLng('38.267451', '-77.180927'),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  // create map	        	
  var map = new google.maps.Map($el[0], args);

  //map.markers = [];

  //add default marker
  var marker = new google.maps.Marker({
    position: new google.maps.LatLng('38.267451', '-77.180927'),
    icon: mapMarker,
    map: map
  });
  markers.push(marker);
  setMapOnAll(map)
  //end default marker

  // center map
  center_map(map);

  // return
  return map;
}

function setMapOnAll(map){
  for(var i = 0; i < markers.length; i++){
    markers[i].setMap(map);
  }
}

function add_markers(pois, map){
  //console.log(pois);
      if(markers){
        setMapOnAll(null);
      }
  $.each(pois, function(key, poi){
    if(poi.lat !== '' && poi.lng !== ''){
      var latlng = new google.maps.LatLng(poi.lat, poi.lng);
      var infoWindowContent = '';
      
      infoWindowContent += '<h4>' + poi.title + '</h4>';
      infoWindowContent += '<p>' + poi.description + '</p>';
      infoWindowContent += '<a href="' + poi.website + '" target="_blank">Visit Website</a>';

      var marker = new google.maps.Marker({
        position: latlng,
        icon: mapMarker,
        map: map
      });

      markers.push(marker);

      
      var infoWindow = new google.maps.InfoWindow({
        content: infoWindowContent
      });

      google.maps.event.addListener(marker, 'click', function(){
        if(prevInfoWindow){
          prevInfoWindow.close();
        }

        prevInfoWindow = infoWindow;
        infoWindow.open(map, marker);
      });
    }
  });

  center_map(map);
}

function center_map(map) {
  // vars
  var bounds = new google.maps.LatLngBounds();
  // loop through all markers and create bounds
  $.each(markers, function (i, marker) {
    var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
    bounds.extend(latlng);
  });

  // only 1 marker?
  if (markers.length == 1) {
    // set center of map
    map.setCenter(bounds.getCenter());
    map.setZoom(14);
  }
  else {
    // fit to bounds
    map.fitBounds(bounds);
  }
}

// global var
var map = null;
var markers = [];
var prevInfoWindow = false;