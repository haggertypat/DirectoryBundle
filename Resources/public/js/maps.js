var map; // keep a global map var for the marker closures

function listingsMapInit()
{
    var mapOptions = {
        zoom: 25,
        center: new google.maps.LatLng(40.4406, -70.4969),                
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: false                
    };

    map = new google.maps.Map(document.getElementById("map-canvas"),
        mapOptions);

    var bounds = new google.maps.LatLngBounds();
            
    for(var listingId in listingMapData) {
        var listing = listingMapData[listingId];
        var listingLatLng = new google.maps.LatLng(listing.lat, listing.lng);
        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
            position: listingLatLng, 
            map: map
        });
        bounds.extend(listingLatLng);
        
        google.maps.event.addListener(marker, 'click', (function(marker, listing) {
            return function() {
                infowindow.setContent(listing.infoWindowContent);
                infowindow.open(map, marker);
            }
        })(marker, listing));
        
        google.maps.event.addListener(map, 'zoom_changed', function() {
         //   if (map.getZoom() > 17) map.setZoom(17);
        });        
    }
        
    map.fitBounds(bounds);
}

function profileMapInit()
{
    var listingLatLng = new google.maps.LatLng(listing.lat, listing.lng);

    var mapOptions = {
        center: listingLatLng,
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: false
    };

    var infowindow = new google.maps.InfoWindow();

    var map = new google.maps.Map(document.getElementById("map-canvas"),
        mapOptions);

    var marker = new google.maps.Marker({
        position: listingLatLng, 
        map: map
    });

    google.maps.event.addListener(marker, 'click', (function(marker, listing) {
        return function() {
          infowindow.setContent(listing.infoWindowContent);
          infowindow.open(map, marker);
        }
    })(marker, listing));   
    
}

$(document).ready(function() {
    // map needs to be visible when rendered
    var mapInitialized = false;
    
    if($('div#listings-map-container').is(':visible')) {
        listingsMapInit();
        mapInitialized = true;
    }

    $('a#listings-map-toggle').live("click", function(e){
        e.preventDefault();
        $(this).addClass('disabled');
        $('a#listings-list-toggle').removeClass('disabled');
        
        $('div#listings-map-container').show();
        $('div#listings-list-container').hide();
        
        if(!mapInitialized) {
            listingsMapInit();
            mapInitialized = true;
        }

        $('#directory-tab-value').val('map');
    });
    $('a#listings-list-toggle').live("click", function(e){
        e.preventDefault();
        $(this).addClass('disabled');
        $('a#listings-map-toggle').removeClass('disabled');
        
        $('div#listings-list-container').show();
        $('div#listings-map-container').hide();
        
        $('#directory-tab-value').val('list');
    });
});