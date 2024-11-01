<?php

class ShapeShifter_Related_Entries extends SSE_Widget {

	public static $defaults = array();
	
	private $post_formats = array();

	function __construct() {

		self::$defaults = array(
			'title_related' => 'Related Entries',
			'related_entry_count' => 5,
			'title_not_display' => false,
			'excerpt_display' => false,
		);
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

		 parent::__construct( false, $name = esc_html__( 'ShapeShifter Related Posts List', ShapeShifter_Extensions::TEXTDOMAIN ) );//ShapeShifter 関連記事一覧
	}
	function widget( $args, $instance ) {
		
		if( ! is_single() ) {
			$args = $instance = null;
			return;
		} 
		
		extract( $args ); $args = null;

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

		echo $before_widget; $before_widget = null;

			$title_related = esc_html( strip_tags( $instance['title_related'] ) );
			$title_not_display = ( ! empty( $instance['title_not_display'] ) ? true : false );
			if( ! $title_not_display ) {
				if( $title_related != '' ) {
					echo $before_title . '<span class="title-related-entries">' . esc_html( $title_related ) . '</span>' . $after_title; 
				}
			} $title_not_display = $before_title = $title_related = $after_title = null;

			echo '<ul>';
				
				$loopCount = 0;
				
				global $post;
				$post_id = absint( $post->ID );
				
				$tags = wp_get_post_tags( $post_id );
				$tag_IDs = array();
				foreach( $tags as $tag ) {
					array_push( $tag_IDs, absint( $tag->term_id ) );
				}

				$related_entry_count = absint( $instance['related_entry_count'] );
				$related_entry_count = absint( $related_entry_count ? $related_entry_count : 5 );					
				$args1 = array(
					'post__not_in' => array( $post_id ),
					'posts_per_page' => $related_entry_count,
					'tag__in' => $tag_IDs,
					'orderby' => 'rand',
					'tax_query' => array( 
						$tax_query,
					)
				);
				$posts = get_posts( $args1 ); $args1 = null;

				$excerpt_display = shapeshifter_boolval( $instance['excerpt_display'] != '' ? true : false );
				foreach ( $posts as $post ) {
					if( $post->post_type !== 'post' ) continue;
					if( $loopCount >= $related_entry_count ){ 
						break;
					}
					self::print_widget_entry_li( $post, 'related-entry', $excerpt_display );

					$loopCount = $loopCount + 1; 
				} $posts = null;
				
				if( $loopCount < $related_entry_count ) { 
				
					$categories = get_the_category( $post_id );
					$category_ID = array();
					foreach( $categories as $category ) {
						array_push( $category_ID, $category->cat_ID);
					} $categories = null;

					$args2 = array(
						'post__not_in' => array( $post_id ),
						'posts_per_page' => absint( $related_entry_count - $loopCount ),
						'category__in' => $category_ID,
						'tag__not_in' => $tag_IDs,
						'orderby' => 'rand',
						'tax_query' => array( 
							$tax_query,
						)
					);
					$posts = get_posts( $args2 ); $args2 = null;

					foreach ( $posts as $post ) { 
						if( $post->post_type !== 'post' ) continue;
						if( $loopCount >= $related_entry_count ) { 
							break;
						}
						self::print_widget_entry_li( $post, 'related-entry', $excerpt_display );
					
						$loopCount = $loopCount + 1; 
					} $posts = null;

					if( $loopCount < $related_entry_count ) { 
					
						$args3 = array(
							'post__not_in' => array( $post_id ),
							'posts_per_page' => absint( $related_entry_count - $loopCount ),
							'category__not_in' => $category_ID,
							'tag__not_in' => $tag_IDs,
							'orderby' => 'rand',
							'tax_query' => array( 
								$tax_query,
							)
						);
						$posts = get_posts( $args3 ); $args3 = null;
						
						foreach ( $posts as $post ) { 
							if( $post->post_type !== 'post' ) continue;
							if( $loopCount >= $related_entry_count ) { 
								break;
							}
							self::print_widget_entry_li( $post, 'related-entry', $excerpt_display );
							$loopCount = $loopCount + 1; 
						} $posts = null;
					} 
				} wp_reset_postdata(); 
				$post_id = $category_ID = $tag_IDs = $loopCount = $excerpt_display = $related_entry_count = null; 
			echo '</ul>';
		echo $after_widget; $after_widget = null;
		
	}
	
	function update( $new_instance, $old_instance ) {

		$new_instance = wp_parse_args( $new_instance, self::$defaults );
		$instance = $old_instance;
		
		$instance['title_related'] = $instance['title'] = sanitize_text_field( strip_tags( $new_instance['title_related'] ) );
		$instance['related_entry_count'] = absint( $new_instance['related_entry_count'] );
		$instance['title_not_display'] = sanitize_text_field( $new_instance['title_not_display'] );
		$instance['excerpt_display'] = sanitize_text_field( $new_instance['excerpt_display'] );

		foreach( $this->post_formats as $post_format => $text ) {
			$instance['display_format_' . $post_format ] = sanitize_text_field( strip_tags( $new_instance['display_format_' . $post_format ] ) );
		}

		return $instance;

	}
	
	function form( $instance ) {

		$instance = wp_parse_args( ( array ) $instance, self::$defaults );

		foreach( $this->post_formats as $post_format => $text ) {

			$display_format['display_format_' . $post_format ] = esc_attr( 'display_format_' . $post_format );

		}
		$instance = wp_parse_args( ( array ) $instance, $display_format );

		$title_related = esc_attr( strip_tags( $instance['title_related'] ) );
		$related_entry_count = absint( $instance['related_entry_count'] );
		$title_not_display = esc_attr( $instance['title_not_display'] );
		$excerpt_display = esc_attr( $instance['excerpt_display'] );
		?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_related' ) ); ?>">
				<strong><?php esc_html_e( 'Title', ShapeShifter_Extensions::TEXTDOMAIN );//タイトル ?></strong>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_related' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_related' ) ); ?>" type="text" value="<?php echo esc_attr( $title_related ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_not_display' ) ); ?>">
				<strong><?php esc_html_e( 'Not Display the Title', ShapeShifter_Extensions::TEXTDOMAIN );//タイトルを非表示（ヘッダー左上・ヘッダー右上の場合はチェック不要） ?></strong>
			</label>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'title_not_display' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title_not_display' ) ); ?>" value="title_not_display" <?php checked( $title_not_display, 'title_not_display' ); ?> style="width:0;"/>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'related_entry_count' ) ); ?>">
				<strong><?php esc_html_e( 'Num to display', ShapeShifter_Extensions::TEXTDOMAIN );//表示件数 ?></strong>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'related_entry_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'related_entry_count' ) ); ?>" type="text" value="<?php echo esc_attr( $related_entry_count ? $related_entry_count : 5 ); ?>" />
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
		
		<?php

	}
}


?>