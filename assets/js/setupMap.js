export const setupMap = () => {
  window.onload = () => {
    const container = document.getElementById('map');
    const longitude = container.getAttribute('data-longitude');
    const latitude = container.getAttribute('data-latitude');

    const uluru = {lat: Number(latitude), lng: Number(longitude)};

    const map = new google.maps.Map(container, {zoom: 18, center: uluru, disableDefaultUI: true});

    const marker = new google.maps.Marker({
      position: uluru,
      map: map,
    });
  };
};