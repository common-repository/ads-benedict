<?php

function advertisers_init() {
	register_taxonomy( 'advertisers', array( 'adsbenedict' ), array(
		'hierarchical'      => false,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_ui'           => true,
		'show_admin_column' => false,
		'query_var'         => true,
		'rewrite'           => true,
		'capabilities'      => array(
			'manage_terms'  => 'edit_posts',
			'edit_terms'    => 'edit_posts',
			'delete_terms'  => 'edit_posts',
			'assign_terms'  => 'edit_posts'
		),
		'labels'            => array(
			'name'                       => __( 'Advertisers', 'adsbenedict' ),
			'singular_name'              => _x( 'Advertisers', 'taxonomy general name', 'adsbenedict' ),
			'search_items'               => __( 'Search advertisers', 'adsbenedict' ),
			'popular_items'              => __( 'Popular advertisers', 'adsbenedict' ),
			'all_items'                  => __( 'All advertisers', 'adsbenedict' ),
			'parent_item'                => __( 'Parent advertisers', 'adsbenedict' ),
			'parent_item_colon'          => __( 'Parent advertisers:', 'adsbenedict' ),
			'edit_item'                  => __( 'Edit advertisers', 'adsbenedict' ),
			'update_item'                => __( 'Update advertisers', 'adsbenedict' ),
			'add_new_item'               => __( 'New advertisers', 'adsbenedict' ),
			'new_item_name'              => __( 'New advertisers', 'adsbenedict' ),
			'separate_items_with_commas' => __( 'Advertisers separated by comma', 'adsbenedict' ),
			'add_or_remove_items'        => __( 'Add or remove advertisers', 'adsbenedict' ),
			'choose_from_most_used'      => __( 'Choose from the most used advertisers', 'adsbenedict' ),
			'menu_name'                  => __( 'Advertisers', 'adsbenedict' ),
		),
	) );

}
add_action( 'init', 'advertisers_init' );
