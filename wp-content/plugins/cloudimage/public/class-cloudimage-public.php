<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cloudimage.io
 * @since      1.0.0
 *
 * @package    Cloudimage
 * @subpackage Cloudimage/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cloudimage
 * @subpackage Cloudimage/public
 * @author     Cloudimage <hello@cloudimage.io>
 */

use kornrunner\Blurhash\Blurhash;

class Cloudimage_Public
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
     * Cloudimage domain
     *
     * @since    2.0.0
     * @access   private
     * @var      string $cloudimage_domain The domain enter in the admin
     */
    private $cloudimage_domain;

    /**
     * Check if responsive JS implementation is enable or not
     *
     * @since    2.0.0
     * @access   private
     * @var      string $cloudimage_use_responsive_js 0 or 1 regarding is responsive JS is enable
     */
    private $cloudimage_use_responsive_js;

    /**
     * Check if JS lazy loading function is enabled or not
     *
     * @since    2.0.5
     * @access   private
     * @var      string $cloudimage_use_lazy_loading 0 or 1 regarding is lazy loading JS is enabled
     */
    private $cloudimage_use_lazy_loading;

    /**
     * Check for using blur hash function
     *
     * @since    2.0.6
     * @access   private
     * @var      string $cloudimage_use_blurhash 0 or 1 regarding is we have to use blurhash
     */
    private $cloudimage_use_blurhash;

    /**
     * Define all the classes you want to skip - only used in $cloudimage_use_responsive_js
     * TODO: Not fiished to implemetended
     *
     * @since    2.0.0
     * @access   private
     * @var      string $cloudimage_skip_classes string that need to be split with ','
     */
    private $cloudimage_skip_classes;

    /**
     * Enable a low quality src by default in the img tag - only used in $cloudimage_use_responsive_js
     * TODO: Not fiished to implemetended
     *
     * @since    2.0.0
     * @access   private
     * @var      string $cloudimage_add_default_low_quality 0 or 1
     */
    private $cloudimage_add_default_low_quality;

    /**
     * Default placeholder for lazy loading - used with $cloudimage_add_default_low_quality
     * TODO: Not fiished to implemetended
     *
     * @since    2.0.0
     * @access   private
     * @var      string $cloudimage_placeholder_url Can be a url or a base64 image
     */
    private $cloudimage_placeholder_url;


    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @param bool $is_dev Check if environnement is local or not
     *
     * @version  2.0.5
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version, $is_dev)
    {
        $this->is_dev = $is_dev;
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->cloudimage_options = get_option($this->plugin_name);

        $this->cloudimage_domain = $this->cloudimage_options['cloudimage_domain'];
        $this->cloudimage_use_responsive_js = $this->cloudimage_options['cloudimage_use_responsive_js'];
        $this->cloudimage_use_lazy_loading = $this->cloudimage_options['cloudimage_use_lazy_loading'];

        /* TODO: Future improvements with adjustment of skip classes and default low quality option
         * $this->cloudimage_skip_classes = $this->cloudimage_options['cloudimage_skip_classes'];
         * $this->cloudimage_add_default_low_quality = $this->cloudimage_options['cloudimage_add_default_low_quality'];
         * $this->cloudimage_placeholder_url = $this->cloudimage_options['cloudimage_placeholder_url'];
         */

        $this->cloudimage_use_blurhash = $this->cloudimage_options['cloudimage_use_blurhash'];
    }


    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        return null;
    }


    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @version  2.0.5
     * @since    1.0.0
     *
     */
    public function enqueue_scripts()
    {
        $cloudimage_domain = $this->cloudimage_domain;
        $use_responsive_js = $this->cloudimage_use_responsive_js;
        $use_lazy_loading = $this->cloudimage_use_lazy_loading;
        $use_blurhash = $this->cloudimage_use_blurhash;

        if (isset($cloudimage_domain) && $use_responsive_js) {
            //Initialize only JavaScipt repsonsive scripts

            //If we use blurhash - there is different JavaScript file for handling ci-blur-hash, otherwise we use standart JS Cloudimage Responsive
            if ($use_blurhash) {
                wp_enqueue_script('js-cloudimage-responsive', 'https://cdn.scaleflex.it/plugins/js-cloudimage-responsive/3.2.0/blur-hash/js-cloudimage-responsive.min.js', $use_lazy_loading ? ['lazysizes'] : [], 3, true);
            } else {
                wp_enqueue_script('js-cloudimage-responsive', 'https://cdn.scaleflex.it/plugins/js-cloudimage-responsive/3.2.0/js-cloudimage-responsive.min.js', $use_lazy_loading ? ['lazysizes'] : [], 3, true);
            }

            wp_add_inline_script('js-cloudimage-responsive', $this->initializeResponsivePlugin());

            if ($use_lazy_loading) {
                //In addition - initialize lazyloading scripts
                wp_enqueue_script('lazysizes', 'https://cdn.scaleflex.it/filerobot/js-cloudimage-responsive/lazysizes.min.js', [], null, true);
                wp_add_inline_script('lazysizes', $this->initializeLazysizesPlugin(), 'before');
            }

        }
    }


    /**
     * Filters the attachment's url - apply on filter  wp_get_attachment_url
     * (https://core.trac.wordpress.org/browser/tags/4.8/src/wp-includes/post.php#L5077)
     *
     * @param string $url
     * @param int $post_id
     *
     * @return string
     *
     * @since    2.0.0
     */
    public function filter_cloudimage_wp_get_attachment_url($url, $post_id)
    {
        if ($this->is_dev || !$this->cloudimage_domain) {
            return $url;
        }

        $res_url = $this->cloudimage_get_url($post_id, false, $url);

        if (!$res_url) {
            return $url;
        }

        return $res_url;
    }


    /**
     * Filters the image srcset urls and convert them to cloudimage.
     * apply on filter wp_calculate_image_srcset
     * (https://core.trac.wordpress.org/browser/tags/5.2/src/wp-includes/media.php#L1045)
     *
     * @param string $url
     * @param int $post_id
     *
     * @return array
     *
     * @since    2.0.0
     */
    public function filter_cloudimage_wp_calculate_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id)
    {
        if ($this->is_dev || !$this->cloudimage_domain) {

            return $sources;
        }

        if ($this->cloudimage_use_responsive_js) {
            return [];
        }


        foreach ($sources as $img_width => &$source) {
            $img_url = wp_get_attachment_image_src($attachment_id, 'full');
            $source['url'] = $this->cloudimage_build_url($img_url[0], null, ['w' => $img_width]);
        }

        return $sources;
    }


    /**
     * Filters whether to preempt the output of image_downsize().
     * (https://core.trac.wordpress.org/browser/tags/5.2/src/wp-includes/media.php#L182)
     *
     * @param $downsize Whether to short-circuit the image downsize. Default false.
     * @param $id Attachment ID for image.
     * @param $size Size of image. Image size or array of width and height values (in that order).
     *                Default 'medium'.
     *
     * @return array|bool
     */
    public function filter_cloudimage_image_downsize($short_cut, $id, $size)
    {
        if ($short_cut || $this->is_dev || !$this->cloudimage_domain) {
            return false;
        }

        return $this->cloudimage_get_url($id, $size);
    }


    /**
     * Filters whether to modify the whole HTML return.
     * (https://core.trac.wordpress.org/browser/tags/5.2/src/wp-includes/media.php#L182)
     *
     * @param $content the whole HTML of the page
     *
     * @return string
     *
     * @version  2.0.5
     * @since    1.0.0
     */
    public function filter_cloudimage_the_content($content)
    {
        if ($this->cloudimage_use_responsive_js) {
            $placeholder_url = $this->cloudimage_placeholder_url;
            $placeholder_url = apply_filters('cloudimage_filter_default_placeholder', $placeholder_url, 'image');
            if (!strlen($placeholder_url)) {
                $placeholder_url = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
            }

            $match_content = $this->_get_content_haystack($content);

            $matches_img_tags = array();
            preg_match_all('/<img[\s\r\n]+.*?>/is', $match_content, $matches_img_tags);

            $search_img_tags = array();
            $replace_img_tags = array();

            foreach ($matches_img_tags[0] as $imgHTML) {

                // don't do the replacement if the image is a data-uri or already a ci-src
                if (!preg_match("/src=['\"]data:image/is", $imgHTML)
                    && !preg_match("/ci-src=['\"].*['\"]/is", $imgHTML)) {
                    $image_src = '';
                    $image_blurhash = '';

                    if ($this->cloudimage_add_default_low_quality) {
                        $placeholder_url_used = $placeholder_url;

                        // use low res preview image as placeholder if applicable
                        if (preg_match('/class=["\'].*?wp-image-([0-9]*)/is', $imgHTML, $id_matches)) {
                            $tiny_image_size = [40, 40, true];
                            $img_id = intval($id_matches[1]);
                            $tiny_img_data = wp_get_attachment_image_src($img_id, $tiny_image_size);
                            $placeholder_url_used = $tiny_img_data[0];
                        }
                        $image_src = 'src="' . esc_attr($placeholder_url_used) . '"';
                    }

                    //If blurhash is actived, search for attachment ID and get calculated value
                    if ($this->cloudimage_use_blurhash) {
                        if (preg_match('/class=["\'].*?wp-image-([0-9]*)/is', $imgHTML, $id_matches)) {
                            $img_id = intval($id_matches[1]);
                            //Check if id is image
                            if (wp_attachment_is_image($img_id)) {
                                $image_blurhash = wp_get_attachment_metadata($img_id);
                                $image_blurhash = isset($image_blurhash['image_meta']['blurhash']) ? $image_blurhash['image_meta']['blurhash'] : '';
                            }
                        }
                        $image_blurhash = isset($image_blurhash) ? 'ci-blur-hash="' . esc_attr($image_blurhash) . '" ' : '';
                    }

                    // replace the src and add the data-src attribute
                    $replaceHTML = preg_replace('/<img(.*?)src=/is', '<img$1' . $image_src . ' ci-src=', $imgHTML);

                    // also replace the srcset (responsive images)
                    $replaceHTML = str_replace('srcset', 'ci-srcset', $replaceHTML);

                    // replace sizes to avoid w3c errors for missing srcset
                    $replaceHTML = str_replace('sizes', 'ci-sizes', $replaceHTML);

                    //Add blurhash option before the class, we attached to static and generic piece of HTML
                    $replaceHTML = str_replace('class', $image_blurhash . 'class', $replaceHTML);

                    // In case of No JS put back the correct tag
                    $replaceHTML .= '<noscript>' . $imgHTML . '</noscript>';

                    array_push($search_img_tags, $imgHTML);
                    array_push($replace_img_tags, $replaceHTML);
                }
            }

            $content = str_replace($search_img_tags, $replace_img_tags, $content);

            // All background image my not be tackle as some might be in CSS or JS so we need to find some other way to get them - Create proxy Ultrafast to replace this... 
            $matches_bg_img = array();
            preg_match_all('/style="([^"]*)background-image:url\(([^\)]*?)\)([^"]*)"/is', $match_content, $matches_bg_img);

            $search_bg_img = array();
            $replace_bg_img = array();

            foreach ($matches_bg_img[0] as $imgHTML) {

                // don't do the replacement if the image is a data-uri or already a ci-src
                if (!preg_match("/data:image/is", $imgHTML)) {
                    $image_src = '';

                    // replace the src and add the data-src attribute
                    $replaceHTML = preg_replace('/style="([^"]*)background-image:url\([\'"]?([^\)]*?)[\'"]?\)([^"]*)"/is', 'ci-bg-url="$2" style="$1$3"', $imgHTML);

                    array_push($search_bg_img, $imgHTML);
                    array_push($replace_bg_img, $replaceHTML);
                }
            }

            $content = str_replace($search_bg_img, $replace_bg_img, $content);
        }


        return $content;
    }




    /**
     *
     * Public function that can be used in templates / by other developers
     *
     */


    /**
     * Return the Javascript script to init the lazysize
     *
     * @param integer $id - Can be post_id or attachement_id
     * @param string|array $size Worpress size format
     * @param string|bool $url an simple url to transform to cloudimage URL
     *
     * @return string|array {
     * @type string $url - url of content with cloudimage format
     * @type int $width - width of image
     * @type int $height - height of the image
     * @type bool $intermediate - true if image is consider as intermediate
     * }
     * @since    2.0.0
     *
     */
    public function cloudimage_get_url($id, $size, $url = false)
    {

        if ($url) {
            // In this case $id -> $post_id
            if (wp_attachment_is_image($id)) {
                return $this->cloudimage_build_url($url);
            } else {
                return $this->cloudimage_build_url($url, 'proxy');
            }

        }

        // In this case $id -> $attachement_id

        $img_url = wp_get_attachment_url($id);
        $meta = wp_get_attachment_metadata($id);

        $cloudimage_parameters = $this->cloudimage_parse_parameters($size, $meta);

        $img_func = $cloudimage_parameters['func'];
        $img_size = $cloudimage_parameters['size'];
        $img_filters = $cloudimage_parameters['filters'];
        $size_meta = $cloudimage_parameters['size_meta'];


        $img_filters = apply_filters('cloudimage_filter_parameters', $img_filters, $id, $size, $meta);


        $width = isset($size_meta['width']) ? $size_meta['width'] : 0;
        $height = isset($size_meta['height']) ? $size_meta['height'] : 0;

        //Calculate blurhash only if we have thumb, checkbox is switched on and we dont't have already calculated value
        if (isset($meta['sizes']['thumbnail']['file']) && $this->cloudimage_use_blurhash && !isset($meta['image_meta']['blurhash'])) {
            //Get file path including upload dir
            $pathinfo = pathinfo($meta['file']);

            //Get main upload dir
            $wp_upload_dir = wp_upload_dir();

            //Get basedir
            $upload_dir = $wp_upload_dir['basedir'];

            //Clear of the path if organizing by year and month is not turned on
            $dir_name = ($pathinfo['dirname'] === ".") ? '/' : '/' . $pathinfo['dirname'] . '/';

            //Return 0 if the WordPress uploads directory does not exist or attachment is not image
            if (!is_dir($upload_dir) || !wp_attachment_is_image($id)) {
                return 0;
            }

            //Construct full path to file
            $full_file_path = $upload_dir . $dir_name . $meta['sizes']['thumbnail']['file'];

            //Calculate the blurhash for the image from thumbnail
            $blurhash = $this->calculate_blurhash($full_file_path);

            //Only update fields if we have blurhash, different from zero
            if ($blurhash !== 0) {
                //Add in array of meta data new field - blurhash
                $meta['image_meta']['blurhash'] = $blurhash;

                //Update the attachment with the new information in the database
                wp_update_attachment_metadata($id, $meta);
            }
        }

        return [
            $this->cloudimage_build_url($img_url, $img_func, $img_size, $img_filters),
            $width,
            $height,
            true,
        ];
    }


    /**
     * Builds an Cloudimage URL for a dynamically sized image.
     *
     * @param string $img_url
     * @param string $img_func
     * @param array $img_size {
     * @type int|null $w
     * @type int|null $h
     * }
     * @param array $img_filters {
     * @type array|null $filter_name {
     * @type string $filter_value
     *  }
     * }
     *
     * @return string
     */
    public function cloudimage_build_url($img_url, $img_func = false,
                                         $img_size = false, $img_filters = false)
    {
        $domain = $this->cloudimage_domain;
        $url = $img_url;

        //Only make URLs rewriting if we dont't want to use JavaScript responsive plugin. Otherwise the JS should handle all the responsive optimization.
        if ($this->cloudimage_use_responsive_js) {
            return $url;
        }

        if (substr($img_url, 0, strlen('https://' . $domain . '/v7/')) !== 'https://' . $domain . '/v7/') {
            $url = 'https://' . $domain . '/v7/' . $img_url;
        }

        if (strpos($url, '?') === false) {
            $url .= '?';
        }

        if ($img_func) {
            $url .= '&func=' . $img_func;
        }

        if ($img_size) {
            if (isset($img_size['w']) && $img_size['w'] > 0) {
                $url .= '&w=' . $img_size['w'];
            }

            if (isset($img_size['h']) && $img_size['h'] > 0) {
                $url .= '&h=' . $img_size['h'];
            }
        }

        if ($img_filters) {
            foreach ($img_filters as $filter_name => $filter_value) {
                $url .= '&' . $filter_name ($filter_value ? '=' . $filter_value : '');
            }
        }

        $url = str_replace('?&', '?', $url);

        $url = trim($url, '?');


        return $url;
    }




    /**
     *
     * Private function used by previous functions
     *
     */


    /**
     * Parse wordpress size and meta to get all Cloudimage parameters
     *
     * @param string|array $size
     * @param array $meta
     *
     * @return array
     */
    private function cloudimage_parse_parameters($size, $meta)
    {

        if (is_array($size)) {
            $size_meta = [
                "width" => $size[0],
                "height" => $size[1],
                "crop" => isset($size[2]) ? $size[2] : null
            ];
        } else {
            $size_meta = $this->cloudimage_image_sizes($size);
        }

        $filters = [];

        // Update $filters in the function if we need to set gravity
        $func = $this->cloudimage_define_function($size_meta, $meta, $filters);

        // Update $size_meta in the function if we sizes asked are bigger than original
        $size = $this->cloudimage_get_size($size_meta, $meta);

        return [
            'func' => $func,
            'size' => $size,
            'filters' => $filters,
            'size_meta' => $size_meta
        ];
    }


    /**
     * Define Cloudimage function regarding the wordpress size asked
     *
     * @param string|array $size
     * @param array $meta
     *
     * @return array
     */
    private function cloudimage_define_function($size_array, &$filters)
    {
        if ($size_array['crop']) {
            if ($size_array['width'] > 0 && $size_array['height'] > 0) {

                // if crop is array we need to define gravity center
                if (is_array($size_array['crop'])) {
                    $filters = array_merge(
                        $filters,
                        $this->cloudimage_convert_wordpress_crop_array_to_gravity_filters($size_array['crop'])
                    );
                }

                return 'crop';
            }
        }

        if ($size_array['width'] > 0 && $size_array['height'] > 0) {
            return 'bound';
        }

        return null;
    }


    /**
     * Define Cloudimage function regarding the wordpress size asked
     * (https://havecamerawilltravel.com/photographer/wordpress-thumbnail-crop)
     *
     * @param array $crop_array - Should be a crop array from Worpress specification
     *
     * @return array
     */
    private function cloudimage_convert_wordpress_crop_array_to_gravity_filters($crop_array)
    {
        if (count($crop_array) != 2) {
            return [];
        }

        $gravity = 'center';


        if (in_array('left', $crop_array) && in_array('top', $crop_array)) {
            $gravity = 'northwest';
        } elseif (in_array('center', $crop_array) && in_array('top', $crop_array)) {
            $gravity = 'north';
        } elseif (in_array('right', $crop_array) && in_array('top', $crop_array)) {
            $gravity = 'northeast';
        } elseif (in_array('center', $crop_array) && in_array('left', $crop_array)) {
            $gravity = 'west';
        } elseif (in_array('center', $crop_array) && in_array('right', $crop_array)) {
            $gravity = 'east';
        } elseif (in_array('bottom', $crop_array) && in_array('left', $crop_array)) {
            $gravity = 'southwest';
        } elseif (in_array('center', $crop_array) && in_array('bottom', $crop_array)) {
            $gravity = 'south';
        } elseif (in_array('bottom', $crop_array) && in_array('right', $crop_array)) {
            $gravity = 'southeast';
        }

        return ['gravity' => $gravity];
    }


    /**
     * Get Cloudimage function regarding the wordpress size asked
     *
     * @param array $size_array
     * @param array $meta
     *
     * @return array
     */
    private function cloudimage_get_size(&$size_array, $meta)
    {
        //Check if we have not set width and height
        if (!isset($meta['width']) && !isset($meta['height'])) {
            return [
                'w' => 0,
                'h' => 0,
            ];
        }

        // use min not to resize the images to bigger size than original one
        $size_array['width'] = min($size_array['width'], $meta['width']);
        $size_array['height'] = isset($size_array['height']) ? min($size_array['height'], $meta['height']) : 0;

        return [
            'w' => $size_array['width'],
            'h' => $size_array['height'],
        ];
    }


    /**
     * Get all Wordpress declared image Sizes or only one specific size
     *
     * @param string $size - value of one size to return the exact object and not an array
     *
     * @return array
     */
    private function cloudimage_image_sizes($size = null)
    {
        global $_wp_additional_image_sizes;


        $sizes = [];

        // Retrieve all possible image sizes generated by Wordpress
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        foreach ($get_intermediate_image_sizes as $_size) {
            // If the size parameter is a default Worpress size
            if (in_array($_size, ['thumbnail', 'medium', 'medium_large', 'large'])) {
                $array_size_construct = [
                    'width' => get_option($_size . '_size_w'),
                    'height' => get_option($_size . '_size_h'),
                    'crop' => get_option($_size . '_crop'),
                ];
            } else if (isset($_wp_additional_image_sizes[$_size])) {
                $array_size_construct = [
                    'width' => $_wp_additional_image_sizes[$_size]['width'],
                    'height' => $_wp_additional_image_sizes[$_size]['height'],
                    'crop' => $_wp_additional_image_sizes[$_size]['crop'],
                ];
            }

            if ($size != null && $size == $_size) {
                return $array_size_construct;
            }

            $sizes[$_size] = $array_size_construct;
        }

        if ($size != null) {
            return null;
        }

        return $sizes;
    }


    /**
     * Remove elements we don't want to filter from the HTML string
     *
     * We are reducing the haystack by removing the hay we know we don't want to look for needles in
     *
     * @param string $content The HTML string
     * @return string The HTML string without the unwanted elements
     */
    protected function _get_content_haystack($content)
    {
        // Remove <noscript> elements from HTML string
        $content = preg_replace('/<noscript.*?(\/noscript>)/i', '', $content);

        // Remove HTML elements with certain classnames (or IDs) from HTML string
        $skip_classes = $this->_get_skip_classes('html');

        /*
        http://stackoverflow.com/questions/1732348/regex-match-open-tags-except-xhtml-self-contained-tags/1732454#1732454
        We canâ€™t do this, but we still do it.
        */
        $skip_classes_quoted = array_map('preg_quote', $skip_classes);
        $skip_classes_ORed = implode('|', $skip_classes_quoted);

        $regex = '/<\s*\w*\s*class\s*=\s*[\'"](|.*\s)' . $skip_classes_ORed . '(|\s.*)[\'"].*>/isU';

        $content = preg_replace($regex, '', $content);

        return $content;
    }


    /**
     * Get the skip classes
     *
     * @param string $content_type The content type (image/iframe etc)
     * @return array An array of strings with the class names
     */
    protected function _get_skip_classes($content_type)
    {

        $skip_classes = array();

        $skip_classes_str = $this->cloudimage_skip_classes;

        if (strlen(trim($skip_classes_str))) {
            $skip_classes = array_map('trim', explode(',', $skip_classes_str));
        }

        if (!in_array('lazy', $skip_classes)) {
            $skip_classes[] = 'lazy';
        }

        /**
         * Filter the class names to skip
         *
         * @param array $skip_classes The current classes to skip
         * @param string $content_type The current content type
         */
        $skip_classes = apply_filters('cloudimage_filter_skip_classes', $skip_classes, $content_type);

        return $skip_classes;
    }




    /**
     *
     * Function related to enqueue_scripts
     *
     */


    /**
     * Return the Javascript script to init the Responsive plugin
     *
     * @return string
     *
     * @since    2.0.0
     *
     */
    private function initializeResponsivePlugin()
    {
        $add_domain_if_needed = '';
        $lazy_sizes_if_needed = '';

        $exploded_domain = explode('.', $this->cloudimage_domain);

        $token = array_shift($exploded_domain);
        $domain = implode('.', $exploded_domain);

        $use_lazy_loading = $this->cloudimage_use_lazy_loading;
        $lazy_loading = 'false';


        if ($domain) {
            //Add the domain if it is needed
            $add_domain_if_needed = 'domain: "' . $domain . '"';
        }

        if ($use_lazy_loading) {
            //Change lazy loading to 'true' if enabled from admin. Init the lazySizes.
            $lazy_loading = 'true';
            $lazy_sizes_if_needed = 'window.lazySizes.init();';
        }

        return
            'var cloudimgResponsive = new window.CIResponsive({
            token: "' . $token . '", 
            ' . $add_domain_if_needed . ',
            baseUrl: "' . get_site_url() . '",
            lazyLoading: ' . $lazy_loading . ',
            ratio: 1,
        }); 
        ' . $lazy_sizes_if_needed;
    }


    /**
     * Return the Javascript script to init the lazysize
     *
     * @return string
     *
     * @since    2.0.0
     *
     */
    private function initializeLazysizesPlugin()
    {
        return 'window.lazySizesConfig = window.lazySizesConfig || {}; window.lazySizesConfig.init = false;';
    }

    /**
     * @param $file_path string The full path to file
     * @return int|string Return calculation of blurhash or 0 in case of error
     */
    private function calculate_blurhash($file_path)
    {

        //Return 0 if file path is not correct
        if (!is_readable($file_path)) {
            return 0;
        }

        list($width, $height) = getimagesize($file_path);

        //Blurhash script doesn't work for big images for now
        if ($width > 550) {
            return 0;
        }

        //Get image object from file path
        $image = $this->get_image_matrix($file_path);

        //Bits operation of Blurhash algorithm
        $pixels = [];
        for ($y = 0; $y < $height; ++$y) {
            $row = [];

            for ($x = 0; $x < $width; ++$x) {
                $rgb = imagecolorat($image, $x, $y);

                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $row[] = [$r, $g, $b];
            }
            $pixels[] = $row;
        }

        $components_x = 4;
        $components_y = 3;

        //Get the Blurhash from the external class
        $blurhash = Blurhash::encode($pixels, $components_x, $components_y);

        return $blurhash;
    }

    /**
     * Get image from path with PHP builted in methods
     *
     * @param $full_file_path string Full path to file
     * @return bool|false|resource
     */
    private function get_image_matrix($full_file_path)
    {
        //Check with exif_imagetype with byte method for additional validation
        $file_type = exif_imagetype($full_file_path); //Reference to exif_imagetype: https://www.php.net/manual/en/function.exif-imagetype.php

        //Allowed types of images, additionally can add more
        $allowed_img_types = array(
            1,  // [] gif
            2,  // [] jpg
            3,  // [] png
        );

        //Check if it is allowed type
        if (!in_array($file_type, $allowed_img_types)) {
            return false;
        }

        //Choose the right one build in PHP method for create new image
        switch ($file_type) {
            case 1 :
                $image = imagecreatefromgif($full_file_path);
                break;
            case 2 :
                $image = imagecreatefromjpeg($full_file_path);
                break;
            case 3 :
                $image = imagecreatefrompng($full_file_path);
                break;
        }
        return $image;
    }
}
