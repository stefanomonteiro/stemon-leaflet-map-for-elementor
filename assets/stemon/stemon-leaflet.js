class LeafletJSMap extends elementorModules.frontend.handlers.Base {
  getDefaultSettings() {
    return {
      controls: {
        apiKey: this.getElementSettings("api_key"),
        latitude: this.getElementSettings("lat"),
        longitude: this.getElementSettings("lon"),
        zoom: this.getElementSettings("zoom"),
        mapStyle: this.getElementSettings("map_style"),
        pinImage: this.getElementSettings("pin"),
        pinSize: this.getElementSettings("pin_size"),
        pins: this.getElementSettings("pins"),
        getCoord: this.getElementSettings("get_coord")
      }
    };
  }

  getDefaultElements() {
    const controls = this.getSettings("controls");
    return {
      apiKey: this.$element.find(controls.apiKey),
      latitude: this.$element.find(controls.latitude),
      longitude: this.$element.find(controls.longitude),
      zoom: this.$element.find(controls.zoom),
      mapStyle: this.$element.find(controls.mapStyle),
      pinImage: this.$element.find(controls.pinImage),
      pinSize: this.$element.find(controls.pinSize),
      pins: this.$element.find(controls.pins),
      getCoord: this.$element.find(controls.getCoord)
    };
  }

  bindEvents() {
    const set = {
      apiKey: this.elements.apiKey.selector,
      latitude: this.elements.latitude.selector,
      longitude: this.elements.longitude.selector,
      zoom: this.elements.zoom.selector.size,
      mapStyle: this.elements.mapStyle.selector,
      pinImage: this.elements.pinImage.selector.url,
      pinSize: this.elements.pinSize.selector,
      pins: this.elements.pins.selector,
      getCoord: this.elements.getCoord.selector
    };

    var mymap = L.map("stemonLeafletMap").setView(
      [set.latitude, set.longitude],
      set.zoom
    );
    L.tileLayer(
      `https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=${set.apiKey}`,
      {
        attribution: `Developed by <a href="http://stefanomonteiro.com/" target="_blank">Stefano Monteiro</a>`,
        maxZoom: 18,
        id: `mapbox.${set.mapStyle}`,
        accessToken: "your.mapbox.access.token"
      }
    ).addTo(mymap);

    var newIcon = L.icon({
      iconUrl: set.pinImage,
      iconSize: [set.pinSize, set.pinSize]
    });

    set.pins.forEach(pin => {
      let pinLat = pin.pin_lat || pin.attributes.pin_lat;
      let pinLon = pin.pin_lon || pin.attributes.pin_lon;
      let pinTitle = pin.pin_title || pin.attributes.pin_title;
      let pinContent = pin.pin_content || pin.attributes.pin_content;
      return L.marker([pinLat, pinLon], { icon: newIcon })
        .addTo(mymap)
        .bindPopup(`<h3>${pinTitle}</h3><p>${pinContent}</p>`);
    });

    if (set.getCoord === "yes") {
      var popup = L.popup();
      function onMapClick(e) {
        popup
          .setLatLng(e.latlng)
          .setContent(
            "Latitude: " + e.latlng.lat + " <br> Longitude: " + e.latlng.lng
          )
          .openOn(mymap);
      }
      mymap.on("click", onMapClick);
    }
  }
}

jQuery(window).on("elementor/frontend/init", () => {
  const addHandler = $element => {
    elementorFrontend.elementsHandler.addHandler(LeafletJSMap, {
      $element
    });
  };

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/leaflet-map.default",
    addHandler
  );
});
