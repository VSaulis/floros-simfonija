import lightGallery from "lightgallery";
import lgZoom from "lightgallery/plugins/zoom";
import lgThumbnail from "lightgallery/plugins/thumbnail";

export const setupGallery = () => {
  lightGallery(document.getElementById('images'), {
    plugins: [lgZoom, lgThumbnail],
    selector: '.image-container',
    thumbnail: true,
  });
};