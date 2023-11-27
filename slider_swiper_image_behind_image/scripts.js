	// wcl-acf-block-2

	if (document.querySelector('.wcl-acf-block-2')) {
		let section = document.querySelector('.wcl-acf-block-2')
		let images = section.querySelector('.data-gallery').getAttribute('data-images')
		images = JSON.parse(images);

		function gallery_next_img() {
			let gallery = section.querySelector('.data-gallery')
			let active = gallery.querySelector('img.active')
			let nodes = Array.prototype.slice.call(gallery.querySelectorAll('img'));
			let active_index = nodes.indexOf(active);
			let images = gallery.getAttribute('data-images')
			let items_image = gallery.querySelectorAll('img')
			images = JSON.parse(images);
			let index = parseInt(gallery.getAttribute('data-index'))
			if (index == 3) {
				index = 0
			}
			index = index + 1

			gallery.setAttribute('data-index', index)

			let active_index_offset = -1;
			if (!items_image[active_index - 1]) {
				active_index_offset = 1;
			}

			items_image[active_index + active_index_offset].classList.remove('next')
			items_image[active_index + active_index_offset].classList.add('active')
			items_image[active_index].classList.remove('active')
			items_image[active_index].classList.add('next')

			setTimeout(() => {
				if (images.length > 2) {
					if (index == 3) {
						index = 0
					}
					gallery.querySelector('img.next').src = images[index]
				}
			}, 0);
		}


		if (section.querySelector('.mod-more-one.data-gallery-out')) {
			section.querySelector('.mod-more-one.data-gallery-out').addEventListener('click', function (e) {
				gallery_next_img();
			})
		}

	}