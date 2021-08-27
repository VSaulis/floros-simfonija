import Swiper, {Navigation, Pagination} from 'swiper';

export const setupReviews = () => {
  Swiper.use([Pagination, Navigation]);

  new Swiper('.reviews', {
    slidesPerView: 3,
    spaceBetween: 30,
    initialSlide: 1,
    centeredSlides: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
  });
};