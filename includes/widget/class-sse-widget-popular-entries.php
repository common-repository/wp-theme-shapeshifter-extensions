<?php
class ShapeShifter_Popular_Entries extends SSE_Widget {
	
	public static $defaults = array();

	private $terms = array();
	private $post_formats = array();

	function __construct() {
		self::$defaults = array(
			'title_popular' => esc_attr__( 'Popular Entries', ShapeShifter_Extensions::TEXTDOMAIN ),
			'entry_count' => 5,
			'title_not_display' => false,
			'excerpt_display' => false,
		);
		$this->terms = get_terms( 'category' );
		$this->post_formats = array(
			'standard' => esc_html__( 'Display Standard', ShapeShifter_Extensions::TEXTDOMAIN ),
			'aside' => esc_html__( 'Display Aisde', ShapeShifter_Extensions::TEXTDOMAIN ),
			'gallery' => esc_html__( 'Display Gallary', ShapeShifter_Extensions::TEXTDOMAIN ),
			'image' => esc_html__( 'Display Image', ShapeShifter_Extensions::TEXTDOMAIN ),
			'link' => esc_html__( 'Display Link', ShapeShifter_Extensions::TEXTDOMAIN ),
			'quote' => esc_html__( 'Display Quote', ShapeShifter_Extensions::TEXTDOMAIN ),
			'status' => esc_html__( 'Display Status', ShapeShifter_Extensions::TEXTDOMAIN ),
			'video' => esc_html__( 'Display Video', ShapeShifter_Extensions::TEXTDOMAIN ),
			'audio' => esc_html__( 'Display Audio', ShapeShifter_Extensions::TEXTDOMAIN ),
			'chat' => esc_html__( 'Display Chat', ShapeShifter_Extensions::TEXTDOMAIN ),
		);
		parent::__construct( false, $name = esc_html__( 'ShapeShifter Popular Posts List', ShapeShifter_Extensions::TEXTDOMAIN ) );

	}
	
	function widget( $args, $instance ) {

		extract( $args ); $args = null;

		$this->terms = get_terms( 'category' );

		// 投稿フォーマット
			$this->post_formats = array(
				'standard' => esc_html__( 'Display Standard', ShapeShifter_Extensions::TEXTDOMAIN ),
				'aside' => esc_html__( 'Display Aisde', ShapeShifter_Extensions::TEXTDOMAIN ),
				'gallery' => esc_html__( 'Display Gallary', ShapeShifter_Extensions::TEXTDOMAIN ),
				'image' => esc_html__( 'Display Image', ShapeShifter_Extensions::TEXTDOMAIN ),
				'link' => esc_html__( 'Display Link', ShapeShifter_Extensions::TEXTDOMAIN ),
				'quote' => esc_html__( 'Display Quote', ShapeShifter_Extensions::TEXTDOMAIN ),
				'status' => esc_html__( 'Display Status', ShapeShifter_Extensions::TEXTDOMAIN ),
				'video' => esc_html__( 'Display Video', ShapeShifter_Extensions::TEXTDOMAIN ),
				'audio' => esc_html__( 'Display Audio', ShapeShifter_Extensions::TEXTDOMAIN ),
				'chat' => esc_html__( 'Display Chat', ShapeShifter_Extensions::TEXTDOMAIN ),
			);
			$post_format_terms = array();
			foreach( $this->post_formats as $post_format => $text ) {

				if( $instance['display_format_' . $post_format ] != '' ) {
					$post_format_terms[ $post_format ] = esc_attr( 'post-format-' . $post_format );
				} else {
					$post_format_terms[ $post_format ] = '';
				}

			}
			$tax_query = $this->shapeshifter_get_tax_query_post_formats( $post_format_terms );

		$category_id = array();
		$this->terms = get_terms( 'category' );
		foreach( $this->terms as $term ) {
			if( $term->count > 0 
				&& intval(
					( isset( $instance['category_id' . $term->term_id ] ) 
						&& $instance['category_id' . $term->term_id ] != '' ) 
					? $instance['category_id' . $term->term_id ] 
					: 0 
				) !== 0 
			) {
				$category_id[] = intval( $instance['category_id' . $term->term_id ] );
			}
		} $this->terms = null;
		
		echo $before_widget; $before_widget = null;
			$title_popular = esc_html( strip_tags( $instance['title_popular'] ) );
			$title_not_display = ( ! empty( $instance['title_not_display'] ) ? true : false );
			if( ! $title_not_display ) { 
				if( $title_popular != '' )
					echo $before_title . '<span class="title-new-entries">' . $title_popular . '</span>' . $after_title;
			} $title_not_display = $before_title = $title_popular = $after_title = null;
			echo '<ul>';
				$entry_count = intval( $instance['entry_count'] );
				$entry_count = absint( $entry_count ? $entry_count : 5 );
				$widget_popular_args = array(
					'orderby' => 'meta_value_num',
					'category__in' => ( isset( $category_id ) ? $category_id : array() ),
					'post_type' => array(
						'post'
					),
					'post_status' => array(
						'publish',
					),
					'posts_per_page' => $entry_count,
					'meta_key' => 'shapeshifter-views',
					'tax_query' => array( 
						$tax_query,
					)
				);
				$category_id = $entry_count = $tax_query = null;
				
				$posts = get_posts( $widget_popular_args ); $widget_popular_args = null;
				$excerpt_display = shapeshifter_boolval( isset( $instance['excerpt_display'] ) && $instance['excerpt_display'] != '' ? true : false );
				foreach ( $posts as $post ) {
					if( $post->post_type !== 'post' ) continue;
					self::print_widget_entry_li( $post, 'popular-entry', $excerpt_display );
				} wp_reset_postdata(); $posts = $excerpt_display = null;
			echo '</ul>';
		echo $after_widget; $after_widget = null;
	}
	
	function update( $new_instance, $old_instance ) {

		$new_instance = wp_parse_args( $new_instance, self::$defaults );
		$instance = $old_instance;

		$instance['title_popular'] = $instance['title'] = sanitize_text_field( strip_tags( $new_instance['title_popular'] ) );
		$instance['entry_count'] = absint( $new_instance['entry_count'] );
		$instance['title_not_display'] = sanitize_text_field( $new_instance['title_not_display'] );
		$instance['excerpt_display'] = sanitize_text_field( $new_instance['excerpt_display'] );

		foreach( $this->post_formats as $post_format => $text ) {
			$instance['display_format_' . $post_format ] = sanitize_text_field( strip_tags( $new_instance['display_format_' . $post_format ] ) );
		}

		foreach( $this->terms as $term ) {
			$instance['category_id' . $term->term_id ] = absint( $new_instance['category_id' . $term->term_id ] );
		}

		return $instance;
	}
	
	function form( $instance ) {

		$instance = wp_parse_args( ( array ) $instance, self::$defaults );

		foreach( $this->post_formats as $post_format => $text ) {

			$display_format['display_format_' . $post_format ] = esc_attr( 'display_format_' . $post_format );

		}
		$instance = wp_parse_args( ( array ) $instance, $display_format );

		foreach( $this->terms as $term ) {
			$category_id['category_id' . $term->term_id ] = 0;
		}
		$instance = wp_parse_args( ( array ) $instance, $category_id );

		$title_popular = esc_attr( $instance['title_popular'] );
		$entry_count = absint( $instance['entry_count'] );
		$title_not_display = esc_attr( $instance['title_not_display'] );
		$excerpt_display = esc_attr( $instance['excerpt_display'] );
		?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_popular' ) ); ?>">
				<strong><?php esc_html_e( 'Title', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_popular' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_popular' ) ); ?>" type="text" value="<?php echo esc_attr( $title_popular ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_not_display' ) ); ?>">
				<strong><?php esc_html_e( 'Not Display the Title', ShapeShifter_Extensions::TEXTDOMAIN ); //タイトルを非表示（ヘッダー左上・ヘッダー右上の場合はチェック不要） ?></strong>
			</label>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'title_not_display' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title_not_display' ) ); ?>" value="title_not_display" <?php checked( $title_not_display, 'title_not_display' ); ?> style="width:0;"/>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'entry_count' ) ); ?>">
				<strong><?php esc_html_e( 'Num to display', ShapeShifter_Extensions::TEXTDOMAIN ); //表示件数 ?></strong>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'entry_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'entry_count' ) ); ?>" type="text" value="<?php echo esc_attr( $entry_count ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_display' ) ); ?>">
				<strong><?php esc_html_e( 'Display the Excerpt', ShapeShifter_Extensions::TEXTDOMAIN ); // 抜粋を表示 ?></strong>
			</label>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_display' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_display' ) ); ?>" value="excerpt_display" <?php checked( $excerpt_display, 'excerpt_display' ); ?> style="width:0;"/>
		</p>

		<p><strong><?php esc_html_e( 'Specify the Format to display', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong></p>

		<p>
			<?php foreach( $this->post_formats as $post_format => $text ) { ?>
				<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'display_format_' . $post_format ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'display_format_' . $post_format ) ); ?>" value="display_format_<?php echo esc_attr( $post_format ); ?>" <?php checked( esc_attr( $instance['display_format_' . $post_format ] ), 'display_format_' . $post_format ); ?> style="width:0;"/><span><?php echo esc_html( $text ); ?></span><br>
			<?php } ?>
		</p>
		
		<a id="popup-next-category-settings-box-<?php echo esc_attr( $this->number ); ?>" class="button popup-next-settings-box" href="javascript:void( 0 );"><?php esc_html_e( 'Specify the Categories to display', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>
		
		<div id="widget-category-settings-box-<?php echo esc_attr( $this->number ); ?>" class="widget-settings-box widget-vegas-background-images-settings-box">

			<p><strong><?php esc_html_e( 'Specify the Categories', ShapeShifter_Extensions::TEXTDOMAIN ); //カテゴリーを指定（未指定の場合は全カテゴリー） ?></strong></p>
			<?php
			foreach( $this->terms as $term ) {
				if ( $term->count > 0 ) {
					
					echo '<p>
						<label for="' . esc_attr( $this->get_field_id( 'category_id' . $term->term_id ) ) . '">
							' . esc_html( $term->name . sprintf( __( ' ( Links Num : %d )', ShapeShifter_Extensions::TEXTDOMAIN ), $term->count ) ) . '
						</label>
						<input 
							class="widefat" 
							type="checkbox"
							id="' . esc_attr( $this->get_field_name( 'category_id' . $term->term_id ) ) . '" 
							name="' . esc_attr( $this->get_field_name( 'category_id' . $term->term_id ) ) . '" 
							value="' . esc_attr( $term->term_id ) . '" 
							' . checked( intval( $instance['category_id' . $term->term_id ] ), $term->term_id, false ) . ' 
						/>
					</p>';

				}
			}
			?>

			<a id="close-category-settings-box-<?php echo esc_attr( $this->number ); ?>" class="button close-this-settings-box" href="javascript:void( 0 );"><?php esc_html_e( 'Close', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>

		</div>

		<?php

	}

}
?>