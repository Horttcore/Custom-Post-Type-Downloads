<?php
/**
 *
 *  Custom Post Type Downloads Shortcode
 *
 * @author Ralf Hortt <me@horttcore.de>
 * @since v0.3
 */
class Custom_Post_Type_Downloads_Shortcode
{


    /**
     * Register shortcode
     *
     * @return void
     * @since v0.3
     * @author Ralf Hortt <me@horttcore.de>
     */
    public function __construct()
    {
        add_shortcode( 'downloads', array( $this, 'shortcode' ) );

    } // END __construct


    /**
     * Shortcode output
     *
     * @param is_array $atts Shortcode attributes
     * @return string Shortcode output
     * @since v0.3
     * @author Ralf Hortt <me@horttcore.de>
     */
    public function shortcode( $atts )
    {

        $query = shortcode_atts( array(
            'category' => '',
            'orderby' => 'title',
            'order' => 'ASC',
            'showposts' => get_option( 'posts_per_page' ),
        ), $atts );
        $query['post_type'] = 'download';
        $query['download-category'] = $query['category'];
        unset($query['category']);
        $query = array_filter( $query );

        $query = new WP_Query( $query );

        ob_start();

        if ( $query->have_posts() ) :

            /**
             * Before loop output
             *
             * @param obj $query WP_Query object
             * @param array $args Arguments
             * @param array $instance Widget instance
             * @hooked Custom_Post_Type::loop_before - 10
             */
            do_action( 'custom-post-type-downloads-before-loop', $query );

            while ( $query->have_posts() ) : $query->the_post();

                /**
                 * Loop output
                 *
                 * @param array $args Arguments
                 * @param array $instance Widget instance
                 * @param obj $query WP_Query object
                 * @hooked Custom_Post_Type::widget_loop - 10
                 */
                do_action( 'custom-post-type-downloads-loop', $query );

            endwhile;

            /**
             * After loop output
             *
             * @param obj $query WP_Query object
             * @param array $args Arguments
             * @param array $instance Widget instance
             * @hooked Custom_Post_Type::loop_after - 10
             */
            do_action( 'custom-post-type-downloads-after-loop', $query );

        endif;

        wp_reset_query();
        return ob_get_clean();

    } // END shortcode


} // END class Custom_Post_Type_Downloads_Shortcode
new Custom_Post_Type_Downloads_Shortcode;
