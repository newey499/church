/******************************************

googlemaps.js

Date		Programmer			Description
12/06/2012	CDN			Created


********************************************/

var loadGoogleMaps = false;


/*************
Loads Google Map into div with id ="location_map_canvas">
**********************/
function loadGoogleMapApiVer2()
{

	if (document.getElementById('location_map_canvas') && GBrowserIsCompatible())
	{
		// Church lat and long
		var lat = 52.45752599999999;
		var long = -2.1140520;
		var zoomLevel = 16;  // 13;

		var map = new GMap2(document.getElementById('location_map_canvas'));
		var marker = new GMarker(new GLatLng(lat, long), zoomLevel);
		var html = 'Christ Church Lye';

		map.setCenter(new GLatLng(lat, long), zoomLevel);
		map.addControl(new GSmallMapControl());
		map.addControl(new GMapTypeControl());
		map.addControl(new GScaleControl());
		map.addOverlay(marker);
		//marker.openInfoWindowHtml(html,{maxWidth:50});
	}

}