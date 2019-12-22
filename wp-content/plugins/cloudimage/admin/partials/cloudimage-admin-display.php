<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cloudimage.io
 * @since      1.0.0
 *
 * @package    Cloudimage
 * @subpackage Cloudimage/admin/partials
 */
?>

<?php
// Grab all options
$options = get_option($this->plugin_name);

$domain = $options['cloudimage_domain'];
$use_responsive_js = $options['cloudimage_use_responsive_js'];
$use_lazy_loading = $options['cloudimage_use_lazy_loading'];
$use_blurhash = $options['cloudimage_use_blurhash'];

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="cloudimg-plugin-container">
    <div class="cloudimg-lower">
        <div class="cloudimg-box">
            <div class="content-container">
                <div class="top_part">
                    <div class="small-cloud-image">
                        <img src=" <?php echo plugin_dir_url(__FILE__); ?>../images/small_cloud.png" width="50"
                             alt="small cloud">
                    </div>
                    <div class="cloud-image">
                        <img src=" <?php echo plugin_dir_url(__FILE__); ?>../images/big_cloud.png" alt="big cloud">
                    </div>
                    <div class="a_logo">
                        <a target="_blank" href="http://cloudimg.io/">
                            <img src=" <?php echo plugin_dir_url(__FILE__); ?>../images/logo_cloudimage.png"
                                 alt="cloudimage logo">
                        </a>
                    </div>
                    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
                </div>


                <div class="intro_text">
                    <p class="big_p">
                        <?php esc_attr_e('Cloudimage will resize, compress and optimise your Wordpress images before delivering responsive images lightning fast over Content Delivery Networks all around the World. Simply add your Cloudimage token below and the plugin will do the magic automatically.', 'cloudimage') ?>
                    </p>
                    <?php if (!$domain) { ?>
                        <p class="big_p">
                            <?php esc_attr_e('To start using Cloudimage you will need to sign up for a Cloudimage account and obtain a Cloudimage token. Sign up is free and takes only few seconds. ', 'cloudimage'); ?></p>
                        <p class="big_p">
                            <a href="https://www.cloudimage.io/en/register_page"
                               target="_blank"><?php esc_attr_e('Get your Cloudimage token', 'cloudimage'); ?></a>
                        </p>
                        <p class="big_p">
                            <?php _e('After signing up, please enter your Cloudimage token below:', 'cloudimage'); ?>
                        </p>
                    <?php } else { ?>
                        <p class="big_p">
                            <?php esc_attr_e('Thank you for connecting your Cloudimage account, you have successfully set up Cloudimage. If you need any help or have any concerns please drop us a message at ', 'cloudimage'); ?>
                            <a href="mailto:hello@cloudimage.io"
                               target="_blank"> <?php esc_attr_e('hello@cloudimage.io', 'cloudimage'); ?></a>.
                        </p>
                    <?php } ?>
                </div>
            </div>
        </div>


        <form method="post" name="cloudimg_settings" action="options.php" class="cloudimg-boxes">
            <?php
            settings_fields($this->plugin_name);
            do_settings_sections($this->plugin_name);
            ?>
            <div class="cloudimg-box">
                <div class="content-container">
                    <h1><?php esc_attr_e('Configuration', 'cloudimage'); ?></h1>
                    <table class="form-table">
                        <tbody>
                        <!-- domain -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="<?php echo $this->plugin_name; ?>-domain" class="cloudimage-domain">
                                    <?php esc_attr_e('Cloudimage token or custom domain: ', 'cloudimage'); ?>
                                    <div class="tooltip">?
                                        <span class="tooltiptext"><?php esc_attr_e('Cloudimage token from your Cloudimage account', 'cloudimage') ?></span>
                                    </div>
                                </label>
                            </th>
                            <td class="forminp forminp-text">
                                <input type="text" id="<?php echo $this->plugin_name; ?>-domain" placeholder="efuevfz"
                                       name="<?php echo $this->plugin_name; ?>[domain]"
                                       class="widefat"
                                       value="<?php if (!empty($domain)) echo $domain; ?>">
                                <div class="cloudimage__description">
                                    <?php esc_attr_e('Enter token: ', 'cloudimage') ?>
                                    <code><?php esc_attr_e('for example azbxuwxXXX or img.acme.com', 'cloudimage') ?></code>
                                </div>
                            </td>
                        </tr>

                        <tr valign="top">
                            <td colspan="2">
                            <span class="cloudimage-demo">
                                <?php esc_attr_e('By default, the plugin will resize all images and deliver them over the Cloudimage CDN. Your Theme\'s Wordpress native support for ', 'cloudimage') ?><i>srcset</i><?php esc_attr_e(' will continue to be used for delivering responsive images.', 'cloudimage') ?>
                                <br><br>
                                <?php esc_attr_e('Cloudimage offers a powerful alternative for enabling responsive images using the ', 'cloudimage') ?>
                                <a href="https://scaleflex.github.io/js-cloudimage-responsive/" target="_blank">Cloudimage Responsive Images JS plugin</a>
                                <?php esc_attr_e(' below:', 'cloudimage') ?>

                            </span>
                            </td>
                        </tr>

                        <!-- Use responsive JS plugin -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="<?php echo $this->plugin_name; ?>-use_responsive_js">
                                    <?php esc_attr_e('Enable responsive images: ', 'cloudimage'); ?>
                                    <div class="tooltip">?
                                        <span class="tooltiptext"><?php esc_attr_e('Automatically resize your image using the Cloudimage Responsive Images JS plugin to fit your viewer\'s screen size. The image processing will be done via the JS plugin on the frontend, there will be no visibile change in your WordPress media gallery (* recommened).', 'cloudimage') ?></span>
                                    </div>
                                </label>
                            </th>

                            <td class="forminp forminp-text">
                                <input type="checkbox" id="<?php echo $this->plugin_name; ?>-use_responsive_js"
                                       name="<?php echo $this->plugin_name; ?>[use_responsive_js]" <?php checked($use_responsive_js, 1); ?> >
                            </td>
                        </tr>

                        <!-- Use lazy loading from JS plugin -->
                        <tr valign="top" id="lazy-loading-section" style="display: none">
                            <th scope="row" class="titledesc">
                                <label for="<?php echo $this->plugin_name; ?>-use_lazy_loading">
                                    <?php esc_attr_e('Enable lazy loading: ', 'cloudimage'); ?>
                                    <div class="tooltip">?
                                        <span class="tooltiptext"><?php esc_attr_e('Automatically add lazy loading to your images, if JavaScript plugin is enabled. You can disable this option if you have implemented lazy loading with another plugin / method.', 'cloudimage') ?></span>
                                    </div>
                                </label>
                            </th>

                            <td class="forminp forminp-text">
                                <input type="checkbox" id="<?php echo $this->plugin_name; ?>-use_lazy_loading"
                                       name="<?php echo $this->plugin_name; ?>[use_lazy_loading]" <?php checked($use_lazy_loading, 1); ?> >
                            </td>
                        </tr>

                        <!-- Use blur hash -->
                        <tr valign="top" id="blurhash-section" style="display: none;">
                            <th scope="row" class="titledesc">
                                <label for="<?php echo $this->plugin_name; ?>-use_blurhash">
                                    <?php esc_attr_e('Enable BlurHash: ', 'cloudimage'); ?>
                                    <div class="tooltip">?
                                        <span class="tooltiptext"><?php esc_attr_e('The BlurHash algorithm allows beautiful user experience for progressive loading (* recommended)', 'cloudimage') ?></span>
                                    </div>
                                    <a href="https://cdn.scaleflex.it/plugins/js-cloudimage-responsive/demo/blur-hash/index.html"
                                       class="cloudimage-link" target="_blank" title="BlurHash demo">demo</a> |
                                    <a href="https://github.com/woltapp/blurhash" class="cloudimage-link"
                                       target="_blank" title="Learn more about BlurHash">learn more</a>
                                </label>

                            </th>

                            <td class="forminp forminp-text">
                                <input type="checkbox" id="<?php echo $this->plugin_name; ?>-use_blurhash"
                                       name="<?php echo $this->plugin_name; ?>[use_blurhash]" <?php checked($use_blurhash, 1); ?> >
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    <div class="warning-wrapper">
                        <p><?php _e('We recommend checking all pages, after turning on responsive images, especially on JavaScript-heavy themes.', 'cloudimage'); ?></p>
                    </div>

                    <?php submit_button(__('Save all changes', 'cloudimage'), ['primary', 'large'], 'submit', true); ?>
                </div>
            </div>


            <div class="cloudimg-box">
                <h4>
                    <?php _e('Notes about compatibility: The current version of the plugin optimises all images included in the wp_posts table. It will not optimise images in the header (logo), footer and any addtional custom content sections added by your theme. These images will be optimised in a future version of the plugin. ', 'cloudimage'); ?>
                </h4>
            </div>

            <br>

            <div class="cloudimg-box">
                <h4>
                    <?php _e('Advanced options : ', 'cloudimage'); ?>
                    <a href="https://www.cloudimage.io/en/login_page" class="cloudimage-link" target="_blank">
                        <?php _e('Cloudimage Admin ', 'cloudimage'); ?>
                    </a>
                </h4>
            </div>
        </form>
    </div>
</div>

<script>
    jQuery(document).ready(function () {
        //Variables initialization
        var cloudimage_use_responsive_js = jQuery('#cloudimage-use_responsive_js');
        var lazy_loading_section = jQuery('#lazy-loading-section');
        var cloudimage_use_lazy_loading = jQuery('#cloudimage-use_lazy_loading');
        var blurhash_section = jQuery('#blurhash-section');
        var cloudimage_blurhash_checkbox = jQuery('#cloudimage-use_blurhash');

        //Check if JavaScript is enabled to display lazy loading section
        if (cloudimage_use_responsive_js.is(':checked')) {
            lazy_loading_section.css("display", "table-row");
            blurhash_section.css("display", "table-row");
        } else {
            lazy_loading_section.css("display", "none");
            blurhash_section.css("display", "none");
        }

        //Attach event to change of Cloudimage use resposnive JS checkbox
        cloudimage_use_responsive_js.change(function () {
            if (this.checked) {
                //If checked - show additional table row with checkbox
                lazy_loading_section.css("display", "table-row");
                blurhash_section.css("display", "table-row");
            } else {
                //If turned off - hide the additional table row and unmark the checkbox
                lazy_loading_section.css("display", "none");
                blurhash_section.css("display", "none");

                cloudimage_use_lazy_loading.prop("checked", false);
                cloudimage_blurhash_checkbox.prop("checked", false);
            }
        });
    });
</script>
