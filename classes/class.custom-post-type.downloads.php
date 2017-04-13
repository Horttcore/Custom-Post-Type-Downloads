<?php
/*
Plugin Name: Custom Post Type Downloads
Plugin URI: https://horttcore.de
Description: Custom Post Type Downloads
Version: 0.2
Author: Ralf Hortt
Author URI: https://horttcorte.de
License: GPL2
*/



/**
 *
 *  Custom Post Type Produts
 *
 */
class Custom_Post_Type_Downloads
{



	/**
	 * Plugin constructor
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function __construct()
	{

		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

	} // END __construct



	/**
	 * Load plugin textdomain
	 *
	 * @access public
	 * @since v1.1.0
	 * @author Ralf Hortt
	 **/
	public function load_plugin_textdomain()
	{

		load_plugin_textdomain( 'custom-post-type-downloads', false, dirname( plugin_basename( __FILE__ ) ) . '/../languages/'  );

	} // END load_plugin_textdomain



	/**
	 *
	 * POST TYPES
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
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
			'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' )
		));

	} // END register_post_type



	/**
	 *
	 * CUSTOM TAXONOMY
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
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
			)
		) );

	} // END register_taxonomy



} // END class Custom_Post_Type_Downloads

new Custom_Post_Type_Downloads;
