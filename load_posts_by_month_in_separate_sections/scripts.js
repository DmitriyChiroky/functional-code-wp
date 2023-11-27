
// wcl-posts-landing

if (document.querySelector('.wcl-posts-landing')) {
    let section = document.querySelector('.wcl-posts-landing')


    let load_more = section.querySelector('.wcl-load-more')
    if (load_more) {
        load_more.addEventListener("click", function (e) {
            e.preventDefault();
            if (e.target.classList.contains('d2-btn')) {
                let self = e.target;
                if (self.getAttribute("disable") == 'disable') {
                    return false;
                }
                posts_landing_page_load_post('load-more');
            }
        });
    }

    let cats = section.querySelectorAll('.d6-item a')
    if (cats) {
        cats.forEach(element => {
            element.addEventListener("click", function (e) {
                e.preventDefault();
                if (this.parentNode.classList.contains('active')) {
                    return false;
                }
                let self = this;
                cats.forEach(element => {
                    element.parentNode.classList.remove('active');
                });

                if (self.getAttribute('data-id') == 'all') {
                    section.querySelector('.data-title span').textContent = 'All'
                } else {
                    section.querySelector('.data-title span').textContent = self.textContent
                }
                self.parentNode.classList.add('active');
                posts_landing_page_load_post();
            });
        });
    }

    function initSlider() {
        section.querySelectorAll('.data-list:not(.swiper-initialized)').forEach(element => {
            let count = 3;
            if (window.matchMedia("(max-width: 575px)").matches) {
                count = 1
            } else if (window.matchMedia("(max-width: 767)").matches) {
                count = 2
            }
            if (element.querySelectorAll('.data-item').length > count) {
                let parent = element.closest('.data-a-item')
                let swiper = new Swiper(element, {
                    slidesPerView: 3,
                    spaceBetween: 80,
                    autoHeight: true,
                    navigation: {
                        nextEl: parent.querySelector('.data-list-nav-btn.mod-next'),
                        prevEl: parent.querySelector('.data-list-nav-btn.mod-prev'),
                    },
                    breakpoints: {
                        0: {
                            loop: true,
                            slidesPerView: 'auto',
                            centeredSlides: true,
                            spaceBetween: 20,
                        },
                        576: {
                            spaceBetween: 20,
                            slidesPerView: 2,
                        },
                        767: {
                            spaceBetween: 20,
                            slidesPerView: 3,
                        },
                        991: {
                            slidesPerView: 3,
                            spaceBetween: 40,
                        },
                        1199: {
                            slidesPerView: 3,
                            spaceBetween: 80,
                        },
                    }
                });

                swiper.on('slideChange', function (e) {
                    parent.querySelectorAll('.data-list-dots-item ').forEach(function (element_2, index) {
                        let dot_index = element_2.getAttribute('data-index')
                        if (swiper.activeIndex >= dot_index) {
                            parent.querySelectorAll('.data-list-dots-item.active').forEach(element_3 => {
                                element_3.classList.remove('active')
                            });
                            element_2.classList.add('active')
                        }
                    });
                });

                parent.querySelectorAll('.data-list-dots-item').forEach(element => {
                    element.addEventListener('click', function (e) {
                        let section = this.closest('.data-a-item')
                        let index = this.getAttribute('data-index')
                        section.querySelector('.data-list').swiper.slideTo(index, 700);
                    })
                });
            }
        });
    }

    function posts_landing_page_load_post(event = '') {
        let paged = -1;
        let post_date = '';
        let category = section.querySelector('.d6-item.active a')

        if (category) {
            category = category.getAttribute("data-id");
        } else {
            category = 'all';
        }

        if (event == 'load-more') {
            post_date = section.querySelector('.wcl-load-more button').getAttribute('data-post-date')
        }

        let data_req = {
            action: 'posts_landing_page_load_post',
            paged: parseInt(paged) + 1,
            post_date: post_date,
            category: category,
        }
        let xhr = new XMLHttpRequest();
        xhr.open('POST', wcl_obj.ajax_url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.onload = function (data) {
            if (xhr.status >= 200 && xhr.status < 400) {
                var data = JSON.parse(xhr.responseText);
                if (event == 'load-more') {
                    section.querySelector('.data-a .data-a-container').insertAdjacentHTML('beforeend', data.posts);
                } else {
                    section.querySelector('.data-a .data-a-container').innerHTML = data.posts;
                }
                section.querySelector('.wcl-load-more .d2-container').innerHTML = data.button;
                initSlider();
            };
        };
        data_req = new URLSearchParams(data_req).toString();
        xhr.send(data_req);
    }

}