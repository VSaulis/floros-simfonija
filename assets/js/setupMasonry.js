import Masonry from 'masonry-layout'

export const setupMasonry = () => {
  window.onload = () => {
    const masonry = new Masonry('.masonry', {
      itemSelector: '.masonry-item',
    });

    masonry.layout();
  };
};