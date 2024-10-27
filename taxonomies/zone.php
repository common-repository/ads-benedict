<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function zone_init() {
	register_taxonomy( 'zone', array( 'adsbenedict' ), array(
		'hierarchical'      => false,
		'public'            => false,
		'show_in_nav_menus' => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => true,
		'capabilities'      => array(
			'manage_terms'  => 'edit_posts',
			'edit_terms'    => 'edit_posts',
			'delete_terms'  => 'edit_posts',
			'assign_terms'  => 'edit_posts'
		),
		'labels'            => array(
			'name'                       => __( 'Zones', 'adsbenedict' ),
			'singular_name'              => _x( 'Zone', 'taxonomy general name', 'adsbenedict' ),
			'search_items'               => __( 'Search zones', 'adsbenedict' ),
			'popular_items'              => __( 'Popular zones', 'adsbenedict' ),
			'all_items'                  => __( 'All zones', 'adsbenedict' ),
			'parent_item'                => __( 'Parent zone', 'adsbenedict' ),
			'parent_item_colon'          => __( 'Parent zone:', 'adsbenedict' ),
			'edit_item'                  => __( 'Edit zone', 'adsbenedict' ),
			'update_item'                => __( 'Update zone', 'adsbenedict' ),
			'add_new_item'               => __( 'New zone', 'adsbenedict' ),
			'new_item_name'              => __( 'New zone', 'adsbenedict' ),
			'separate_items_with_commas' => __( 'Zones separated by comma', 'adsbenedict' ),
			'add_or_remove_items'        => __( 'Add or remove zones', 'adsbenedict' ),
			'choose_from_most_used'      => __( 'Choose from the most used zones', 'adsbenedict' ),
			'menu_name'                  => __( 'Zones', 'adsbenedict' ),
		),
	) );

}
add_action( 'init', 'zone_init' );
