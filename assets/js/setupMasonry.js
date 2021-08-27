import Masonry from 'masonry-layout'

export const setupMasonry = () => {
  const masonry = new Masonry('.masonry', {
    itemSelector: '.masonry-item',
  });

  masonry.layout();
};