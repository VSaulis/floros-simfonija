import './scss/app.scss';
import {setupGallery} from "./js/setupGallery";
import {setupMasonry} from "./js/setupMasonry";
import {setupMap} from "./js/setupMap";
import {setupReviews} from "./js/setupReviews";

const galleryPage = document.getElementsByClassName('gallery-page');
if (galleryPage.length > 0) setupMasonry();

const homePage = document.getElementsByClassName('home-page');
if (homePage.length > 0) setupReviews();

const map = document.getElementById('map');
if (map) setupMap();

setupGallery();