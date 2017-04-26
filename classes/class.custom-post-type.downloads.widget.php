<?php
/**
 *
 *  Custom Post Type Downloads Widget
 *
 * @author Ralf Hortt <me@horttcore.de>
 * @since v0.3
 */
if ( !class_exists( 'Custom_Post_Type_Downloads_Widget' ) ) :
class Custom_Post_Type_Downloads_Widget extends WP_Widget {



	/**
	 * Constructor
	 *
	 * @access public
	 * @since v0.3
	 * @author Ralf Hortt <me@horttcore.de>
	 */
	public function __construct()
	{

		parent::__construct( 'widget-downloads', __( 'Downloads', 'custom-post-type-downloads' ), array(
			'classname' => 'widget-downloads',
			'description' => __( 'Lists downloads', 'custom-post-type-downloads' ),
		), array(
			'id_base' => 'widget-downloads'
		) );

	} // END __construct



	/**
	 * Widget settings
	 *
	 * @access public
	 * @param array $instance Widget instance
	 * @since v0.3
	 * @author Ralf Hortt <me@horttcore.de>
	 */
	public function form( $instance )
	{

		?>

		<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label><br>
			<input class="regular-text" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" id="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if ( isset( $instance['title'] ) ) echo esc_attr( $instance['title'] ) ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_name( 'orderby' ); ?>"><?php _e( 'Order By:', 'custom-post-type-downloads' ); ?></label><br>
			<select name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_name( 'orderby' ); ?>">
				<option <?php selected( $instance['orderby'], '' ) ?> value=""><?php _e( 'None' ); ?></option>
				<option <?php selected( $instance['orderby'], 'ID' ) ?> value="ID"><?php _e( 'ID', 'custom-post-type-downloads' ); ?></option>
				<option <?php selected( $instance['orderby'], 'title' ) ?> value="title"><?php _e( 'Title' ); ?></option>
				<option <?php selected( $instance['orderby'], 'date' ) ?> value="date"><?php _e( 'Date' ); ?></option>
				<option <?php selected( $instance['orderby'], 'rand' ) ?> value="rand"><?php _e( 'Random' ); ?></option>
				<option <?php selected( $instance['orderby'], 'menu_order' ) ?> value="menu_order"><?php _e( 'Menu order' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_name( 'order' ); ?>"><?php _e( 'Order:' ); ?></label><br>
			<select name="<?php echo $this->get_field_name( 'order' ); ?>" id="<?php echo $this->get_field_name( 'order' ); ?>">
				<option <?php selected( $instance['order'], 'ASC') ?> value="ASC"><?php _e( 'Ascending', 'custom-post-type-downloads' ); ?></option>
				<option <?php selected( $instance['order'], 'DESC') ?> value="DESC"><?php _e( 'Descending', 'custom-post-type-downloads' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_name( 'limit' ); ?>"><?php _e( 'Count:', 'custom-post-type-downloads' ); ?></label><br>
			<input class="regular-text" type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>" id="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php if ( isset( $instance['limit'] ) )  echo esc_attr( $instance['limit'] ) ?>">
		</p>

		<?php
		$category_dropdown = wp_dropdown_categories(array(
			'show_option_all' => __( 'All', 'custom-post-type-downloads' ),
			'taxonomy' => 'download-category',
			'name' => $this->get_field_name( 'download-category' ),
			'selected' => $instance['download-category'],
			'hide_if_empty' => TRUE,
			'hide_empty' => FALSE,
			'hierarchical' => TRUE,
			'echo' => FALSE
		));

		if ( $category_dropdown ) :

			?>

			<p>
				<label for="<?php echo $this->get_field_name( 'download-category' ); ?>"><?php _e( 'Category' ); ?></label><br>
				<?php echo $category_dropdown ?>
			</p>

			<?php

		endif;

	} // END form



	/**
	 * Save widget settings
	 *
	 * @access public
	 * @param array $new_instance New widget instance
	 * @param array $old_instance Old widget instance
	 * @return array Widget instance
	 * @since v0.3
	 * @author Ralf Hortt <me@horttcore.de>
	 */
	public function update( $new_instance, $old_instance )
	{

		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['order'] = $new_instance['order'];
		$instance['limit'] = $new_instance['limit'];
		$instance['download-category'] = ( isset( $new_instance['download-category'] ) ) ? $new_instance['download-category'] : FALSE;

		return $instance;

	} // END update



	/**
	 * Output
	 *
	 * @access public
	 * @param array $args Arguments
	 * @param array $instance Widget instance
	 * @return void
	 * @since v0.3
	 * @author Ralf Hortt <me@horttcore.de>
	 */
	public function widget( $args, $instance )
	{

		$query = array(
			'post_type' => 'download',
			'showposts' => $instance['limit'],
			'orderby' => $instance['orderby'],
			'order' => $instance['order'],
		);

		if ( 0 != $instance['download-category'] ) :
			$query['tax_query'] = array(
				array(
					'taxonomy' => 'download-category',
					'field' => 'term_id',
					'terms' => $instance['download-category'],
				)
			);
		endif;

		$query = array_filter( $query );
		$query = new WP_Query( $query );

		if ( $query->have_posts() ) :

			echo $args['before_widget'];

			if ( $instance['title'] )
				echo $args['before_title'] . $instance['title'] . $args['after_title'];

			/**
			 * Before loop output
			 *
			 * @param obj $query WP_Query object
			 * @param array $args Arguments
			 * @param array $instance Widget instance
			 * @hooked Custom_Post_Type::loop_before - 10
			 */
			do_action( 'custom-post-type-downloads-before-loop', $query, $args, $instance );

			while ( $query->have_posts() ) : $query->the_post();

				/**
				 * Loop output
				 *
				 * @param array $args Arguments
				 * @param array $instance Widget instance
				 * @param obj $query WP_Query object
				 * @hooked Custom_Post_Type::widget_loop - 10
				 */
				do_action( 'custom-post-type-downloads-loop', $query, $args, $instance );

			endwhile;

			/**
			 * After loop output
			 *
			 * @param obj $query WP_Query object
			 * @param array $args Arguments
			 * @param array $instance Widget instance
			 * @hooked Custom_Post_Type::loop_after - 10
			 */
			do_action( 'custom-post-type-downloads-after-loop', $query, $args, $instance );

		endif;

		wp_reset_query();

	} // END widget



} // END class Custom_Post_Type_Downloads_Widget

endif;
