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
