<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ShapeShifter_TOC extends SSE_Widget {

	/**
	 * Static
	**/
		/**
		 * Defaults
		**/
		public static $defaults = array();

	function __construct() {
		self::$defaults = array( 'title_toc' => esc_html__( 'TOC', ShapeShifter_Extensions::TEXTDOMAIN ) );
		 parent::__construct( false, $name = esc_html__( 'ShapeShifter TOC', ShapeShifter_Extensions::TEXTDOMAIN ) );//ShapeShifter TOC
	}

	function widget( $args, $instance ) {

		if( ! is_singular() ) return;

		extract( $args ); $args = null;

		echo $before_widget; $before_widget = null;

			$title_toc = esc_html( strip_tags( $instance['title_toc'] ) );
			if( $title_toc != '' ) {
				echo $before_title . '<span class="title-toc">' . $title_toc . '</span>' . $after_title;
			} $before_title = $title_toc = $after_title = null;
		
			echo '<div class="toc"></div>';
		
		echo '<div class="clearfix"></div>';
		
		echo $after_widget; $after_widget = null;

	}
	
	function update( $new_instance, $old_instance ) {
		$new_instance = wp_parse_args( $new_instance, self::$defaults );
		$instance = $old_instance;
		$instance['title_toc'] = $instance['title'] = sanitize_text_field( strip_tags( $new_instance['title_toc'] ) );
		return $instance;
	}
	
	function form( $instance ) {
		$instance = wp_parse_args( ( array ) $instance, self::$defaults );
		$title_toc = esc_attr( $instance['title_toc'] );
		?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_toc' ) ); ?>">
				<strong><?php esc_html_e( 'Title', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_toc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_toc' ) ); ?>" type="text" value="<?php echo esc_attr( $title_toc ); ?>" />
		</p>

		<?php
	}
}


