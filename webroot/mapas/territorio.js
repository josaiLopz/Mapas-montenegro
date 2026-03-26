/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * SPDX-License-Identifier: Apache-2.0
 */
// This example creates a simple polygon representing the Bermuda Triangle. Note
// that the code specifies only three LatLng coordinates for the polygon. The
// API automatically draws a stroke connecting the last LatLng back to the first
// LatLng.

const countries = {
 
    mx: {
      center: { lat: 23.6, lng: -102.5 },
      zoom: 5,
    },
   
  };

function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: countries["mx"].zoom,
      center: countries["mx"].center,
      mapTypeControl: false,
      panControl: false,
      zoomControl: false,
      streetViewControl: false,
    });
    // Define the LatLng coordinates for the polygon's path. Note that there's
    // no need to specify the final coordinates to complete the polygon, because
    // The Google Maps JavaScript API will automatically draw the closing side.
    const triangleCoords = [
      { lat: 31.574573128212293, lng: -113.17871093749999 },
      { lat: 30.898233936068536, lng: -109.31152343749999 },
      { lat: 28.067876464032377, lng: -108.30078124999999 },
      { lat: 29.492939453270022, lng: -111.15722656249999 },
    ];

  
    const triangleCoords1 = [
        { lat: 30.898233936068536, lng: -109.31152343749999 },
        { lat: 28.067876464032377, lng: -108.30078124999999 },
        { lat: 26.348330199405922, lng:  -103.77441406249999 },
        { lat: 29.3398211512735, lng:  -104.38964843749999 },

    ];

      const triangleCoords2 = [
        { lat: 27.348251398702335, lng: -108.55598745780676 },
        { lat: 27.348251398702335, lng: -107.42512808280676 },
        { lat: 24.180981934477188, lng:  -106.12825308280676 },
        { lat: 23.781222245924084, lng: -106.91926870780676 },
      ];

      

    const bermudaTriangle = new google.maps.Polygon({
      paths: triangleCoords,
      strokeColor: "#FF0000",
      strokeOpacity: 0.8,
      strokeWeight: 3,
      fillColor: "#FF0000",
      fillOpacity: 0.35,
    });
  
    bermudaTriangle.setMap(map);

    const bermudaTriangle1 = new google.maps.Polygon({
        paths: triangleCoords1,
        strokeColor: "#FFFF00",
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: "#FFFF00",
        fillOpacity: 0.35,
      });
    
      bermudaTriangle1.setMap(map);

      const bermudaTriangle2 = new google.maps.Polygon({
        paths: triangleCoords2,
        strokeColor: "#FF00FF",
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: "#FF00FF",
        fillOpacity: 0.35,
      });
    
      bermudaTriangle2.setMap(map);
  }
  
  window.initMap = initMap;
  