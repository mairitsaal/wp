<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cloudimage.io
 * @since      1.0.0
 *
 * @package    Cloudimage
 * @subpackage Cloudimage/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cloudimage
 * @subpackage Cloudimage/admin
 * @author     Cloudimage <hello@cloudimage.io>
 */
class Cloudimage_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Is Dev env.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $is_dev
     */
    private $is_dev;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @param bool $is_dev Check if environnement is local or not
     *
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version, $is_dev = false)
    {

        $this->is_dev = $is_dev;
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->cloudimage_options = get_option($this->plugin_name);

    }


    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cloudimage-admin.css', array(), $this->version, 'all');

    }


    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        return null;
    }


    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu()
    {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        add_menu_page(
            'Welcome to the Cloudimage WordPress Plugin',
            'Cloudimage',
            'manage_options',
            $this->plugin_name, array($this, 'display_plugin_setup_page'),
            plugin_dir_url(__FILE__) . '../admin/images/cloudimage_icon.png'
        );
    }


    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links)
    {
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', 'cloudimage') . '</a>',
        );

        return array_merge($settings_link, $links);
    }


    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page()
    {
        include_once('partials/cloudimage-admin-display.php');
    }


    /**
     * Validate data from admin
     *
     * @version  2.0.5
     * @since    1.0.0
     */
    public function validate($input)
    {

        // All checkboxes inputs
        $valid = array();

        //Cleanup
        if (!empty($input['domain']) && strpos($input['domain'], '.') === false) {
            $valid['domain'] = $valid['cloudimage_domain'] = $input['domain'] . '.cloudimg.io';
        } else {
            $valid['domain'] = $valid['cloudimage_domain'] = $input['domain'];
        }

        $valid['cloudimage_use_responsive_js'] = $input['use_responsive_js'] ? 1 : 0;
        $valid['cloudimage_use_lazy_loading'] = $input['use_lazy_loading'] ? 1 : 0;
        $valid['cloudimage_use_blurhash'] = $input['use_blurhash'] ? 1 : 0;

        //Additional validators for future improvements functions
        $valid['cloudimage_skip_classes'] = $input['skip_classes'] ? $input['skip_classes'] : 0;
        $valid['cloudimage_add_default_low_quality'] = $input['add_default_low_quality'] ? 1 : 0;
        $valid['cloudimage_placeholder_url'] = $input['placeholder_url'] ? $input['placeholder_url'] : 0;

        return $valid;
    }


    /**
     * Register option once they are updated
     *
     * @since    1.0.0
     */
    public function options_update()
    {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    }


    /**
     * Add notice if domain is not set
     *
     * @since    1.0.0
     */
    public function cloudimage_admin_notice_no_domain()
    {
        $class = 'notice notice-warning';
        $message = __('Cloudimage is almost ready. To get started, please fill your cloudimage domain : ', 'cloudimage');

        if (!$this->cloudimage_options['cloudimage_domain']) {
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message) . '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">here</a>');
        }
    }


    /**
     * Add notice if we are on Localhost
     *
     * @since    1.0.0
     */
    public function cloudimage_admin_notice_localhost()
    {
        $class = 'notice notice-warning';
        $message = __('Cloudimage has been disable because your are running on LocalHost. Cloudimage needs accessible URL to work', 'cloudimage');

        if ($this->is_dev) {
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        }
    }


}
