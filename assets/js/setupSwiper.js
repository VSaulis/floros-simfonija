import Swiper, {Navigation, Pagination} from 'swiper';

export const setupSwiper = () => {
  Swiper.use([Pagination, Navigation]);

  new Swiper('.slideshow', {
    slidesPerView: "auto",
    spaceBetween: 1,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
  });
};