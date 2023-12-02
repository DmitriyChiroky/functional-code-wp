	// Section 2

	if (document.querySelector('.wcl-section-2')) {
		let section = document.querySelectorAll('.wcl-section-2')

		section.forEach(element => {
			let swiper = new Swiper(element.querySelector('.data-list'), {
				slidesPerView: 3,
				loop: true,
				speed: 1000,
				autoplay: {
					delay: 3000,
				},
				navigation: {
					nextEl: element.querySelector('.mod-next'),
					prevEl: element.querySelector('.mod-prev'),
				},
				breakpoints: {
					0: {
						slidesPerView: 1,
					},
					576: {
						slidesPerView: 2,
					},
					1000: {
						slidesPerView: 3,
					}
				}
			});
		});
	}
