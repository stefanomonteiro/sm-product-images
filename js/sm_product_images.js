document.addEventListener("DOMContentLoaded", function () {
  
  const timeOut = window.location.href.includes("elementor-preview") ? 6000 : 0; 

  // Create as many carousel as in the page (each shortcode must contain a different extra_class)
  function createCarousel(carousel, options) {
    const extraClass = carousel.classList[1];

    const newCarousel = new Flickity(carousel, options);

    // Setup Nav Buttons classes (add extra class if it has it)
    const carouselPrevButtonClass = extraClass
      ? `.carousel-slider-prev.${extraClass}`
      : `.carousel-slider-prev`;
    const carouselNextButtonClass = extraClass
      ? `.carousel-slider-next.${extraClass}`
      : `.carousel-slider-next`;
    const selectButtonsClass = extraClass
      ? `.carousel-slider-select.${extraClass}`
      : `.carousel-slider-select`;



    // Prev    
    if (document.querySelector(carouselPrevButtonClass)) {
      const carouselPrevButton = document.querySelector(carouselPrevButtonClass);
      carouselPrevButton.addEventListener("click", (e) => {
        newCarousel.previous();
      });
    }
    // Next
    if (document.querySelector(carouselNextButtonClass)) {
      const carouselNextButton = document.querySelector(carouselNextButtonClass);
      carouselNextButton.addEventListener("click", (e) => {
        newCarousel.next();
      });
    }
    // Select
    if (document.querySelectorAll(selectButtonsClass).length) {
      const selectButtons = document.querySelectorAll(selectButtonsClass);
      selectButtons.forEach((button, index, array) => {
        button.addEventListener("click", (e) => {
          addSelectedClass(index, array);
          newCarousel.select(index);
        });
      });

      // On Change
      newCarousel.on("change", function (index) {
        // console.log("Flickity change " + index);
        addSelectedClass(index, selectButtons);
      });

      // Add class to navigation
      const addSelectedClass = (index, array) => {
        array.forEach((select) => {
          select.classList.remove("slide_selected");
        });
        array[index].classList.add("slide_selected");
      };

      // Add slide_selected class on init
      selectButtons[0].classList.add("slide_selected");
    }
  }

  setTimeout(() => {

    // ! Full Width Carousel
    if (document.querySelector(".sm_product-images-carousel")) {
      let options = {
        cellAlign: "left",
        wrapAround: true,
        percentPosition: false,
        // pageDots: false,
        prevNextButtons: false,
      };

      const carouselArray = document.querySelectorAll(
        ".sm_product-images-carousel"
      );
      if (window.innerWidth < 768) {
      carouselArray.forEach((carousel) => {
          createCarousel(carousel, options);
        });
      }
    }

  }, timeOut);
});
