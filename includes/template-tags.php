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
 * @param type var Description
 * @return return type
 */
function the_download_count( $before = '', $after = '', $post_id = NULL )
{

	$post_id = ( NULL !== $post_id ) ? $post_id : get_the_ID();

	echo $before . number_format_i18n( get_download_count( $post_id ) ) . $after;

}
