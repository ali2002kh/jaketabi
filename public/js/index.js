document.querySelectorAll('.multiple-card-slider .carousel').forEach(function(element) {
  var currentCarouselId = '#' + element.id;
  const multipleItemCarousel = document.querySelector(currentCarouselId);
  
  if(window.matchMedia("(min-width:576px)").matches) {
      const carousel = new bootstrap.Carousel(multipleItemCarousel, { interval: false, wrap: false });
      var carouselWidth = document.querySelector(currentCarouselId + ' .carousel-inner').scrollWidth;
      var cardWidth = document.querySelector(currentCarouselId + ' .carousel-item').offsetWidth;
      var scrollPosition = 0;
      
      document.querySelector(currentCarouselId + ' .carousel-control-next').addEventListener('click', function() {
          if(scrollPosition < (carouselWidth - (cardWidth * 4))) {
              console.log('next');
              scrollPosition = scrollPosition + cardWidth;
              document.querySelector(currentCarouselId + ' .carousel-inner').scroll({left: scrollPosition , behavior:"smooth"});
          }
      });
      
      document.querySelector(currentCarouselId + ' .carousel-control-prev').addEventListener('click', function() {
          if(scrollPosition > 0) {
              console.log('prev');
              scrollPosition = scrollPosition - cardWidth;
              document.querySelector(currentCarouselId + ' .carousel-inner').scroll({left: scrollPosition , behavior:"smooth"});
          }
      });
  } else {
      multipleItemCarousel.classList.add('slide');
  }
});

document.querySelectorAll('.card-slider .carousel').forEach(function(element) {
    var currentCarouselId = '#' + element.id;
    const multipleItemCarousel = document.querySelector(currentCarouselId);
    
    if(window.matchMedia("(min-width:576px)").matches) {
        const carousel = new bootstrap.Carousel(multipleItemCarousel, { interval: false, wrap: false });
        var carouselWidth = document.querySelector(currentCarouselId + ' .carousel-inner').scrollWidth;
        var cardWidth = document.querySelector(currentCarouselId + ' .carousel-item-activity').offsetWidth;
        var scrollPosition = 0;
        
        document.querySelector(currentCarouselId + ' .carousel-control-next').addEventListener('click', function() {
            if(scrollPosition < (carouselWidth - (cardWidth * 4))) {
                console.log('next');
                scrollPosition = scrollPosition + cardWidth;
                document.querySelector(currentCarouselId + ' .carousel-inner').scroll({left: scrollPosition , behavior:"smooth"});
            }
        });
        
        document.querySelector(currentCarouselId + ' .carousel-control-prev').addEventListener('click', function() {
            if(scrollPosition > 0) {
                console.log('prev');
                scrollPosition = scrollPosition - cardWidth;
                document.querySelector(currentCarouselId + ' .carousel-inner').scroll({left: scrollPosition , behavior:"smooth"});
            }
        });
    } else {
        multipleItemCarousel.classList.add('slide');
    }
  });