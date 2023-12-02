	// wcl-section-1

	if (document.querySelector('.wcl-section-1')) {
		let section = document.querySelector('.wcl-section-1')
		let btn_load_more = section.querySelector('.data-b2-load-more')

		// load_post

		function load_post(paged_new) {
			let paged = -1;

			if (paged_new) {
				paged = paged_new;
			}

			let data_req = {
				action: 'load_post',
				paged: parseInt(paged) + 1,
			}

			section.querySelector('.data-list').classList.add('active')
			btn_load_more.setAttribute('disabled', 'disabled')

			let xhr = new XMLHttpRequest();
			xhr.open('POST', wcl_obj.ajax_url, true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
			xhr.onload = function (data) {
				if (xhr.status >= 200 && xhr.status < 400) {
					var data = JSON.parse(xhr.responseText);
					console.log(data)

					if (paged_new) {
						section.querySelector('.data-list').insertAdjacentHTML('beforeend', data.posts);
						section.querySelector('.data-b2-load-more .b2-container').innerHTML = data.button;
					} else {
						section.querySelector('.data-list').innerHTML = data.posts;
						section.querySelector('.data-b2-load-more .b2-container').innerHTML = data.button;
					}
				};
			};
			data_req = new URLSearchParams(data_req).toString();
			xhr.send(data_req);
		}

		// btn_load_more

		if (btn_load_more) {
			btn_load_more.addEventListener("click", function (e) {
				e.preventDefault();
				if (e.target.classList.contains('b2-btn')) {
					if (e.target.getAttribute("disable") == 'disable') {
						return false;
					}
					
					let paged = e.target.getAttribute("data-page");
					load_post(paged);
				}
			});
		}
	}

