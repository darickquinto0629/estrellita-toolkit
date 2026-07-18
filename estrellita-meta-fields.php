<?php
function estrellita_orders( $meta_boxes ) {
	$prefix = 'silibas';
/*
	$meta_boxes[] = array(
		'id' => 'order_info',
		'title' => esc_html__( 'Purchase Order Information', 'silibas' ),
		'post_types' => array('shop_order' ),
		'context' => 'side',
		'priority' => 'high',
		'autosave' => 'false',
		'fields' => array(
			array(
				'id' => $prefix . 'ispo',
				'name' => esc_html__( 'Purchase Order', 'silibas' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Is a purchase order', 'silibas' ),
			),
			array(
				'id' => $prefix . 'ponumber',
				'type' => 'text',
				'name' => esc_html__( 'Purchase Order Number', 'silibas' ),
			),
		),
	);
*/

	// $meta_boxes[] = array(
	// 	'id' => 'backorder',
	// 	'title' => esc_html__( 'Disposition', 'silibas' ),
	// 	'post_types' => array('shop_order' ),
	// 	'context' => 'side',
	// 	'priority' => 'high',
	// 	'autosave' => 'false',
	// 	'fields' => array(
	// 		array(
	// 			'id' => $prefix . 'onback',
	// 			'name' => esc_html__( 'Backordered?', 'silibas' ),
	// 			'type' => 'checkbox',
	// 			'desc' => esc_html__( 'Is this order on backorder from Midnight?', 'silibas' ),
	// 		),
	// 	),
	// );

	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'estrellita_orders' );