
document.addEventListener('DOMContentLoaded', function () {
    /* 
    wcl_img_loader
    */
    function wcl_img_loader() {
        if (document.querySelector('.wcl-b2-img-loader')) {
            let section = document.querySelector('.wcl-b2-img-loader');
            let popup = document.querySelector('.wcl-member-popup')

            // delete

            if (section.querySelector('.b2-delete')) {
                section.querySelector('.b2-delete').addEventListener('click', function (e) {
                    let post_id = popup.getAttribute('data-post-id');

                    section.querySelector('.b2-img img').remove()

                    let data_req = {
                        action: 'member_remove_picture',
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

            // render func

            function wclEncodeImgtoBase64(element) {
                let img_new = document.createElement('img');
                let img = element.files[0];

                let reader = new FileReader();
                reader.readAsDataURL(img);
                reader.onload = function () {
                    img_new.src = reader.result;

                    if (popup.classList.contains('mod-edit')) {
                        section.querySelector('.b2-img').appendChild(img_new);
                        section.querySelector('.b2-delete').classList.add('active')
                    } else {
                        section.querySelector('label').appendChild(img_new);
                    }
                }
            }

            // render on change event

            section.querySelector('input').addEventListener('change', function (event) {
                if (this.files[0].size > 2000000) {
                    alert('Image size exceeds 2MB');
                    return;
                }
                let file = this.files[0];
                let fileType = file["type"];
                let validImageTypes = ["image/gif", "image/jpeg", "image/png"];
                if (!validImageTypes.includes(fileType)) {
                    alert('The image must be in valid formats (gif, jpg, png)');
                    return;
                }

                wclEncodeImgtoBase64(this)

                if (!document.querySelector('.wcl-member-popup').classList.contains('mod-edit')) {
                    let post_id = popup.getAttribute('data-post-id');
                    let picture = popup.querySelector('input[name="picture"]').files[0]

                    var fd = new FormData();

                    fd.append("action", "member_add_picture");
                    fd.append("post_id", post_id);
                    fd.append("picture", picture);

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', wcl_obj.ajax_url, true);
                    xhr.onload = function (data) {
                        if (xhr.status >= 200 && xhr.status < 400) {
                            var data = JSON.parse(xhr.responseText);
                        }
                    };

                    xhr.send(fd);
                }
            })
        }
    }

});


