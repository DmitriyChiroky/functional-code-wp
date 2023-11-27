if (document.querySelector('.wcl-b2-img-loader')) {
	let section = document.querySelector('.wcl-b2-img-loader');

	section.querySelector('.b2-delete').addEventListener('click', function (e) {
		let post_id = section.getAttribute('data-post-id');
	
		let data_req = {
			action: 'action_name',
			post_id: post_id,
		}
	
		if (this.classList.contains('active')) {
			this.classList.remove('active')
		}
	
		let xhr = new XMLHttpRequest();
		xhr.open('POST', wcl_obj.ajax_url, true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
	
		xhr.onload = function (data) {
			if (xhr.status >= 200 && xhr.status < 400) {
				let data = JSON.parse(xhr.responseText);
			};
		};
	
		data_req = new URLSearchParams(data_req).toString();
		xhr.send(data_req);
	})
	
}
