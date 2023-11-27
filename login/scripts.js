document.addEventListener('DOMContentLoaded', function(){
 
    	// page-template-login

		if (document.querySelector('.page-template-login')) {
			if (document.querySelector('.form__login')) {
				document.querySelector('.form__login').addEventListener("submit", function (e) {
					e.preventDefault();
					let form = this;
					let form_state = form.getAttribute('data-state')

					if (form_state == 'login') {
						let user_email = form.querySelector('input[name="loginEmail"]').value
						let user_pass = form.querySelector('input[name="loginPassword"]').value
						let user_remember = form.querySelector('input[name="checkBox"]').checked
						let data_req = {
							action: 'wcl_login_form',
							user_email: user_email,
							user_pass: user_pass,
							user_remember: user_remember,
						}
						form.querySelector('.data-form-error').innerHTML = '';
						let xhr = new XMLHttpRequest();
						xhr.open('POST', wcl_obj.ajax_url, true);
						xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
						xhr.onload = function (data) {
							if (xhr.status >= 200 && xhr.status < 400) {
								let data = JSON.parse(xhr.responseText);
								if (data.errors) {
									form.querySelector('.data-form-error').innerHTML = data.errors;
								} else if (data.redirect) {
									window.location.href = data.redirect;
								}
							}
						};
						data_req = new URLSearchParams(data_req).toString();
						xhr.send(data_req);
					} else if (form_state == 'reset-password') {
						let user_email = form.querySelector('input[name="loginEmail"]').value
						let data_req = {
							action: 'wcl_reset_pass',
							user_email: user_email,
							form_state: form_state,
						}
						form.querySelector('.data-form-error').innerHTML = '';
						let xhr = new XMLHttpRequest();
						xhr.open('POST', wcl_obj.ajax_url, true);
						xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
						xhr.onload = function (data) {
							if (xhr.status >= 200 && xhr.status < 400) {
								let data = JSON.parse(xhr.responseText);
								if (data.errors) {
									form.querySelector('.data-form-error').innerHTML = data.errors;
								} else if (data.redirect) {
									window.location.href = data.redirect;
								}
							}
						};
						data_req = new URLSearchParams(data_req).toString();
						xhr.send(data_req);
					} else if (form_state == 'code-conformation') {
						let user_code = form.querySelector('input[name="code"]').value
						let data_req = {
							action: 'wcl_reset_pass',
							user_code: user_code,
							form_state: form_state,
						}
						form.querySelector('.data-form-error').innerHTML = '';
						let xhr = new XMLHttpRequest();
						xhr.open('POST', wcl_obj.ajax_url, true);
						xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
						xhr.onload = function (data) {
							if (xhr.status >= 200 && xhr.status < 400) {
								let data = JSON.parse(xhr.responseText);
								if (data.errors) {
									form.querySelector('.data-form-error').innerHTML = data.errors;
								} else if (data.redirect) {
									window.location.href = data.redirect;
								}
							}
						};
						data_req = new URLSearchParams(data_req).toString();
						xhr.send(data_req);

					} else if (form_state == 'new-password') {
						let new_password = form.querySelector('input[name="new_password"]').value
						let confirm_password = form.querySelector('input[name="confirm_password"]').value
						let data_req = {
							action: 'wcl_reset_pass',
							new_password: new_password,
							confirm_password: confirm_password,
							form_state: form_state,
						}
						form.querySelector('.data-form-error').innerHTML = '';
						let xhr = new XMLHttpRequest();
						xhr.open('POST', wcl_obj.ajax_url, true);
						xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
						xhr.onload = function (data) {
							if (xhr.status >= 200 && xhr.status < 400) {
								let data = JSON.parse(xhr.responseText);
								if (data.errors) {
									form.querySelector('.data-form-error').innerHTML = data.errors;
								} else if (data.redirect) {
									window.location.href = data.redirect;
								}
							}
						};
						data_req = new URLSearchParams(data_req).toString();
						xhr.send(data_req);
					}
				});
			}
		}
        
});