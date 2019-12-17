<?php
/**
 * Plugin Name: My First Plugin
 * Plugin URI: http://saalmairit.ikt.khk/wordpress/my-first-plugin
 * Description: The very first plugin that I have ever created.
 * Version: 1.0
 * Author: Your Name
 * Author URI: http://saalmairit.ikt.khk/wordpress
 */
 add_action( 'the_content', 'my_thank_you_text' );

function my_thank_you_text ( $content ) {
    return $content .= '<p>Thank you for reading!</p>';
}
?>