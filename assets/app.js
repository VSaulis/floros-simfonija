import './scss/app.scss';
import {setupGallery} from "./js/setupGallery";
import {setupMasonry} from "./js/setupMasonry";
import {setupMap} from "./js/setupMap";

const galleryPage = document.getElementsByClassName('gallery-page');
if (galleryPage.length > 0) setupMasonry();

setupGallery();
setupMap();
