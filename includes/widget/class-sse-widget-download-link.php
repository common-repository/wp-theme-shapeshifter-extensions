<?php
class ShapeShifter_Download_Link extends SSE_Widget {

	public static $defaults = array();

	function __construct() {

		self::$defaults = array(
			'title_download_link' => esc_html__( 'Download Link', ShapeShifter_Extensions::TEXTDOMAIN ),
			'widget_title_not_display' => false,
			'download_title' => '',
			'download_description' => '',
			'download_url' => '',
			'demo_page_url' => '',
			'download_text_color' => ''
		);
		parent::__construct( false, $name = esc_html__( 'ShapeShifter DownloadLink', '' ) );

	}

	function widget( $args, $instance ) { 

		if( ( isset( $instance['download_url'] ) && $instance['download_url'] == '' ) ) {
			$args = $instance = null;
			return;
		}

		extract( $args ); $args = null;

		$download_id = esc_attr( $this->id );

		$download_title = esc_html( strip_tags( isset( $instance['download_title'] ) ? $instance['download_title'] : '' ) );
		$download_description = html_entity_decode( isset( $instance['download_description'] ) ? $instance['download_description'] : '' );

		$demo_page_url = esc_url( isset( $instance['demo_page_url'] ) ? $instance['demo_page_url'] : '' );
		$download_url = esc_url( isset( $instance['download_url'] ) ? $instance['download_url'] : '' );

		$download_text_color = esc_attr( 
			( isset( $instance['download_text_color'] ) && $instance['download_text_color'] != '' ) 
			? $instance['download_text_color'] 
			: '#FFF' 
		);


		echo $before_widget; $before_widget = null;

			$title_download_link = esc_html( strip_tags( isset( $instance['title_download_link'] ) ? $instance['title_download_link'] : '' ) );
			$widget_title_not_display = shapeshifter_boolval( 
				( isset( $instance['widget_title_not_display'] ) 
					&& $instance['widget_title_not_display'] != '' 
				) 
				? true 
				: false 
			);
			if( ! $widget_title_not_display ) {
				if( ! empty( $title_download_link ) )
					echo $before_title . $title_download_link . $after_title;
			} $widget_title_not_display = $before_title = $title_download_link = $after_title = null;

			echo '<div id="' . $download_id . '" class="image-slider-wrapper" style="position: relative; overflow: auto; width:100%; padding: 40px 30px;">';
				echo '<div id="' . $download_id . '-wrapper" class="download-box" style="position:static; bottom:30px; width: 100%; color: ' . $download_text_color . '">';

					echo '<p id="' . $download_id . '-title" class="download-title" style="text-align:center; width: 100%; margin: auto; margin-bottom: 20px; font-size: 20px;">';
						echo $download_title;
					echo '</p>';
					echo '<p id="' . $download_id . '-description" class="download-description" style="text-align:center; width: 100%; margin: 20px auto;"><small>' . $download_description . '</small></p>';
					echo '<p id="' . $download_id . '-button" class="download-button" style="text-align:center; margin: auto; line-height: 4;">';
						if( ! empty( $demo_page_url ) ) {
							echo '<a id="' . $download_id . '-demo-link" href="' . $demo_page_url . '" style="margin: auto 15px; padding: 15px; border: solid ' . $download_text_color . ' 2px; border-radius: 5px; color: ' . $download_text_color . '">';
								echo 'Demo';
							echo '</a>';
						}
						echo '<a id="' . $download_id . '-download-link" href="' . $download_url . '" style="margin: auto 15px; padding: 15px; border: solid ' . $download_text_color . ' 2px; border-radius: 5px; color: ' . $download_text_color . '">';
						echo 'Download';
						echo '</a>';
					echo '</p>';
				echo '</div>';
			echo '</div>';

			$download_id = $download_title = $download_description = $demo_page_url = $download_url = $download_text_color = null;

		echo $after_widget; $after_widget = null;
		
		$this->output_vegas_background_images_for_widget( $instance ); $instance = null;

	}
	
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance = $this->update_vegas_background_images_for_widget( $new_instance, $instance );


		$new_instance = wp_parse_args( $new_instance, self::$defaults );

		$instance['title_download_link'] = sanitize_text_field( strip_tags( $new_instance['title_download_link'] ) );
		$instance['widget_title_not_display'] = sanitize_text_field( strip_tags( $new_instance['widget_title_not_display'] ) );

		$instance['download_text_color'] = sanitize_text_field( strip_tags( $new_instance['download_text_color'] ) );

		$instance['download_title'] = sanitize_text_field( strip_tags( $new_instance['download_title'] ) );
		$instance['download_description'] = esc_textarea( $new_instance['download_description'] );
		$instance['download_url'] = esc_url_raw( $new_instance['download_url'] );

		$instance['demo_page_url'] = esc_url_raw( $new_instance['demo_page_url'] );

		return $instance;

	}
	
	function form( $instance ) {

		$widget_id = $this->id;

		$init = ( array ) $instance;

		$instance = wp_parse_args( (array) $instance, self::$defaults );

		$title_download_link = esc_attr( strip_tags( $instance['title_download_link'] ) );
		$widget_title_not_display = esc_attr( $instance['widget_title_not_display'] );
		$download_title = esc_attr( strip_tags( $instance['download_title'] ) );
		$download_description = esc_textarea( $instance['download_description'] );

		$demo_page_url = esc_url( $instance['demo_page_url'] );
		$download_url = esc_url( $instance['download_url'] );
		$download_text_color = esc_attr( $instance['download_text_color'] );

		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_name( 'title_download_link' ) ); ?>">
				<strong><?php esc_html_e( 'Title', ShapeShifter_Extensions::TEXTDOMAIN );//タイトル ?></strong>
			</label><br>
			<input name="<?php echo esc_attr( $this->get_field_name( 'title_download_link' ) ); ?>"
				type="text" 
				id="<?php echo esc_attr( $this->get_field_id( 'title_download_link' ) ); ?>" 
				class="regular-text-field" 
				value="<?php echo esc_attr( $title_download_link ); ?>" 
			/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_name( 'widget_title_not_display' ) ); ?>">
				<strong><?php esc_html_e( 'Not Display the Title', ShapeShifter_Extensions::TEXTDOMAIN );//タイトルを非表示 ?></strong>
			</label>
			<input name="<?php echo esc_attr( $this->get_field_name( 'widget_title_not_display' ) ); ?>"
				type="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'widget_title_not_display' ) ); ?>"
				class="widefat" 
				value="widget_title_not_display" <?php checked( $widget_title_not_display, 'widget_title_not_display' ); ?> 
		   	/>
		</p>

		<p><?php // テキスト色
			$this->shapeshifter_print_input_tag( 
				esc_html__( 'Text Color', ShapeShifter_Extensions::TEXTDOMAIN ), 
				'text', 
				$this->get_field_id( 'download_text_color' ), 
				$this->get_field_name( 'download_text_color' ), 
				( isset( $instance['download_text_color'] ) 
					? $instance['download_text_color'] 
					: '' 
				), 
				$atts = array(
					'class' => 'regular-text-field color-setting',
				) 
			);
		?></p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'download_title' ) ); ?>">
				<strong><?php esc_html_e( 'Download Name', ShapeShifter_Extensions::TEXTDOMAIN );//ダウンロードコンテンツの名前 ?></strong>
			</label><br>
			<input name="<?php echo esc_attr( $this->get_field_name( 'download_title' ) ); ?>"
				type="text" 
				id="<?php echo esc_attr( $this->get_field_id( 'download_title' ) ); ?>" 
				class="regular-text-field widefat" 
				value="<?php echo esc_attr( $download_title ); ?>" 
			/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'download_description' ) ); ?>">
				<strong><?php esc_html_e( 'Download Description', ShapeShifter_Extensions::TEXTDOMAIN );//ダウンロードの詳細 ?></strong>
			</label><br>
			<textarea name="<?php echo esc_attr( $this->get_field_name( 'download_description' ) ); ?>"
				type="hidden"
				id="<?php echo esc_attr( $this->get_field_id( 'download_description' ) ); ?>"
				class="regular-textarea" 
				style="width:100%; height: 200px;"
			><?php echo $download_description; ?></textarea>
		</p>


		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'download_url' ) ); ?>">
				<strong><?php esc_html_e( 'Download URL', ShapeShifter_Extensions::TEXTDOMAIN );//ダウンロードURL ?></strong>
			</label><br>
			<input name="<?php echo esc_attr( $this->get_field_name( 'download_url' ) ); ?>"
				type="text"
				id="<?php echo esc_attr( $this->get_field_id( 'download_url' ) ); ?>"
				class="widefat" 
				value="<?php echo esc_url( $download_url ); ?>"
		   	/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'demo_page_url' ) ); ?>">
				<strong><?php esc_html_e( 'Demo Page URL', ShapeShifter_Extensions::TEXTDOMAIN );//デモページのURL（オプション） ?></strong>
			</label>
			<input name="<?php echo esc_attr( $this->get_field_name( 'demo_page_url' ) ); ?>"
				type="text"
				id="<?php echo esc_attr( $this->get_field_id( 'demo_page_url' ) ); ?>"
				class="widefat" 
				value="<?php echo esc_url( $demo_page_url ); ?>" 
		   	/>
		</p>

		<?php
		$this->form_vegas_background_images_for_widget( $instance );
	}

}
?>