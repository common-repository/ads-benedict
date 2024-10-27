<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function sb_getshortcode($link) {
	
  $url=esc_attr(get_option('ab_yourls_url'));
  if (strpos($link,$url)!==FALSE) {
	  return $link;
  }
  if (substr($url,'-1')!='/') {
    $url.='/';
  }
  $key=esc_attr(get_option('ab_yourls_token'));
  $link=urlencode($link);
  $request = new WP_Http;
  $query=$url."yourls-api.php?action=shorturl&signature=".$key."&format=simple&url=$link";
  $result = $request->request( $query );
  return $result['body'];
}

function sb_stats($link) {
	if ($link=='') {
		return;
	}
  $url=esc_attr(get_option('ab_yourls_url'));
  if (substr($url,'-1')!='/') {
    $url.='/';
  }
  $key=esc_attr(get_option('ab_yourls_token'));
  $link=urlencode($link);
  $request = new WP_Http;
  $query=$url."yourls-api.php?action=url-stats&signature=".$key."&format=json&shorturl=$link";
  $result = $request->request( $query );
  return $result['body'];
}

add_filter('manage_adsbenedict_posts_columns', 'adsbenedict_show_yourls');
function adsbenedict_show_yourls($columns) {
    $columns['ab_performance'] = 'Ad Performance';
    return $columns;
}

add_filter('manage_posts_custom_column', 'adsbenedict_show_yourls_data');
function adsbenedict_show_yourls_data($name) {
  global $post;
	switch($name) {
		case 'ab_performance':
		  $url=get_post_meta($post->ID,'ab_yourls_img',true);
		  if ( false === ($stats = get_transient( "ads_benedict_ad_performance_impressions_$post->ID"))) {
		  	$stats=json_decode(sb_stats($url),true);
			set_transient( "ads_benedict_ad_performance_impressions_$post->ID", $stats,HOUR_IN_SECONDS);
		  }
		  if (isset($stats['link']['clicks'])) {
		  	$impressions=$stats['link']['clicks'];
		  }	else {
			  $impressions=0;
		  }
			
			$url=get_post_meta($post->ID,'ab_yourls_link',true);
      	  	
			if ( false === ( $stats = get_transient( "ads_benedict_ad_performance_click_$post->ID" ) ) ) {
				$stats=json_decode(sb_stats($url),true);
				set_transient( "ads_benedict_ad_performance_click_$post->ID" , $stats, HOUR_IN_SECONDS );
			}
			
			if (isset($stats['link']['clicks']) ) {
				$clicks=$stats['link']['clicks'];
			} else {
				$clicks=0;
			}
			
			
			
			if ($impressions > 0 AND $clicks > 0 ) {
				echo "$impressions impressions<BR>$clicks clicks";
				$ratio=round(($clicks/$impressions)*100,2);
				echo "<BR>$ratio % click-through";
			} else { 
				echo "$impressions impressions<BR><i>not enough data gathered yet</i>";
			}
			break;
	}
}