<?php
if ( ! class_exists( 'SSE_Widget_Text' ) ) {
/**
 * 
**/
class ShapeShifter_Widget_Text extends SSE_Widget {

	/**
	 * Static
	**/
		/**
		 * Defaults
		**/
		public static $defaults = array();


	function __construct() {

		self::$defaults = array(
			'title_text' => '',
			'title_not_display' => false,
			'textarea' => '',
			'image_url_str' => '',
			'download_transition' => 'fade',
			'download_delay' => 3000,
			'download_transition_duration' => 2000,
		);
		parent::__construct( false, $name = esc_html__( 'ShapeShifter Text', ShapeShifter_Extensions::TEXTDOMAIN ) );

	}

	function widget( $args, $instance ) {

		extract( $args ); $args = null;

		# General
			$title_text = esc_html( strip_tags( $instance['title_text'] ) );
			$title_not_display = ( ! empty( $instance['title_not_display'] ) ? true : false );
			$textarea = html_entity_decode( $instance['textarea'] );

		$title_text = ( $title_text ? $title_text : '' );

		# Output
		echo $before_widget; $before_widget = null;
			if( ! $title_not_display && $title_text != '' ) {
				echo $before_title . '<span class="title-textarea">' . $title_text . '</span>' . $after_title; 
			} $title_not_display = $before_title = $title_text = $after_title = null;

			echo '<div id="' . esc_attr( $this->id ) . '" class="textarea-wrapper"><div class="textarea-content" style="padding:10px;">' . shapeshifter_get_string_eof( $textarea ) . '</div></div>'; $textarea = null;
		echo $after_widget; $after_widget = null;
		
		$this->output_vegas_background_images_for_widget( $instance );

	}
	
	function update( $new_instance, $old_instance ) {

		$new_instance = wp_parse_args( $new_instance, self::$defaults );

		$instance = $old_instance;

		$instance = $this->update_vegas_background_images_for_widget( $new_instance, $instance );

		$instance['title_text'] = $instance['title'] = sanitize_text_field( strip_tags( $new_instance['title_text'] ) );
		$instance['title_not_display'] = sanitize_text_field( $new_instance['title_not_display'] );
		$instance['textarea'] = esc_textarea( $new_instance['textarea'] );

		return $instance;

	}
	
	function form( $instance ) {

		$post_types = get_post_types();

		$instance = wp_parse_args( ( array ) $instance, self::$defaults );

		$title_text = esc_attr( $instance['title_text'] );
		$title_not_display = esc_attr( $instance['title_not_display'] );
		$textarea = html_entity_decode( $instance['textarea'] );

		$download_transition = esc_attr( $instance['download_transition'] );
		$download_delay = intval( $instance['download_delay'] );
		$download_transition_duration = intval( $instance['download_transition_duration'] );

		$widget_id = $this->id;

		if( ! did_action( 'wp_enqueue_media' ) ) { wp_enqueue_media(); }

		?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_text' ) ); ?>">
				<strong><?php esc_html_e( 'Title', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_text' ) ); ?>" type="text" value="<?php echo esc_attr( $title_text ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_not_display' ) ); ?>">
				<strong><?php esc_html_e( 'Not Display the Title', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'title_not_display' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title_not_display' ) ); ?>" value="title_not_display" <?php checked( $title_not_display, 'title_not_display' ); ?> style="width:0;"/>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'textarea' ) ); ?>">
				<strong><?php esc_html_e( 'Texts to print', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'textarea' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'textarea' ) ); ?>" rows="10"
			><?php echo $textarea; ?></textarea>
		</p>

		<?php 

		$this->form_vegas_background_images_for_widget( $instance );

	}

}
}
