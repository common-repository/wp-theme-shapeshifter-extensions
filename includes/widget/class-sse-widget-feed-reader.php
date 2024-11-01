<?php
class ShapeShifter_Feed_Reader extends SSE_Widget {	

	public static $defaults = array();
	
	function __construct() {
		self::$defaults = array(
			'title_feed' => esc_html__( 'Feed Reader', ShapeShifter_Extensions::TEXTDOMAIN ),
			'feed_count' => 5,
			'feed_url' => '',
			'title_not_display' => false
		);
		 parent::__construct( false, $name = esc_html__( 'ShapeShifter Feed Reader with thumbnail', ShapeShifter_Extensions::TEXTDOMAIN ) );//ShapeShifter イメージ付きフィードリーダー
	}

	function widget( $args, $instance ) {

		$feed_url = esc_attr( ! isset( $instance['feed_url'] ) || empty( $instance['feed_url'] ) 
			? false
			: $instance['feed_url']
		);
		if( $feed_url === false ) {
			$feed_url = $args = $instance = null;
			return;
		}

		$feed_count = intval( isset( $instance['feed_count'] ) ? $instance['feed_count'] : 5 );

		$rss = fetch_feed( $feed_url );

		$maxitems = 0;
		if ( ! is_wp_error( $rss ) ) {
			$maxitems = $rss->get_item_quantity( $feed_count );
			$rss_items = $rss->get_items( 0, $maxitems );
		} else {
			$args = $instance = null;
			return;
		}

		extract( $args ); $args = null;

		if ( $maxitems >= 1  ) { 

			echo $before_widget; $before_widget = null;

			$title_feed = esc_html( strip_tags( $instance['title_feed'] ) );
			$title_not_display = ( ! empty( $instance['title_not_display'] ) ? true : false );
			if( ! $title_not_display ) {
				if( mb_strlen( $title_feed ) > 0 )
					echo $before_title . '<span class="title-new-entries">' . $title_feed . '</span>' . $after_title; 
			} $title_not_display = $before_title = $title_feed = $after_title = null;

			?>
				
			<ul>
				<?php foreach ( $rss_items as $item ) { ?>
					<li class="feed">
						<a class="feed-a" href="<?php echo esc_url( $item->get_permalink() ); ?>" style="width: 100%; height: 100px;">
							<div class="feed-thumbnail-div">
								<?php
								$first_img = '';
								$div_width = '100px';
								$div_height = '100px';
								
								// 記事中の1枚目の画像を取得
								if( preg_match( 
									'/<img[^>]+?src\s*=\s*[\'"]([^\'"]+)[\'"][^>]*>/i', 
									$item->get_content(), 
									$matches 
								) ) {
									$first_img = esc_url( $matches[ 1 ] );
								}
								echo $this->shapeshifter_get_default_thumbnail_div_tag( 'widget-entry-thumbnail-img feed-thumbnail-img', array( 'width' => '80px', 'height' => '80px' ), $first_img );

								?>
							</div>
											
							<div class="feed-title">
								<span><?php echo esc_html( $item->get_title() ); ?></span>
							</div>
							<div class="feed-excerpt widget-entry-excerpt"><?php echo esc_html( wp_strip_all_tags( sse_get_the_excerpt( $item->get_content(), 100 ) ) ); ?></div>
						</a>
					</li>
				
				<?php 
				} $rss_items = $first_img = $div_width = $div_height = null;
				?>
				
			</ul>
			<?php 
			echo $after_widget; $after_widget = null;
		} 
		
	}
	
	function update( $new_instance, $old_instance ) {

		$new_instance = wp_parse_args( $new_instance, self::$defaults );
		$instance = $old_instance;

		$instance['title_feed'] = $instance['title'] = sanitize_text_field( strip_tags( $new_instance['title_feed'] ) );
		$instance['feed_count'] = intval( $new_instance['feed_count'] );
		$instance['feed_url'] = esc_url_raw( strip_tags( $new_instance['feed_url'] ) );
		$instance['title_not_display'] = sanitize_text_field( strip_tags( $new_instance['title_not_display'] ) );

		return $instance;

	}
	
	function form( $instance ) {
		
		$instance = wp_parse_args( ( array ) $instance, self::$defaults );
		$title_feed = esc_attr( $instance['title_feed'] );
		$feed_count = intval( $instance['feed_count'] );
		$feed_url = esc_url( $instance['feed_url'] );
		$title_not_display = esc_attr( $instance['title_not_display'] );

		?>
		<p>
		<?php esc_html_e( "If the post doesn't include images( this means IMG tags ), default thumbnail that is set by theme customizer will be applied.", ShapeShifter_Extensions::TEXTDOMAIN ); ?>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_feed' ) ); ?>">
				<strong><?php esc_html_e( 'Title', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_feed' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_feed' ) ); ?>" type="text" value="<?php echo esc_attr( $title_feed ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_not_display' ) ); ?>">
				<strong><?php esc_html_e( 'Not Display the Title', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'title_not_display' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title_not_display' ) ); ?>" value="title_not_display" <?php checked( $title_not_display, 'title_not_display' ); ?> style="width:0;"/>
		</p>		
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'feed_count' ) ); ?>">
				<strong><?php esc_html_e( 'Num to display', ShapeShifter_Extensions::TEXTDOMAIN );//表示件数 ?></strong>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'feed_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'feed_count' ) ); ?>" type="text" value="<?php echo esc_attr( $feed_count ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'feed_url' ) ); ?>">
				<strong>Feed URL</strong>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'feed_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'feed_url' ) ); ?>" type="url" value="<?php echo esc_url( $feed_url ); ?>" />
		</p>
		
		<?php

	}

}
?>