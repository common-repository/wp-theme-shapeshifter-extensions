<?php
class ShapeShifter_Entries_Channels extends SSE_Widget {	

	public static $defaults = array();

	private $terms = array();
	private $post_formats = array();
	
	function __construct() {

		self::$defaults = array(
			'title_switch' => esc_html__( 'ShapeShifter Channels', ShapeShifter_Extensions::TEXTDOMAIN ),
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
		parent::__construct( false, $name = esc_html__( 'ShapeShifter Channels ( includes New Related Popular Posts )', ShapeShifter_Extensions::TEXTDOMAIN ) );

	}
	
	function widget( $args, $instance ) {

		extract( $args );

		$entry_count = intval( isset( $instance['entry_count'] ) ? $instance['entry_count'] : 5 );

		$excerpt_display = ( 
			isset( $instance['excerpt_display'] ) 
				&& $instance['excerpt_display'] != '' 
			? true 
			: false 
		);

		$this->terms = get_terms( 'category' );

		# Post Formats
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
					$post_format_terms[ $post_format ] = 'post-format-' . $post_format;
				} else {
					$post_format_terms[ $post_format ] = '';
				}

			}
			$tax_query = $this->shapeshifter_get_tax_query_post_formats( $post_format_terms );

		# Categories
			$category_id = array();
			foreach( $this->terms as $term ) {
				if ( $term->count > 0 
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
		
		$title_switch = esc_html( strip_tags( $instance['title_switch'] ) );
		$title_not_display = ( ! empty( $instance['title_not_display'] ) ? true : false );
		if( ! $title_not_display ) {
			if( $title_switch != '' )
				echo $before_title . '<span class="title-switch">' . esc_html( $title_switch ) . '</span>' . $after_title; 
		} $title_not_display = $before_title = $title_switch = $after_title = null;
		?>

		<ul id="<?php echo esc_attr( $this->get_field_id( 'tab' ) ); ?>" class="metabox-tabs switch-select-tab-ul">
		
			<li id="<?php echo esc_attr( $this->get_field_id( 'tab_new_entries_1' ) ); ?>" class="tab switch-tab new_entries switch-select-tab-li" data-selected-name="new">
				<a id="<?php echo esc_attr( $this->get_field_id( 'tab_new_entries' ) ); ?>" class="switch-select-tab-li-a" href="javascript:void(0)"><?php esc_html_e( 'New Posts List', ShapeShifter_Extensions::TEXTDOMAIN ); //新着記事一覧 ?></a>
			</li>
			
			<?php if( is_single() ) { ?>
				<li id="<?php echo esc_attr( $this->get_field_id( 'tab_related_entries_2' ) ); ?>" class="tab switch-tab related_entries switch-select-tab-li" data-selected-name="related">
					<a id="<?php echo esc_attr( $this->get_field_id( 'tab_related_entries' ) ); ?>" class="switch-select-tab-li-a" href="javascript:void(0)"><?php esc_html_e( 'Related Posts List', ShapeShifter_Extensions::TEXTDOMAIN ); //関連記事一覧 ?></a>
				</li>
			<?php } ?>
				
			<li id="<?php echo esc_attr( $this->get_field_id( 'tab_popular_entries_3' ) ); ?>" class="tab switch-tab popular_entries switch-select-tab-li" data-selected-name="popular">
				<a id="<?php echo esc_attr( $this->get_field_id( 'tab_popular_entries' ) ); ?>" class="switch-select-tab-li-a" href="javascript:void(0)"><?php esc_html_e( 'Popular Posts List', ShapeShifter_Extensions::TEXTDOMAIN ); //人気記事一覧 ?></a>
			</li>
			
		</ul>
				
		<div id="<?php echo esc_attr( $this->get_field_id( 'new_entries' ) ); ?>" class="switch-selected-div switch-selected-div-new" style="display: block;">

			<ul>
				<?php 
				
				$widget_new_args = array(
					'posts_per_page' => $entry_count,
					'category__in' => ( isset( $category_id[ 0 ] ) ? $category_id : false ),
					'tax_query' => array( 
						$tax_query,
					)
				);
				
				$new_posts = get_posts( $widget_new_args ); $widget_new_args = null;
				foreach ( $new_posts as $post ) {

					if( $post->post_type !== 'post' ) continue;
					self::print_widget_entry_li( $post, 'new-entry', $excerpt_display, true );
				
				} wp_reset_postdata(); $new_posts = null; ?>
				
			</ul>

		</div>
		
		<div id="<?php echo esc_attr( $this->get_field_id( 'related_entries' ) ); ?>" class="switch-selected-div switch-selected-div-related" style="display: none;">
		
			<?php if( is_single() ) { ?>
			
				<ul>
				
				<?php 

					$loopCount = 0;
					global $post;

					$postID = $post->ID;

					$tags = wp_get_post_tags( $postID );
					$tag_IDs = array();
					foreach( $tags as $tag ) {
						array_push( $tag_IDs, $tag->term_id );
					} $tags = null;
					
					$args1 = array(
						'post__not_in' => array( $postID ),
						'posts_per_page' => $entry_count,
						'tag__in' => $tag_IDs,
						'orderby' => 'rand',
						'tax_query' => array( 
							$tax_query,
						)
					);
					$posts = get_posts( $args1 ); $args1 = null;
					foreach ( $posts as $post ) {

						if( $post->post_type !== 'post' ) continue;
						if( $loopCount >= $entry_count ) { 
							break;
						}
						self::print_widget_entry_li( $post, 'related-entry', $excerpt_display );
						$loopCount = $loopCount + 1;

					} $posts = null;

					if( $loopCount < $entry_count ) { 

						$categories = get_the_category( $postID );
						$category_ID = array();
						foreach( $categories as $category ) {
							array_push( $category_ID, $category->cat_ID );
						} $categories = null;

						$args2 = array(
							'post__not_in' => array( $postID ),
							'posts_per_page' => $entry_count - $loopCount,
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
							if( $loopCount >= $entry_count ) { 
								break;
							}
							self::print_widget_entry_li( $post, 'related-entry', $excerpt_display );
							$loopCount = $loopCount + 1;

						} $posts = null;

						if( $loopCount < $entry_count ) { 

							$args3 = array(
								'post__not_in' => array( $postID ),
								'posts_per_page' => $entry_count - $loopCount,
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
								if( $loopCount >= $entry_count ) { 
									break;
								}
								self::print_widget_entry_li( $post, 'related-entry', $excerpt_display );
								$loopCount = $loopCount + 1;
							} $posts = null;
						}
					} wp_reset_postdata(); 
					$postID = $tag_IDs = $category_ID = $loopCount  = null;
					?>

				</ul>
			
			<?php } else { ?>
				
				<p><?php esc_html_e( 'Not Post Page', ShapeShifter_Extensions::TEXTDOMAIN ); //投稿ページではありません。 ?></p>
			
			<?php } ?>
			
		</div>

		<div id="<?php echo esc_attr( $this->get_field_id( 'popular_entries' ) ); ?>" class="switch-selected-div switch-selected-div-popular" style="display: none;">
		
			<ul>
				<?php 
				
				$widget_new_args = array(
					'orderby' => 'meta_value_num',
					'category__in' => $category_id,
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
				); $entry_count = $category_id = $tax_query = null;
				
				$popular_posts = get_posts( $widget_new_args ); $widget_new_args = null;
				
				foreach ( $popular_posts as $post ) {

					if( $post->post_type !== 'post' ) continue;
					self::print_widget_entry_li( $post, 'popular-entry', $excerpt_display );

				} wp_reset_postdata(); 
				$popular_posts = null;

				?>
				
			</ul>			
		
		</div>
	 
		
		<?php 
		
		echo $after_widget; $after_widget = null;
		
	}

	function print_frontend_menu() {
		
		include( SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR . 'widgets/frontend/switch' );

	}

	function print_new_entry() {

	}
	
	function update( $new_instance, $old_instance ) {

		$new_instance = wp_parse_args( $new_instance, self::$defaults );
		$instance = $old_instance;

		$instance['title_switch'] = $instance['title'] = sanitize_text_field( strip_tags( $new_instance['title_switch'] ) );
		$instance['entry_count'] = intval( $new_instance['entry_count'] );
		$instance['title_not_display'] = sanitize_text_field( $new_instance['title_not_display'] );
		$instance['excerpt_display'] = sanitize_text_field( $new_instance['excerpt_display'] );

		foreach( $this->post_formats as $post_format => $text ) {
			$instance['display_format_' . $post_format ] = sanitize_text_field( strip_tags( $new_instance['display_format_' . $post_format ] ) );
		}

		foreach( $this->terms as $term ) {
			$instance['category_id' . $term->term_id ] = intval( $new_instance['category_id' . $term->term_id ] );
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

		$title_switch = esc_attr( $instance['title_switch'] );
		$entry_count = intval( $instance['entry_count'] );
		$title_not_display = esc_attr( $instance['title_not_display'] );
		$excerpt_display = esc_attr( $instance['excerpt_display'] );
		?>
		<p>
		<?php esc_html_e( 'This is a widget that can display the posts in 3 ways that can be switched by clicking tabs. Related Posts List is displayed only in posts page. FYI, This Widget is relying on JavaScript', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_switch' ) ); ?>">
				<strong><?php esc_html_e( 'Title', ShapeShifter_Extensions::TEXTDOMAIN );//タイトル ?></strong>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_switch' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_switch' ) ); ?>" type="text" value="<?php echo $title_switch; ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_not_display' ) ); ?>">
				<strong><?php esc_html_e( 'Not Display the Title', ShapeShifter_Extensions::TEXTDOMAIN );//タイトルを非表示（ヘッダー左上・ヘッダー右上の場合はチェック不要） ?></strong>
			</label>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'title_not_display' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title_not_display' ) ); ?>" value="title_not_display" <?php checked( $title_not_display, 'title_not_display' ); ?> style="width:0;"/>
		</p>
			
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'entry_count' ) ); ?>">
				<strong><?php esc_html_e( 'Num to display', ShapeShifter_Extensions::TEXTDOMAIN );//表示件数 ?></strong>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'entry_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'entry_count' ) ); ?>" type="text" value="<?php echo $entry_count; ?>" />
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

			<p><strong><?php esc_html_e( 'Specify the Categories to display', ShapeShifter_Extensions::TEXTDOMAIN );//カテゴリーを指定（未指定の場合は全カテゴリー） ?></strong></p>

			<?php
			foreach( $this->terms as $term ) {
				if ( $term->count > 0 ) {
					
					echo '<p>
						<label for="' . esc_attr( $this->get_field_id( 'category_id' . $term->term_id ) ) . '">
							' . esc_html( $term->name . sprintf( ( ' ( Links Num : %d )' ), $term->count ) ) . '
						</label>
						<input 
							class="widefat" 
							type="checkbox"
							id="' . esc_attr( $this->get_field_name( 'category_id'.$term->term_id ) ) . '" 
							name="' . esc_attr( $this->get_field_name( 'category_id'.$term->term_id ) ) . '" 
							value="' . esc_attr( $term->term_id ) . '" 
							' . checked( esc_attr( $instance['category_id' . $term->term_id ] ), $term->term_id, false ) . ' 
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