<?php
   /*
   Plugin Name: Very First Plugin
   Plugin URI: http://saalmairit.ikt.khk/wordpress
   description: >-
  a plugin to create awesomeness and spread joy
   Version: 1.2
   Author: M. Saal
   Author URI: http://saalmairit.ikt.khk/wordpress
   License: 1.0
   */
//* Modify the WordPress read more link
add_filter( 'the_content_more_link', 'sp_read_more_link' );
function sp_read_more_link() {
	return '<a class="more-link" href="' . get_permalink() . '">[Jätka lugemist...]</a>';
}
 
?>