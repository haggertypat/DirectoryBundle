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
                infowindow.setContent('<h4>' + listing.name + '</h4>' + listing.address + '<br/><br/><a class="btn" href="' + listing.profileLink + '"><i class="icon-search"></i> View Profile</a>');
                infowindow.open(map, marker);
            }
        })(marker, listing));
    }
        
    map.fitBounds(bounds);
            
    // set a minimum zoom level
    google.maps.event.addListener(map, 'zoom_changed', function() {
        if (map.getZoom() > 12) map.setZoom(12);
    });
}

function profileMapInit()
{
    var listingLatLng = new google.maps.LatLng(listing.lat, listing.lng);

    var mapOptions = {
        center: listingLatLng,
        zoom: 9,
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
          infowindow.setContent('<h4>' + listing.name + '</h4>' + listing.address);
          infowindow.open(map, marker);
        }
    })(marker, listing));   
    
}