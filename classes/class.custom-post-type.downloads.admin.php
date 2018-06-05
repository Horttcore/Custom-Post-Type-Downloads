<?php
/**
 *
 *  Custom Post Type Downloads Admin
 *
 * @author Ralf Hortt <me@horttcore.de>
 * @since v0.2
 */
class Custom_Post_Type_Downloads_Admin
{


    /**
     * Admin plugin constructor
     *
     * @access public
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     **/
    public function __construct()
    {

        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_register_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
        add_filter( 'manage_download_posts_columns', [$this, 'manage_download_posts_columns'] );
        add_action( 'manage_download_posts_custom_column', [$this, 'manage_download_posts_custom_column'], 10, 2 );
        add_filter( 'manage_edit-download_sortable_columns', [$this, 'manage_edit_download_sortable_columns']);
        add_action( 'wp_ajax_download-information', array( $this, 'ajax_download_information' )  );
        add_action( 'wp_ajax_reset-download-counter', array( $this, 'ajax_reset_download_counter' )  );
        add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
        add_action( 'save_post', array( $this, 'save_post' ) );
        add_filter( 'upload_dir', [$this, 'upload_dir'] );

    } // END __construct


    /**
     * Add meta boxes
     *
     * @access public
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     **/
    public function add_meta_boxes()
    {

        add_meta_box( 'file-download-counter', __( 'Download Counter', 'custom-post-type-downloads'  ), array( $this, 'metabox_counter' ), 'download' );
        add_meta_box( 'file-download-metabox', __( 'File', 'custom-post-type-downloads'  ), array( $this, 'metabox_file' ), 'download' );

    } // END add_meta_boxes


    /**
     * Register scripts
     *
     * @access public
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     **/
    public function admin_register_scripts()
    {

        wp_register_script( 'custom-post-type.downloads.admin.file', plugins_url( dirname( plugin_basename( __FILE__ ) ) . '/../assets/javascript/custom-post-type.downloads.admin.file.js' ), array( 'jquery', 'media-upload', 'thickbox' ), FALSE, TRUE );
        wp_register_script( 'custom-post-type.downloads.admin.counter', plugins_url( dirname( plugin_basename( __FILE__ ) ) . '/../assets/javascript/custom-post-type.downloads.admin.counter.js' ), array( 'jquery' ), FALSE, TRUE );
        wp_localize_script( 'custom-post-type.downloads.admin.file', 'CustomPostTypeDownloads', array(
            'selectAsDownload' => __( 'Select as Download', 'custom-post-type-downloads' ),
            'selectFile' => __( 'Select a File', 'custom-post-type-downloads' ),
            'resetCounter' => __( 'Reset Download Counter?', 'custom-post-type-downloads' )
        ) );

    } // END admin_register_scripts


    /**
     * Register styles
     *
     * @access public
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     **/
    public function admin_enqueue_styles()
    {

        wp_register_style( 'custom-post-type.downloads.admin', plugins_url( dirname( plugin_basename( __FILE__ ) ) . '/../assets/styles/custom-post-type.downloads.admin.css' ) );

        $screen = get_current_screen();

        if ( 'post' != $screen->base )
            return;

        if ( 'download' != $screen->post_type )
            return;

        wp_enqueue_style( 'custom-post-type.downloads.admin' );

    } // END admin_enqueue_styles


    /**
     * Ajax response for download information
     *
     * @param type var Description
     * @return return type
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
 	 */
    public function ajax_download_information()
    {

        $file_id = ( $_REQUEST['file_id'] ) ? $_REQUEST['file_id'] : $file_id;

        if ( !$file_id )
            return;

        ob_start();

        $this->file_info( $file_id );

        wp_send_json_success( array(
            'output' => ob_get_clean()
        ) );

    } // END ajax_download_information


    /**
     * Reset download counter
     *
     * @access public
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     **/
    public function ajax_reset_download_counter()
    {

        if ( !$_REQUEST['post_id'] )
            wp_send_json_error( __( 'Post ID missing', 'custom-post-type-downloads' ) );

        $post_id = absint( $_REQUEST['post_id'] );

        if ( !$_REQUEST['nonce'] )
            wp_send_json_error( __( 'Nonce missing', 'custom-post-type-downloads' ) );

        if ( !wp_verify_nonce( $_REQUEST['nonce'], 'reset-downlaod-counter-' . $post_id ) )
            wp_send_json_error( __( 'Nonce invalid', 'custom-post-type-downloads' ) );

        if ( !current_user_can( 'edit_post', $post_id ) )
            wp_send_json_error( __( 'No user permission', 'custom-post-type-downloads' ) );

        update_post_meta( $post_id, '_download_counter', '0' );

        wp_send_json_success( array(
            'message' => __( 'No Downloads yet', 'custom-post-type-downloads' ),
            'counter' => 0
        ) );

    } // END reset_download_count


    /**
     * Display file info
     *
     * @access public
     * @param int $file_id Attachment ID
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     */
    protected function file_info( $file_id )
    {

        if ( !$file_id )
            return;

        $file = get_post( $file_id );
        ?>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e( 'Preview' ) ?></th>
                    <th><?php _e( 'Name' ) ?></th>
                    <th><?php _e( 'Filesize' ) ?></th>
                    <th><?php _e( 'Filetype' ) ?></th>
                    <th><?php _e( 'Author' ) ?></th>
                    <th><?php _e( 'Date' ) ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo wp_get_attachment_image( $file->ID, 'thumbnail', TRUE ) ?></td>
                    <td><?php echo $file->post_title ?></td>
                    <td><?php echo size_format( filesize( get_attached_file( $file->ID ) ) ); ?></td>
                    <td><?php echo get_post_mime_type( $file->ID ) ?></td>
                    <td><?php echo get_the_author_meta( 'display_name', $file->post_author ) ?></td>
                    <td><?php echo get_the_date( get_option( 'date_format' ), $file->ID ) ?></td>
                </tr>
            </tbody>
        </table>
        <?php

    } // END file_info


    /**
     * Add admin columns
     *
     * @param array $columns Columns
     * @return array Columns
     */
    public function manage_download_posts_columns( $columns )
    {
        $columns['downloads'] = __( 'Download', 'custom-post-type-downloads' );
        return $columns;
    }


    /**
     * Print admin column
     *
     * @param string $column Column name
     * @param int $post_id Post ID
     * @return void
     */
    public function manage_download_posts_custom_column( $column, $post_id )
    {
        switch ( $column ) :
            case 'downloads' :
                the_download_count('', '', $post_id);
                break;
        endswitch;
    }


    /**
     * undocumented function summary
     *
     * @param type var Description
     * @return return type
     */
    public function manage_edit_download_sortable_columns( $columns )
    {
        $columns['downloads'] = 'download-count';
        return $columns;
    }


    /**
     * Counter Metabox
     *
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
 	 **/
    public function metabox_counter( $post )
    {

        wp_enqueue_script( 'custom-post-type.downloads.admin.counter' );

        $counter = get_download_count( $post->ID );
        ?>
        <p class="download-count">
            <?php printf( _n( '<strong>%s</strong> Download counted', '<strong>%s</strong> Downloads counted', $counter, 'custom-post-type-downloads' ), number_format_i18n( $counter ) ) ?>
        </p>

        <?php if ( 0 < $counter) : ?>
        <p>
            <button class="button reset-counter" data-nonce="<?php echo wp_create_nonce( 'reset-downlaod-counter-' . $post->ID ) ?>"><?php _e( 'Reset counter', 'custom-post-type-downloads' ) ?></button>
        </p>
        <?php endif;

    } // END metabox_counter


    /**
     * File metabox
     *
     * @access public
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     **/
    public function metabox_file( $post )
    {

        $file_id = get_post_meta( $post->ID, '_file', TRUE );
        $style_add = ( !$file_id ) ? '' : 'style="display:none"';
        $style_remove = ( $file_id ) ? '' : 'style="display:none"';

        wp_enqueue_script( 'custom-post-type.downloads.admin.file' );
        ?>

        <div class="file-info">
            <?php $this->file_info( $file_id ) ?>
        </div>

        <p>
            <input type="hidden" name="file-download-id" id="file-download-id" value="<?php echo $file_id ?>">
            <a href="#" id="file-download" name="file-download" class="button" <?php echo $style_add ?>><?php _e( 'Select a file', 'custom-post-type-downloads' ); ?></a>
            <a href="#" id="remove-file-download" name="remove-file-download" class="submitdelete deletion" <?php echo $style_remove ?>><?php _e( 'Remove file', 'custom-post-type-downloads' ); ?></a>
        </p>

        <?php
        wp_nonce_field( 'save-download-file', 'download-file-nonce' );

    } // END metabox_file


    /**
     * Update messages
     *
     * @access public
     * @param array $messages Update messages
     * @return array Update messages
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     **/
    public function post_updated_messages( $messages )
    {

        $post             = get_post();
        $post_type        = 'download';
        $post_type_object = get_post_type_object( $post_type );

        $messages[$post_type] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __( 'Download updated.', 'custom-post-type-downloads' ),
            2  => __( 'Custom field updated.' ),
            3  => __( 'Custom field deleted.' ),
            4  => __( 'Download updated.', 'custom-post-type-downloads' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Download restored to revision from %s', 'custom-post-type-downloads' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => __( 'Download published.', 'custom-post-type-downloads' ),
            7  => __( 'Download saved.', 'custom-post-type-downloads' ),
            8  => __( 'Download submitted.', 'custom-post-type-downloads' ),
            9  => sprintf( __( 'Download scheduled for: <strong>%1$s</strong>.', 'custom-post-type-downloads' ), date_i18n( __( 'M j, Y @ G:i', 'custom-post-type-downloads' ), strtotime( $post->post_date ) ) ),
            10 => __( 'Download draft updated.', 'custom-post-type-downloads' )
        );

        if ( !$post_type_object->publicly_queryable )
            return $messages;

        $permalink = get_permalink( $post->ID );

        $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View download', 'custom-post-type-downloads' ) );
        $messages[$post_type][1] .= $view_link;
        $messages[$post_type][6] .= $view_link;
        $messages[$post_type][9] .= $view_link;

        $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
        $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview download', 'custom-post-type-downloads' ) );
        $messages[$post_type][8]  .= $preview_link;
        $messages[$post_type][10] .= $preview_link;

        return $messages;

    } // END post_updated_messages


    /**
     * Save post callback
     *
     * @access public
     * @param int $post_id Post id
     * @return void
     * @since v0.2
     * @author Ralf Hortt <me@horttcore.de>
     **/
    public function save_post( $post_id )
    {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;

        if ( !isset( $_POST['download-file-nonce'] ) || !wp_verify_nonce( $_POST['download-file-nonce'], 'save-download-file' ) )
            return;

        if ( !isset( $_POST['file-download-id'] ) || '' == $_POST['file-download-id'] )
            delete_post_meta( $post_id, '_file' );

        if ( isset( $_POST['file-download-id'] ) && '' != $_POST['file-download-id'] )
            update_post_meta( $post_id, '_file', absint( $_POST['file-download-id'] ) );

    } // END save_post


    /**
     * Restriction meta box
     *
     * @param array $params Upload DIR params
     * @return array Upload DIR params
     */
    public function upload_dir( $params )
    {

        $post_id = ( isset( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : '' );

        if( !$post_id )
            return $params;

        if ( get_post_type( $post_id ) != 'download' )
            return $params;

        $params['path'] = $params['basedir'] . '/downloads';
        $params['url'] = $params['baseurl'] . '/downloads';
        $params['subdir'] = '/';

        return $params;

    } // END upload_dir


} // END class Custom_Post_Type_Downloads_Admin

new Custom_Post_Type_Downloads_Admin;
