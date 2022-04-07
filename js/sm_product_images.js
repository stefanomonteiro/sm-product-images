document.addEventListener("DOMContentLoaded", function () {
	// ! Create Mobile Carousel
	let options = {
		cellAlign: "left",
		wrapAround: true,
		percentPosition: false,
		// pageDots: false,
		// prevNextButtons: false,
	};
	let imageCarousel;
	if (window.innerWidth < 768) {
		imageCarousel = new Flickity(
			document.querySelector(".sm_product-images-carousel"),
			options
		);
	}

	// ! Variable Image Manipulation
	// Loop trough php obj to get appropriate attributes being used by the product (ie. Color, size, etc.)
	sm_product_images["data-attributes"].forEach((attribute) => {
		const selectAttribute = document.querySelector(
			`[data-attribute_name=${attribute}]`
		);
		const variableImages = document.querySelectorAll(".sm_variable_image");

		selectAttribute.addEventListener("change", function (e) {
			if (window.innerWidth > 767) {
				// Tablet and Desktop
				variableImages.forEach((image) => {
					image.style.zIndex = -1;
					image.style.display = "none";

					if (image.dataset.attribute_pa_cor === this.value) {
						image.style.zIndex = 1;
						image.style.display = "unset";
					}
				});
			} else {
				// Carousel Manipulation (Mobile)
				let selectedIndex;
				imageCarousel.cells.forEach((cell, index) => {
					if (cell.element.dataset.attribute_pa_cor === this.value) {
						selectedIndex = index;
					}
				});
				imageCarousel.select(selectedIndex);
			}
		});
	});
});
