<?php
/**
 *
 * Custom Post Type Downloads
 *
 * @author Ralf Hortt <me@horttcore.de>
 * @since v0.2
 */
class Custom_Post_Type_Downloads
{


    /**
     * Plugin constructor
     *
     * @access public
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     **/
    public function __construct()
    {

        add_action( 'custom-post-type-downloads-before-loop', 'Custom_Post_Type_Downloads::loop_before', 10, 3 );
        add_action( 'custom-post-type-downloads-loop', 'Custom_Post_Type_Downloads::loop', 10, 3 );
        add_action( 'custom-post-type-downloads-after-loop', 'Custom_Post_Type_Downloads::loop_after', 10, 3 );
        add_action( 'init', [$this, 'register_post_type'] );
        add_action( 'init', [$this, 'register_taxonomy'] );
        add_action( 'plugins_loaded', [$this, 'load_plugin_textdomain'] );
        add_action( 'pre_get_posts', [$this, 'pre_get_posts'] );
        add_filter( 'single_template', [$this, 'get_template'], 10, 3 );
        add_filter( 'widgets_init', [$this, 'widgets_init'] );

    } // END __construct


    /**
     * Locate template file
     *
     * @param string $template  Path to the template. See locate_template().
     * @param string $type      Filename without extension.
     * @param array  $templates A list of template candidates, in descending order of priority.
     * @return string Path to the template
     */
    public function get_template( $template, $type, $templates )
    {
        if ( !in_array( 'single-download.php', $templates ) )
            return $template;

        $template = plugin_dir_path( __FILE__ ) . '../templates/single-download.php';

        return $template;
    }


    /**
     * Load plugin textdomain
     *
     * @access public
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     **/
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain( 'custom-post-type-downloads', false, dirname( plugin_basename( __FILE__ ) ) . '/../languages/'  );

    } // END load_plugin_textdomain



    /**
     * Display loop
     *
     * @static
     * @access public
     * @return void
     * @since v0.3
     * @author Ralf Hortt <me@horttcore.de>
     */
    static public function loop()
    {

        ?>
        <li>
            <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
        </li>
        <?php

    } // END loop_after



    /**
     * Display after loop
     *
     * @static
     * @access public
     * @return void
     * @since v0.3
     * @author Ralf Hortt <me@horttcore.de>
     */
    static public function loop_after()
    {

        ?>
        </ul><!-- .list-downloads -->
        <?php

    } // END loop_after



    /**
     * Display before loop
     *
     * @static
     * @access public
     * @return void
     * @since v0.3
     * @author Ralf Hortt <me@horttcore.de>
     */
    static public function loop_before()
    {

        ?>
        <ul class="list-downloads">
        <?php

    } // END loop_before


    /**
     * Custom query
     *
     * @param WP_Query $query Query object
     * @return void
     */
    public function pre_get_posts( $query )
    {
        if ( $query->get( 'orderby') == 'download-count' ) :
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', '_download_counter');
        endif;
    }


    /**
     *
     * POST TYPES
     *
     * @access public
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     */
    public function register_post_type()
    {

        register_post_type( 'download', array(
            'labels' => array(
                'name' => _x( 'Downloads', 'post type general name', 'custom-post-type-downloads' ),
                'singular_name' => _x( 'Download', 'post type singular name', 'custom-post-type-downloads' ),
                'add_new' => _x( 'Add New', 'Download', 'custom-post-type-downloads' ),
                'add_new_item' => __( 'Add New Download', 'custom-post-type-downloads' ),
                'edit_item' => __( 'Edit Download', 'custom-post-type-downloads' ),
                'new_item' => __( 'New Download', 'custom-post-type-downloads' ),
                'view_item' => __( 'View Download', 'custom-post-type-downloads' ),
                'view_items' => __( 'View Downloads', 'custom-post-type-downloads' ),
                'search_items' => __( 'Search Downloads', 'custom-post-type-downloads' ),
                'not_found' =>  __( 'No Downloads found', 'custom-post-type-downloads' ),
                'not_found_in_trash' => __( 'No Downloads found in Trash', 'custom-post-type-downloads' ),
                'parent_item_colon' => __( 'Parent Download:', 'custom-post-type-downloads' ),
                'all_items' => __( 'All Downloads', 'custom-post-type-downloads' ),
                'archives' => __( 'Download Archives', 'custom-post-type-downloads' ),
                'attributes' => __( 'Download Attributes', 'custom-post-type-downloads' ),
                'insert_into_item' => __( 'Insert into download', 'custom-post-type-downloads' ),
                'uploaded_to_this_item' => __( 'Uploaded to this download', 'custom-post-type-downloads' ),
                'featured_image' => __( 'Featured Image', 'custom-post-type-downloads' ),
                'set_featured_image' => __( 'Set featured image', 'custom-post-type-downloads' ),
                'remove_featured_image' => __( 'Remove featured image', 'custom-post-type-downloads' ),
                'use_featured_image' => __( 'Use as featured image', 'custom-post-type-downloads' ),
                'filter_items_list' => __( 'Filter downloads list', 'custom-post-type-downloads' ),
                'items_list_navigation' => __( 'Downloads list navigation', 'custom-post-type-downloads' ),
                'items_list' => __( 'Downloads list', 'custom-post-type-downloads' ),
            ),
            'public' => TRUE,
            'publicly_queryable' => TRUE,
            'show_ui' => TRUE,
            'show_in_menu' => TRUE,
            'query_var' => TRUE,
            'rewrite' => array(
                'slug' => _x( 'download', 'Post Type Slug', 'custom-post-type-downloads' ),
                'with_front' => FALSE,
            ),
            'capability_type' => 'post',
            'has_archive' => TRUE,
            'hierarchical' => FALSE,
            'menu_position' => NULL,
            'menu_icon' => 'dashicons-download',
            'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'show_in_rest' => true,
        ));

    } // END register_post_type


    /**
     *
     * CUSTOM TAXONOMY
     *
     * @access public
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     */
    public function register_taxonomy()
    {

        register_taxonomy( 'download-category', array( 'download' ), array(
            'hierarchical' => TRUE,
            'labels' => array(
                'name' => _x( 'Download Categories', 'taxonomy general name', 'custom-post-type-downloads' ),
                'singular_name' => _x( 'Download Category', 'taxonomy singular name', 'custom-post-type-downloads' ),
                'search_items' =>  __( 'Search Download Categories', 'custom-post-type-downloads' ),
                'popular_items' =>  __( 'Popular Download Categories', 'custom-post-type-downloads' ),
                'all_items' => __( 'All Download Categories', 'custom-post-type-downloads' ),
                'parent_item' => __( 'Parent Download Category', 'custom-post-type-downloads' ),
                'parent_item_colon' => __( 'Parent Download Category:', 'custom-post-type-downloads' ),
                'edit_item' => __( 'Edit Download Category', 'custom-post-type-downloads' ),
                'view_item' => __( 'View Download Category', 'custom-post-type-downloads' ),
                'update_item' => __( 'Update Download Category', 'custom-post-type-downloads' ),
                'add_new_item' => __( 'Add New Download Category', 'custom-post-type-downloads' ),
                'new_item_name' => __( 'New Download Category Name', 'custom-post-type-downloads' ),
                'separate_items_with_commas' => __( 'Separate download categories with commas', 'custom-post-type-downloads' ),
                'add_or_remove_items' => __( 'Add or remove download categories', 'custom-post-type-downloads' ),
                'choose_from_most_used' => __( 'Choose from the most used download categories', 'custom-post-type-downloads' ),
                'not_found' => __( 'No download categories found', 'custom-post-type-downloads' ),
                'no_terms' => __( 'No download categories', 'custom-post-type-downloads' ),
                'items_list_navigation' => __( 'Download Categories list navigation', 'custom-post-type-downloads' ),
                'items_list' => __( 'Download Categories list', 'custom-post-type-downloads' ),
            ),
            'show_ui' => TRUE,
            'query_var' => TRUE,
            'rewrite' => array(
                'slug' => _x( 'download-category', 'Download Category Slug', 'custom-post-type-downloads' ),
            ),
            'show_admin_column' => TRUE,
            'show_in_rest' => true,
        ) );

    } // END register_taxonomy


    /**
     * Register widget
     *
     * @access public
     * @return void
     * @author Ralf Hortt <me@horttcore.de>
     * @since v0.3
     **/
    public function widgets_init()
    {

        register_widget( 'Custom_Post_Type_Downloads_Widget' );

    } // END widgets_init


} // END class Custom_Post_Type_Downloads

new Custom_Post_Type_Downloads;
