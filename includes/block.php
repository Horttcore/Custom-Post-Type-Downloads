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

    $posts = get_posts($args);

    if(!is_array($posts)){
        $posts = [$posts];
    }

    ob_start();
    echo '<ul class="wp-block-horttcore-downloads-list '.$class.'">';
    foreach($posts as $post){
        do_action('render_download_post', $post);
    }
    echo '</ul>';
    return ob_get_clean();
}

add_action('render_download_post', 'renderDownloadPost', 10, 1);
function renderDownloadPost($post){
    echo '<li>';
        echo '<a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a>';
    echo '</li>';
}