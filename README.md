# Custom Post Type Downloads

A custom post type to manage downloads

## Supports

* Title
* Editor
* Thumbnail
* Page Attributes

## Custom Fields

* Attachment ID

## Language Support

* english
* german

Translation ready

## Template functions

* `download_file_size( $before = '', $after = '' )`
* `download_icon()`
* `download_mime_type()`
* `download_thumbnail( $size = 'thumbnail', $icon = false, $attr = '' )`
* `get_download_attachment_id( $post_id )`
* `get_download_count( $post_id )`
* `get_download_file_size( $post_id, $formatted = TRUE )`
* `get_download_icon( $post_id )`
* `get_download_mime_type( $post_id )`
* `get_download_thumbnail( $post_id, $size = 'thumbnail', $icon = false, $attr = '' )`
* `the_download_count( $before = '', $after = '', $post_id = NULL )`

## Hooks

### Actions

* `custom-post-type-downloads-before-loop`
* `custom-post-type-downloads-loop`
* `custom-post-type-downloads-after-loop`

## Changelog

### v0.4

* Added: Access control management
* Added: Download single template
* Added: Widget
* Added: Shortcode
* Added: Template functions

### v0.3

* Added: Shortcode [downloads]
* Added: Widget
* Added: Hooks
* Fix: Do not display file information if no file is selected

### v0.2

* Refactoring

### v0.1

* Initial release
