/* 
submit_ajax
 */
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.wcl-enter-pass')) {
        let section = document.querySelector('.wcl-enter-pass');

        section.querySelector('input[name="password"]').addEventListener("input", function() {
            let inputField = this;
            let inputValue = inputField.value;

            // Remove any non-digit characters
            inputValue = inputValue.replace(/\D/g, '');

            // Truncate to at most four digits
            if (inputValue.length > 4) {
                inputValue = inputValue.slice(0, 4);
            }

            inputField.value = inputValue;
        });


        section.querySelector('.data-form').addEventListener('submit', function(e) {
            e.preventDefault()
            let form = this
            let password = form.querySelector('input').value
            let tree_id = section.getAttribute('data-tree-id')

            let data_req = {
                action: 'enter_password_check',
                password: password,
                tree_id: tree_id,
            }

            form.querySelector('.data-form-pass').classList.remove('mod-error')

            if (form.querySelector('.data-form-notify')) {
                form.querySelector('.data-form-notify').remove()
            }

            let xhr = new XMLHttpRequest();
            xhr.open('POST', wcl_obj.ajax_url, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

            xhr.onload = function(data) {
                if (xhr.status >= 200 && xhr.status < 400) {
                    var data = JSON.parse(xhr.responseText);

                    if (data.error) {
                        let tag = '<div class="data-form-notify">' + data.error + '</div>'
                        form.insertAdjacentHTML('beforeend', tag)
                        form.querySelector('.data-form-pass').classList.add('mod-error')
                    } else if (data.submit) {
                        form.querySelector('.data-form-pass').classList.remove('mod-error')
                    }
                };
            };

            data_req = new URLSearchParams(data_req).toString();
            xhr.send(data_req);
        })
    }
});