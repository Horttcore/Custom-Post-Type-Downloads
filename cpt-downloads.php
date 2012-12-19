<?php
/*
Plugin Name: Custom Post Type Downloads
Plugin URI: http://horttcore.de.de
Description: Custom Post Type Downloads
Version: 0.1
Author: Ralf Hortt
Author URI: http://horttcorte.de
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
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'wp_ajax_get-file-info', array( $this, 'file_info' ) );
		add_action( 'wp_ajax_reset-download-counter', array( $this, 'reset_download_count' ) );
		add_shortcode( 'DOWNLOADS', array( $this, 'shortcode_downloads' ) );

		load_plugin_textdomain( 'cpt-downloads', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'  );
	}



	/**
	 * Add meta boxes
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function add_meta_boxes()
	{
		add_meta_box( 'file-download-counter', __( 'Download Counter', 'cpt-downloads'  ), array( $this, 'metabox_counter' ), 'download' );
		add_meta_box( 'file-download-metabox', __( 'File', 'cpt-downloads'  ), array( $this, 'metabox_file' ), 'download' );
	}



	/**
	 * Register scripts
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function admin_enqueue_scripts()
	{
		wp_register_script( 'cpt-downloads-admin', plugins_url( dirname( plugin_basename( __FILE__ ) ) . '/javascript/cpt-downloads-admin.js' ), array( 'media-upload', 'thickbox' ), FALSE, TRUE );
		$translation_array = array( 'selectAsDownload' => __( 'Select as Download', 'cpt-downloads' ), 'selectFile' => __( 'Select a File', 'cpt-downloads' ), 'resetCounter' => __( 'Reset Download Counter?', 'cpt-downloads' ) );
		wp_localize_script( 'cpt-downloads-admin', 'CustomPostTypeDownloads', $translation_array );
	}



	/**
	 * Get file size for attachment
	 *
	 * @return void
	 * @author Ralf Hortt
	 **/
	protected function get_file_size( $attachment_id )
	{
		$bytes = filesize( get_attached_file( $attachment_id ) );

		if ( !$bytes )
			return;

		$s = array('B', 'KB', 'MB', 'GB');
		$e = floor(log($bytes)/log(1024));
		return sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
	}



	/**
	 * Get file type
	 *
	 * @return void
	 * @author Ralf Hortt
	 **/
	function get_file_type( $post_id )
	{
		$type = get_post_mime_type( $post_id );

		if ( !$type )
			return false;

		$type = explode( '/', $type );
		return $type[1];
	}



	/**
	 * Display file info
	 *
	 * @access protected
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function file_info( $file_id = FALSE )
	{
		$file_id = ( $_POST['file_id'] ) ? $_POST['file_id'] : $file_id;

		if ( !$file_id )
			return;

		$file = get_post( $file_id );
		$meta = wp_get_attachment_metadata( $file_id );
		$meta['filesize'] = $this->get_file_size( $file_id );
		$class = ( wp_attachment_is_image( $file_id ) ) ? 'image' : 'file';

		echo '<div class="type-' . $class . '">' . get_attachment_icon( $file_id ) . ' <span class="file-title">' . $file->post_title . '</span></div>';

		if ( $_POST['file_id'])
			die('');
	}



	/**
	 * Force file download
	 *
	 * @return void
	 * @author Ralf Hortt
	 **/
	function force_download()
	{
		$file_id = get_post_meta( get_the_ID(), '_file', TRUE );
		$file = get_post( $file_id );

		$download_counter = get_post_meta( get_the_ID(), '_download_counter', TRUE );
		$download_counter++;
		update_post_meta( get_the_ID(), '_download_counter', $download_counter);

		$type = get_post_mime_type( $file );
		$filename = end( explode( '/',$file->guid ) );

		header( 'Content-type: ' . $type );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Pragma: no-cache');
		header( 'Expires: 0');
	}


	/**
	 * Counter Metabox
	 *
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function metabox_counter( $post )
	{
		$counter = (int) get_post_meta( $post->ID, '_download_counter', TRUE );

		?>
			<p class="clearfix">
				<span class="download-count">
					<?php
					if ( !$counter || 0 == $counter) :
						_e( 'No Downloads yet', 'cpt-downloads' );
					elseif ( 1 == $counter ) :
						printf( __( '<strong>%s</strong> Download counted', 'cpt-downloads' ), $counter );
					else :
						printf( __( '<strong>%s</strong> Downloads counted', 'cpt-downloads' ), $counter );
					endif;
					?>
				</span>
				<?php if ( 0 < $counter) : ?>
					<a href="#" class="right button reset-counter"><?php _e( 'Reset counter', 'cpt-downloads' ) ?></a>
				<?php endif; ?>
			</p>
		<?php
	}



	/**
	 * File metabox
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function metabox_file( $post )
	{
		$file_id = get_post_meta( $post->ID, '_file', TRUE );
		$style_add = ( !$file_id ) ? '' : 'style="display:none"';
		$style_remove = ( $file_id ) ? '' : 'style="display:none"';

		wp_enqueue_script( 'cpt-downloads-admin' );
		wp_enqueue_style( 'thickbox' );
		?>
		<div class="file-info">
			<?php $this->file_info( $file_id ) ?>
		</div>
		<p>

			<input type="hidden" name="file-download-id" id="file-download-id" value="<?php echo $file_id ?>">
			<a href="#" id="file-download" name="file-download" class="button" <?php echo $style_add ?>><?php _e( 'Select a file', 'cpt-downloads' ); ?></a>
			<a href="#" id="remove-file-download" name="remove-file-download" class="submitdelete deletion" <?php echo $style_remove ?>><?php _e( 'Remove file', 'cpt-downloads' ); ?></a>
		</p>
		<?php
		wp_nonce_field( 'save-download-file', 'download-file-nonce' );

	}


	/**
	 * Update messages
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function post_updated_messages( $messages ) {
		global $post, $post_ID;

		$messages['download'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Download updated. <a href="%s">View Download</a>', 'cpt-downloads'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.', 'cpt-downloads'),
			3 => __('Custom field deleted.', 'cpt-downloads'),
			4 => __('Download updated.', 'cpt-downloads'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Download restored to revision from %s', 'cpt-downloads'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Download published. <a href="%s">View Download</a>', 'cpt-downloads'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Download saved.', 'cpt-downloads'),
			8 => sprintf( __('Download submitted. <a target="_blank" href="%s">Preview Download</a>', 'cpt-downloads'), esc_url( add_query_arg( 'preview', 'TRUE', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('Download scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Download</a>', 'cpt-downloads'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Download draft updated. <a target="_blank" href="%s">Preview Download</a>', 'cpt-downloads'), esc_url( add_query_arg( 'preview', 'TRUE', get_permalink($post_ID) ) ) ),
		);

		return $messages;
	}



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
		$labels = array(
			'name' => _x( 'Downloads', 'post type general name', 'cpt-downloads' ),
			'singular_name' => _x( 'Download', 'post type singular name', 'cpt-downloads' ),
			'add_new' => _x( 'Add New', 'Download', 'cpt-downloads' ),
			'add_new_item' => __( 'Add New Download', 'cpt-downloads' ),
			'edit_item' => __( 'Edit Download', 'cpt-downloads' ),
			'new_item' => __( 'New Download', 'cpt-downloads' ),
			'view_item' => __( 'View Download', 'cpt-downloads' ),
			'search_items' => __( 'Search Download', 'cpt-downloads' ),
			'not_found' =>  __( 'No Downloads found', 'cpt-downloads' ),
			'not_found_in_trash' => __( 'No Downloads found in Trash', 'cpt-downloads' ),
			'parent_item_colon' => '',
			'menu_name' => __( 'Downloads', 'cpt-downloads' )
		);

		$args = array(
			'labels' => $labels,
			'public' => TRUE,
			'publicly_queryable' => TRUE,
			'show_ui' => TRUE,
			'show_in_menu' => TRUE,
			'query_var' => TRUE,
			'rewrite' => array( 'slug' => _x( 'download', 'Post Type Slug', 'cpt-downloads' ) ),
			'capability_type' => 'post',
			'has_archive' => TRUE,
			'hierarchical' => FALSE,
			'menu_position' => NULL,
			'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
		);

		register_post_type( 'download', $args);
	}



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
		$labels = array(
			'name' => _x( 'Categories', 'taxonomy general name', 'cpt-downloads' ),
			'singular_name' => _x( 'Category', 'taxonomy singular name', 'cpt-downloads' ),
			'search_items' =>  __( 'Search Categories', 'cpt-downloads' ),
			'all_items' => __( 'All Categories', 'cpt-downloads' ),
			'parent_item' => __( 'Parent Category', 'cpt-downloads' ),
			'parent_item_colon' => __( 'Parent Category:', 'cpt-downloads' ),
			'edit_item' => __( 'Edit Category', 'cpt-downloads' ),
			'update_item' => __( 'Update Category', 'cpt-downloads' ),
			'add_new_item' => __( 'Add New Category', 'cpt-downloads' ),
			'new_item_name' => __( 'New Category Name', 'cpt-downloads' ),
			'menu_name' => __( 'Categories', 'cpt-downloads' ),
		);

		register_taxonomy('download-category',array('download'), array(
			'hierarchical' => TRUE,
			'labels' => $labels,
			'show_ui' => TRUE,
			'query_var' => TRUE,
			'rewrite' => array( 'slug' => _x('download-category', 'Download Category Slug', 'cpt-downloads') )
		));
	}



	/**
	 * Reset download counter
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function reset_download_count()
	{
		if ( !$_POST['post_id'] )
			return;

		update_post_meta( $_POST['post_id'], '_download_counter', '0' );

		_e( 'No Downloads yet', 'cpt-downloads' );

		die();
	}


	/**
	 * Save post callback
	 *
	 * @access public
	 * @param int $post_id Post id
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function save_post( $post_id )
	{
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !isset( $_POST['download-file-nonce'] ) || !wp_verify_nonce( $_POST['download-file-nonce'], 'save-download-file' ) )
			return;

		update_post_meta( $post_id, '_file', $_POST['file-download-id'] );
	}



	/**
	 * Shortcode [DOWNLOADS]
	 *
	 * @return void
	 * @author Ralf Hortt
	 **/
	function shortcode_downloads( $atts )
	{
		extract( shortcode_atts( array(
			'query' => NULL,
			'imagesize' => 'post-thubmnail'
		), $atts ) );

		$query = new WP_Query( 'post_type=download&showposts=-1&order=ASC&orderby=menu_order' );

		if ( $query->have_posts() ) :

			$output = '<div class="downloads">';

			while ( $query->have_posts() ) : $query->the_post();

				$file = get_post_meta( get_the_ID(), '_file', TRUE );
				$type = $this->get_file_type( $file );

				$output .= '<div id="download-' . get_the_ID() . '" class="' . implode( ' ', get_post_class( 'clearfix type-' . $type, get_the_ID() ) ) . '">';
				$output .= '<div class="download-thumbnail"><a href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), $imagesize ) . '</a></div>';
				$output .= '<div class="download-content"><h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>' . apply_filters( 'the_content', get_the_content() ) . '</div>';
				$output .= '<div class="download-file-data"><img class="file-type-icon" alt="' . $type . '" src="' . plugins_url( dirname( plugin_basename( __FILE__ ) ) . '/images/icon-' . $type . '.png' ) . '">' . $this->get_file_size( $file ) . '</div>';
				$output .= '</div><!-- #download-' . get_the_ID() . '-->';

			endwhile;

			$output .= '</div><!-- .downloads -->';

		endif;

		wp_reset_query();

		return $output;
	}
}

new Custom_Post_Type_Downloads;