<?php
/*
Plugin Name: Ads Benedict
Version: 0.3.0
Description: Add Ads to Your WordPress
Author: Gary Kovar
Author URI: http://www.binarygary.com
Text Domain: adsbenedict
Domain Path: /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require('post-types/adsbenedict.php');
require('taxonomies/zone.php');
require('taxonomies/advertisers.php');
require('include/admin.php');
if (NULL != get_option('ab_yourls_url') && NULL != get_option('ab_yourls_token')) {
	require('include/yourls.php');
}

function adsbenedict_shortcode($attr) {
	  
	foreach ($attr as $index=>$key) {
		$time=time()+(get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
		//get ads that are assigned to this zone...and do something with them
		if ('zone'==$index) {
			$args = array (
				'tax_query' => array ( 
					array (
						'taxonomy' => $index,
						'field'	=> 'slug',
						'terms' => $key,
						'operator' => 'IN',
					),
				),
				
				
				'post_type'	=> array( 'adsbenedict' ),
				'fields' => 'ids',
				'post_status' => 'publish',
				'posts_per_page' => -1,
			);
			
			if ( false === ( $adids = get_transient( "ads_benedict_ad_ids_$key" ) ) ) {
				$adids=new WP_Query($args);
				set_transient( "ads_benedict_ad_ids_$key" , $adids, DAY_IN_SECONDS );
			}
			
			
			if (count($adids->posts)<1) {
				return;
			} else {
				shuffle($adids->posts);
				
				foreach($adids->posts as $ad) {
					$expiration=get_post_meta($ad,'adsbenedict_expiration',true);
					if ($expiration>time() OR $expiration=='') {
						$displayad=$ad;
						break;
					}
				}
			
				$url=get_post_meta($ad,'adsbenedict_url',true);
				if (NULL != get_option('ab_yourls_url') && NULL != get_option('ab_yourls_token')) {
					if (!get_post_meta($ad,'ab_yourls_link',true)) {
						$shorturl=sb_getshortcode($url);
						add_post_meta($ad,'ab_yourls_link',$shorturl);
					} else {
						$shorturl=get_post_meta($ad,'ab_yourls_link',true);
					}
					if (!get_post_meta($ad,'ab_yourls_img',true)) {
						$shortimg=sb_getshortcode( wp_get_attachment_url( get_post_thumbnail_id( $ad ) ) );
						add_post_meta($ad,'ab_yourls_img',$shortimg);
					} else {
						$shortimg=get_post_meta($ad,'ab_yourls_img',true);
					}
					echo "<a href=$shorturl>";
					echo "<img src=$shortimg style=\"max-width: 100%; display: block; height: auto;\" >";
					echo "</a>";
				} else {
					echo "<a href=$url>";
					echo get_the_post_thumbnail($ad,'full');
					echo "</a>";
				}
				return;
			}
			
			
			
		}
	}
	
}
add_shortcode('adsbenedict','adsbenedict_shortcode');


//AJAX STUFF
/*




*/

add_action( 'wp_enqueue_scripts', 'adsbenedict_enqueue_scripts' );
function adsbenedict_enqueue_scripts() {
	wp_enqueue_script( 'abajax', plugins_url( '/include/adsbenedict.js', __FILE__ ), array('jquery'), '', true );
	wp_localize_script( 'abajax', 'loadadsbenedict', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
}

add_action( 'wp_ajax_nopriv_adsbenedict_load', 'adsbenedict_ajax_load_ad' );
add_action( 'wp_ajax_adsbenedict_load', 'adsbenedict_ajax_load_ad' );
function adsbenedict_ajax_load_ad() {
	
	$time=time()+(get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
		$zone=$_POST['zone'];
		$args = array (
			'tax_query' => array ( 
				array (
					'taxonomy' => 'zone',
					'field'	=> 'slug',
					'terms' => $zone,
					'operator' => 'IN',
				),
			),
			'post_type'	=> array( 'adsbenedict' ),
			'fields' => 'ids',
			'post_status' => 'publish',
		);
		if ( false === ( $adids = get_transient( "ads_benedict_ad_ids_test" ) ) ) {
			$adids=new WP_Query($args);
			set_transient( "ads_benedict_ad_ids_test" , $adids, DAY_IN_SECONDS );
		}
		
			
		if (count($adids->posts)<1) {
			return;
		} else {
			shuffle($adids->posts);
			
			foreach($adids->posts as $ad) {
				$expiration=get_post_meta($ad,'adsbenedict_expiration',true);
				if ($expiration>time() OR $expiration=='') {
					$displayad=$ad;
					break;
				}
			}
			
			
				$url=get_post_meta($ad,'adsbenedict_url',true);
				if (NULL != get_option('ab_yourls_url') && NULL != get_option('ab_yourls_token')) {
					if (!get_post_meta($ad,'ab_yourls_link',true)) {
						$shorturl=sb_getshortcode($url);
						add_post_meta($ad,'ab_yourls_link',$shorturl);
					} else {
						$shorturl=get_post_meta($ad,'ab_yourls_link',true);
					}
					if (!get_post_meta($ad,'ab_yourls_img',true)) {
						$shortimg=sb_getshortcode( wp_get_attachment_url( get_post_thumbnail_id( $ad ) ) );
						add_post_meta($ad,'ab_yourls_img',$shortimg);
					} else {
						$shortimg=get_post_meta($ad,'ab_yourls_img',true);
					}
					$a['shorturl']=$shorturl;
					$a['shortimg']=$shortimg;
				}
			echo json_encode($a);
			}
		}
die();	
}
	
add_shortcode('adsbenedictajax','adsbenedictajax_shortcode');
function adsbenedictajax_shortcode($attr) {
	echo "<a href=\"#\" class=\"adsajaxhref\" id=\"adsajaxhref\" data-zone=\"" . $attr['zone'] . "\">";
	echo "<img src=\"\" class=\"adsajaxsrc\" id=\"adsajaxsrc\" style=\"max-width: 100%; display: block; height: auto;\" >";
	echo "</a>";
}