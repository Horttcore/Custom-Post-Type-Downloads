<?php
function registerDownloadsBlocksScripts(){
    wp_register_script('cpt-downloads-block-script', plugins_url('../assets/dist/js/downloads-block.js', __FILE__), ['wp-blocks', 'wp-i18n', 'wp-element'], date('ymd', time()));
}
add_action('init', 'registerDownloadsBlocksScripts');

function enqueueDownloadsBlocksScripts(){
    wp_enqueue_script('cpt-downloads-block-script');
}
add_action('enqueue_block_editor_assets', 'enqueueDownloadsBlocksScripts');

function registerDownloadsList() {
	// Hook server side rendering into render callback
	register_block_type( 'horttcore/downloads-list', [
		'render_callback' => 'renderDownloadsList',
	] );
}
add_action( 'init', 'registerDownloadsList' );

function renderDownloadsList($atts){
    $args = [
        'suppress_filters' => 0
    ];
    if(isset($atts['posttype'][1])){
        $args['post_type'] = $atts['posttype'][1];
    } else {
        $args['post_type'] = 'download';
    }
    if(isset($atts['taxonomie'][1])){
        if(isset($atts['terms'])){
            $terms = $atts['terms'];
        } else {
            $terms = [-1];
        }
        $args['tax_query'] = [
            [
                'taxonomy' => $atts['taxonomie'][1],
                'field' => 'id',
                'terms' => $terms
            ]
        ];
    }
    if(isset($atts['amount'])){
        $args['posts_per_page'] = $atts['amount'];
    }
    if(isset($atts['order'])){
        $args['order'] = $atts['order'];
    }
    if(isset($atts['orderBy'])){
        $args['orderBy'] = $atts['orderBy'];
    } else {
        $args['orderBy'] = 'id';
    }
    $class = "";
    if(isset($atts['className'])){
        $class = $atts['className'];
    }

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args['paged'] = $paged;

    $query = new WP_Query($args);
    $oldMaxNumPages = $GLOBALS['wp_query']->max_num_pages;
    ob_start();
    if($query->have_posts()){
        $GLOBALS['wp_query']->max_num_pages = $query->max_num_pages;
        do_action('custom-post-type-download-block-output');
    }

    wp_reset_query();
    $GLOBALS['wp_query']->max_num_pages = $oldMaxNumPages;
    return ob_get_clean();
}

add_action('custom-post-type-download-output', function($query, $class){
    echo '<div class="wp-block-horttcore-downloads-list-wrapper">';
    do_action('custom-post-type-download-before-block-loop', $class);
    while($query->have_posts()){
        $query->the_post();
        do_action('custom-post-type-download-block-loop', get_post());
    }
    do_action('custom-post-type-download-after-block-loop');

    the_posts_pagination(
        array(
            'prev_text' => '<span class="screen-reader-text">' . __( 'Previous', 'fbo' ) . '</span>',
            'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'fbo' ) . '</span>',
        )
    );
    echo '</div>';
}, 10, 2);

add_action('custom-post-type-download-before-block-loop', function($class){
    echo '<ul class="wp-block-horttcore-downloads-list '.$class.'">';
}, 10, 1);

add_action('custom-post-type-download-block-loop', function ($post){
    echo '<li>';
        echo '<a href="'.get_permalink().'">'.get_the_title().'</a>';
    echo '</li>';
}, 10, 1);

add_action('custom-post-type-download-after-block-loop', function(){
    echo '</ul>';
}, 10, 0);