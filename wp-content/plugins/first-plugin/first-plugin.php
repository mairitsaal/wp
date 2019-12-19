<?php
/**
 * Plugin Name: First Plugin
 * Plugin URI: http://saalmairit.ikt.khk.ee/first-plugin
 * Description: The very first plugin that I have ever created.
 * Version: 1.0
 * Author: Mairit Saal
 * Author URI: http://saalmairit.ikt.khk.ee
 */
 function dh_modify_read_more_link() {
    return '<a class="more-link" href="' . get_permalink() . '">Click to Read!</a>';
}
add_filter( 'the_content_more_link', 'dh_modify_read_more_link' );
 