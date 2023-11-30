<?php

/* wp_loop_get_images_of_acf_single_post();
 */
function wp_loop_get_images_of_acf_single_post() {

	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => 300,
		//'post__in'       => [1249],
	);

	$query_obj   = new WP_Query($args);
	$total_count = $query_obj->found_posts;
	//var_dump(   $total_count ); 

	$count = 0;

	$image_ids_main = [];

	if ($query_obj->have_posts()) {
		global $post;
		while ($query_obj->have_posts()) {
			$query_obj->the_post();
			$post_id = get_the_ID();
			$content = get_the_content();
			//var_dump($post_id);

			$image_ids = get_images_of_acf_single_post($content);

			//var_dump($image_ids );

			foreach ($image_ids as $item) {
				$image_ids_main[] = $item;
			}
		}

		wp_reset_postdata();

		//var_dump($image_ids_main);

		echo '<pre>';
		foreach ($image_ids_main as $item) {
			echo $item;
			echo '<br>';
		}
		echo '</pre>';
	}
}


/* 
 get_images_of_acf_single_post
 */
function get_images_of_acf_single_post($data) {
	$main_ids = [];

	/// two-images-overlapped

	$on = true;

	if (true) {
		$pattern = '/<!-- wp:acf\/two-images-overlapped {"id":"(block_[a-zA-Z0-9]+)".*?"block_two_images_overlapped_gp_image1":(\d+).*?"block_two_images_overlapped_gp_image2":(\d+)/s';

		preg_match_all($pattern, $data, $matches, PREG_SET_ORDER);

		$blocks = array();

		foreach ($matches as $match) {
			$blockId = $match[1];
			$imageId1 = $match[2];
			$imageId2 = $match[3];

			$blockData = array(
				'blockId' => $blockId,
				'imageId1' => $imageId1,
				'imageId2' => $imageId2,
			);

			$blocks[] = $blockData;

			$main_ids[] = $imageId1;
			$main_ids[] = $imageId2;
		}

		//print_r($blocks);
	}

	/// shop-product-list

	if ($on) {
		//Ищем все блоки с классом "shop-product-list"
		preg_match_all('/<!--\s*wp:acf\/shop-product-list[^>]*-->.*?/s', $data, $blocks);

		// Создаем массив для айди блока и его айди картинок
		$blockImageIds = array();

		foreach ($blocks[0] as $block) {
			// Извлекаем айди блока
			preg_match('/"id":"block_([^"]+)"/', $block, $blockIdMatch);
			$blockId = $blockIdMatch[1];

			// Извлекаем айди картинок
			preg_match_all('/"block_shop_list_products_\d+_image":(\d+),/', $block, $imageIdMatches);
			$imageIds = $imageIdMatches[1];

			// Добавляем айди блока и его айди картинок в массив
			$blockImageIds[$blockId] = $imageIds;

			foreach ($imageIds as $item) {
				$main_ids[] = $item;
			}
		}

		// Выводим результат
		//print_r($blockImageIds);
	}


	// image-overlap

	if ($on) {
		$pattern = '/<!-- wp:acf\/image-overlap {"id":"(block_[a-zA-Z0-9]+)".*?"block_image_overlap_img":(\d+)/s';

		$blocks = array();

		if (preg_match_all($pattern, $data, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$blockId = $match[1];
				$imageId = $match[2];

				$blockData = array(
					'blockId' => $blockId,
					'imageId' => $imageId,
				);

				$blocks[] = $blockData;

				$main_ids[] = $imageId;
			}
		}

		// print_r($blocks);
	}


	// product-focus

	if ($on) {
		$pattern = '/<!-- wp:acf\/product-focus {"id":"(block_[a-zA-Z0-9]+)".*?"product_focus_gp_image":(\d+)/s';

		preg_match_all($pattern, $data, $matches, PREG_SET_ORDER);

		$blocks = array();

		foreach ($matches as $match) {
			$blockId = $match[1];
			$imageId = $match[2];

			$blockData = array(
				'blockId' => $blockId,
				'imageId' => $imageId,
			);

			$blocks[] = $blockData;

			$main_ids[] = $imageId;
		}

		//print_r($blocks);
	}

	return $main_ids;
}


/* 
get_url_image
 */
function get_url_image() {
	$ids = [61671, 61672, 61673, 61675, 61674, 61676, 61678, 61679, 61623, 61622, 61620, 61632, 61606, 61607, 61609, 61610, 61599, 61576, 61577, 61578, 61579, 61582, 61583, 61585, 61586, 61587, 61534, 61536, 61537, 61535, 61532, 61323, 61324, 61327, 61326, 61424, 61426, 61428, 61422, 61307, 61306, 61519, 61437, 61439, 61444, 61442, 61441, 61448, 61432, 61446, 61495, 61047, 61046, 61058, 61059, 61060, 61061, 61062, 61073, 61049, 61050, 61051, 61052, 61053, 61054, 61055, 61056, 61057, 60997, 60998, 61002, 61003, 61004, 61005, 61006, 61007, 61022, 61016, 61017, 61018, 61019, 61020, 60828, 60826, 60830, 60831, 60832, 60834, 60854, 60848, 60844, 60845, 60849, 60851, 60850, 60853, 60837, 60838, 60839, 60840, 60841, 60843, 60852, 60856, 60859, 60861, 60654, 60655, 60656, 60657, 60664, 60658, 60660, 60661, 60662, 60663, 60666, 60668, 60669, 60670, 60671, 60667, 60674, 60687, 60675, 60677, 60685, 60679, 60680, 60681, 60682, 60683, 60653, 60659, 60672, 60673, 60678, 60332, 60333, 60334, 60335, 60336, 60337, 60338, 60339, 60340, 60341, 60342, 60343, 60387, 60388, 60389, 60390, 60391, 60392, 60346, 60347, 60348, 60349, 60350, 60351, 60352, 60353, 60354, 60355, 60356, 60269, 60270, 60271, 60272, 60273, 60262, 60263, 60264, 60265, 60266, 60267, 60268, 59891, 59892, 59893, 59894, 59896, 59897, 59898, 59899, 59900, 59901, 59902, 59903, 59904, 59687, 59685, 59688, 59686, 59691, 59690, 59693, 59692, 59719, 59720, 59721, 59722, 59723, 59724, 59725, 59727, 59728, 59729, 59730, 59731, 59732, 59733, 59734, 59735, 59739, 59741, 59742, 59567, 59568, 59572, 59571, 59589, 59591, 59592, 59593, 59596, 59597, 59598, 59599, 59601, 59602, 59603, 59604, 59606, 59607, 59608, 59609, 59611, 59612, 59613, 59614, 59616, 59617, 59618, 59619, 59621, 59622, 59623, 59624, 59626, 59627, 59641, 59629, 59631, 59632, 59633, 59634, 59635, 59636, 59637, 59639, 59594, 59595, 59600, 59605, 59610, 59615, 59620, 59625, 59630, 59638, 59277, 59278, 59280, 59281, 59562, 59563, 59564, 59230, 59229, 59460, 59404, 59405, 59453, 59473, 59406, 59407, 59408, 59409, 59410, 59337, 59336, 59341, 59340, 59344, 59343, 59346, 59345, 59123, 59207, 59209, 59210, 59213, 59212, 58803, 58802, 59086, 59085, 58806, 58807, 59134, 59135, 59136, 59133, 59137, 59133, 58960, 58959, 58963, 58962, 58514, 58339, 58338, 58342, 58341, 58344, 58343, 58346, 58345, 58349, 58348, 58357, 58359, 58360, 58361, 58362, 58363, 58364, 58366, 58367, 58368, 58369, 58370, 58371, 58372, 58373, 58374, 58375, 58376, 58377, 58378, 58379, 58380, 58381, 58383, 58384, 58385, 58386, 58340, 58365];

	$images_arr = [];

	foreach ($ids as $attachment_id) {
		$image_url = wp_get_attachment_url($attachment_id);

		$images_arr[$attachment_id] = $image_url;
	}

	echo '<pre>';
	print_r($images_arr);
	echo '</pre>';
}


/* 
load_image_to_local_by_url
 */
function load_image_to_local_by_url() {
	$data = [
		61671 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0004.jpg',
		61672 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0005.jpg',
		61673 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0007.jpg',
		61675 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0002.jpg',
		61674 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0003.jpg',
		61676 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0010.jpg',
		61678 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0009.jpg',
		61679 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0012.jpg',
		61623 => 'https://thechrisellefactor.com/wp-content/uploads/2021/05/591A1107.jpg',
		61622 => 'https://thechrisellefactor.com/wp-content/uploads/2021/05/591A1092.jpg',
		61620 => 'https://thechrisellefactor.com/wp-content/uploads/2021/05/2021-L4-CK1-70380864-03-8.jpg',
		61632 => 'https://thechrisellefactor.com/wp-content/uploads/2022/05/CHARLES-KEITH-CK2-20270688-09.jpg',
		61606 => 'https://thechrisellefactor.com/wp-content/uploads/2021/02/IMG_5142-1.jpg',
		61607 => 'https://thechrisellefactor.com/wp-content/uploads/2021/02/IMG_5204-1.jpg',
		61609 => 'https://thechrisellefactor.com/wp-content/uploads/2021/02/IMG_5198-1.jpg',
		61610 => 'https://thechrisellefactor.com/wp-content/uploads/2021/02/IMG_5122-copy-1.jpg',
		61599 => 'https://thechrisellefactor.com/wp-content/uploads/2021/02/5c6d75fc-980c-446d-a3ca-57311499ef61.jpg',
		61576 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1326.jpg',
		61577 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1296.jpg',
		61578 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1344.jpg',
		61579 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1365.jpg',
		61582 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1389.jpg',
		61583 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1408.jpg',
		61585 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/Screen-Shot-2020-12-03-at-3.49.21-PM.png',
		61586 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/Screen-Shot-2020-12-03-at-3.49.57-PM.png',
		61587 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/Screen-Shot-2020-12-03-at-3.50.38-PM.png',
		61534 => 'https://thechrisellefactor.com/wp-content/uploads/2020/10/IMG_4109-e1601948204773.jpg',
		61536 => 'https://thechrisellefactor.com/wp-content/uploads/2020/10/IMG_4085.jpg',
		61537 => 'https://thechrisellefactor.com/wp-content/uploads/2020/10/IMG_4087.jpg',
		61535 => 'https://thechrisellefactor.com/wp-content/uploads/2020/10/IMG_4111.jpg',
		61532 => 'https://thechrisellefactor.com/wp-content/uploads/2020/10/Screen-Shot-2020-10-05-at-6.33.36-PM.png',
		61323 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/IMG_4986.jpg',
		61324 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/IMG_4978.jpg',
		61327 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/IMG_4984.jpg',
		61326 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/IMG_4981.jpg',
		61424 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-5.49.44-PM.png',
		61426 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-5.52.07-PM.png',
		61428 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-5.53.46-PM.png',
		61422 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-5.45.01-PM.png',
		61307 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/IMG_7857.jpg',
		61306 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-29-at-6.29.03-PM.png',
		61519 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-07-02-at-8.48.07-AM.png',
		61437 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.15.04-PM.png',
		61439 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.19.12-PM.png',
		61444 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.25.15-PM.png',
		61442 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.22.32-PM.png',
		61441 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.21.32-PM.png',
		61448 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.30.01-PM.png',
		61432 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.07.29-PM.png',
		61446 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.28.31-PM.png',
		61495 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-07-01-at-12.06.50-PM.png',
		61047 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/IMG_4873.jpg',
		61046 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/IMG_4869.jpg',
		61058 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-3.00.27-PM.png',
		61059 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-3.01.18-PM.png',
		61060 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-3.02.31-PM.png',
		61061 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-3.04.46-PM.png',
		61062 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-3.05.52-PM.png',
		61073 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-19-at-4.32.39-PM.png',
		61049 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.07.06-PM.png',
		61050 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.09.51-PM.png',
		61051 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.10.54-PM.png',
		61052 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.12.08-PM.png',
		61053 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.13.12-PM.png',
		61054 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.14.31-PM.png',
		61055 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.15.50-PM.png',
		61056 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.17.44-PM.png',
		61057 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.19.16-PM.png',
		60997 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/IMG_0529.jpeg',
		60998 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/IMG_0530.jpeg',
		61002 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.18.22-PM.png',
		61003 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.18.07-PM.png',
		61004 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.18.46-PM.png',
		61005 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.19.06-PM.png',
		61006 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.19.17-PM.png',
		61007 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.46.48-PM.png',
		61022 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-6.52.12-PM.png',
		61016 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/a7938ced-ba2b-42e5-a6f6-197e57d36ae7.jpg',
		61017 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-6.46.34-PM.png',
		61018 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-6.47.45-PM.png',
		61019 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-6.49.40-PM.png',
		61020 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-6.50.43-PM.png',
		60828 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/IMG_4893-1.jpg',
		60826 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/IMG_4905.jpg',
		60830 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-09-at-12.56.09-PM.png',
		60831 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/hmgoepprod.jpg',
		60832 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/0325ed4f-f17c-4677-b046-ca1764fba29f.jpg',
		60834 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/b28e88fb-48d1-487f-a982-a4095a197043.jpg',
		60854 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-10-at-1.36.51-PM.png',
		60848 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-09-at-3.20.10-PM.png',
		60844 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-09-at-2.47.41-PM.png',
		60845 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-09-at-2.48.10-PM.png',
		60849 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/hmgoepprod-1.jpg',
		60851 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-10-at-1.09.25-PM.png',
		60850 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-10-at-1.08.19-PM.png',
		60853 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/s20_04_a02_77541_6335_off_a.jpg',
		60837 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Bevza.jpg',
		60838 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/LoQ.jpg',
		60839 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Topshop-Dixie-Strappy-Sandal.jpg',
		60840 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-09-at-1.50.35-PM.png',
		60841 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Topshop-Sandal.jpg',
		60843 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/15aafe2b-0d4e-41b0-bb03-dcf25a0b9105.jpg',
		60852 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-10-at-1.12.51-PM.png',
		60856 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Isela-Dress-20200321141400.jpg',
		60859 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/s20_04_a04_77786_5864_off_a.jpg',
		60861 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-15-at-1.29.36-PM.png',
		60654 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/2c1e7df8-8fd5-4f85-9f84-bf4db492a111.jpg',
		60655 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/1227772_in_pp.jpg',
		60656 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/c8ea56e1-c7b6-42b8-8702-708d2ba3d7ff.jpg',
		60657 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/14a76e4f-b27c-477d-a024-3627a74732b3.jpg',
		60664 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/large_jacquemus-green-la-veste-tablier-belted-wool-blend-blazer.jpg',
		60658 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/fc090e21-9744-473f-9b66-08df32b49274.jpg',
		60660 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/bdb5b9e0-ca70-48a4-9cef-e1a39ddc27c7.jpg',
		60661 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/s20_01_a04_76585_1274_off_a.jpg',
		60662 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/a13c6050-a561-4ebc-b87f-8838a5326e55.jpg',
		60663 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/large_acler-neutral-graham-linen-blend-jacket.jpg',
		60666 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-28-at-3.41.25-PM.png',
		60668 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-28-at-3.48.12-PM.png',
		60669 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/683d8404-6487-489b-9b57-43d74b121f48.jpg',
		60670 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/32a33815-d561-4573-98c6-0305406e39e8.jpg',
		60671 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/edb143fa-7803-4072-b87c-0483a255c7d8.jpg',
		60667 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/8938f482-875f-4577-822d-9ebdf8cd7457.jpg',
		60674 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/1215900_in_pp.jpg',
		60687 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/brxp_xlProduct.jpg',
		60675 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/1129052_in_pp.jpg',
		60677 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/hmgoepprod.jpg',
		60685 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/Screen-Shot-2020-03-01-at-3.59.55-PM.png',
		60679 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/Screen-Shot-2020-03-01-at-2.32.46-PM.png',
		60680 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/hmgoepprod-1.jpg',
		60681 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/e75bef6f-a3ff-46bd-8130-86db498544d2.jpg',
		60682 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/lione3004277087_q1_2-0.jpg',
		60683 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/Screen-Shot-2020-03-01-at-3.07.54-PM.png',
		60653 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/s20_04_a04_77786_5864_off_a.jpg',
		60659 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/b961c66f-8146-43eb-98e4-c01de6164913.jpg',
		60672 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/Screen-Shot-2020-03-01-at-2.04.44-PM.png',
		60673 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/Screen-Shot-2020-03-01-at-2.10.02-PM.png',
		60678 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/b142ef13-cc74-44d2-bc59-53d8e5c64d97.jpg',
		60332 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/6eddbdda-6dcc-4583-b855-156ad27e8639.jpg',
		60333 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/pi_hcs_refl_2.png',
		60334 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/s2300804-main-zoom.jpg',
		60335 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-2.50.31-PM.png',
		60336 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-2.51.05-PM.png',
		60337 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/DP0118201813164736M.jpg',
		60338 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.06.45-PM.png',
		60339 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.07.52-PM.png',
		60340 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.10.05-PM.png',
		60341 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/10787.jpg',
		60342 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.15.48-PM.png',
		60343 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.16.50-PM.png',
		60387 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-20-at-1.56.36-PM.png',
		60388 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-20-at-1.58.17-PM.png',
		60389 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/f749454a-dcfc-4dc8-891d-d576eb3e5c92.jpg',
		60390 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/4c7c5f95-156c-406a-b1c1-4611224f5af6.jpg',
		60391 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-20-at-2.02.06-PM.png',
		60392 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-20-at-2.03.40-PM.png',
		60346 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.40.06-PM.png',
		60347 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/tower-28-sos-saveourskin-daily-rescue-facial-spray.jpg',
		60348 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/GUEST_9abdecaf-5bb0-425d-b262-c43d68b6fb39.jpg',
		60349 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/80631.jpg',
		60350 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.46.17-PM.png',
		60351 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.47.21-PM.png',
		60352 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/fe0a9152-a10b-41ee-a661-95f63b2f0b53.jpg',
		60353 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Solution.jpg',
		60354 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.48.50-PM.png',
		60355 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.49.24-PM.png',
		60356 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.50.08-PM.png',
		60269 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/a204d230-a224-4924-aed5-e84e8cd18fb7.jpg',
		60270 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-1.26.06-PM.png',
		60271 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-1.26.58-PM.png',
		60272 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-1.28.02-PM.png',
		60273 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-1.28.36-PM.png',
		60262 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/1206752_bk_pp.jpg',
		60263 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/1214457_ou_pp.jpg',
		60264 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/1150413_in_pp.jpg',
		60265 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/1200393_bk_pp.jpg',
		60266 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-12.01.25-PM.png',
		60267 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-12.02.05-PM.png',
		60268 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-12.03.16-PM.png',
		59891 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/IMG_5072.jpg',
		59892 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/IMG_5073.jpg',
		59893 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/IMG_5074.jpg',
		59894 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/IMG_5075.jpg',
		59896 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.03.44-PM.png',
		59897 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.05.20-PM.png',
		59898 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.06.50-PM.png',
		59899 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.08.00-PM.png',
		59900 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.09.08-PM.png',
		59901 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.11.28-PM.png',
		59902 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.12.20-PM.png',
		59903 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.14.44-PM.png',
		59904 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.15.41-PM.png',
		59687 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9372.jpg',
		59685 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9393.jpg',
		59688 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9380.jpg',
		59686 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9396.jpg',
		59691 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9504.jpg',
		59690 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9397.jpg',
		59693 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9383.jpg',
		59692 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9381.jpg',
		59719 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/e77bf98a-5bff-48a6-96c7-35444dee2291.jpg',
		59720 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/d8bd8552-eefb-4502-845c-27272c37fd08.jpg',
		59721 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/f9a592b3-d599-4e12-b343-1a13a92f978d.jpg',
		59722 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.31.50-AM.png',
		59723 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.32.34-AM.png',
		59724 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.33.09-AM.png',
		59725 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.33.52-AM.png',
		59727 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.46.27-AM.png',
		59728 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/74411.jpg',
		59729 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.51.07-AM.png',
		59730 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.03.37-PM.png',
		59731 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.05.02-PM.png',
		59732 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.06.27-PM.png',
		59733 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.06.50-PM.png',
		59734 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.08.16-PM.png',
		59735 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.09.04-PM.png',
		59739 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.27.32-PM.png',
		59741 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.28.59-PM.png',
		59742 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.29.49-PM.png',
		59567 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_4169.jpg',
		59568 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_4196.jpg',
		59572 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_4214.jpg',
		59571 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_4175.jpg',
		59589 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/22cfb03f-44aa-44af-a7bf-12ac218f1e34.jpg',
		59591 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/boyis3004415d49_q6_2-0._UX357_QL90_.jpg',
		59592 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1153698_fr_pp.jpg',
		59593 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/3f1927c2-09d3-4669-b849-7df4ce10a028.jpg',
		59596 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/02c47a70-80e9-45db-914a-8db5555f0a5a.jpg',
		59597 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.47.08-PM.png',
		59598 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.49.02-PM.png',
		59599 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.49.37-PM.png',
		59601 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1107942_fr_pp.jpg',
		59602 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/SMCC-WO61_V2.jpg',
		59603 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.55.20-PM.png',
		59604 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.56.47-PM.png',
		59606 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.09.18-PM.png',
		59607 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.13.55-PM.png',
		59608 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1904e3f1-8fc5-40f8-b28c-24ac4e1e782f.jpg',
		59609 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/6390fbf0-0d36-432d-bc32-8f23b00a3373.jpg',
		59611 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/acb99150-c68e-4a2c-bfb8-21c8dc50a620.jpg',
		59612 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/ed97dfb4-5008-47f1-b0f0-017bb5ca0445.jpg',
		59613 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/fd768061-fb2e-4e2c-aedf-e6a2208b50e1.jpg',
		59614 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/5a6afa01-559b-43f1-b6d3-a8e0c82e0bca.jpg',
		59616 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/rachc210871071c_q6_2-0._UX357_QL90_.jpg',
		59617 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/97f53d7c-f982-4da6-a403-ba179bc9a55b.jpg',
		59618 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/mandb3003460403_q6_2-0._UX357_QL90_.jpg',
		59619 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.36.38-PM.png',
		59621 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/14079189_20969043_1000.jpg',
		59622 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.41.52-PM.png',
		59623 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/f23e6c42-636c-4174-bab3-7e3715e90991.jpg',
		59624 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1132578_in_pp.jpg',
		59626 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1203172_in_pp.jpg',
		59627 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.53.50-PM.png',
		59641 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1171107_in_pp.jpg',
		59629 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/b1ce36ab-f8e9-44f4-a387-e711c590c438.jpg',
		59631 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/0fa8edab-fa49-49f4-b1c2-5ed7a0505b2b.jpg',
		59632 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/astrr300831071c_q6_2-0._UX357_QL90_.jpg',
		59633 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/58a22715-ee57-4651-89d2-5d94ec71fc4c.jpg',
		59634 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/f60daa7d-dee2-4d41-882a-f3b5e711e0af.jpg',
		59635 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/b5cdaba4-de90-4e14-a4f0-073293d98d72-1.jpg',
		59636 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/vejaa3026016c6f_q6_2-0._UX357_QL90_-1.jpg',
		59637 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/ythre308361700c_q6_2-0._UX357_QL90_.jpg',
		59639 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/cb4004c7-d18a-48db-8fa2-1039a0232730.jpg',
		59594 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.36.07-PM.png',
		59595 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.42.13-PM.png',
		59600 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/fc090e21-9744-473f-9b66-08df32b49274.jpg',
		59605 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/fc02e753-b949-4d89-b695-2b1cd302694b.jpg',
		59610 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1174864_in_pp.jpg',
		59615 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/4390e6a3-d740-41ed-bf8c-2970a5a8937d.jpg',
		59620 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.38.57-PM.png',
		59625 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1242191_in_pp.jpg',
		59630 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/cb93cb74-98d7-4652-bd9e-9a26bc720185.jpg',
		59638 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/32ed84e7-e575-4420-90cf-531d6ff67f36.jpg',
		59277 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_3943.jpg',
		59278 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_3937.jpg',
		59280 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_3942.jpg',
		59281 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_3954.jpg',
		59562 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-09-at-10.16.07-AM.png',
		59563 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-09-at-10.17.37-AM.png',
		59564 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-09-at-10.18.29-AM.png',
		59230 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_4623.jpg',
		59229 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_4617.jpg',
		59460 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-06-at-7.25.26-PM-1.png',
		59404 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/cdn.cliqueinc.com__cache__posts__208755__-1981094-1479408874.700x0c-4776dc13bc85453f9b2798ac69d363ac-1.jpg',
		59405 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-02-at-10.15.44-AM.png',
		59453 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-06-at-2.06.46-PM.png',
		59473 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-07-at-11.01.11-AM.png',
		59406 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-02-at-10.24.24-AM.png',
		59407 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-02-at-10.26.59-AM.png',
		59408 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-02-at-10.28.41-AM.png',
		59409 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-02-at-10.31.20-AM.png',
		59410 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/06933a11-795f-4f16-9f33-14c92cbb01d6.jpg',
		59337 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.57.48-PM.png',
		59336 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.57.15-PM.png',
		59341 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.58.21-PM.png',
		59340 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.58.00-PM.png',
		59344 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.57.38-PM.png',
		59343 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.59.04-PM.png',
		59346 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.58.48-PM.png',
		59345 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.58.12-PM.png',
		59123 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_4628.jpg',
		59207 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-16-at-11.20.24-AM.png',
		59209 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-16-at-12.00.11-PM.png',
		59210 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-16-at-12.03.05-PM.png',
		59213 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-16-at-12.11.35-PM.png',
		59212 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/530bf1b2-5001-4001-81d8-839d5dbc5ba6.jpg',
		58803 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_2332.jpg',
		58802 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_2318.jpg',
		59086 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_9346.jpg',
		59085 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_9360.jpg',
		58806 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_2315.jpg',
		58807 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_2338.jpg',
		59134 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-11-at-12.23.04-PM.png',
		59135 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-11-at-12.24.04-PM.png',
		59136 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-11-at-12.24.53-PM.png',
		59133 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-11-at-12.10.12-PM.png',
		59137 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/956463_in_pp.jpg',
		58960 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_4005.jpg',
		58959 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_3987.jpg',
		58963 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_3978.jpg',
		58962 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_4026.jpg',
		58514 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_9731.jpg',
		58339 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/F069E082-A4FD-4FC4-9D21-7CE5AFB8D79D.jpg',
		58338 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_1029.jpg',
		58342 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/7C832A99-FA2E-4649-AFF4-E3DEC4098206.jpg',
		58341 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/3FD997F4-CD06-4DBC-B231-C99575EAE82A.jpg',
		58344 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_1435.jpeg',
		58343 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_9563.jpeg',
		58346 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_9820.jpeg',
		58345 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_0157.jpeg',
		58349 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_0988.jpg',
		58348 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/FC3BDA3A-922E-4024-B787-26EAFC96DB76.jpg',
		58357 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.23.27-PM.png',
		58359 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/40b3bd1a-ac07-4e90-a2ea-248c80eb7cd6.jpg',
		58360 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/1a8ec0bd-b5b2-4b13-814c-71d765299e9a.jpg',
		58361 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/f39e754a-52b2-44a7-9941-ae003f9e54b1.jpg',
		58362 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/b73bcccf-6206-47cc-a6a9-02db42eecf30.jpg',
		58363 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/fb85d299-5c30-4b76-b7d5-88f6e3b31daa.jpg',
		58364 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/ee21fdc1-76ba-4eeb-b981-40335d01ca5a.jpg',
		58366 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.46.32-PM.png',
		58367 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/492491a5-55de-4e81-86c1-0ff71f449452.jpg',
		58368 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.50.18-PM.png',
		58369 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.51.11-PM.png',
		58370 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.51.52-PM.png',
		58371 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.52.35-PM.png',
		58372 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.53.35-PM.png',
		58373 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/i-035808-jet-lag-mask-64g-1-940.jpg',
		58374 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.55.44-PM.png',
		58375 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.58.34-PM.png',
		58376 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/stretch-concealer.jpg',
		58377 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.02.14-PM.png',
		58378 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/6d51724b-cd8a-4056-b027-1376c91fb4ba.jpg',
		58379 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.06.48-PM.png',
		58380 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/boy-brow.jpg',
		58381 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/images.jpg',
		58383 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.10.16-PM.png',
		58384 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.11.43-PM.png',
		58385 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.12.32-PM.png',
		58386 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.13.07-PM.png',
		58340 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_9135.jpeg',
		58365 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/80ab354f-f5b4-4d50-b10f-091114f78ad3.jpg',
	];

	// load image from url
	if (false) {
		# code...

		$theme_directory = get_template_directory(); // Get the path to your theme's directory
		$image_subfolder = 'new-images'; // Subfolder within your theme where images will be saved

		$downloaded_count = 0; // Initialize the downloaded image count

		foreach ($data as $image_id => $image_url) {
			// if ($downloaded_count >= 10) {
			// 	break; // Exit the loop once 10 images have been downloaded
			// }

			// Construct the local file path
			$local_file_path = trailingslashit($theme_directory) . $image_subfolder . '/' . basename($image_url);

			// Check if the image file already exists
			if (file_exists($local_file_path)) {
				echo 'Image already exists at ' . $local_file_path . '<br>';
			} else {
				// Create the subfolder if it doesn't exist
				$subfolder_path = trailingslashit($theme_directory) . $image_subfolder;
				if (!is_dir($subfolder_path)) {
					mkdir($subfolder_path, 0755, true);
				}

				// Use wp_remote_get to retrieve the image
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $image_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 100); // Set the timeout to 30 seconds

				$response = curl_exec($ch);

				if ($response === false) {
					echo 'Error fetching the image: ' . curl_error($ch);
				} else {
					// Get the image data
					// ... rest of your code
				}

				// Use wp_remote_get to retrieve the image
				$response = wp_remote_get($image_url);

				if (is_wp_error($response)) {
					echo 'Error fetching the image: ' . $response->get_error_message();
				} else {
					// Get the image body
					$image_data = wp_remote_retrieve_body($response);

					// Save the image to your theme's subfolder
					if (file_put_contents($local_file_path, $image_data) !== false) {
						echo 'Image downloaded and saved successfully to ' . $local_file_path . '<br>';
						$downloaded_count++; // Increment the downloaded image count
					} else {
						echo 'Failed to save the image.<br>';
					}
				}
			}
		}
	}

	// get not exist image
	if (false) {
		// Get the path to your theme's directory
		$theme_directory = get_template_directory();

		// Specify the subfolder within the theme where you want to check for existing images
		$image_subfolder = 'new-images';

		// Initialize an array to store missing images
		$missing_images = [];

		// Iterate through the array of image URLs
		foreach ($data as $image_id => $image_url) {
			// Extract the image filename from the URL
			$image_filename = basename($image_url);

			// Construct the path to check in the theme's subfolder
			$image_path = trailingslashit($theme_directory) . $image_subfolder . '/' . $image_filename;

			// Check if the image exists in the subfolder
			if (!file_exists($image_path)) {
				$missing_images[$image_id] = $image_url; // Image is missing, add it to the missing_images array
			}
		}


		// foreach ($missing_images as $image_id => $image_url) {
		// 	echo $image_id . ' - ' . $image_url;
		// 	echo '<br>';
		// }

		// Output the missing image URLs as download links
		echo 'Missing images to download:<br>';
		foreach ($missing_images as $image_id => $image_url) {
			echo '<a href="' . $image_url . '" download>Download Image ' . $image_id . '</a><br>';
		}
	}

	// loaad image to wp
	if (false) {
		// Get the path to the "new-images" folder within your theme
		$theme_directory = get_template_directory();
		$image_subfolder = 'new-images';
		$image_folder_path = trailingslashit($theme_directory) . $image_subfolder;

		// Check if the "new-images" folder exists
		if (is_dir($image_folder_path)) {
			// Get a list of image files in the folder
			$image_files = glob($image_folder_path . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

			// Loop through the image files
			foreach ($image_files as $image_file) {
				// Upload each image to the WordPress media library
				$attachment_id = media_handle_sideload(array('file' => $image_file), 0);

				if (is_wp_error($attachment_id)) {
					// Handle the error, if any
					echo 'Error uploading image: ' . $attachment_id->get_error_message();
				} else {
					// Image uploaded successfully
					echo 'Image uploaded with ID: ' . $attachment_id . '<br>';

					// Set the image ID in a custom field (replace 'custom_field_name' with your field name)
					update_post_meta(1, 'custom_field_name', $attachment_id);
					// Replace '1' with the post or page ID where you want to set the custom field
				}
			}
		} else {
			echo 'The "new-images" folder does not exist.';
		}
	}
}


/* 
get_image_from_live
 */
function get_image_from_live() {
	$data = [
		61671 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0004.jpg',
		61672 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0005.jpg',
		61673 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0007.jpg',
		61675 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0002.jpg',
		61674 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0003.jpg',
		61676 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0010.jpg',
		61678 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0009.jpg',
		61679 => 'https://thechrisellefactor.com/wp-content/uploads/2021/06/ChriselleLim_setlist0012.jpg',
		61623 => 'https://thechrisellefactor.com/wp-content/uploads/2021/05/591A1107.jpg',
		61622 => 'https://thechrisellefactor.com/wp-content/uploads/2021/05/591A1092.jpg',
		61620 => 'https://thechrisellefactor.com/wp-content/uploads/2021/05/2021-L4-CK1-70380864-03-8.jpg',
		61632 => 'https://thechrisellefactor.com/wp-content/uploads/2022/05/CHARLES-KEITH-CK2-20270688-09.jpg',
		61606 => 'https://thechrisellefactor.com/wp-content/uploads/2021/02/IMG_5142-1.jpg',
		61607 => 'https://thechrisellefactor.com/wp-content/uploads/2021/02/IMG_5204-1.jpg',
		61609 => 'https://thechrisellefactor.com/wp-content/uploads/2021/02/IMG_5198-1.jpg',
		61610 => 'https://thechrisellefactor.com/wp-content/uploads/2021/02/IMG_5122-copy-1.jpg',
		61599 => 'https://thechrisellefactor.com/wp-content/uploads/2021/02/5c6d75fc-980c-446d-a3ca-57311499ef61.jpg',
		61576 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1326.jpg',
		61577 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1296.jpg',
		61578 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1344.jpg',
		61579 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1365.jpg',
		61582 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1389.jpg',
		61583 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/IMG_1408.jpg',
		61585 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/Screen-Shot-2020-12-03-at-3.49.21-PM.png',
		61586 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/Screen-Shot-2020-12-03-at-3.49.57-PM.png',
		61587 => 'https://thechrisellefactor.com/wp-content/uploads/2020/12/Screen-Shot-2020-12-03-at-3.50.38-PM.png',
		61534 => 'https://thechrisellefactor.com/wp-content/uploads/2020/10/IMG_4109-e1601948204773.jpg',
		61536 => 'https://thechrisellefactor.com/wp-content/uploads/2020/10/IMG_4085.jpg',
		61537 => 'https://thechrisellefactor.com/wp-content/uploads/2020/10/IMG_4087.jpg',
		61535 => 'https://thechrisellefactor.com/wp-content/uploads/2020/10/IMG_4111.jpg',
		61532 => 'https://thechrisellefactor.com/wp-content/uploads/2020/10/Screen-Shot-2020-10-05-at-6.33.36-PM.png',
		61323 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/IMG_4986.jpg',
		61324 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/IMG_4978.jpg',
		61327 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/IMG_4984.jpg',
		61326 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/IMG_4981.jpg',
		61424 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-5.49.44-PM.png',
		61426 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-5.52.07-PM.png',
		61428 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-5.53.46-PM.png',
		61422 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-5.45.01-PM.png',
		61307 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/IMG_7857.jpg',
		61306 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-29-at-6.29.03-PM.png',
		61519 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-07-02-at-8.48.07-AM.png',
		61437 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.15.04-PM.png',
		61439 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.19.12-PM.png',
		61444 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.25.15-PM.png',
		61442 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.22.32-PM.png',
		61441 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.21.32-PM.png',
		61448 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.30.01-PM.png',
		61432 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.07.29-PM.png',
		61446 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-06-30-at-6.28.31-PM.png',
		61495 => 'https://thechrisellefactor.com/wp-content/uploads/2020/07/Screen-Shot-2020-07-01-at-12.06.50-PM.png',
		61047 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/IMG_4873.jpg',
		61046 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/IMG_4869.jpg',
		61058 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-3.00.27-PM.png',
		61059 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-3.01.18-PM.png',
		61060 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-3.02.31-PM.png',
		61061 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-3.04.46-PM.png',
		61062 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-3.05.52-PM.png',
		61073 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-19-at-4.32.39-PM.png',
		61049 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.07.06-PM.png',
		61050 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.09.51-PM.png',
		61051 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.10.54-PM.png',
		61052 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.12.08-PM.png',
		61053 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.13.12-PM.png',
		61054 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.14.31-PM.png',
		61055 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.15.50-PM.png',
		61056 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.17.44-PM.png',
		61057 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-18-at-2.19.16-PM.png',
		60997 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/IMG_0529.jpeg',
		60998 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/IMG_0530.jpeg',
		61002 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.18.22-PM.png',
		61003 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.18.07-PM.png',
		61004 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.18.46-PM.png',
		61005 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.19.06-PM.png',
		61006 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.19.17-PM.png',
		61007 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-2.46.48-PM.png',
		61022 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-6.52.12-PM.png',
		61016 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/a7938ced-ba2b-42e5-a6f6-197e57d36ae7.jpg',
		61017 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-6.46.34-PM.png',
		61018 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-6.47.45-PM.png',
		61019 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-6.49.40-PM.png',
		61020 => 'https://thechrisellefactor.com/wp-content/uploads/2020/05/Screen-Shot-2020-05-06-at-6.50.43-PM.png',
		60828 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/IMG_4893-1.jpg',
		60826 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/IMG_4905.jpg',
		60830 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-09-at-12.56.09-PM.png',
		60831 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/hmgoepprod.jpg',
		60832 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/0325ed4f-f17c-4677-b046-ca1764fba29f.jpg',
		60834 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/b28e88fb-48d1-487f-a982-a4095a197043.jpg',
		60854 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-10-at-1.36.51-PM.png',
		60848 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-09-at-3.20.10-PM.png',
		60844 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-09-at-2.47.41-PM.png',
		60845 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-09-at-2.48.10-PM.png',
		60849 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/hmgoepprod-1.jpg',
		60851 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-10-at-1.09.25-PM.png',
		60850 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-10-at-1.08.19-PM.png',
		60853 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/s20_04_a02_77541_6335_off_a.jpg',
		60837 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Bevza.jpg',
		60838 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/LoQ.jpg',
		60839 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Topshop-Dixie-Strappy-Sandal.jpg',
		60840 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-09-at-1.50.35-PM.png',
		60841 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Topshop-Sandal.jpg',
		60843 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/15aafe2b-0d4e-41b0-bb03-dcf25a0b9105.jpg',
		60852 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-10-at-1.12.51-PM.png',
		60856 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Isela-Dress-20200321141400.jpg',
		60859 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/s20_04_a04_77786_5864_off_a.jpg',
		60861 => 'https://thechrisellefactor.com/wp-content/uploads/2020/04/Screen-Shot-2020-04-15-at-1.29.36-PM.png',
		60654 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/2c1e7df8-8fd5-4f85-9f84-bf4db492a111.jpg',
		60655 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/1227772_in_pp.jpg',
		60656 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/c8ea56e1-c7b6-42b8-8702-708d2ba3d7ff.jpg',
		60657 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/14a76e4f-b27c-477d-a024-3627a74732b3.jpg',
		60664 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/large_jacquemus-green-la-veste-tablier-belted-wool-blend-blazer.jpg',
		60658 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/fc090e21-9744-473f-9b66-08df32b49274.jpg',
		60660 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/bdb5b9e0-ca70-48a4-9cef-e1a39ddc27c7.jpg',
		60661 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/s20_01_a04_76585_1274_off_a.jpg',
		60662 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/a13c6050-a561-4ebc-b87f-8838a5326e55.jpg',
		60663 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/large_acler-neutral-graham-linen-blend-jacket.jpg',
		60666 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-28-at-3.41.25-PM.png',
		60668 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-28-at-3.48.12-PM.png',
		60669 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/683d8404-6487-489b-9b57-43d74b121f48.jpg',
		60670 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/32a33815-d561-4573-98c6-0305406e39e8.jpg',
		60671 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/edb143fa-7803-4072-b87c-0483a255c7d8.jpg',
		60667 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/8938f482-875f-4577-822d-9ebdf8cd7457.jpg',
		60674 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/1215900_in_pp.jpg',
		60687 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/brxp_xlProduct.jpg',
		60675 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/1129052_in_pp.jpg',
		60677 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/hmgoepprod.jpg',
		60685 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/Screen-Shot-2020-03-01-at-3.59.55-PM.png',
		60679 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/Screen-Shot-2020-03-01-at-2.32.46-PM.png',
		60680 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/hmgoepprod-1.jpg',
		60681 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/e75bef6f-a3ff-46bd-8130-86db498544d2.jpg',
		60682 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/lione3004277087_q1_2-0.jpg',
		60683 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/Screen-Shot-2020-03-01-at-3.07.54-PM.png',
		60653 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/s20_04_a04_77786_5864_off_a.jpg',
		60659 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/b961c66f-8146-43eb-98e4-c01de6164913.jpg',
		60672 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/Screen-Shot-2020-03-01-at-2.04.44-PM.png',
		60673 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/Screen-Shot-2020-03-01-at-2.10.02-PM.png',
		60678 => 'https://thechrisellefactor.com/wp-content/uploads/2020/03/b142ef13-cc74-44d2-bc59-53d8e5c64d97.jpg',
		60332 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/6eddbdda-6dcc-4583-b855-156ad27e8639.jpg',
		60333 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/pi_hcs_refl_2.png',
		60334 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/s2300804-main-zoom.jpg',
		60335 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-2.50.31-PM.png',
		60336 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-2.51.05-PM.png',
		60337 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/DP0118201813164736M.jpg',
		60338 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.06.45-PM.png',
		60339 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.07.52-PM.png',
		60340 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.10.05-PM.png',
		60341 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/10787.jpg',
		60342 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.15.48-PM.png',
		60343 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.16.50-PM.png',
		60387 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-20-at-1.56.36-PM.png',
		60388 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-20-at-1.58.17-PM.png',
		60389 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/f749454a-dcfc-4dc8-891d-d576eb3e5c92.jpg',
		60390 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/4c7c5f95-156c-406a-b1c1-4611224f5af6.jpg',
		60391 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-20-at-2.02.06-PM.png',
		60392 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-20-at-2.03.40-PM.png',
		60346 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.40.06-PM.png',
		60347 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/tower-28-sos-saveourskin-daily-rescue-facial-spray.jpg',
		60348 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/GUEST_9abdecaf-5bb0-425d-b262-c43d68b6fb39.jpg',
		60349 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/80631.jpg',
		60350 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.46.17-PM.png',
		60351 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.47.21-PM.png',
		60352 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/fe0a9152-a10b-41ee-a661-95f63b2f0b53.jpg',
		60353 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Solution.jpg',
		60354 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.48.50-PM.png',
		60355 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.49.24-PM.png',
		60356 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-19-at-3.50.08-PM.png',
		60269 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/a204d230-a224-4924-aed5-e84e8cd18fb7.jpg',
		60270 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-1.26.06-PM.png',
		60271 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-1.26.58-PM.png',
		60272 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-1.28.02-PM.png',
		60273 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-1.28.36-PM.png',
		60262 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/1206752_bk_pp.jpg',
		60263 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/1214457_ou_pp.jpg',
		60264 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/1150413_in_pp.jpg',
		60265 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/1200393_bk_pp.jpg',
		60266 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-12.01.25-PM.png',
		60267 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-12.02.05-PM.png',
		60268 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-02-14-at-12.03.16-PM.png',
		59891 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/IMG_5072.jpg',
		59892 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/IMG_5073.jpg',
		59893 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/IMG_5074.jpg',
		59894 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/IMG_5075.jpg',
		59896 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.03.44-PM.png',
		59897 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.05.20-PM.png',
		59898 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.06.50-PM.png',
		59899 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.08.00-PM.png',
		59900 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.09.08-PM.png',
		59901 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.11.28-PM.png',
		59902 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.12.20-PM.png',
		59903 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.14.44-PM.png',
		59904 => 'https://thechrisellefactor.com/wp-content/uploads/2020/02/Screen-Shot-2020-01-31-at-3.15.41-PM.png',
		59687 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9372.jpg',
		59685 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9393.jpg',
		59688 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9380.jpg',
		59686 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9396.jpg',
		59691 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9504.jpg',
		59690 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9397.jpg',
		59693 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9383.jpg',
		59692 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_9381.jpg',
		59719 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/e77bf98a-5bff-48a6-96c7-35444dee2291.jpg',
		59720 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/d8bd8552-eefb-4502-845c-27272c37fd08.jpg',
		59721 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/f9a592b3-d599-4e12-b343-1a13a92f978d.jpg',
		59722 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.31.50-AM.png',
		59723 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.32.34-AM.png',
		59724 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.33.09-AM.png',
		59725 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.33.52-AM.png',
		59727 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.46.27-AM.png',
		59728 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/74411.jpg',
		59729 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-11.51.07-AM.png',
		59730 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.03.37-PM.png',
		59731 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.05.02-PM.png',
		59732 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.06.27-PM.png',
		59733 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.06.50-PM.png',
		59734 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.08.16-PM.png',
		59735 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.09.04-PM.png',
		59739 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.27.32-PM.png',
		59741 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.28.59-PM.png',
		59742 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-21-at-12.29.49-PM.png',
		59567 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_4169.jpg',
		59568 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_4196.jpg',
		59572 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_4214.jpg',
		59571 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_4175.jpg',
		59589 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/22cfb03f-44aa-44af-a7bf-12ac218f1e34.jpg',
		59591 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/boyis3004415d49_q6_2-0._UX357_QL90_.jpg',
		59592 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1153698_fr_pp.jpg',
		59593 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/3f1927c2-09d3-4669-b849-7df4ce10a028.jpg',
		59596 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/02c47a70-80e9-45db-914a-8db5555f0a5a.jpg',
		59597 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.47.08-PM.png',
		59598 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.49.02-PM.png',
		59599 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.49.37-PM.png',
		59601 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1107942_fr_pp.jpg',
		59602 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/SMCC-WO61_V2.jpg',
		59603 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.55.20-PM.png',
		59604 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.56.47-PM.png',
		59606 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.09.18-PM.png',
		59607 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.13.55-PM.png',
		59608 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1904e3f1-8fc5-40f8-b28c-24ac4e1e782f.jpg',
		59609 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/6390fbf0-0d36-432d-bc32-8f23b00a3373.jpg',
		59611 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/acb99150-c68e-4a2c-bfb8-21c8dc50a620.jpg',
		59612 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/ed97dfb4-5008-47f1-b0f0-017bb5ca0445.jpg',
		59613 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/fd768061-fb2e-4e2c-aedf-e6a2208b50e1.jpg',
		59614 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/5a6afa01-559b-43f1-b6d3-a8e0c82e0bca.jpg',
		59616 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/rachc210871071c_q6_2-0._UX357_QL90_.jpg',
		59617 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/97f53d7c-f982-4da6-a403-ba179bc9a55b.jpg',
		59618 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/mandb3003460403_q6_2-0._UX357_QL90_.jpg',
		59619 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.36.38-PM.png',
		59621 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/14079189_20969043_1000.jpg',
		59622 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.41.52-PM.png',
		59623 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/f23e6c42-636c-4174-bab3-7e3715e90991.jpg',
		59624 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1132578_in_pp.jpg',
		59626 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1203172_in_pp.jpg',
		59627 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.53.50-PM.png',
		59641 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1171107_in_pp.jpg',
		59629 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/b1ce36ab-f8e9-44f4-a387-e711c590c438.jpg',
		59631 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/0fa8edab-fa49-49f4-b1c2-5ed7a0505b2b.jpg',
		59632 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/astrr300831071c_q6_2-0._UX357_QL90_.jpg',
		59633 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/58a22715-ee57-4651-89d2-5d94ec71fc4c.jpg',
		59634 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/f60daa7d-dee2-4d41-882a-f3b5e711e0af.jpg',
		59635 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/b5cdaba4-de90-4e14-a4f0-073293d98d72-1.jpg',
		59636 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/vejaa3026016c6f_q6_2-0._UX357_QL90_-1.jpg',
		59637 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/ythre308361700c_q6_2-0._UX357_QL90_.jpg',
		59639 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/cb4004c7-d18a-48db-8fa2-1039a0232730.jpg',
		59594 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.36.07-PM.png',
		59595 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-1.42.13-PM.png',
		59600 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/fc090e21-9744-473f-9b66-08df32b49274.jpg',
		59605 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/fc02e753-b949-4d89-b695-2b1cd302694b.jpg',
		59610 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1174864_in_pp.jpg',
		59615 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/4390e6a3-d740-41ed-bf8c-2970a5a8937d.jpg',
		59620 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-13-at-2.38.57-PM.png',
		59625 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/1242191_in_pp.jpg',
		59630 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/cb93cb74-98d7-4652-bd9e-9a26bc720185.jpg',
		59638 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/32ed84e7-e575-4420-90cf-531d6ff67f36.jpg',
		59277 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_3943.jpg',
		59278 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_3937.jpg',
		59280 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_3942.jpg',
		59281 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/IMG_3954.jpg',
		59562 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-09-at-10.16.07-AM.png',
		59563 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-09-at-10.17.37-AM.png',
		59564 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-09-at-10.18.29-AM.png',
		59230 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_4623.jpg',
		59229 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_4617.jpg',
		59460 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-06-at-7.25.26-PM-1.png',
		59404 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/cdn.cliqueinc.com__cache__posts__208755__-1981094-1479408874.700x0c-4776dc13bc85453f9b2798ac69d363ac-1.jpg',
		59405 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-02-at-10.15.44-AM.png',
		59453 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-06-at-2.06.46-PM.png',
		59473 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-07-at-11.01.11-AM.png',
		59406 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-02-at-10.24.24-AM.png',
		59407 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-02-at-10.26.59-AM.png',
		59408 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-02-at-10.28.41-AM.png',
		59409 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/Screen-Shot-2020-01-02-at-10.31.20-AM.png',
		59410 => 'https://thechrisellefactor.com/wp-content/uploads/2020/01/06933a11-795f-4f16-9f33-14c92cbb01d6.jpg',
		59337 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.57.48-PM.png',
		59336 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.57.15-PM.png',
		59341 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.58.21-PM.png',
		59340 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.58.00-PM.png',
		59344 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.57.38-PM.png',
		59343 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.59.04-PM.png',
		59346 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.58.48-PM.png',
		59345 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-18-at-3.58.12-PM.png',
		59123 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_4628.jpg',
		59207 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-16-at-11.20.24-AM.png',
		59209 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-16-at-12.00.11-PM.png',
		59210 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-16-at-12.03.05-PM.png',
		59213 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-16-at-12.11.35-PM.png',
		59212 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/530bf1b2-5001-4001-81d8-839d5dbc5ba6.jpg',
		58803 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_2332.jpg',
		58802 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_2318.jpg',
		59086 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_9346.jpg',
		59085 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_9360.jpg',
		58806 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_2315.jpg',
		58807 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_2338.jpg',
		59134 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-11-at-12.23.04-PM.png',
		59135 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-11-at-12.24.04-PM.png',
		59136 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-11-at-12.24.53-PM.png',
		59133 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/Screen-Shot-2019-12-11-at-12.10.12-PM.png',
		59137 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/956463_in_pp.jpg',
		58960 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_4005.jpg',
		58959 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_3987.jpg',
		58963 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_3978.jpg',
		58962 => 'https://thechrisellefactor.com/wp-content/uploads/2019/12/IMG_4026.jpg',
		58514 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_9731.jpg',
		58339 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/F069E082-A4FD-4FC4-9D21-7CE5AFB8D79D.jpg',
		58338 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_1029.jpg',
		58342 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/7C832A99-FA2E-4649-AFF4-E3DEC4098206.jpg',
		58341 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/3FD997F4-CD06-4DBC-B231-C99575EAE82A.jpg',
		58344 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_1435.jpeg',
		58343 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_9563.jpeg',
		58346 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_9820.jpeg',
		58345 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_0157.jpeg',
		58349 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_0988.jpg',
		58348 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/FC3BDA3A-922E-4024-B787-26EAFC96DB76.jpg',
		58357 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.23.27-PM.png',
		58359 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/40b3bd1a-ac07-4e90-a2ea-248c80eb7cd6.jpg',
		58360 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/1a8ec0bd-b5b2-4b13-814c-71d765299e9a.jpg',
		58361 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/f39e754a-52b2-44a7-9941-ae003f9e54b1.jpg',
		58362 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/b73bcccf-6206-47cc-a6a9-02db42eecf30.jpg',
		58363 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/fb85d299-5c30-4b76-b7d5-88f6e3b31daa.jpg',
		58364 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/ee21fdc1-76ba-4eeb-b981-40335d01ca5a.jpg',
		58366 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.46.32-PM.png',
		58367 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/492491a5-55de-4e81-86c1-0ff71f449452.jpg',
		58368 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.50.18-PM.png',
		58369 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.51.11-PM.png',
		58370 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.51.52-PM.png',
		58371 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.52.35-PM.png',
		58372 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.53.35-PM.png',
		58373 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/i-035808-jet-lag-mask-64g-1-940.jpg',
		58374 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.55.44-PM.png',
		58375 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-2.58.34-PM.png',
		58376 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/stretch-concealer.jpg',
		58377 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.02.14-PM.png',
		58378 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/6d51724b-cd8a-4056-b027-1376c91fb4ba.jpg',
		58379 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.06.48-PM.png',
		58380 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/boy-brow.jpg',
		58381 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/images.jpg',
		58383 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.10.16-PM.png',
		58384 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.11.43-PM.png',
		58385 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.12.32-PM.png',
		58386 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/Screen-Shot-2019-11-05-at-3.13.07-PM.png',
		58340 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/IMG_9135.jpeg',
		58365 => 'https://thechrisellefactor.com/wp-content/uploads/2019/11/80ab354f-f5b4-4d50-b10f-091114f78ad3.jpg',
	];

	return $data;
}


/* 
search_image
 */
function search_image($searchName) {
	// $searchName = 'ChriselleLim_setlist0002.jpg';

	$data = get_image_from_live();
	$image_id = '';

	foreach ($data as $id => $url) {
		if (strpos($url, $searchName) !== false) {
			$index = array_search($id, array_keys($data));
			// echo "ID: $id, Index: $index\n";
			$image_id = $id;
		}
	}

	return $image_id;
}


/* 
load_image_from_folder
 */
function load_image_from_folder() {
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	$image_files = get_image_from_live();

	// Get the path to the "new-images" folder within your theme
	$theme_directory = get_template_directory();
	$image_subfolder = 'new-images';
	$image_folder_path = trailingslashit($theme_directory) . '/' . $image_subfolder . '/';


	// Check if the "new-images" folder exists
	if (is_dir($image_folder_path)) {

		// Loop through the image files
		foreach ($image_files as $image_file) {
			// Define the path to your local image file
			$name = basename($image_file);
			$local_image_path = $image_folder_path . $name; // Change this to the actual path of your image

			$old_image_id = search_image($name);

			// Get the file name from the path
			$file_name = basename($local_image_path);

			// Create an array of file data for the upload
			$file = array(
				'name'     => $file_name,
				'type'     => mime_content_type($local_image_path),
				'tmp_name' => $local_image_path,
				'error'    => 0,
				'size'     => filesize($local_image_path),
			);

			// Upload the image to the media library
			$upload = wp_upload_bits($file_name, null, file_get_contents($local_image_path));

			// Check if the upload was successful
			if ($upload['error'] === false) {
				$image_url = $upload['url'];
				$image_path = $upload['file'];

				$attachment = array(
					'guid'           => $image_url,  // URL of the image
					'post_mime_type' => 'image/jpeg', // Change the mime type according to your image type
					'post_title'     => $file_name,
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				$attachment_id = wp_insert_attachment($attachment, $image_path);

				// You may need to generate attachment metadata and update the post with it
				$attachment_data = wp_generate_attachment_metadata($attachment_id, $image_path);
				wp_update_attachment_metadata($attachment_id, $attachment_data);
			} else {
				// Something went wrong, and the image was not uploaded
				echo 'Image upload failed: ' . $upload['error'];
			}

			update_post_meta($attachment_id, 'old_image_id', $old_image_id);
		}
	} else {
		echo 'The "new-images" folder does not exist.';
	}
}


/* 
  get_image_by_old_image_id
 */
function get_image_by_old_image_id($image_old_id) {
	$attachment_id = '';

	$query_args = array(
		'posts_per_page' => 1,              // Limit to one result
		'post_type'      => 'attachment',
		'post_status'    => 'any',
		'meta_query' => array(
			array(
				'key'     => 'old_image_id', // Replace with the actual metadata key
				'value'   => $image_old_id, // Replace with the desired metadata value
				'compare' => '='
			)
		),
	);

	$query = new WP_Query($query_args);
	$attachment_id = wp_list_pluck($query->posts, 'ID');

	return $attachment_id[0];
}


/* 
get_all_image_with_old_id
 */
function get_all_image_with_old_id() {
	$old_id_meta_key = 'old_image_id'; // Replace with the actual meta key for old IDs

	$query_args = array(
		'post_type'      => 'attachment',
		'meta_key'       => $old_id_meta_key,
		'meta_compare'   => 'EXISTS',           // Ensures the meta field exists
		'posts_per_page' => -1,                 // Retrieve all matching images
		'post_status'    => 'any',
	);

	$query = new WP_Query($query_args);

	$old_to_current_id_map = array();

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			$attachment_id = get_the_ID();
			$old_id = get_post_meta($attachment_id, $old_id_meta_key, true);

			if (!empty($old_id)) {
				$old_to_current_id_map[$old_id] = $attachment_id;
			}
		}
	}

	wp_reset_postdata(); // Reset the query

	// Display the array on the screen
	echo '<pre>';
	print_r($old_to_current_id_map);
	echo '</pre>';
}


/* 
get_images_old_id_new_id
 */
function get_images_old_id_new_id() {

	$data = [
		'58373' => 5572,
		'58374' => 5573,
		'58375' => 5574,
		'58376' => 5575,
		'58377' => 5576,
		'58378' => 5577,
		'58379' => 5578,
		'58380' => 5579,
		'58381' => 5580,
		'58383' => 5581,
		'58384' => 5582,
		'58385' => 5583,
		'58386' => 5584,
		'58340' => 5585,
		'58365' => 5586,
		'59408' => 5515,
		'59409' => 5516,
		'59410' => 5517,
		'59337' => 5518,
		'59336' => 5519,
		'59341' => 5520,
		'59340' => 5521,
		'59344' => 5522,
		'59343' => 5523,
		'59346' => 5524,
		'59345' => 5525,
		'59123' => 5526,
		'59207' => 5527,
		'59209' => 5528,
		'59210' => 5529,
		'59213' => 5530,
		'59212' => 5531,
		'58803' => 5532,
		'58802' => 5533,
		'59086' => 5534,
		'59085' => 5535,
		'58806' => 5536,
		'58807' => 5537,
		'59134' => 5538,
		'59135' => 5539,
		'59136' => 5540,
		'59133' => 5541,
		'59137' => 5542,
		'58960' => 5543,
		'58959' => 5544,
		'58963' => 5545,
		'58962' => 5546,
		'58514' => 5547,
		'58339' => 5548,
		'58338' => 5549,
		'58342' => 5550,
		'58341' => 5551,
		'58344' => 5552,
		'58343' => 5553,
		'58346' => 5554,
		'58345' => 5555,
		'58349' => 5556,
		'58348' => 5557,
		'58357' => 5558,
		'58359' => 5559,
		'58360' => 5560,
		'58361' => 5561,
		'58362' => 5562,
		'58363' => 5563,
		'58364' => 5564,
		'58366' => 5565,
		'58367' => 5566,
		'58368' => 5567,
		'58369' => 5568,
		'58370' => 5569,
		'58371' => 5570,
		'58372' => 5571,
		'59571' => 5448,
		'59589' => 5449,
		'59591' => 5450,
		'59592' => 5451,
		'59593' => 5452,
		'59596' => 5453,
		'59597' => 5454,
		'59598' => 5455,
		'59599' => 5456,
		'59601' => 5457,
		'59602' => 5458,
		'59603' => 5459,
		'59604' => 5460,
		'59606' => 5461,
		'59607' => 5462,
		'59608' => 5463,
		'59609' => 5464,
		'59611' => 5465,
		'59612' => 5466,
		'59613' => 5467,
		'59614' => 5468,
		'59616' => 5469,
		'59617' => 5470,
		'59618' => 5471,
		'59619' => 5472,
		'59621' => 5473,
		'59622' => 5474,
		'59623' => 5475,
		'59624' => 5476,
		'59626' => 5477,
		'59627' => 5478,
		'59641' => 5479,
		'59629' => 5480,
		'59631' => 5481,
		'59632' => 5482,
		'59633' => 5483,
		'59634' => 5484,
		'59635' => 5485,
		'59636' => 5486,
		'59637' => 5487,
		'59639' => 5488,
		'59594' => 5489,
		'59595' => 5490,
		'59600' => 5338,
		'59605' => 5492,
		'59610' => 5493,
		'59615' => 5494,
		'59620' => 5495,
		'59625' => 5496,
		'59630' => 5497,
		'59638' => 5498,
		'59277' => 5499,
		'59278' => 5500,
		'59280' => 5501,
		'59281' => 5502,
		'59562' => 5503,
		'59563' => 5504,
		'59564' => 5505,
		'59230' => 5506,
		'59229' => 5507,
		'59460' => 5508,
		'59404' => 5509,
		'59405' => 5510,
		'59453' => 5511,
		'59473' => 5512,
		'59406' => 5513,
		'59407' => 5514,
		'60340' => 5372,
		'60341' => 5373,
		'60342' => 5374,
		'60343' => 5375,
		'60387' => 5376,
		'60388' => 5377,
		'60389' => 5378,
		'60390' => 5379,
		'60391' => 5380,
		'60392' => 5381,
		'60346' => 5382,
		'60347' => 5383,
		'60348' => 5384,
		'60349' => 5385,
		'60350' => 5386,
		'60351' => 5387,
		'60352' => 5388,
		'60353' => 5389,
		'60354' => 5390,
		'60355' => 5391,
		'60356' => 5392,
		'60269' => 5393,
		'60270' => 5394,
		'60271' => 5395,
		'60272' => 5396,
		'60273' => 5397,
		'60262' => 5398,
		'60263' => 5399,
		'60264' => 5400,
		'60265' => 5401,
		'60266' => 5402,
		'60267' => 5403,
		'60268' => 5404,
		'59891' => 5405,
		'59892' => 5406,
		'59893' => 5407,
		'59894' => 5408,
		'59896' => 5409,
		'59897' => 5410,
		'59898' => 5411,
		'59899' => 5412,
		'59900' => 5413,
		'59901' => 5414,
		'59902' => 5415,
		'59903' => 5416,
		'59904' => 5417,
		'59687' => 5418,
		'59685' => 5419,
		'59688' => 5420,
		'59686' => 5421,
		'59691' => 5422,
		'59690' => 5423,
		'59693' => 5424,
		'59692' => 5425,
		'59719' => 5426,
		'59720' => 5427,
		'59721' => 5428,
		'59722' => 5429,
		'59723' => 5430,
		'59724' => 5431,
		'59725' => 5432,
		'59727' => 5433,
		'59728' => 5434,
		'59729' => 5435,
		'59730' => 5436,
		'59731' => 5437,
		'59732' => 5438,
		'59733' => 5439,
		'59734' => 5440,
		'59735' => 5441,
		'59739' => 5442,
		'59741' => 5443,
		'59742' => 5444,
		'59567' => 5445,
		'59568' => 5446,
		'59572' => 5447,
		'61002' => 5297,
		'61003' => 5298,
		'61004' => 5299,
		'61005' => 5300,
		'61006' => 5301,
		'61007' => 5302,
		'61022' => 5303,
		'61016' => 5304,
		'61017' => 5305,
		'61018' => 5306,
		'61019' => 5307,
		'61020' => 5308,
		'60828' => 5309,
		'60826' => 5310,
		'60830' => 5311,
		'60677' => 5352,
		'60832' => 5313,
		'60834' => 5314,
		'60854' => 5315,
		'60848' => 5316,
		'60844' => 5317,
		'60845' => 5318,
		'60680' => 5355,
		'60851' => 5320,
		'60850' => 5321,
		'60853' => 5322,
		'60837' => 5323,
		'60838' => 5324,
		'60839' => 5325,
		'60840' => 5326,
		'60841' => 5327,
		'60843' => 5328,
		'60852' => 5329,
		'60856' => 5330,
		'60653' => 5359,
		'60861' => 5332,
		'60654' => 5333,
		'60655' => 5334,
		'60656' => 5335,
		'60657' => 5336,
		'60664' => 5337,
		'60660' => 5339,
		'60661' => 5340,
		'60662' => 5341,
		'60663' => 5342,
		'60666' => 5343,
		'60668' => 5344,
		'60669' => 5345,
		'60670' => 5346,
		'60671' => 5347,
		'60667' => 5348,
		'60674' => 5349,
		'60687' => 5350,
		'60675' => 5351,
		'60685' => 5353,
		'60679' => 5354,
		'60681' => 5356,
		'60682' => 5357,
		'60683' => 5358,
		'60659' => 5360,
		'60672' => 5361,
		'60673' => 5362,
		'60678' => 5363,
		'60332' => 5364,
		'60333' => 5365,
		'60334' => 5366,
		'60335' => 5367,
		'60336' => 5368,
		'60337' => 5369,
		'60338' => 5370,
		'60339' => 5371,
		'61428' => 5264,
		'61422' => 5265,
		'61307' => 5266,
		'61306' => 5267,
		'61519' => 5268,
		'61437' => 5269,
		'61439' => 5270,
		'61444' => 5271,
		'61442' => 5272,
		'61441' => 5273,
		'61448' => 5274,
		'61432' => 5275,
		'61446' => 5276,
		'61495' => 5277,
		'61047' => 5278,
		'61046' => 5279,
		'61058' => 5280,
		'61059' => 5281,
		'61060' => 5282,
		'61061' => 5283,
		'61062' => 5284,
		'61073' => 5285,
		'61049' => 5286,
		'61050' => 5287,
		'61051' => 5288,
		'61052' => 5289,
		'61053' => 5290,
		'61054' => 5291,
		'61055' => 5292,
		'61056' => 5293,
		'61057' => 5294,
		'60997' => 5295,
		'60998' => 5296,
		'61671' => 5227,
		'61672' => 5228,
		'61673' => 5229,
		'61675' => 5230,
		'61674' => 5231,
		'61676' => 5232,
		'61678' => 5233,
		'61679' => 5234,
		'61623' => 5235,
		'61622' => 5236,
		'61620' => 5237,
		'61632' => 5238,
		'61606' => 5239,
		'61607' => 5240,
		'61609' => 5241,
		'61610' => 5242,
		'61599' => 5243,
		'61576' => 5244,
		'61577' => 5245,
		'61578' => 5246,
		'61579' => 5247,
		'61582' => 5248,
		'61583' => 5249,
		'61585' => 5250,
		'61586' => 5251,
		'61587' => 5252,
		'61534' => 5253,
		'61536' => 5254,
		'61537' => 5255,
		'61535' => 5256,
		'61532' => 5257,
		'61323' => 5258,
		'61324' => 5259,
		'61327' => 5260,
		'61326' => 5261,
		'61424' => 5262,
		'61426' => 5263,
	];

	return $data;
}


/* 
upload_image_featured
 */
function upload_image_featured() {
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => 300,
		'offset'         => 0,
		'meta_key'       => 'wcl_featured_temp',
		'meta_compare'   => 'EXISTS',
	);

	$query_obj   = new WP_Query($args);
	$total_count = $query_obj->found_posts;

	$count = 0;

	if ($query_obj->have_posts()) {
		global $post;
		while ($query_obj->have_posts()) {
			$query_obj->the_post();
			$post_id = get_the_ID();

			$image_url = get_post_meta($post->ID, 'wcl_featured_temp', true);

			if (!has_post_thumbnail()) {
				if (!empty($image_url)) {
					$count++;
					$media = media_sideload_image($image_url, null, null, 'id');

					if (!is_wp_error($media)) {
						set_post_thumbnail($post_id, $media);

						echo 'Image downloaded and saved: ' . $media . '<br>';
					} else {
						echo 'Failed to download the image: ' . $media->get_error_message() . '<br>';
					}
				}
			}
		}
		wp_reset_postdata();
	}
}



//wp_loop_set_images_of_acf_single_post();
function wp_loop_set_images_of_acf_single_post() {

	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => 300,
		//	'post__in'       => [1249],
	);

	$query_obj   = new WP_Query($args);
	$total_count = $query_obj->found_posts;
	$counter = 0;

	$images_new  = get_images_old_id_new_id();

	if ($query_obj->have_posts()) {
		global $post;
		while ($query_obj->have_posts()) {
			$query_obj->the_post();
			$post_id = get_the_ID();
			$switch  = true;

			// Получите контент поста
			$content = $post->post_content;

			/// two-images-overlapped

			if ($switch) {
				// Регулярное выражение для поиска ID картинок в полях block_two_images_overlapped_gp_image1 и block_two_images_overlapped_gp_image2
				$pattern_image1 = '/"block_two_images_overlapped_gp_image1":(\d+),/';
				$pattern_image2 = '/"block_two_images_overlapped_gp_image2":(\d+),/';

				preg_match_all($pattern_image1, $content, $matches1);
				preg_match_all($pattern_image2, $content, $matches2);

				// $matches1[1] и $matches2[1] содержат массивы ID картинок для полей block_two_images_overlapped_gp_image1 и block_two_images_overlapped_gp_image2
				$image_ids1 = $matches1[1];
				$image_ids2 = $matches2[1];

				// Сверьте каждый ID с ключами массива $images_new и обновите их
				foreach ($image_ids1 as $image_id) {
					if (isset($images_new[$image_id])) {
						$counter++;
						$new_id = $images_new[$image_id];
						$content = str_replace("\"block_two_images_overlapped_gp_image1\":$image_id,", "\"block_two_images_overlapped_gp_image1\":$new_id,", $content);
					}
				}

				foreach ($image_ids2 as $image_id) {
					if (isset($images_new[$image_id])) {
						$counter++;
						$new_id = $images_new[$image_id];
						$content = str_replace("\"block_two_images_overlapped_gp_image2\":$image_id,", "\"block_two_images_overlapped_gp_image2\":$new_id,", $content);
					}
				}
			}


			/// shop-product-list

			if ($switch) {
				// Регулярное выражение для поиска ID картинок в блоках wp:acf/shop-product-list
				$pattern = '/"block_shop_list_products_(\d+)_image":(\d+),/';
				preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

				// Переберите все соответствия и обновите ID картинок
				foreach ($matches as $match) {
					$index = $match[1]; // Номер индекса (например, 0, 1, 2)
					$image_id = $match[2]; // Старый ID картинки

					if (isset($images_new[$image_id])) {
						$counter++;
						$new_id = $images_new[$image_id];
						$content = preg_replace("/\"block_shop_list_products_" . $index . "_image\":$image_id,/", "\"block_shop_list_products_" . $index . "_image\":$new_id,", $content);
					}
				}
			}


			/// image-overlap

			if ($switch) {
				// Регулярное выражение для поиска ID картинок в блоках wp:acf/image-overlap
				$pattern = '/"block_image_overlap_img":(\d+),/';
				preg_match_all($pattern, $content, $matches);

				// $matches[1] содержит массив ID картинок для блока wp:acf/image-overlap
				$image_ids = $matches[1];

				// Сверьте каждый ID с ключами массива $images_new и обновите их
				foreach ($image_ids as $image_id) {
					if (isset($images_new[$image_id])) {
						$counter++;
						$new_id = $images_new[$image_id];
						//	var_dump(123);
						$content = str_replace("\"block_image_overlap_img\":$image_id,", "\"block_image_overlap_img\":$new_id,", $content);
					}
				}
			}

			// product-focus

			if ($switch) {
				// Регулярное выражение для поиска ID картинок в блоках wp:acf/product-focus
				$pattern = '/"product_focus_gp_image":(\d+),/';
				preg_match_all($pattern, $content, $matches);

				// $matches[1] содержит массив ID картинок для блока wp:acf/product-focus
				$image_ids = $matches[1];

				// Сверьте каждый ID с ключами массива $images_new и обновите их
				foreach ($image_ids as $image_id) {
					if (isset($images_new[$image_id])) {
						$counter++;
						$new_id = $images_new[$image_id];
						$content = str_replace("\"product_focus_gp_image\":$image_id,", "\"product_focus_gp_image\":$new_id,", $content);
					}
				}
			}


			//var_dump($content);
			// Сохраните обновленный контент в пост
			$post->post_content = $content;
			wp_update_post($post);
		}
		var_dump($counter);

		wp_reset_postdata();
	}
}


