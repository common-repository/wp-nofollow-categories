<?php

/*
Plugin Name: WP Nofollow Categories
Version: 0.1.3
Description: This SEO plugin automatically nofollows category links and author links across the site. Also adds my_nofollow() function which you can use to nofollow links in any text.
Author: Vladimir Prelovac
Author URI: http://www.prelovac.com/vladimir/
Plugin URI: http://www.prelovac.com/vladimir/wordpress-plugins/wp-nofollow-categories
*/

/*  
Copyright 2008  Vladimir Prelovac  (email : vprelovac@gmail.com)

Released under GPL License.
*/

add_filter( 'wp_list_categories', 'my_nofollow' );
add_filter( 'the_category', 'my_nofollow_cat' );

function my_nofollow( $text ) {
	
	$text = stripslashes($text);
	$text = preg_replace_callback('|<a (.+?)>|i', 'wp_rel_nofollow_callback', $text);
	return $text;
}

function my_nofollow_cat( $text ) {
	
	$text = str_replace('rel="category tag"', "", $text);
	$text = my_nofollow($text);
	return $text;
}

function my_noindex_cat()
{
	if (is_category() && !is_feed())
	{
			echo "\n<!-- wp-nofollow-categories -->\n";
			echo '<meta name="robots" content="noindex" />';
			echo "\n<!-- /wp-nofollow-categories -->\n";
	}
}

add_action('wp_head', 'my_noindex_cat');

function nofollow_the_author_posts_link($deprecated = '') {
	global $authordata;
	printf(
		'<a rel="nofollow" href="%1$s" title="%2$s">%3$s</a>',
		get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
		sprintf( __( 'Posts by %s' ), attribute_escape( get_the_author() ) ),
		get_the_author()
	);
}


?>
