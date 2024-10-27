<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function adsbenedict_add_ad_url() {
	add_meta_box(
		'adsbenedict_url',
		'URL',
		'adsbenedict_ad_url_callback',
		'adsbenedict'
	);
	add_meta_box(
		'adsbenedict_expiration',
		'Expire',
		'adsbenedict_expiration_callback',
		'adsbenedict'
	);
}
add_action( 'add_meta_boxes' , 'adsbenedict_add_ad_url' );

function adsbenedict_ad_url_callback( $post ) {
	wp_nonce_field( 'adsbenedict_save_ad_url', 'adsbenedict_save_ad_url_nonce' );
	$url = get_post_meta( $post->ID, 'adsbenedict_url', true );
	
	echo '<label for="adsbenedict_url">URL: </label>';
	echo '<input type="text" id="adsbenedict_url" name="adsbenedict_url" value="' . esc_attr( $url ) . '" size="25" />';
}

function adsbenedict_expiration_callback( $post ) {
	wp_nonce_field( 'adsbenedict_expiration_callback', 'adsbenedict_expiration_callback_nonce' );
	$offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
	$expiration = time()+YEAR_IN_SECONDS+$offset;
	if ( !!get_post_meta( $post->ID, 'adsbenedict_expiration') ) {
  	$expiration = get_post_meta( $post->ID, 'adsbenedict_expiration', true );
	} 
	$month=date('n', $expiration );
	$year=date('Y',$expiration );
	$day=date('j',$expiration );
	$hour=date('G',$expiration );
	$minute=date('i',$expiration );
	$second=date('s', $expiration );
	
	echo '<label for="adsbenedict_expiration">Month: </label>';
	echo '<select id="month" name="month">';
	for ($i=1; $i<13; $i++) {
			$numberMonth=$i;
			if (strlen($numberMonth)<2) {
				$numberMonth="0"."$numberMonth";
			}
			echo "<option value=$numberMonth ";
			if ((int)$month === (int)$numberMonth) {
				echo "selected";
			}
			echo ">$numberMonth</option>";
		}
	echo '</select>';
	?>	
		<label><span class="screen-reader-text">Day</span>
		<input type="text" id="day" name="day" value="<?php echo $day; ?>" size="2" maxlength="2" autocomplete="off" /></label>, <label><span class="screen-reader-text">Year</span>
		<input type="text" id="year" name="year" value="<?php echo $year; ?>" size="4" maxlength="4" autocomplete="off" /></label> @ <label><span class="screen-reader-text">Hour</span>
		<input type="text" id="hour" name="hour" value="<?php echo $hour; ?>" size="2" maxlength="2" autocomplete="off" /></label>:<label><span class="screen-reader-text">Minute</span>
		<input type="text" id="minute" name="minute" value="<?php echo $minute; ?>" size="2" maxlength="2" autocomplete="off" /></label>
	<?php

}

function adsbenedict_save_ad_url( $post_id ) {
	if ( ! isset( $_POST['adsbenedict_save_ad_url_nonce'] ) ) {
			return;
		}
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['adsbenedict_save_ad_url_nonce'], 'adsbenedict_save_ad_url' ) ) {
			return;
		}
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		/* OK, it's safe for us to save the data now. */
	
		// Make sure that it is set.
		if ( ! isset( $_POST['adsbenedict_url'] ) ) {
			return;
		}
		// Sanitize user input.
		
		if ($_POST['day'] !='' ) {
			$month=sanitize_text_field( $_POST['month'] );
			$day=sanitize_text_field( $_POST['day'] );
			$year=sanitize_text_field( $_POST['year'] );
			$hour=sanitize_text_field( $_POST['hour'] );
			$minute=sanitize_text_field( $_POST['minute'] );
			$ab_expiration=strtotime("$month/$day/$year $hour:$minute");
			update_post_meta( $post_id, 'adsbenedict_expiration', $ab_expiration);
		}
		
		
		$ab_url = sanitize_text_field( $_POST['adsbenedict_url'] );
	
		// Update the meta field in the database.
		update_post_meta( $post_id, 'adsbenedict_url', $ab_url );
		
	
		
		//change the title
		remove_action( 'save_post', 'adsbenedict_save_ad_url', 1);
		
		if ( get_post_type($post_id)=='adsbenedict'){
			$advertiser=wp_get_post_terms($post_id,'advertisers');
			$zone=wp_get_post_terms($post_id,'zone');
			$advertiser=$advertiser[0]->name;
			$zone=$zone[0]->name;
			$title="$advertiser in $zone";
			wp_update_post( array( 'ID' => $post_id, 'post_title' => $title ) );
		}
		add_action( 'save_post', 'adsbenedict_save_ad_url', 1);
		
}
add_action( 'save_post' , 'adsbenedict_save_ad_url', 1);

function adsbenedict_init() {
	wp_enqueue_script( plugin_dir_path( __FILE__ ) . 'include/adsbenedict.js');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	register_post_type( 'adsbenedict', array(
		'labels'            => array(
			'name'                => __( 'Ads Benedict', 'adsbenedict' ),
			'singular_name'       => __( 'Ad', 'adsbenedict' ),
			'all_items'           => __( 'Ads', 'adsbenedict' ),
			'new_item'            => __( 'New ad', 'adsbenedict' ),
			'add_new'             => __( 'Add New', 'adsbenedict' ),
			'add_new_item'        => __( 'Add New ad', 'adsbenedict' ),
			'edit_item'           => __( 'Edit ad', 'adsbenedict' ),
			'view_item'           => __( 'View ad', 'adsbenedict' ),
			'search_items'        => __( 'Search ads', 'adsbenedict' ),
			'not_found'           => __( 'No ads found', 'adsbenedict' ),
			'not_found_in_trash'  => __( 'No ads found in trash', 'adsbenedict' ),
			'parent_item_colon'   => __( 'Parent adsbenedict', 'adsbenedict' ),
			'menu_name'           => __( 'Ads Benedict', 'adsbenedict' ),
		),
		'public'            => false,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'thumbnail', 'link' , 'zone', 'advertisers'),
		'has_archive'       => false,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_icon'         => 'dashicons-megaphone',
	) );

}
add_action( 'init', 'adsbenedict_init' );

function adsbenedict_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['adsbenedict'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Adsbenedict updated. <a target="_blank" href="%s">View adsbenedict</a>', 'adsbenedict'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'adsbenedict'),
		3 => __('Custom field deleted.', 'adsbenedict'),
		4 => __('Adsbenedict updated.', 'adsbenedict'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Adsbenedict restored to revision from %s', 'adsbenedict'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Adsbenedict published. <a href="%s">View adsbenedict</a>', 'adsbenedict'), esc_url( $permalink ) ),
		7 => __('Adsbenedict saved.', 'adsbenedict'),
		8 => sprintf( __('Adsbenedict submitted. <a target="_blank" href="%s">Preview adsbenedict</a>', 'adsbenedict'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Adsbenedict scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview adsbenedict</a>', 'adsbenedict'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Adsbenedict draft updated. <a target="_blank" href="%s">Preview adsbenedict</a>', 'adsbenedict'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'adsbenedict_updated_messages' );


add_filter('manage_adsbenedict_posts_columns', 'adsbenedict_show_thumb');
function adsbenedict_show_thumb($columns) {
    $columns['ab_thumb'] = 'Thumb';
		$columns['ab_size'] = 'Ad Size';
		$columns['ab_expiration'] = 'Expiration';
    return $columns;
}


add_filter('manage_posts_custom_column', 'adsbenedict_show_thumb_display');
function adsbenedict_show_thumb_display($name) {
	add_image_size( 'ab_admin_thumb', 320, 320, false );
    global $post;
	switch($name) {
		case 'ab_thumb':
			echo '<a href="' . get_edit_post_link() . '">';
		    echo the_post_thumbnail( 'ab_admin_thumb' );
		    echo '</a>';
		    break;
		case 'ab_size':
			$tn_id = get_post_thumbnail_id( $post->ID );
			$img = wp_get_attachment_image_src( $tn_id, 'full' );
			$width = $img[1];
			$height = $img[2];
			echo "$width X $height";
			break;
		case 'ab_expiration':
			if ( !!get_post_meta( $post->ID, 'adsbenedict_expiration') ) {
  			$expiration = get_post_meta( $post->ID, 'adsbenedict_expiration', true );
				if ($expiration<time()+get_option( 'gmt_offset' ) * HOUR_IN_SECONDS) {
					echo "<font color=red><B>";
				}
				echo date('l jS \of F Y h:i A',$expiration);
				if ($expiration<time()+get_option( 'gmt_offset' ) * HOUR_IN_SECONDS) {
					echo "</B></font>";
				}
			} else {
				echo "expiration not set yet";
			}
			
	}
}

add_action('admin_head', 'ab_column_width');
function ab_column_width() {
    echo '<style type="text/css">';
		echo '.column-ab_thumb {width:320px !important;}';
    echo '</style>';
}