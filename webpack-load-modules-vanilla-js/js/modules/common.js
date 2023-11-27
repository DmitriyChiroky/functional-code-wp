




// wcl-section-24

if (document.querySelector('.wcl-section-24')) {
	let section = document.querySelector('.wcl-section-24');
	let slider = section.querySelector('.data-list')
	let swiper = new Swiper(slider, {
		slidesPerView: 'auto',
		spaceBetween: 49,
		slidesOffsetAfter: 0,
		breakpoints: {
			0: {
				slidesOffsetAfter: 0,
			},
			767: {
				slidesOffsetAfter: 50,
			},
			1300: {
				slidesOffsetAfter: 0,
			},
		}
	});
}

// setCookie

export function setCookie(name, value, days) {
	var expires = "";
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + (value || "") + expires + "; path=/";
}


// addGradientForDesc

export function addGradientForDesc(section) {
	section.querySelectorAll('.data-item-desc').forEach(element => {
		let height = element.clientHeight
		let desc_height = element.children[0].clientHeight

		if (desc_height > height) {
			element.classList.add('mask')
		}

		element.addEventListener('mouseover', function (e) {
			let height = this.clientHeight
			let desc_height = this.children[0].clientHeight

			if (desc_height <= height) {
				if (this.classList.contains('mask')) {
					this.classList.remove('mask')
				}
			}
		})

		element.addEventListener('scroll', function (e) {
			let height = this.clientHeight
			let desc_height = this.children[0].clientHeight
			if (Math.ceil(height + this.scrollTop) >= desc_height) {
				this.classList.remove('mask')
			} else {
				this.classList.add('mask')
			}
		})
	});
}
