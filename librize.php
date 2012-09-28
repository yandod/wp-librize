<?php
/*
Plugin Name: Librize Plugin
Plugin URI: https://github.com/yandod/wp-librize
Description: <a href="http://librize.com/">リブライズ</a>の情報をショートコードで埋め込むプラグインです [librize place=3 limit=3]　のように投稿やテキストウィジェットに記述すると、id3の本棚から新着書籍を3件表示します。
Version: 1.0
Author: Yusuke Ando
Author URI: https://github.com/yandod/
License: GPL2
*/

function librize($atts) {
	$params = shortcode_atts(array(
		'place' => 3,
		'limit' => 10,
	), $atts);
	$ret = wp_remote_get(
		sprintf(
			'http://librize.com/places/%d/place_items.json?limit=%d',
			$params['place'],
			$params['limit']
		)
	);
	$data = json_decode($ret['body']);
	$html = '<ul>';
	foreach ($data as $row) {
		$node = '<li>';
		$node .= '<dl>';
		$node .= '<dt><a href="' . $row->url . '">' . $row->title . '</a></dt>';
		$node .= '<dd><img src="' . $row->image . '"></dd>';
		$node .= '</dl>';
		$node .= '</li>';
		$html .= $node;
	}
	$html .= '</ul>';
	return $html;
}

add_shortcode('librize', 'librize');
add_filter('widget_text', 'do_shortcode');
