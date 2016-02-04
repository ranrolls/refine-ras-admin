(function($){
    $.fn.mapbrowse = function(configs){
        var default_configs = {
            data: '',
            mapOptions: {
                zoom: 2,
                center: {lat: 62.323907, lng: -150.109291},
                scrollwheel: false,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.LARGE
                },
                fitBoundMaxZoom: 15
            },
            markerUrl: '',
            JUriRoot: '',
            offsetX: 30,
            offsetY: 100
        };

        configs.mapOptions = $.extend({}, default_configs.mapOptions, configs.mapOptions);
        var opts = $.extend({}, default_configs, configs);

        return this.each(function(){
            var self = $(this),
                markers = [],
                map,
                latlngbounds,
                markerClusterer;

            var mapDiv = self.find('.map-canvas');

            function initialize() {
                // Initialize map
                map = new google.maps.Map(self.find('.map-canvas').get(0), opts.mapOptions);
                latlngbounds = new google.maps.LatLngBounds();

                // This is needed to set the zoom after fitbounds,
                google.maps.event.addListener(map, 'zoom_changed', function() {
                    zoomChangeBoundsListener =
                        google.maps.event.addListener(map, 'bounds_changed', function(event) {
                            if (this.getZoom() > opts.mapOptions.fitBoundMaxZoom && this.initialZoom == true) {
                                // Change max/min zoom here
                                this.setZoom(opts.mapOptions.fitBoundMaxZoom);
                                this.initialZoom = false;
                            }
                            google.maps.event.removeListener(zoomChangeBoundsListener);
                        });
                });

                map.initialZoom = true;

                // Add marker from db
                if(opts.data != null && opts.data != ""){
                    var data = JSON.parse(opts.data);
                    $.each(data, function(key, value){
                        value.remove = 0;
                        addMarkerToMap(value, true, (opts.type == 'edit'));
                    });

                    var _markers = [];
                    for (i in markers) {
                        if (markers.hasOwnProperty(i)) {
                            _markers.push(markers[i]);
                        }
                    }

                    markerClusterer = new MarkerClusterer(map, _markers, {
                        maxZoom: 12,
                        gridSize: null
                    });

                    map.setCenter(latlngbounds.getCenter());
                    map.fitBounds(latlngbounds);
                }

                // Find my location
                self.find(".my_location").click(function(){
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            var myLocationLatlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                            if(markers['my_location'] === undefined){
                                markers['my_location'] = new google.maps.Marker({
                                    map: map,
                                    position: myLocationLatlng,
                                    icon: 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=O|FFFF00|000000'
                                });
                            }else{
                                markers['my_location'].setPosition(myLocationLatlng);
                            }

                            geocoder.geocode({'latLng': myLocationLatlng}, function (results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    if (results[0]) {
                                        if(markers['origin'] === undefined){
                                            addmarkerOrigin(myLocationLatlng, results[0].formatted_address);
                                        }else{
                                            markers['origin'].setPosition(myLocationLatlng);
                                            markers['origin'].infowindow.setContent(results[0].formatted_address);
                                        }
                                    }
                                }
                            });

                            if(position.coords.accuracy > 100){
                                if(markers['my_location_circle'] === undefined){
                                    var populationOptions = {
                                        strokeColor: '#FF0000',
                                        strokeOpacity: 0.8,
                                        strokeWeight: 2,
                                        fillColor: '#FF0000',
                                        fillOpacity: 0.25,
                                        map: map,
                                        center: myLocationLatlng,
                                        radius: (position.coords.accuracy)
                                    };
                                    // Add the circle for this city to the map.
                                    markers['my_location_circle'] = new google.maps.Circle(populationOptions);
                                }else{
                                    markers['my_location_circle'].setRadius((position.coords.accuracy));
                                }
                            }
                            map.setZoom(15);
                            map.setCenter(myLocationLatlng);
                        }, function(){
                            handleNoGeolocation(true);
                        }, {timeout:60000, enableHighAccuracy:true});
                    }else{
                        handleNoGeolocation(false);
                    }
                });

                function handleNoGeolocation(errorFlag) {
                    if (errorFlag) {
                        var content = "Error: The Geolocation service failed.";
                    } else {
                        var content = "Error: Your browser does not support geolocation.";
                    }

                    var options = {
                        map: map,
                        position: opts.mapOptions.center,
                        content: content
                    };

                    var infowindow = new google.maps.InfoWindow(options);
                    map.setCenter(options.position);
                }

                // Add marker to map
                function addMarkerToMap(data) {
                    var latLng = new google.maps.LatLng(data.lat, data.lng);
                    if(latLng){
                        markers[data.id] = new google.maps.Marker({
                            map: map,
                            position: latLng,
                            icon: data.marker_icon ? opts.markerUrl + data.marker_icon : null,
                            draggable: (opts.type == 'edit'),
                            animation: google.maps.Animation.DROP
                        });

                        markerAddEvent(data);

                        latlngbounds.extend(latLng);
                    }
                }

                // Add marker event
                function markerAddEvent(data){
                    var html = generateMarkerContent(data);
                    if(html){
                        markers[data.id].infowindow = new InfoBox({
                            content: html,
                            disableAutoPan: false,
                            maxWidth: 150,
                            pixelOffset: new google.maps.Size(-50, -20),
                            boxStyle: {
                                width: "290px"
                            },
                            closeBoxMargin: "4px",
                            closeBoxURL: opts.JUriRoot + "/components/com_judirectory/assets/img/pop_up-close.png",
                            // enableEventPropagation: true,
                            infoBoxClearance: new google.maps.Size(1, 1)
                        });
                        /*markers[data.id].infowindow = new google.maps.InfoWindow({
                         content: html,
                         maxWidth: 150
                         });*/

                        markers[data.id].infowindow.marker_key = data.id;
                        google.maps.event.addDomListener(markers[data.id], 'click', function () {
                            for (i in markers) {
                                if (markers.hasOwnProperty(i) && i != data.id) {
                                    markers[i].infowindow.setMap(null);
                                }
                            }
                            markers[data.id].infowindow.open(map, markers[data.id]);

                            setTimeout(function(){
                                map.panTo(markers[data.id].getPosition());
                                // if map is small
                                var iWidth = $(markers[data.id].infowindow.div_).width();
                                var iHeight = $(markers[data.id].infowindow.div_).height();
                                if((mapDiv.width() / 2) < (iWidth + opts.offsetX)){
                                    var offsetX = iWidth - (mapDiv.width() / 2) + opts.offsetX;
                                    map.panBy(offsetX,0);
                                }
                                if((mapDiv.height() / 2) < (iHeight + opts.offsetY) ){
                                    var offsetY = -(iHeight - (mapDiv.height() / 2) + opts.offsetY);
                                    map.panBy(0,offsetY);
                                }
                            },0);

                        });
                    }
                }

                function generateMarkerContent(data){
                    var html = '';
                    if(data.address || data.description){
                        html += '<div class="marker-holder">';
                        html += '<div class="marker-content with-image">';
                        if(data.image){
                            html += '<img src="'+data.image+'" alt="'+data.title+'">';
                        }
                        html += '<div class="map-item-info">';
                        if(data.title){
                            html += '<div class="title"><a href="'+data.link+'">'+data.title+'</a></div>';
                        }
                        if(data.address){
                            html += '<div class="address">'+data.address+'</div>';
                        }
                        if(data.description){
                            html += '<div class="description">'+data.description+'</div>';
                        }
                        html += '</div>';
                        html += '<div class="arrow"></div>';
                        html += '</div>';
                        html += '</div>';
                    }
                    return html;
                }

                /*
                 image = {
                 url: "media/com_judirectory/images/marker_icon.png",
                 scaledSize: new google.maps.Size(25 * 1.5, 30 * 1.5)
                 }	 */
                /*-------------------------END FONTEND--------------------------------*/
            }

            //initialize function
            google.maps.event.addDomListener(window, "load", initialize);

        });
    }
})(jQuery);