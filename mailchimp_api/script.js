
document.addEventListener('DOMContentLoaded', function(){
 	// wcl-newsletter

     if (document.querySelector('.wcl-newsletter')) {
		let section = document.querySelector('.wcl-newsletter')

		section.querySelector('.data-form').addEventListener('submit', function (e) {
			e.preventDefault();
			let form = this;
			let email = form.querySelector('input[name="email"]').value

			let data_req = {
				action: 'wcl_subscribe',
				email: email,
			}

			if (form.querySelector('.data-form-note')) {
				form.querySelector('.data-form-note').remove()
			}

			form.querySelector('button').setAttribute('disabled', 'disabled')

			var xhr = new XMLHttpRequest();
			xhr.open('POST', wcl_obj.ajax_url, true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
			xhr.onload = function (data) {
				if (xhr.status >= 200 && xhr.status < 400) {
					var data = JSON.parse(xhr.responseText);

					form.querySelector('button').removeAttribute('disabled')

					if (data['message']) {
						let tag = '<div class="data-form-note">' + data['message'] + '</div>';
						form.insertAdjacentHTML('beforeend', tag)
					}
				}
			};

			data_req = new URLSearchParams(data_req).toString();
			xhr.send(data_req);
		});
	}
});

