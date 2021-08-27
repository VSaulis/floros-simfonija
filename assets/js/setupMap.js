export const setupMap = () => {
  const platform = new H.service.Platform({
    'apikey': 'm4lGHin-vS0UD96y9vw2l_k7Fvgb95nO-b3J6HoNXno'
  });

  const container = document.getElementById('map');
  const longitude = container.getAttribute('data-longitude');
  const latitude = container.getAttribute('data-latitude');

  const mapTypes = platform.createDefaultLayers();

  const map = new H.Map(
    document.getElementById('map'),
    mapTypes.vector.normal.map,
    {zoom: 17, center: {lng: longitude, lat: latitude}}
  );

  const icon = new H.map.Icon("https://cdn3.iconfinder.com/data/icons/tourism/eiffel200.png", {size: {w: 56, h: 56}});

  const hotelMarker = new H.map.Marker({lat: latitude, lng: longitude}, {icon: icon});
  map.addObject(hotelMarker);

  window.addEventListener('resize', () => map.getViewPort().resize());
};