<?php
/**
 * Get download count
 *
 * @param int $post_id Post ID
 * @return int Download count
 */
function get_download_count( $post_id )
{
	$counter = get_post_meta( $post_id, '_download_counter', TRUE );
	return absint( $counter );
}


/**
 * Print download count
 *
 * @param string $before Before
 * @param string $after After
 * @param int $post_id Post ID
 * @return string Formatted download counter
 */
function the_download_count( $before = '', $after = '', $post_id = NULL )
{
	$post_id = ( NULL !== $post_id ) ? $post_id : get_the_ID();

	echo $before . number_format_i18n( get_download_count( $post_id ) ) . $after;
}


/**
 * Get download attachment id
 *
 * @param int $post_id Post ID
 * @return int Attachment ID
 */
function get_download_attachment_id( $post_id )
{
	return intval( get_post_meta( $post_id, '_file', TRUE ) );
}


/**
 * Get download mime type
 *
 * @param int $post_id Post ID
 * @return string Mime type
 */
function get_download_mime_type( $post_id )
{
	$post = get_post( get_download_attachment_id( $post_id ) );
	return $post->post_mime_type;
}


/**
 * Get download mime type
 *
 * @param int $post_id Post ID
 * @return string Mime type
 */
function download_mime_type()
{
	echo get_download_mime_type( get_the_ID() );
}


/**
 * Get download icon
 *
 * @param int $post_id Post ID
 * @return string Download icon image tag
 */
function get_download_icon( $post_id )
{
	$mime_type = get_download_mime_type( $post_id );
	$icon = wp_mime_type_icon( $mime_type );
	return '<img src="' . $icon . '" alt="' . esc_attr($icon) . '" title="' . $mime_type . '" aria-hidden="true" />';
}


/**
 * Print download icon
 *
 * @return void
 */
function download_icon()
{
	echo get_download_icon( get_the_ID() );
}


/**
 * Get download thumbnail
 *
 * @param int $post_id Post ID
 * @return int Download count
 */
function get_download_thumbnail( $post_id, $size = 'thumbnail', $icon = false, $attr = '' )
{
	return wp_get_attachment_image( get_download_attachment_id( $post_id ), $size, $icon, $attr );
}


/**
 * Print download thumbnail
 *
 * @param int $post_id Post ID
 * @return int Download count
 */
function download_thumbnail( $size = 'thumbnail', $icon = false, $attr = '' )
{
	echo get_download_thumbnail( get_the_ID(), $size, $icon, $attr );
}


/**
 * Get download filesize
 *
 * @param int $post_id Post ID
 * @return string
 */
function get_download_file_size( $post_id, $formatted = TRUE )
{
	$meta = wp_get_attachment_metadata( get_download_attachment_id( $post_id ) );

	if ( !isset( $meta['filesize'] ) || !$meta['filesize'] )
		return;

	return size_format( $meta['filesize'] );
}


/**
 * Print download file size
 *
 * @param bool $formatted Should the file size be formatted
 * @return void
 */
function download_file_size( $before = '', $after = '' )
{
	$size = get_download_file_size( get_the_ID(), true );

	if ( !$size )
		return;

	echo $before . $size . $after;
}
