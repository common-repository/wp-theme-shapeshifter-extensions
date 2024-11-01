<?php
class ShapeShifter_SNS_Share_Icons extends SSE_Widget {

	public static $defaults = array();
	
	function __construct() {
		self::$defaults = array(
			'title_sns_share_icons' => esc_html__( 'SNS Share Icons', ShapeShifter_Extensions::TEXTDOMAIN ),
			'separation_sns_share_icons_num' => 4,
			'type_sns_share_icons' => 'carre',
		);
		parent::__construct( false, $name = esc_html__( 'ShapeShifter SNS Share Icons', ShapeShifter_Extensions::TEXTDOMAIN ) );
	}

	function widget( $args, $instance ) {

		$permalink_url = sse_get_current_url_by_query_for_sns_share();
		if( $permalink_url == '' ) {
			return;
		}

		extract( $args ); $args = null;

		$widget_id = $this->get_field_id( 'sns-icons' );

		$type_sns_share_icons = esc_attr( $instance['type_sns_share_icons'] );
		$select_sns_share_icons = array(
			'twitter' => esc_attr( $instance['select_sns_share_icons_twitter'] ),
			'facebook' => esc_attr( $instance['select_sns_share_icons_facebook'] ),
			'googleplus' => esc_attr( $instance['select_sns_share_icons_googleplus'] ),
			'hatena' => esc_attr( $instance['select_sns_share_icons_hatena'] ),
			'pocket' => esc_attr( $instance['select_sns_share_icons_pocket'] ),
			'line' => esc_attr( $instance['select_sns_share_icons_line'] ),
			'feedly' => esc_attr( $instance['select_sns_share_icons_feedly'] ),
			'rss' => esc_attr( $instance['select_sns_share_icons_rss'] )
		);

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

		$sns_data = array(
			'twitter' => array(
				'class-prefix' => 'twitter',
				'href' => 'https://twitter.com/share?original_referer=' . $permalink_url . '&amp;text=' . ( $tUrlEncodedTitle ) . '&amp;tw_p=tweetbutton&amp;url=' . $permalink_url,
				'title' => 'twitter',
			),

			'facebook' => array(
				'class-prefix' => 'facebook',
				'href' => 'https://www.facebook.com/sharer/sharer.php?u=' . $permalink_url . '&amp;display=popup&amp;t=' . $urlEncodedTitle,
				'title' => 'facebook',
			),

			'googleplus' => array(
				'class-prefix' => 'google-plus',
				'href' => 'https://plus.google.com/share?url=' . $permalink_url,
				'title' => 'google-plus',
			),

			'hatena' => array(
				'class-prefix' => 'hatena',
				'href' => 'https://b.hatena.ne.jp/add?mode=confirm&amp;url=' . $permalink_url . '&amp;title=' . $urlEncodedTitle,
				'title' => 'hatenabookmark',
			),

			'pocket' => array(
				'class-prefix' => 'pocket',
				'href' => 'https://getpocket.com/edit?url=' . $permalink_url . '&amp;title=' . $urlEncodedTitle,
				'title' => 'pocket',
			),

			'line' => array(
				'class-prefix' => 'line',
				'href' => 'https://line.naver.jp/R/msg/text/?' . $urlEncodedTitle . '%0D%0A' . $permalink_url,
				'title' => 'line',
			),

			'feedly' => array(
				'class-prefix' => 'feedly',
				'href' => 'https://feedly.com/index.html#subscription%2Ffeed%2F' . urlencode( esc_url( get_bloginfo( 'rss2_url' ) ) ),
				'title' => 'feedly',
			),

			'rss' => array(
				'class-prefix' => 'rss',
				'href' => esc_url( SITE_URL . '/feed' ),
				'title' => 'rss',
			),

		); $permalink_url = $urlEncodedTitle = null;

		if( $type_sns_share_icons == 'carre' 
			|| $type_sns_share_icons == 'carre-petit' 
			|| $type_sns_share_icons == 'rond' 
			|| $type_sns_share_icons == 'rond-petit' 
		) {

			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			// IEの場合
			if( strstr( $user_agent, 'Trident' ) || strstr( $user_agent, 'MSIE' ) ) { return; } $user_agent = null;
			
			$sns_data['twitter']['text'] = '<i class="fa fa-twitter"></i>';

			$sns_data['facebook']['text'] = '<i class="fa fa-facebook"></i>';

			$sns_data['googleplus']['text'] = '<i class="fa fa-google-plus"></i>';

			$sns_data['hatena']['text'] = 'B!';

			$sns_data['pocket']['text'] = '<i class="icon-pocket"></i>';

			$sns_data['line']['text'] = '<i class="icon-line"></i>';

			$sns_data['feedly']['text'] = '<i class="icon-feedly"></i>';

			$sns_data['rss']['text'] = '<i class="fa fa-rss"></i>';

		} elseif( $type_sns_share_icons == 'button' ) {

			$sns_data['twitter']['text'] = 'Twitter';

			$sns_data['facebook']['text'] = 'Facebook';

			$sns_data['googleplus']['text'] = 'Google+';

			$sns_data['hatena']['text'] = 'Hatena';

			$sns_data['pocket']['text'] = 'Pocket';

			$sns_data['line']['text'] = 'LINE';

			$sns_data['feedly']['text'] = 'Feedly';

			$sns_data['rss']['text'] = 'RSS';

		} else {

			$sns_data['twitter']['text'] = '<i class="fa fa-twitter"></i>';

			$sns_data['facebook']['text'] = '<i class="fa fa-facebook"></i>';

			$sns_data['googleplus']['text'] = '<i class="fa fa-google-plus"></i>';

			$sns_data['hatena']['text'] = '<span style="font-family: sans-serif;">B!</span>';

			$sns_data['pocket']['text'] = '<i class="icon-pocket"></i>';

			$sns_data['line']['text'] = '<i class="icon-line"></i>';

			$sns_data['feedly']['text'] = '<i class="icon-feedly"></i>';

			$sns_data['rss']['text'] = '<i class="fa fa-rss"></i>';

		}

		$output = $before_widget; $before_widget = null;

		$title_sns_share_icons = esc_html( strip_tags( $instance['title_sns_share_icons'] ) );
		if( $title_sns_share_icons != '' ) {
			$output .= $before_title . '<span class="title-sns-share-icons">' . $title_sns_share_icons . '</span>' . $after_title;
		} $before_title = $title_sns_share_icons = $after_title = null;

		$output .= '<div id="' . esc_attr( $widget_id ) . '" class="widget-sns-share-icons-' . esc_attr( $type_sns_share_icons ) . '">';
			$output .= '<ul id="' . esc_attr( $widget_id ) . '-ul" class="widget-sns-share-icons-ul-' . esc_attr( $type_sns_share_icons ) . ' clearfix">';
			$count = 1;
			$separation_sns_share_icons_num = intval( $instance['separation_sns_share_icons_num'] );
			foreach( $sns_data as $key => $val ) {
				if( $select_sns_share_icons[ $key ] ) {

					$output .= '<li id="' . esc_attr( $widget_id ) . '-' . esc_attr( $val['class-prefix'] ) . '-li-' . esc_attr( $type_sns_share_icons ) . '" ';
						$output .= 'class="widget-sns-share-icons-li-' . esc_attr( $type_sns_share_icons ) . ' ' . esc_attr( $val['class-prefix'] ) . '-icon-in-widget-li-' . esc_attr( $type_sns_share_icons ) . '" ';
					$output .= '>';
						$output .= '<p id="' . esc_attr( $widget_id ) . '-' . esc_attr( $val['class-prefix'] ) . '-li-p-' . esc_attr( $type_sns_share_icons ) . '" ';
							$output .= 'class="widget-sns-share-icons-li-p-' . esc_attr( $type_sns_share_icons ) . ' ' . esc_attr( $val['class-prefix'] ) . '-icon-in-widget-li-p-' . esc_attr( $type_sns_share_icons ) . '" ';
						$output .= '>';
							$output .= '<a href="' . esc_attr( $val['href'] ) . '" ';
								$output .= 'id="' . esc_attr( $widget_id ) . '-' . esc_attr( $val['class-prefix'] ) . '-li-p-a-' . esc_attr( $type_sns_share_icons ) . '" ';
								$output .= 'class="widget-sns-share-icons-li-p-a-' . esc_attr( $type_sns_share_icons ) . ' ' . esc_attr( $val['class-prefix'] ) . '-icon-in-widget-li-p-a-' . esc_attr( $type_sns_share_icons ) . '" ';
								$output .= 'title="' . esc_attr( $val['title'] ) . '" ';
								$output .= 'rel="nofollow" ';
								$output .= 'target="_blank" ';
							$output .= '>';
								$output .= $val['text'];
							$output .= '</a>';
						$output .= '</p>';
					$output .= '</li>';
					if( $count % $separation_sns_share_icons_num == 0 ) {
						$output .= '</ul><ul id="' . esc_attr( $widget_id ) . '-ul-' . $count . '" class="widget-sns-share-icons-ul-' . esc_attr( $type_sns_share_icons ) . ' clearfix">';
					}
					$count++;
				}
			} $separation_sns_share_icons_num = $sns_data = $widget_id = $type_sns_share_icons = $count = null;
			$output .= '</ul>';
		$output .= '</div><div class="clearfix"></div>'; 

		echo $output; $output = null; 
		echo $after_widget; $after_widget = null;
		
	}
	
	function update( $new_instance, $old_instance ) {

		$new_instance = wp_parse_args( $new_instance, self::$defaults );
		$instance = $old_instance;

		$instance['title_sns_share_icons'] = $instance['title'] = sanitize_text_field( strip_tags( $new_instance['title_sns_share_icons'] ) );
		$instance['separation_sns_share_icons_num'] = absint( $new_instance['separation_sns_share_icons_num'] );
		$instance['type_sns_share_icons'] = sanitize_text_field( strip_tags( $new_instance['type_sns_share_icons'] ) );
		$instance['select_sns_share_icons_twitter'] = sanitize_text_field( strip_tags( isset( $new_instance['select_sns_share_icons_twitter'] ) ? $new_instance['select_sns_share_icons_twitter'] : '' ) );
		$instance['select_sns_share_icons_facebook'] = sanitize_text_field( strip_tags( isset( $new_instance['select_sns_share_icons_facebook'] ) ? $new_instance['select_sns_share_icons_facebook'] : '' ) );
		$instance['select_sns_share_icons_googleplus'] = sanitize_text_field( strip_tags( isset( $new_instance['select_sns_share_icons_googleplus'] ) ? $new_instance['select_sns_share_icons_googleplus'] : '' ) );
		$instance['select_sns_share_icons_hatena'] = sanitize_text_field( strip_tags( isset( $new_instance['select_sns_share_icons_hatena'] ) ? $new_instance['select_sns_share_icons_hatena'] : '' ) );
		$instance['select_sns_share_icons_pocket'] = sanitize_text_field( strip_tags( isset( $new_instance['select_sns_share_icons_pocket'] ) ? $new_instance['select_sns_share_icons_pocket'] : '' ) );
		$instance['select_sns_share_icons_line'] = sanitize_text_field( strip_tags( isset( $new_instance['select_sns_share_icons_line'] ) ? $new_instance['select_sns_share_icons_line'] : '' ) );
		$instance['select_sns_share_icons_feedly'] = sanitize_text_field( strip_tags( isset( $new_instance['select_sns_share_icons_feedly'] ) ? $new_instance['select_sns_share_icons_feedly'] : '' ) );
		$instance['select_sns_share_icons_rss'] = sanitize_text_field( strip_tags( isset( $new_instance['select_sns_share_icons_rss'] ) ? $new_instance['select_sns_share_icons_rss'] : '' ) );

		return $instance;
	}
	
	function form( $instance ) {

		$instance = wp_parse_args( ( array ) $instance, self::$defaults );
		
		$title_sns_share_icons = esc_attr( $instance['title_sns_share_icons'] );
		$separation_sns_share_icons_num = absint( $instance['separation_sns_share_icons_num'] );
		$type_sns_share_icons = esc_attr( $instance['type_sns_share_icons'] );
		$select_sns_share_icons = array (
			'twitter' => esc_attr( isset( $instance['select_sns_share_icons_twitter'] ) ? $instance['select_sns_share_icons_twitter'] : '' ),
			'facebook' => esc_attr( isset( $instance['select_sns_share_icons_facebook'] ) ? $instance['select_sns_share_icons_facebook'] : '' ),
			'googleplus' => esc_attr( isset( $instance['select_sns_share_icons_googleplus'] ) ? $instance['select_sns_share_icons_googleplus'] : '' ),
			'hatena' => esc_attr( isset( $instance['select_sns_share_icons_hatena'] ) ? $instance['select_sns_share_icons_hatena'] : '' ),
			'pocket' => esc_attr( isset( $instance['select_sns_share_icons_pocket'] ) ? $instance['select_sns_share_icons_pocket'] : '' ),
			'line' => esc_attr( isset( $instance['select_sns_share_icons_line'] ) ? $instance['select_sns_share_icons_line'] : '' ),
			'feedly' => esc_attr( isset( $instance['select_sns_share_icons_feedly'] ) ? $instance['select_sns_share_icons_feedly'] : '' ),
			'rss' => esc_attr( isset( $instance['select_sns_share_icons_rss'] ) ? $instance['select_sns_share_icons_rss'] : '' ),
		);
		?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_sns_share_icons' ) ); ?>">
				<strong><?php esc_html_e( 'Title', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'title_sns_share_icons' ) ); ?>" 
				class="widefat" 
				name="<?php echo esc_attr( $this->get_field_name( 'title_sns_share_icons' ) ); ?>" 
				type="text" 
				value="<?php echo esc_attr( $title_sns_share_icons ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'separation_sns_share_icons_num' ) ); ?>">
				<strong><?php esc_html_e( 'Num of Icons on a line', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'separation_sns_share_icons_num' ) ); ?>" 
				class="widefat" 
				name="<?php echo esc_attr( $this->get_field_name( 'separation_sns_share_icons_num' ) ); ?>" 
				type="text" 
				value="<?php echo esc_attr( $separation_sns_share_icons_num ); ?>"
			 />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type_sns_share_icons' ) ); ?>">
				<strong><?php esc_html_e( 'type to display', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<select 
				id="<?php echo esc_attr( $this->get_field_id( 'type_sns_share_icons' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'type_sns_share_icons' ) ); ?>"
			>
				<option value="carre" <?php selected( $type_sns_share_icons, 'carre' ); ?>><?php esc_html_e( 'Squares', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
				<option value="carre-petit" <?php selected( $type_sns_share_icons, 'carre-petit' ); ?>><?php esc_html_e( 'Small Squares', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
				<option value="rond" <?php selected( $type_sns_share_icons, 'rond' ); ?>><?php esc_html_e( 'Circles', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
				<option value="rond-petit" <?php selected( $type_sns_share_icons, 'rond-petit' ); ?>><?php esc_html_e( 'Small Circles', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
				<option value="button" <?php selected( $type_sns_share_icons, 'button' ); ?>><?php esc_html_e( 'Texts Buttons', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
				<option value="icon" <?php selected( $type_sns_share_icons, 'icon' ); ?>><?php esc_html_e( 'Icons', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_twitter' ) ); ?>">
				<strong><?php esc_html_e( 'Display the Twitter Icon', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_twitter' ) ); ?>" 
				class="widefat" 
				name="<?php echo esc_attr( $this->get_field_name( 'select_sns_share_icons_twitter' ) ); ?>" 
				type="checkbox" 
				value="twitter" 
				<?php checked( $select_sns_share_icons['twitter'], 'twitter' ); ?>
				style="width:0;"
			 /><br>
			 
			<label for="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_twitter' ) ); ?>">
				<strong><?php esc_html_e( 'Display the Facebook Icon', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_facebook' ) ); ?>" 
				class="widefat" 
				name="<?php echo esc_attr( $this->get_field_name( 'select_sns_share_icons_facebook' ) ); ?>" 
				type="checkbox" 
				value="facebook" 
				<?php checked( $select_sns_share_icons['facebook'], 'facebook' ); ?>
				style="width:0;"
			 /><br>
			 
			<label for="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_googleplus' ) ); ?>">
				<strong><?php esc_html_e( 'Display the Google+ Icon', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_googleplus' ) ); ?>" 
				class="widefat" 
				name="<?php echo esc_attr( $this->get_field_name( 'select_sns_share_icons_googleplus' ) ); ?>" 
				type="checkbox" 
				value="googleplus" 
				<?php checked( $select_sns_share_icons['googleplus'], 'googleplus' ); ?>
				style="width:0;"
			 /><br>
			 
			<label for="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_hatena' ) ); ?>">
				<strong><?php esc_html_e( 'Display the Hatena Bookmark Icon', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_hatena' ) ); ?>" 
				class="widefat" 
				name="<?php echo esc_attr( $this->get_field_name( 'select_sns_share_icons_hatena' ) ); ?>" 
				type="checkbox" 
				value="hatena" 
				<?php checked( $select_sns_share_icons['hatena'], 'hatena' ); ?>
				style="width:0;"
			 /><br>
			 
			<label for="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_pocket' ) ); ?>">
				<strong><?php esc_html_e( 'Display the Pocket Icon', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_pocket' ) ); ?>" 
				class="widefat" 
				name="<?php echo esc_attr( $this->get_field_name( 'select_sns_share_icons_pocket' ) ); ?>" 
				type="checkbox" 
				value="pocket" 
				<?php checked( $select_sns_share_icons['pocket'], 'pocket' ); ?>
				style="width:0;"
			 /><br>
			 
			<label for="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_line' ) ); ?>">
				<strong><?php esc_html_e( 'Display the Line Icon', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_line' ) ); ?>" 
				class="widefat" 
				name="<?php echo esc_attr( $this->get_field_name( 'select_sns_share_icons_line' ) ); ?>" 
				type="checkbox" 
				value="line" 
				<?php checked( $select_sns_share_icons['line'], 'line' ); ?>
				style="width:0;"
			 /><br>
			 
			<label for="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_feedly' ) ); ?>">
				<strong><?php esc_html_e( 'Display the Feedly Icon', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_feedly' ) ); ?>" 
				class="widefat" 
				name="<?php echo esc_attr( $this->get_field_name( 'select_sns_share_icons_feedly' ) ); ?>" 
				type="checkbox" 
				value="feedly" 
				<?php checked( $select_sns_share_icons['feedly'], 'feedly' ); ?>
				style="width:0;"
			 /><br>
			 
			<label for="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_rss' ) ); ?>">
				<strong><?php esc_html_e( 'Display the RSS Icon', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label>
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'select_sns_share_icons_rss' ) ); ?>" 
				class="widefat" 
				name="<?php echo esc_attr( $this->get_field_name( 'select_sns_share_icons_rss' ) ); ?>" 
				type="checkbox" 
				value="rss" 
				<?php checked( $select_sns_share_icons['rss'], 'rss' ); ?>
				style="width:0;"
			 /><br>
		</p>

		<?php

	}

}
?>