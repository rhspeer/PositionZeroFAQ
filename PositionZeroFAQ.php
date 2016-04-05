<?php
/*
Plugin Name: Position Zero FAQ
Plugin URI: http://positionZeroFAQ.com
Description: A Wordpress FAQ plugin with a focus on SEO, customer service, and lead generation
Version: 1.0
Author: Robert Speer
Author URI: http://RobertSpeer.com
License: GPL2
*/
/*
Copyright 2012  Francis Yaconiello  (email : francis@yaconiello.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
|--------------------------------------------------------------------------
| CONSTANTS  http://code.tutsplus.com/articles/plugin-templating-within-wordpress--wp-31088
|--------------------------------------------------------------------------
*/

if ( ! defined( 'PZ_FAQ_BASE_FILE' ) )
    define( 'PZ_FAQ_BASE_FILE', __FILE__ );
if ( ! defined( 'PZ_FAQ_BASE_DIR' ) )
    define( 'PZ_FAQ_BASE_DIR', dirname( PZ_FAQ_BASE_FILE ) );
if ( ! defined( 'PZ_FAQ_PLUGIN_URL' ) )
    define( 'PZ_FAQ_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if(!class_exists('PositionZeroFAQ'))
{
	class PositionZeroFAQ
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$PositionZeroFAQSettings = new PositionZeroFAQSettings();

			// Register custom post types
			require_once(sprintf("%s/post-types/FAQPostType.php", dirname(__FILE__)));
			$FAQPostType = new FAQPostType();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'pluginSettingsLink' ));

            add_filter( 'template_include', array( $this,'pzfaqTemplateChooser'));

            add_action('init', array($this, 'registerMenu'));

            add_action('template_redirect', array($this, 'canonicalArchiveUrl'));


            add_action( 'wp_enqueue_scripts', array($this, 'enqueueBootstrap') );


		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
         *
         * todo: delete data on plugin deactivate
         *
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate

        /**
         *  Enqueue Bootstrap Library & additional assets needed for the bootstrap variant
         *
         *  Limited to just the pzfaq archive page
         *
         *  todo: admin settings to let privileged users choose when & if to load bootstrap
         */
        public static function enqueueBootstrap(){
            if(get_post_type() == 'pzfaq' && is_archive()){
                wp_register_script( 'bootstrap', plugins_url( 'includes/templates/bootstrap/bootstrap-3.3.6/js/bootstrap.min.js', __FILE__ ), array(), '3.3.6', true );
                wp_enqueue_script( 'bootstrap' );

                wp_register_style( 'bootstrap', plugins_url( 'includes/templates/bootstrap/bootstrap-3.3.6/css/bootstrap.min.css', __FILE__ ), array(), '3.3.6', 'all' );
                wp_register_style( 'pzfaq-bootstrap', plugins_url( 'includes/templates/bootstrap/css/style.css', __FILE__ ), array('bootstrap'), '0.1', 'all' );
                wp_enqueue_style( 'pzfaq-bootstrap');
            }
        }


        public function bootstrapPagination( $echo = true ) {
            global $wp_query;

            $big = 999999999; // need an unlikely integer

            $pages = self::pzfaq_paginate_links( array(
                    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                    'format' => '?paged=%#%',
                    'current' => max( 1, get_query_var('paged') ),
                    'total' => $wp_query->max_num_pages,
                    'type'  => 'bootstrap3',
                    'prev_next'   => true,
                    'prev_text'    => __('« Prev'),
                    'next_text'    => __('Next »'),
                    'class_prev_a' => 'page-link',
                    'class_current_a' => 'page-link',
                    'class_a' => 'page-link',
                    'class_a_next' => 'page-link',
                    'class_nav' => 'pzfaq-bootstrap-pagination',
                )
            );

            if ( $echo ) {
                echo $pages;
            } else {
                return $pages;
            }
        }

        /**
         * Well fine then I'll make my own pagination function with hookers and blackjack and the ability to customize
         * css classes and alter the html tags for bootstrap3.
         *
         * Added args:
         *  'class_prev_a' => 'prev page-numbers',
            'class_current_a' => 'page-numbers current',
            'class_a' => 'page-numbers',
            'class_a_dots' => 'page-numbers dots',
            'class_a_next' => 'next page-numbers',
         *
         *  Changed:
         *    current number container from <span> to <a>
         *
         *  Original wp function reference:
         *    https://developer.wordpress.org/reference/functions/paginate_links/
         *
         * @param string $args
         * @return array|string
         */
        public function pzfaq_paginate_links( $args = '' ) {
            global $wp_query, $wp_rewrite;

            // Setting up default values based on the current URL.
            $pagenum_link = html_entity_decode( get_pagenum_link() );
            $url_parts    = explode( '?', $pagenum_link );

            // Get max pages and current page out of the current query, if available.
            $total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
            $current = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

            // Append the format placeholder to the base URL.
            $pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';

            // URL base depends on permalink settings.
            $format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
            $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

            $defaults = array(
                'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
                'format' => $format, // ?page=%#% : %#% is replaced by the page number
                'total' => $total,
                'current' => $current,
                'show_all' => false,
                'prev_next' => true,
                'prev_text' => __('&laquo; Previous'),
                'next_text' => __('Next &raquo;'),
                'end_size' => 1,
                'mid_size' => 2,
                'type' => 'plain',
                'add_args' => array(), // array of query args to add
                'add_fragment' => '',
                'before_page_number' => '',
                'after_page_number' => '',
                'class_prev_a' => 'prev page-numbers',
                'class_current_a' => 'page-numbers current',
                'class_a' => 'page-numbers',
                'class_a_dots' => 'page-numbers dots',
                'class_a_next' => 'next page-numbers',
                'class_nav' => '',
            );

            $args = wp_parse_args( $args, $defaults );

            if ( ! is_array( $args['add_args'] ) ) {
                $args['add_args'] = array();
            }

            // Merge additional query vars found in the original URL into 'add_args' array.
            if ( isset( $url_parts[1] ) ) {
                // Find the format argument.
                $format = explode( '?', str_replace( '%_%', $args['format'], $args['base'] ) );
                $format_query = isset( $format[1] ) ? $format[1] : '';
                wp_parse_str( $format_query, $format_args );

                // Find the query args of the requested URL.
                wp_parse_str( $url_parts[1], $url_query_args );

                // Remove the format argument from the array of query arguments, to avoid overwriting custom format.
                foreach ( $format_args as $format_arg => $format_arg_value ) {
                    unset( $url_query_args[ $format_arg ] );
                }

                $args['add_args'] = array_merge( $args['add_args'], urlencode_deep( $url_query_args ) );
            }

            // Who knows what else people pass in $args
            $total = (int) $args['total'];
            if ( $total < 2 ) {
                return;
            }
            $current  = (int) $args['current'];
            $end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
            if ( $end_size < 1 ) {
                $end_size = 1;
            }
            $mid_size = (int) $args['mid_size'];
            if ( $mid_size < 0 ) {
                $mid_size = 2;
            }
            $add_args = $args['add_args'];
            $r = '';
            $page_links = array();
            $dots = false;

            if ( $args['prev_next'] && $current && 1 < $current ) :
                $link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
                $link = str_replace( '%#%', $current - 1, $link );
                if ( $add_args )
                    $link = add_query_arg( $add_args, $link );
                $link .= $args['add_fragment'];

                /**
                 * Filter the paginated links for the given archive pages.
                 *
                 * @since 3.0.0
                 *
                 * @param string $link The paginated link URL.
                 */
                $page_links[] = '<a class="'.$args['class_prev_a'].'" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['prev_text'] . '</a>';
            endif;
            for ( $n = 1; $n <= $total; $n++ ) :
                if ( $n == $current ) :
                    $page_links[] = "<a class='".$args['class_current_a']."'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</a>";
                    $dots = true;
                else :
                    if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
                        $link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
                        $link = str_replace( '%#%', $n, $link );
                        if ( $add_args )
                            $link = add_query_arg( $add_args, $link );
                        $link .= $args['add_fragment'];

                        /** This filter is documented in wp-includes/general-template.php */
                        $page_links[] = "<a class='".$args['class_a']."' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</a>";
                        $dots = true;
                    elseif ( $dots && ! $args['show_all'] ) :
                        $page_links[] = '<span class="'.$args['class_a_dots'].'>' . __( '&hellip;' ) . '</span>';
                        $dots = false;
                    endif;
                endif;
            endfor;
            if ( $args['prev_next'] && $current && ( $current < $total || -1 == $total ) ) :
                $link = str_replace( '%_%', $args['format'], $args['base'] );
                $link = str_replace( '%#%', $current + 1, $link );
                if ( $add_args )
                    $link = add_query_arg( $add_args, $link );
                $link .= $args['add_fragment'];

                /** This filter is documented in wp-includes/general-template.php */
                $page_links[] = '<a class="'.$args['class_a_next'].'" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['next_text'] . '</a>';
            endif;
            switch ( $args['type'] ) {
                case 'array' :
                    return $page_links;

                case 'list' :
                    $r .= "<ul class='page-numbers'>\n\t<li>";
                    $r .= join("</li>\n\t<li>", $page_links);
                    $r .= "</li>\n</ul>\n";
                    break;
                case 'bootstrap3' :
                    if($args['prev_next'] && $current>1){
                        $current++;
                    }
                    $i=1;
                    $r .= "<nav class=\"".$args['class_nav']."\">\n\t";
                    $r .= "<ul class=\"pagination\">";

                    foreach($page_links as $link){
                        if($i==$current){
                            $r .= "<li class=\"page-item active\">$link</li>";
                        }else{
                            $r .= "<li class=\"page-item\">$link</li>";
                        }
                        $i++;
                    }
                    $r .= "</ul>";
                    $r .= "</nav>\n\t";
                    break;
                default :
                    $r = join("\n", $page_links);
                    break;
            }
            return $r;
        }

        /**
         * redirects from /?post_type=pzfaq to /faq
         * or more generically /?post_type=[post type name] to /[slug]
         *
         * This is to aid with avoiding duplicate content penalties from search engines
         *
         * IF slug is not set in the rewrite settings of create post type WP falls back to the post type
         */
        public static function canonicalArchiveURL(){
           if(is_archive()){
               $post_type = get_post_type();
               if ( $post_type ){
                   if(strpos($_SERVER['REQUEST_URI'], "/?post_type=$post_type")!== false){
                       $post_type_slug = get_post_type_object( $post_type )->rewrite['slug'];
                       wp_redirect( home_url( $post_type_slug, 301 ) );
                       exit();
                   }
                }
            }
        }



		// Add the settings link to the plugins page
		function pluginSettingsLink($links)
		{
			$settings_link = '<a href="options-general.php?page=PositionZeroFAQ">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}

        /**
         * Returns template file from plugin or plugin extension
         *  From: http://code.tutsplus.com/articles/plugin-templating-within-wordpress--wp-31088
         *
         * @since 1.0
         */

        function pzfaqTemplateChooser( $template ) {

            // Post ID
            $post_id = get_the_ID();

            // For all other CPT
            if ( get_post_type( $post_id ) != 'pzfaq' ) {
                return $template;
            }

            // Else use custom template
            if ( is_single() ) {
                return $this->pzfaqGetTemplateHierarchy( 'single' );
            }
            if ( is_archive() ) {
                return $this->pzfaqGetTemplateHierarchy( 'archive' );
            }

        }

        /**
         * Get the custom template if is set
         *
         * @since 1.0
         */

        function pzfaqGetTemplateHierarchy( $template ) {

            // Get the template slug
            $template_slug = rtrim( $template, '.php' );
            $template = $template_slug . '.php';

            // Check if a custom template exists in the theme folder, if not, load the plugin template file
            if ( $theme_file = locate_template( array( 'PositionZeroFAQ_templates/' . $template ) ) ) {
                $file = $theme_file;
            }
            else {
                $file = PZ_FAQ_BASE_DIR . '/includes/templates/' . $template;
            }

            return apply_filters( 'pzrepl_template_' . $template, $file );
        }

        public function registerMenu(){
            register_nav_menu('faq-menu', __('FAQ Menu'));
        }


	} // END class PositionZeroFAQ
} // END if(!class_exists('PositionZeroFAQ'))

if(class_exists('PositionZeroFAQ'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('PositionZeroFAQ', 'activate'));
	register_deactivation_hook(__FILE__, array('PositionZeroFAQ', 'deactivate'));

	// instantiate the plugin class
	$PositionZeroFAQ = new PositionZeroFAQ();

}
