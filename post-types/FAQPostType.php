<?php
/**
 * based on http://www.yaconiello.com/blog/how-to-write-wordpress-plugin/
 */

if(!class_exists('FAQPostType'))
{
    /**
     * A PostTypeTemplate class that provides 3 additional meta fields
     */
    class FAQPostType
    {
        const POST_TYPE	= "pzfaq";
        private $_meta = array( // not sure what this does
            'meta_a',
            'meta_b',
            'meta_c'
     );
        /**
         * The Constructor
         */
        public function __construct()
        {
            // register actions
            add_action('init', array(&$this, 'init'));
            add_action('init', array(&$this, 'create_faq_taxonomies'));
            add_action('admin_init', array(&$this, 'admin_init'));
        } // END public function __construct()

        /**
         * hook into WP's init action hook
         */
        public function init()
        {
            // Initialize Post Type
            $this->create_post_type();
            flush_rewrite_rules();
            add_action('save_post', array(&$this, 'save_post'));
        } // END public function init()

        /**
         * Create the post type
         */
        public function create_post_type()
        {
            $labels = array(
                        'name'                  => _x('Questions', 'Post Type General Name', 'text_domain'),
                        'singular_name'         => _x('Question', 'Post Type Singular Name', 'text_domain'),
                        'menu_name'             => __('FAQ', 'text_domain'),
                        'name_admin_bar'        => __('FAQ', 'text_domain'),
                        'archives'              => __('Question Archives', 'text_domain'),
                        'parent_item_colon'     => __('Parent Item:', 'text_domain'),
                        'all_items'             => __('All Questions', 'text_domain'),
                        'add_new_item'          => __('Add New Question', 'text_domain'),
                        'add_new'               => __('Add Question', 'text_domain'),
                        'new_item'              => __('New Question', 'text_domain'),
                        'edit_item'             => __('Edit Question', 'text_domain'),
                        'update_item'           => __('Update Question', 'text_domain'),
                        'view_item'             => __('View Question', 'text_domain'),
                        'search_items'          => __('Search Questions', 'text_domain'),
                        'not_found'             => __('Not found', 'text_domain'),
                        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
                        'featured_image'        => __('Featured Image', 'text_domain'),
                        'set_featured_image'    => __('Set featured image', 'text_domain'),
                        'remove_featured_image' => __('Remove featured image', 'text_domain'),
                        'use_featured_image'    => __('Use as featured image', 'text_domain'),
                        'insert_into_item'      => __('Insert into item', 'text_domain'),
                        'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
                        'items_list'            => __('Items list', 'text_domain'),
                        'items_list_navigation' => __('Items list navigation', 'text_domain'),
                        'filter_items_list'     => __('Filter items list', 'text_domain'),
                  );

                    $rewrite = array(
                        'slug'                  => 'faq',
                        'with_front'            => true,
                        'pages'                 => true,
                        'feeds'                 => true,
                  );

                    $args = array(
                        'label'                 => __('Question', 'text_domain'),
                        'description'           => __('Frequently Asked Questions', 'text_domain'),
                        'labels'                => $labels,
                        'supports'              => array('title', 'editor', 'comments', 'revisions',),
                        'hierarchical'          => false,
                        'public'                => true,
                        'show_ui'               => true,
                        'show_in_menu'          => true,
                        'menu_position'         => 5,
                        'show_in_admin_bar'     => true,
                        'show_in_nav_menus'     => true,
                        'can_export'            => true,
                        'has_archive'           => true,
                        'exclude_from_search'   => false,
                        'publicly_queryable'    => true,
                        'rewrite'               => $rewrite, // URL rewrites to friendly URL's does not appear to be working
                        'query_var'             => false,
                        'capability_type'       => 'post',
                  );

            register_post_type(self::POST_TYPE, $args);
        }


        /**
         *  Registers custom taxonomy for FAQ's called "Question Type"
         */
        public function create_faq_taxonomies(){
            // Add new taxonomy, make it hierarchical (like categories)
            $labels = array(
                'name'              => _x('Question Types', 'taxonomy general name'),
                'singular_name'     => _x('Question Type', 'taxonomy singular name'),
                'search_items'      => __('Search Question Types'),
                'all_items'         => __('All Question Types'),
                'parent_item'       => __('Parent Type'),
                'parent_item_colon' => __('Parent Type:'),
                'edit_item'         => __('Edit Question Type'),
                'update_item'       => __('Update Question Type'),
                'add_new_item'      => __('Add New Question Type'),
                'new_item_name'     => __('New Question Type'),
                'menu_name'         => __('Question Types'),
          );

            $args = array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array('slug' => 'type'),
                'show_in_nav_menus'  => true,
          );

            register_taxonomy('questiontype', array('pzfaq'), $args);
        }

        /**
         * Save the metaboxes for this custom post type
         */
        public function save_post($post_id)
        {
            // verify if this is an auto save routine. 
            // If it is our form has not been submitted, so we dont want to do anything
            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            {
                return;
            }

            if(isset($_POST['post_type']) && $_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
            {error_log('string test', 3, '/tmp/php_error.log');
                foreach($this->_meta as $field_name)
                {
                    // Update the post's meta field
                    update_post_meta($post_id, $field_name, $_POST[$field_name]);
                }
            }
            else
            {
                return;
            } // if($_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
        } // END public function save_post($post_id)

        /**
         * hook into WP's admin_init action hook
         *
         * todo: add extra meta fields as necessary
         */
        public function admin_init()
        {			
            // Add metaboxes
          // add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
        } // END public function admin_init()

        /**
         * hook into WP's add_meta_boxes action hook
         */
        public function add_meta_boxes()
        {
            // Add this metabox to every selected post
            add_meta_box(
                sprintf('PositionZeroFAQ_%s_section', self::POST_TYPE),
                sprintf('%s Information', ucwords(str_replace("-", " ", self::POST_TYPE))),
                array(&$this, 'add_inner_meta_boxes'),
                self::POST_TYPE
          );					
        } // END public function add_meta_boxes()

        /**
         * called off of the add meta box
         */		
        public function add_inner_meta_boxes($post)
        {		
            // Render the job order metabox
            include(sprintf("%s/../templates/%s_metabox.php", dirname(__FILE__), self::POST_TYPE));			
        } // END public function add_inner_meta_boxes($post)

    } // END class FAQPostType
} // END if(!class_exists('FAQPostType'))
