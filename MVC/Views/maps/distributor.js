(function ($) {
    $(document).ready(function () {

        var mapElement = $('.map-canvas');

        var map = new google.maps.Map(mapElement[0], {
            center: new google.maps.LatLng(51.50, 0.12),
            zoom: 7,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scaleControl: true
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                map.setCenter(pos);

                /*
                 var image = 'images/beachflag.png';
                 var image = {
                 url: 'images/beachflag.png',
                 // This marker is 20 pixels wide by 32 pixels tall.
                 size: new google.maps.Size(20, 32),
                 // The origin for this image is 0,0.
                 origin: new google.maps.Point(0,0),
                 // The anchor for this image is the base of the flagpole at 0,32.
                 anchor: new google.maps.Point(0, 32)
                 };
                 */

                var marker = new google.maps.Marker({
                    position: pos,
                    // map: map,
                    // icon:image,
                    title: "I'm here"
                });

            }, function () {
                // handleNoGeolocation(true);
            });
        } else {
            // handleNoGeolocation(false);
        }

        $.each(window.markers, function (user_id, user_markers) {
            $.each(user_markers, function (id, marker) {
                var pos = new google.maps.LatLng(marker.lat, marker.lng);
                var m = new google.maps.Marker({
                    position: pos,
                    map: map,
                    // icon:image,
                    title: marker.addr
                });

                //google.maps.event.addListener(m, 'mousedown', function () {
                //    alert(1);
                //});
                var contentString = '<div>' +
                    marker.addr +
                    (marker.info ? '<br/>' + marker.info : '') +
                    (marker.email ? ('<br/>E-Mail: <a href="mailto:' + marker.email + '">' + marker.email + '</a>') : '') +
                    '</div>';

                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });

                google.maps.event.addListener(m, 'mousedown', function () {
                    infowindow.open(map, m);
                });


            });
        });


    });
})(jQuery);