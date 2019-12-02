<?php
$current_user_can = apply_filters('custom-post-type-downloads-check-permission', FALSE );
if ( !$current_user_can )
	die('403 - Not allowed');

// Build download
$file_id = get_post_meta( get_the_ID(), '_file', TRUE );
$download_counter = get_post_meta( get_the_ID(), '_download_counter', TRUE );
$download_counter++;
update_post_meta( get_the_ID(), '_download_counter', $download_counter);

$file_path = get_attached_file( $file_id );
$file_name = basename ( $file_path );
$file_type = get_post_mime_type( $file_id );

header('Content-Disposition: attachment; filename="' . $file_name . '"');
header("Content-Type: " . $file_type);
echo file_get_contents( $file_path );
exit;
