<?php
class ShapeShifter_SNS_Share_Buttons extends SSE_Widget {

	public static $defaults = array();

	function __construct() {
		self::$defaults = array(
			'title_sns_share_buttons' => esc_html__( 'SNS Share Buttons', ShapeShifter_Extensions::TEXTDOMAIN ),
			'widget_title_not_display' => false,
			'type_sns_share_buttons' => 'horizontal',
			'is_feed_buttons_on' => '',
		);
		parent::__construct( false, $name = esc_html__( 'ShapeShifter SNS Share Buttons', ShapeShifter_Extensions::TEXTDOMAIN ) );
	}

	function widget( $args, $instance ) {

		extract( $args ); $args = null;

		$widget_id = $this->get_field_id( 'sns-icons' );

		$type_sns_share_buttons = esc_attr( $instance['type_sns_share_buttons'] );

		$is_feed_buttons_on = shapeshifter_boolval( $instance['is_feed_buttons_on'] );

		global $post;
		global $wp_query;

		$class = 'sns-share-icon lazy';
		
		$urlEncodedTitle = urlencode( html_entity_decode( trim( wp_title( '', false ) ) ) );
		
		# for Twitter
			if( is_singular() ) {
				global $post;
				$tUrlEncodedTitle = urlencode( trim( esc_html( get_the_title( $post->ID ) . ' #' . get_bloginfo( 'name' ) ) ) );
			} 
			if ( ! isset( $tUrlEncodedTitle ) ) {
				$tUrlEncodedTitle = $urlEncodedTitle;
			}


		echo $before_widget; $before_widget = null;

		$title_sns_share_buttons = esc_html( strip_tags( $instance['title_sns_share_buttons'] ) );
		$widget_title_not_display = ( ! empty( $instance['widget_title_not_display'] ) ? true : false );
		if( ! $widget_title_not_display ) {
			if( $title_sns_share_buttons !== '' )
				echo $before_title . '<span class="title-sns-share-icons">' . $title_sns_share_buttons . '</span>' . $after_title;
		} $widget_title_not_display = $before_title = $title_sns_share_buttons = $after_title = null;

		echo '<div id="' . esc_attr( $this->get_field_id( 'wrapper' ) ) . '" class="sns-share-buttons-wrapper sns-share-buttons-wrapper-' . esc_attr( $type_sns_share_buttons ) . '">';

		?>

			<!-- シェアボタンに変換される -->
			<ul class="sns-standard-share-buttons">
				<li class="twitter-standard-share-button">
					<a class="twitter-share-button" href="https://twitter.com/share"
						data-size="<?php echo esc_attr( $type_sns_share_buttons == 'vertical'
							? 'large'
							: 'default'
						); ?>" 
						data-dnt="true"
						data-text="<?php echo esc_attr( rawurldecode( $tUrlEncodedTitle ) ); ?>"
						data-count="<?php echo esc_attr( 
							$type_sns_share_buttons == 'horizontal'
							? 'horizontal' 
							: ( 
								$type_sns_share_buttons == 'vertical'
								? 'vertical'
								: 'none'
							) 
						); ?>"
					>Tweet</a>
				</li>

				<li class="facebook-standard-share-button">
					<div class="fb-like" 
						data-layout="<?php echo esc_attr( 
							$type_sns_share_buttons == 'horizontal'
							? 'button_count' 
							: ( 
								$type_sns_share_buttons == 'vertical'
								? 'box_count'
								: 'button'
							) 
						); ?>"
					></div>
				</li>

				<li class="googleplus-standard-share-button">
					<div class="g-plus" data-action="share" 
						data-annotation="<?php echo esc_attr( 
							$type_sns_share_buttons == 'horizontal'
							? 'bubble' 
							: ( $type_sns_share_buttons == 'vertical'
								? 'vertical-bubble'
								: 'none'
							) 
						); ?>" 
						data-height="<?php echo esc_attr( 
							$type_sns_share_buttons == 'horizontal'
							? 20
							: ( $type_sns_share_buttons == 'vertical'
								? 60
								: 20
							) 
						); ?>"
					></div>
				</li>

			</ul>

			<ul class="sns-standard-share-buttons">
				<li class="hatena-standard-share-button" style="width: <?php echo esc_attr( 
					$type_sns_share_buttons == 'horizontal'
					? '' 
					: ( $type_sns_share_buttons == 'vertical'
						? ''
						: 80 . 'px'
					) 
				); ?>; padding-top:1px;"
				>
					<a href="https://b.hatena.ne.jp/entry/" class="hatena-bookmark-button" 
						data-hatena-bookmark-layout="<?php echo esc_attr( 
							$type_sns_share_buttons == 'horizontal'
							? 'standard-balloon' 
							: ( $type_sns_share_buttons == 'vertical'
								? 'vertical-balloon'
								: 'standard-noballoon'
							) 
						); ?>" 
						data-hatena-bookmark-lang="ja" 
						title="このエントリーをはてなブックマークに追加"
					>
						<img src="<?php echo esc_url( SSE_ASSETS_URL ); ?>images/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" />
					</a>
				</li>

				<li class="pocket-standard-share-button" 
					style="width:<?php echo esc_attr( 
						$type_sns_share_buttons == 'horizontal'
						? 100 . 'px' 
						: ( $type_sns_share_buttons == 'vertical'
							? 70 . 'px'
							: 85 . 'px'
						) 
					); ?>;<?php echo esc_attr( 
						$type_sns_share_buttons == 'vertical' 
						? 'margin-left: -15px; margin-right: 15px;' 
						: '' 
					); ?>"
				>
					<a class="pocket-btn" 
						data-pocket-label="pocket" 
						data-pocket-count="<?php echo esc_attr( 
							$type_sns_share_buttons == 'horizontal'
							? 'horizontal' 
							: ( $type_sns_share_buttons == 'vertical'
								? 'vertical'
								: 'none'
							) 
						); ?>" 
						data-lang="en"
						style="width:90px;"
					></a>
				</li>

				<li class="line-standard-share-button" 
					style="padding-top: <?php echo esc_attr( 
						$type_sns_share_buttons == 'vertical'
						? ''
						: 4 . 'px'
					); ?>;"
				>
					<a 
						href="https://line.me/R/msg/text/?<?php echo rawurlencode( esc_html( get_the_title( $post->ID ) ) ); ?>%0D%0A<?php echo ( 
							is_home() || is_front_page() 
							? esc_url( home_url() ) 
							: ( is_singular() 
								? esc_url( get_the_permalink() ) 
								: ''
								)
						); ?>"
					>
						<img 
							src="<?php echo esc_url( 
								$type_sns_share_buttons == 'horizontal'
								? SSE_ASSETS_URL . 'images/linebutton/linebutton_82x20.png' 
								: ( 
									$type_sns_share_buttons == 'vertical'
									? SSE_ASSETS_URL . 'images/linebutton/linebutton_36x60.png'
									: SSE_ASSETS_URL . 'images/linebutton/linebutton_82x20.png'
								) 
							); ?>" 
							width="<?php echo esc_attr( 
								$type_sns_share_buttons == 'horizontal'
								? 82 
								: ( 
									$type_sns_share_buttons == 'vertical'
									? 36
									: 82
								) 
							); ?>" 
							height="<?php echo esc_attr( 
								$type_sns_share_buttons == 'horizontal'
								? 20 
								: ( $type_sns_share_buttons == 'vertical'
									? 60
									: 20
								) 
							); ?>" 
							alt="LINEで送る" 
						/>
					</a>
				</li>
			</ul>

			<?php if( $is_feed_buttons_on ) { ?>
				<ul class="sns-standard-share-buttons">
					<li class="feedly-standard-share-button" 
						style="padding-top: <?php echo esc_attr( 
							$type_sns_share_buttons == 'vertical'
							? ''
							: 4 . 'px'
						); ?>;"
					>
						<a href='https://cloud.feedly.com/#subscription%2Ffeed%2Fhttp%3A%2F%2F<?php echo urlencode( esc_url( get_bloginfo( 'rss2_url' ) ) ); ?>' 
							style="width: <?php echo esc_attr( $type_sns_share_buttons == 'none' 
								? 60 . 'px'
								: ''
							); ?>;"
							target="_blank"
						>
							<?php echo ( 
								$type_sns_share_buttons == 'horizontal'
								? '<img id="feedlyFollow" src="https://s3.feedly.com/img/follows/feedly-follow-rectangle-flat-small_2x.png" alt="follow us in feedly" width="66" height="20" />'
								: ( 
									$type_sns_share_buttons == 'vertical'
									? '<img id="feedlyFollow" src="https://s3.feedly.com/img/follows/feedly-follow-rectangle-flat-medium_2x.png" alt="follow us in feedly" width="71" height="28" />'
									: '<img id="feedlyFollow" src="https://s3.feedly.com/img/follows/feedly-follow-rectangle-flat-small_2x.png" alt="follow us in feedly" width="66" height="20" />'
								) 
							); ?>
						</a>
					</li>

					<li class="rss-standard-share-button">
						<a href="<?php echo esc_url( get_bloginfo( 'rss2_url' ) ); ?>"
							style="color: #FFF;"
						>
							<img alt="rss" src="<?php echo esc_url( SSE_ASSETS_URL . 'images/rss-40674.svg' ); ?>" width="50" height="20"/>
						</a>
					</li>
				</ul>
			<?php } $type_sns_share_buttons = $is_feed_buttons_on = null;
		echo '</div>';

		echo $after_widget; $after_widget = null;

		?>

	<?php }
	
	function update( $new_instance, $old_instance ) {

		$new_instance = wp_parse_args( $new_instance, self::$defaults );
		$instance = $old_instance;

		$instance['title_sns_share_buttons'] = $instance['title'] = sanitize_text_field( strip_tags( $new_instance['title_sns_share_buttons'] ) );
		$instance['widget_title_not_display'] = sanitize_text_field( strip_tags( $new_instance['widget_title_not_display'] ) );
		$instance['type_sns_share_buttons'] = sanitize_text_field( strip_tags( $new_instance['type_sns_share_buttons'] ) );
		$instance['is_feed_buttons_on'] = sanitize_text_field( strip_tags( $new_instance['is_feed_buttons_on'] ) );

		return $instance;
	}
	
	function form( $instance ) {

		$instance = wp_parse_args( ( array ) $instance, self::$defaults );
		
		$title_sns_share_buttons = esc_attr( $instance['title_sns_share_buttons'] );
		$widget_title_not_display = esc_attr( $instance['widget_title_not_display'] );
		$type_sns_share_buttons = esc_attr( $instance['type_sns_share_buttons'] );
		$is_feed_buttons_on = esc_attr( $instance['is_feed_buttons_on'] );
		?>
		
		<p><small>
			<?php printf( wp_kses( 
				__( 'This Widget use offcial JS codes. So This Requires JS SDK with Facebook APP Account. If you don\'t have any, please go get JS-SDK codes to "<a rel="nofollow" target="_blank" href="https://developers.facebook.com/docs/plugins/like-button">Like Button for the Web</a>" and paste it in textarea on "Print in header" of Tab "Auto Insert" in Admin page "<a rel="nofollow" target="_blank" href="%s">Theme Settings</a>"', ShapeShifter_Extensions::TEXTDOMAIN ),
				array(
					'a' => array( 'rel', 'target', 'href' )
				)
			), esc_url( admin_url( 'themes.php?page=theme_settings_menu' ) ) );
		?>
		</small></p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_sns_share_buttons' ) ); ?>">
				<strong><?php esc_html_e( 'Title', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'title_sns_share_buttons' ) ); ?>" 
				class="widefat" 
				name="<?php echo esc_attr( $this->get_field_name( 'title_sns_share_buttons' ) ); ?>" 
				type="text" 
				value="<?php echo esc_attr( $title_sns_share_buttons ); ?>"
			/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_title_not_display' ) ); ?>">
				<strong><?php esc_html_e( 'Not Display the Title', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'widget_title_not_display' ) ); ?>" 
				class="regular-checkbox"
				name="<?php echo esc_attr( $this->get_field_name( 'widget_title_not_display' ) ); ?>"
				type="checkbox"
				value="widget_title_not_display"
				<?php checked( $widget_title_not_display, 'widget_title_not_display' ); ?>
			/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type_sns_share_buttons' ) ); ?>">
				<strong><?php esc_html_e( 'Type to display', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<select 
				id="<?php echo esc_attr( $this->get_field_id( 'type_sns_share_buttons' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'type_sns_share_buttons' ) ); ?>"
			>
				<option value="horizontal" <?php selected( $type_sns_share_buttons, 'horizontal' ); ?>><?php esc_html_e( 'Horizontal', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
				<option value="vertical" <?php selected( $type_sns_share_buttons, 'vertical' ); ?>><?php esc_html_e( 'Vertical', ShapeShifter_Extensions::TEXTDOMAIN );//バルーン ?></option>
				<option value="none" <?php selected( $type_sns_share_buttons, 'none' ); ?>><?php esc_html_e( 'No Counts', ShapeShifter_Extensions::TEXTDOMAIN );//カウント無し ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'is_feed_buttons_on' ) ); ?>">
				<strong><?php esc_html_e( 'Display Feed Links ( Feedly and RSS )', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'is_feed_buttons_on' ) ); ?>" 
				class="regular-checkbox"
				name="<?php echo esc_attr( $this->get_field_name( 'is_feed_buttons_on' ) ); ?>"
				type="checkbox"
				value="is_feed_buttons_on"
				<?php checked( $is_feed_buttons_on, 'is_feed_buttons_on' ); ?>
			/>
		</p>

		<?php 
	}

}
?>