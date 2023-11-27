<?php

$google_api_key = get_field('bob_google_api_key', 'option');
?>
<!DOCTYPE html>
<html <?php echo get_language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php echo wp_get_document_title(); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<?php wp_head(); ?>

	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=places&callback=initMap" async defer></script>
</head>

<body <?php body_class(); ?>>

<?php
$place_id = 1;
?>
<div class="wcl-acf-block-12">
    <div class="data-container wcl-container">
        <div class="data-map" data-place-id="<?php echo $place_id; ?>"></div>
    </div>
</div>

</body>

<script>
    /* 
initMap
 */
    function initMap() {

        // wcl-acf-block-12 

        if (document.querySelector('.wcl-acf-block-12, .wcl-section-3')) {
            let sections = document.querySelectorAll('.wcl-acf-block-12, .wcl-section-3');

            sections.forEach(element => {
                let map_el = element.querySelector('.data-map')

                let placeId = map_el.getAttribute('data-place-id')

                let map = new google.maps.Map(map_el, {
                    center: {
                        lat: -33.8666,
                        lng: 151.1958
                    }, // Default to Sydney, Australia
                    zoom: 15
                });

                let request = {
                    placeId: placeId,
                    fields: ['name', 'formatted_address', 'geometry']
                };

                let service = new google.maps.places.PlacesService(map);

                service.getDetails(request, function(place, status) {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        let marker = new google.maps.Marker({
                            map: map,
                            position: place.geometry.location
                        });

                        map.setCenter(place.geometry.location);
                    }
                });
            });
        }
    }
</script>

<?php wp_footer(); ?>