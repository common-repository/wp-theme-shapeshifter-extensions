<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Frontend_Rendering_Methods {

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return ShapeShifter_Frontend_Rendering_Methods
		**/
		public static function get_instance()
		{

			try {
				$instance = new Self();
			} catch( ShapeShifter_Exception $e ) {
				return $e->getMessage();
			}
			return $instance;

		}

		/**
		 * Constructor
		**/
		protected function __construct()
		{
			$this->init_vars();
			$this->init_hooks();
		}

		/**
		 * Init vars
		**/
		protected function init_vars()
		{
			$this->options = sse()->get_options();
		}

		/**
		 * Init hooks
		**/
		protected function init_hooks()
		{
			add_action( shapeshifter()->get_prefixed_action_hook( 'setup_theme_mods' ), array( $this, 'setup_theme_mods' ) );
			add_action( shapeshifter()->get_prefixed_action_hook( 'init_frontend_post_meta' ), array( $this, 'init_widget_area_hooks' ), 20 );
		}

		/**
		 * Setup theme mods
		**/
		public function setup_theme_mods()
		{
			$this->theme_mods = sse()->get_theme_mods();
		}

		/**
		 * Init widget area hooks
		**/
		public function init_widget_area_hooks()
		{
			$this->outputs_to_widget_area_hook = sse()->get_frontend_manager()->get_outputs_to_widget_area_hook();
		}


	/**
	 * Generators
	**/
		/**
		 * AJAX Pagination
		 * Should be output before pagination
		**/
		public function shapeshifter_ajax_pagination() {

			global $wp_query;

			// Print Query Vars in Input Tags
			if( isset( $wp_query->query_vars ) ) {
				if( is_array( $wp_query->query_vars ) ) { foreach( $wp_query->query_vars as $index => $value ) {
					if( ! empty( $value ) && $index !== 'author_name' ) {
						if( in_array( $index, array( 'paged', 'cat', 'tag_id', 'author', 'posts_per_page' ) ) )
							echo '<input type="hidden" id="query-var-' . esc_attr( $index ) . '" value="' . esc_attr( $value ) . '"/>';
					}
					
				} }
			}

			// Print AJAX Load Button
			if( have_posts() ) {

				if( SHAPESHIFTER_IS_AJAX_LOAD_ON && get_next_posts_link() ) {

					echo '<a id="ajax-next-page" href="javascript:void(0);"><div id="ajax-load-posts-button" style="margin:auto;text-align:center;width:100%;height:40px;padding:10px;border:solid #ccc 1px;"><i class="fa fa-repeat"></i>' . esc_html__( '&nbsp;Load Next Page', ShapeShifter_Extensions::TEXTDOMAIN ) . '</div></a>';

				}

			}

		}

		// Header Image
			/**
			 * Header Image HTML
			 * @uses $this->shapeshifter_get_header_logo()
			 * @return void
			**/
			function shapeshifter_header_logo() {
				if ( sse()->get_frontend_manager()->need_header_logo() ) {
					echo $this->shapeshifter_get_header_logo();
				}
			}

			/**
			 * Get Header Image
			 * @return string 
			**/
			function shapeshifter_get_header_logo() {

				ob_start();

					include_once( SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR . 'header/header-image.php' );

				$header_logo = ob_get_clean();

				return apply_filters( 'shapeshifter_filters_header_logo', $header_logo );

			}

		// Read Later
			/**
			 * Archive Read Later
			 * 
			 * @param string $permalink
			 * @param string $title
			**/
			function shapeshifter_archive_read_later( $permalink, $title ) { 
				?>

				<div class="post-list-read-later">
					<span class="post-list-read-later-span" style="float:left;">
						<?php echo esc_html( $this->theme_mods['archive_page_read_later_text'] ); ?>
					</span>

					<?php 
						if( $this->theme_mods['archive_page_read_later_type'] == 'buttons' ) {

							do_action( 'sse_share_buttons', $permalink, $title );

						} elseif( $this->theme_mods['archive_page_read_later_type'] == 'icons' ) {

							do_action( 'sse_share_icons', $permalink, $title );

						}
					?>
				</div>

				<?php 
			}

				/**
				 * SNS Share Buttons
				 * 
				 * @param string $permalink
				 * @param string $title
				**/
				function share_buttons( $permalink, $title ) {

					include( SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR . 'read-later/share-buttons.php' );
					/*
						echo '<ul class="post-list-read-later-sns-buttons">';
							$this->twitter_share( $permalink, $title );
							$this->facebook_share( $permalink, $title );
							$this->googleplus_share( $permalink, $title );
							$this->hatena_bookmark_share( $permalink, $title );
							$this->pocket_share( $permalink, $title );
							$this->line_share( $permalink, $title );
						echo '</ul>';
					*/

				}

					# ツイッター
						function twitter_share( $permalink, $title ) { ?>

							<li class="post-list-sns-share-button-li post-list-twitter-button-li"><a href="https://twitter.com/share" class="twitter-share-button"
								data-url="<?php echo esc_url( $permalink ); ?>" 
								<?php echo ( $this->options['seo']['twitter_card_account'] != '' 
									? 'data-via="' . esc_attr( $this->options['seo']['twitter_card_account'] ) . '"' 
									: '' 
								); ?>
								data-hashtags="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
								data-text="<?php echo esc_attr( $title ); ?>"
							>Tweet</a></li>

						<?php }

					# フェイスブック
						function facebook_share( $permalink, $title ) { ?>

							<li class="post-list-sns-share-button-li post-list-facebook-button-li">
								<div class="fb-share-button" 
									data-href="<?php echo esc_url( $permalink ); ?>" 
									data-layout="button_count"
								></div>
							</li>

						<?php }

					# グーグルプラス
						function googleplus_share( $permalink, $title ) { ?>

							<li class="post-list-sns-share-button-li post-list-googleplus-button-li"><div class="g-plus" data-action="share" data-annotation="bubble" data-height="20" 
								data-href="<?php echo esc_url( $permalink ); ?>"
							></div></li>

						<?php }

					# はてなブックマーク
						function hatena_bookmark_share( $permalink, $title ) { ?>

							<li class="post-list-sns-share-button-li post-list-hatena-button-li"><a href="http://b.hatena.ne.jp/entry/<?php echo esc_url( $permalink ); ?>" data-hatena-bookmark-title="<?php echo esc_attr( $title ); ?>" class="hatena-bookmark-button" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加">
								<img src="<?php echo esc_url( SSE_ASSETS_URL ); ?>images/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" />
							</a></li>

						<?php }

					# Pocket
						function pocket_share( $permalink, $title ) { ?>

							<li class="post-list-sns-share-button-li post-list-pocket-button-li"><a href="https://getpocket.com/save" class="pocket-btn" data-lang="en"
								data-save-url="<?php echo esc_url( $permalink ); ?>"
								data-pocket-count="horizontal" 
								data-pocket-align="left"
							>Pocket</a></li>

						<?php }

					# LINE
						function line_share( $permalink, $title ) { ?>

							<li class="post-list-sns-share-button-li post-list-line-button-li"><a href="http://line.me/R/msg/text/?<?php echo rawurlencode( $title ); ?>%0D%0A<?php esc_url( $permalink ); ?>">
								<img src="<?php echo esc_url( trailingslashit( SSE_ASSETS_URL ) . 'images/linebutton/linebutton_82x20.png' ); ?>" 
									width="82" 
									height="20" 
									alt="LINEで送る" 
								/>
							</a></li>

						<?php }

				/**
				 * SNS Share Icons
				 * 
				 * @param string $permalink
				 * @param string $title
				**/
				function share_icons( $permalink, $title ) {

					include( SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR . 'read-later/share-icons.php' );
					/*
						echo '<ul class="post-list-read-later-sns-share-icons">';
							$this->twitter_share_icons( $permalink, $title );
							$this->facebook_share_icons( $permalink, $title );
							$this->googleplus_share_icons( $permalink, $title );
							$this->hatena_bookmark_share_icons( $permalink, $title );
							$this->pocket_share_icons( $permalink, $title );
							$this->line_share_icons( $permalink, $title );
						echo '</ul>';
					*/

				}

					# ツイッター
						function twitter_share_icons( $permalink, $title ) { 

							$urlEncodedTitle = urlencode( html_entity_decode( trim( wp_title( '', false ) ) ) );
							$tUrlEncodedTitle = urlencode( trim( $title . ' #' . get_bloginfo( 'name' ) ) );
							?>
							<li class="post-list-sns-share-icon-li post-list-twitter-share-icon-li"
							>
								<p class="post-list-sns-share-icon-li-p post-list-twitter-share-icon-li-p"
								>
									<a href="<?php echo esc_url( 'https://twitter.com/share?original_referer=' . esc_url( $permalink ) . '&amp;text=' . ( $tUrlEncodedTitle ? $tUrlEncodedTitle : $urlEncodedTitle ) . '&amp;tw_p=tweetbutton&amp;url=' . esc_url( $permalink ) ); ?>" 
										class="post-list-sns-share-icon-li-p-a post-list-twitter-share-icon-li-p-a" 
										title="twitter" 
										rel="nofollow" 
										target="_blank"
									>
										<i class="fa fa-twitter"></i>
									</a>
								</p>
							</li>

						<?php }

					# フェイスブック
						function facebook_share_icons( $permalink, $title ) { 

							$urlEncodedTitle = urlencode( $title );
							?>
							<li class="post-list-sns-share-icon-li post-list-facebook-share-icon-li"
							>
								<p class="post-list-sns-share-icon-li-p post-list-faceook-share-icon-li-p"
								>
									<a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . esc_url( $permalink ) . '&amp;display=popup&amp;t=' . $urlEncodedTitle ); ?>" 
										class="post-list-sns-share-icon-li-p-a post-list-faceook-share-icon-li-p-a" 
										title="faceook" 
										rel="nofollow" 
										target="_blank"
									>
										<i class="fa fa-facebook"></i>
									</a>
								</p>
							</li>

						<?php }

					# グーグルプラス
						function googleplus_share_icons( $permalink, $title ) { ?>

							<li class="post-list-sns-share-icon-li post-list-googleplus-share-icon-li"
							>
								<p class="post-list-sns-share-icon-li-p post-list-googleplus-share-icon-li-p"
								>
									<a href="<?php echo esc_url( 'https://plus.google.com/share?url=' . esc_url( $permalink ) ); ?>" 
										class="post-list-sns-share-icon-li-p-a post-list-googleplus-share-icon-li-p-a" 
										title="googleplus" 
										rel="nofollow" 
										target="_blank"
									>
										<i class="fa fa-google-plus"></i>
									</a>
								</p>
							</li>

						<?php }

					# はてなブックマーク
						function hatena_bookmark_share_icons( $permalink, $title ) {

							$urlEncodedTitle = urlencode( $title );
							?>
							<li class="post-list-sns-share-icon-li post-list-hatena-share-icon-li"
							>
								<p class="post-list-sns-share-icon-li-p post-list-hatena-share-icon-li-p"
								>
									<a href="<?php echo esc_url( 'https://b.hatena.ne.jp/add?mode=confirm&amp;url=' . esc_url( $permalink ) . '&amp;title=' . $urlEncodedTitle ); ?>" 
										class="post-list-sns-share-icon-li-p-a post-list-hatena-share-icon-li-p-a" 
										title="hatenabookmark" 
										rel="nofollow" 
										target="_blank"
									>
										<span style="font-family: sans-serif;">B!</span>
									</a>
								</p>
							</li>

						<?php }

					# Pocket
						function pocket_share_icons( $permalink, $title ) { 

							$urlEncodedTitle = urlencode( $title );
							?>
							<li class="post-list-sns-share-icon-li post-list-pocket-share-icon-li"
							>
								<p class="post-list-sns-share-icon-li-p post-list-pocket-share-icon-li-p"
								>
									<a href="<?php echo esc_url( 'https://getpocket.com/edit?url=' . esc_url( $permalink ) . '&amp;title=' . $urlEncodedTitle ); ?>" 
										class="post-list-sns-share-icon-li-p-a post-list-pocket-share-icon-li-p-a" 
										title="pocket" 
										rel="nofollow" 
										target="_blank"
									>
										<i class="icon-pocket"></i>
									</a>
								</p>
							</li>

						<?php }

					# LINE
						function line_share_icons( $permalink, $title ) { 

							$urlEncodedTitle = urlencode( $title );
							?>
							<li class="post-list-sns-share-icon-li post-list-line-share-icon-li"
							>
								<p class="post-list-sns-share-icon-li-p post-list-line-share-icon-li-p"
								>
									<a href="<?php echo esc_url( 'https://line.naver.jp/R/msg/text/?' . $urlEncodedTitle . '%0D%0A' . esc_url( $permalink ) ); ?>" 
										class="post-list-sns-share-icon-li-p-a post-list-line-share-icon-li-p-a" 
										title="line" 
										rel="nofollow" 
										target="_blank"
									>
										<i class="icon-line"></i>
									</a>
								</p>
							</li>

						<?php }

		// Post Meta
			/**
			 * Print by Post Meta
			 * 
			 * @param array $widget_areas_data
			**/
			function shapeshifter_post_meta_outputs_in_widget_area_hook( $widget_areas_data ) {
				echo $this->shapeshifter_get_post_meta_outputs_in_widget_area_hook( $widget_areas_data );
			}

			/**
			 * Get Post Meta Output
			 * 
			 * @param array $widget_areas_data
			 * 
			 * @return string
			**/
			function shapeshifter_get_post_meta_outputs_in_widget_area_hook( $widget_areas_data ) {
				
				if( ! isset( $GLOBALS['post'] ) ) return;

				global $post; $post_id = $post->ID;

				// Holder
				$return = '';
				if( is_array( $this->outputs_to_widget_area_hook ) ) { 
				foreach( $this->outputs_to_widget_area_hook as $item_num => $data ) {
					// Check the Hook
					if( $data['hook'] === 'none' || $data['hook'] !== $widget_areas_data['id'] ) {
						continue;
					}

					// Check the Type
					if( $data['type'] === 'none' ) {
						continue;
					}

					// Check
					if( 
						( $data['device_detect']['is_pc'] != 'is_pc'
							&& ! SSE_IS_MOBILE
						)
						|| ( $data['device_detect']['is_mobile'] != 'is_mobile'
							&& SSE_IS_MOBILE
						)
					) {
						continue;
					}


					// Vars
					$item_wrapper_for_vegas = '';
					$vegas_images_array = $data['background_images'];
					$vegas_delay = 5000;
					$vegas_transition = 'fade';
					$vegas_transition_duration = 2000;

					// 
					if( ! in_array( $data['type'], array( 'widget_from_widget_areas', 'new_registered_widget' ) ) ) {

						$return .= $widget_areas_data['before_widget'] . '<div id="' . esc_attr( $widget_areas_data['id'] . '-widget-inner-' . $item_num ) . '" class="' . esc_attr( $widget_areas_data['id'] . '-widget-inner' ) . '">';

						if( $data['title_is_display'] != '' && $data['title'] != '' ) {
							if( isset( $widget_areas_data['hook'] ) && in_array( $widget_areas_data['hook'], array( 'after_header', 'before_content', 'beginning_of_content', 'before_1st_h2_of_content', 'end_of_content', 'after_content', 'before_footer', 'in_footer' ) ) ) {
								$return .= '<p class="shapeshifter-post-meta-item-title-for-optional-widget-area" style="padding: 20px; text-align: center; font-size: 28px;"><span style="color: ' . esc_attr( $data['text_color'] ) . '; border-top: solid ' . esc_attr( $data['text_color'] ) . ' 1px; border-bottom: solid ' . esc_attr( $data['text_color'] ) . ' 1px; padding: 5px;">' . esc_html( $data['title'] ) . '</span></p>';
							} else {
								$return .= $widget_areas_data['before_title'] . esc_html( $data['title'] ) . $widget_areas_data['after_title'];
							}
						}

					}


					if( $data['type'] === 'textarea' ) {

						$item_wrapper_for_vegas = '#shapeshifter-post-meta-item-textarea-' . $item_num . '-wrapper';
						$return .= $this->shapeshifter_get_post_meta_outputs_textarea(
							$item_num, 
							$widget_areas_data, 
							$data
						);
						$item_wrapper_for_vegas = '#' . $widget_areas_data['id'] . '-widget-inner-' . $item_num;

					} elseif( $data['type'] === 'pagelink' ) {

						$item_wrapper_for_vegas = '#shapeshifter-post-meta-item-pagelink-' . $item_num . '-wrapper';
						$return .= $this->shapeshifter_get_post_meta_outputs_pagelink(
							$item_num, 
							$widget_areas_data, 
							$data
						);
						$item_wrapper_for_vegas = '#' . $widget_areas_data['id'] . '-widget-inner-' . $item_num;

					} elseif( $data['type'] === 'download' ) {

						$item_wrapper_for_vegas = '#shapeshifter-post-meta-item-download-' . $item_num . '-wrapper';
						$return .= $this->shapeshifter_get_post_meta_outputs_download( 
							$item_num, 
							$widget_areas_data, 
							$data 
						);
						$item_wrapper_for_vegas = '#' . $widget_areas_data['id'] . '-widget-inner-' . $item_num;

					} elseif( $data['type'] === 'slider' ) {

						$return .= $this->shapeshifter_get_post_meta_outputs_slider(
							$item_num, 
							$widget_areas_data, 
							$data 
						);

					} elseif( $data['type'] === 'widget_from_widget_areas' ) {

						if ( ! isset( $data['widget_from_widget_area'] ) 
							|| ! isset( $data['widget_from_widget_area']['widget_class'] )
							|| ! class_exists( $data['widget_from_widget_area']['widget_class'] )
						) {
							continue;
						}
						$widget = $data['widget_from_widget_area']['widget_class'];
						$widget_instance = $data['widget_from_widget_area'];
						$widget_args = array();
						$widget_args['before_widget'] = $widget_areas_data['before_widget'];
						$widget_args['after_widget'] = $widget_areas_data['after_widget'];
						$widget_args['before_title'] = $widget_areas_data['before_title'];
						$widget_args['after_title'] = $widget_areas_data['after_title'];

						ob_start();

							if( class_exists( $widget ) ) {

								the_widget( $widget, $widget_instance, $widget_args );

							}

						$return .= html_entity_decode( ob_get_clean() );

					} elseif( $data['type'] === 'new_registered_widget' ) {

						if ( ! isset( $data['new_registered_widget_instance'] ) 
							|| ! isset( $data['new_registered_widget_selected_class'] )
							|| ! class_exists( $data['new_registered_widget_selected_class'] )
						) {
							continue;
						}
						$widget = $data['new_registered_widget_selected_class'];

						$widget_instance = $data['new_registered_widget_instance'];

						$widget_args = array();
						$widget_args['before_widget'] = $widget_areas_data['before_widget'];
						$widget_args['after_widget'] = $widget_areas_data['after_widget'];
						$widget_args['before_title'] = $widget_areas_data['before_title'];
						$widget_args['after_title'] = $widget_areas_data['after_title'];

						ob_start();

							if( class_exists( $widget ) ) {
								//echo '<h2>' . $widget . '</h2>';
								//print_r( $widget_instance );
								the_widget( $widget, $widget_instance, $widget_args );
							}

						$return .= html_entity_decode( ob_get_clean() );

					} else {

						continue;

					}

					if( $item_wrapper_for_vegas != '' 
						&& count( $vegas_images_array ) >= 1
					) { 
						$return .= '<script id="shapeshifter-post-meta-vegas-data-' . $item_num . '">
							if( typeof vegasData == "undefined" ) {
								var vegasData = [];
							}
							vegasData.push({
								"selectorId": "' . esc_attr( $item_wrapper_for_vegas ) . '",
								"properties": {
									delay: ' . absint( $vegas_delay ) . ',
									transition: "' . esc_attr( $vegas_transition ) . '",
									transitionDuration: ' . absint( $vegas_transition_duration ) . ',
									shuffle: true,
									slides: [ 
										';
										if( is_array( $vegas_images_array ) ) { foreach( $vegas_images_array as $index => $src ) {
											$return .= '{ "src": "' . $src . '" }';
											if( $index + 1 !== count( $vegas_images_array ) ) { $return .= ',
										'; }
										} }
									$return .= ' 
									]
								}
							});
						</script>';
					}

					if( ! in_array( $data['type'], array( 'widget_from_widget_areas', 'new_registered_widget' ) ) ) {
						$return .= '</div>' . $widget_areas_data['after_widget'];
					}

				}
				}

				return $return;

			}

			// Text
				/**
				 * Get Text HTML
				 * 
				 * @param int   $item_num
				 * @param array $widget_areas_data
				 * @param array $data
				 * 
				 * @return string
				**/
				function shapeshifter_get_post_meta_outputs_textarea( $item_num, $widget_areas_data, $data ) {

					$textarea_id = esc_attr( 'shapeshifter-post-meta-item-textarea-' . $item_num );
					$textarea_class = esc_attr( 'shapeshifter-post-meta-item-textarea' );

					$textarea = '';

					$textarea .= '<div 
						id="' . esc_attr( $textarea_id ) . '-wrapper" 
						class="' . esc_attr( $textarea_class ) . '-wrapper"
					><div
						style="
							color: ' . esc_attr( $data['text_color'] ) . ';
						"
					>';
						$textarea .= shapeshifter_get_string_eof( html_entity_decode( $data['textarea'] ) );
					$textarea .= '</div></div>';

					return $textarea;

				}

			// Page Link
				/**
				 * Get Page Link HTML
				 * 
				 * @param int   $item_num
				 * @param array $widget_areas_data
				 * @param array $data
				 * 
				 * @return string
				**/
				function shapeshifter_get_post_meta_outputs_pagelink( $item_num, $widget_areas_data, $data ) {

					$pagelink_id = esc_attr( 'shapeshifter-post-meta-item-pagelink-' . $item_num );
					$pagelink_class = esc_attr( 'shapeshifter-post-meta-item-pagelink' );

					$data['pagelink_title'] = esc_html( $data['pagelink_title'] );
					$data['pagelink_description'] = html_entity_decode( $data['pagelink_description'] );
					$data['pagelink_url'] = esc_url( $data['pagelink_url'] );
					$data['text_color'] = esc_attr( $data['text_color'] );

					$pagelink = '';

						$pagelink .= '<div id="' . $pagelink_id . '-wrapper" class="shapeshifter-image-slider-wrapper ' . $pagelink_class . '-wrapper" style="position: relative; overflow: auto; width:100%; padding: 40px 30px;">';
							$pagelink .= '<div id="' . $pagelink_id . '" class="pagelink-box" style="position:static; bottom:30px; width: 100%; color: ' . $data['text_color'] . '">';

								$pagelink .= '<p id="' . $pagelink_id . '-title" class="pagelink-title" style="text-align:center; width: 100%; margin: auto; margin-bottom: 20px; font-size: 20px;">';
									$pagelink .= $data['pagelink_title'];
								$pagelink .= '</p>';
								$pagelink .= '<div id="' . $pagelink_id . '-description" class="pagelink-description" style="text-align:center; width: 100%; margin: 20px auto;">' . $data['pagelink_description'] . '</div>';
								$pagelink .= '<p id="' . $pagelink_id . '-button" class="pagelink-button" style="text-align:center; margin: auto; line-height: 4;">';
									$pagelink .= '<a id="' . $pagelink_id . '-pagelink-link" href="' . $data['pagelink_url'] . '" style="margin: auto 15px; padding: 15px; border: solid ' . $data['text_color'] . ' 2px; border-radius: 5px; color: ' . $data['text_color'] . '">';
										$pagelink .= esc_html__( 'Read', ShapeShifter_Extensions::TEXTDOMAIN );
									$pagelink .= '</a>';
								$pagelink .= '</p>';
							$pagelink .= '</div>';
						$pagelink .= '</div>';

					return $pagelink;

				}

			// Download
				/**
				 * Get Download HTML
				 * 
				 * @param int   $item_num
				 * @param array $widget_areas_data
				 * @param array $data
				 * 
				 * @return string
				**/
				function shapeshifter_get_post_meta_outputs_download( $item_num, $widget_areas_data, $data ) {

					$download_id = esc_attr( 'shapeshifter-post-meta-item-download-' . $item_num );
					$download_class = esc_attr( 'shapeshifter-post-meta-item-download' );

					$data['download_title'] = esc_html( $data['download_title'] );
					$data['download_description'] = html_entity_decode( $data['download_description'] );
					$data['download_url'] = esc_url( $data['download_url'] );
					$data['demo_url'] = esc_url( $data['demo_url'] );
					$data['text_color'] = esc_attr( $data['text_color'] );

					$download = '';

					$download .= '<div id="' . $download_id . '-wrapper" class="shapeshifter-image-slider-wrapper shapeshifter-post-meta-item-download-wrapper" style="position: relative; overflow: auto; width:100%; padding: 40px 30px;">';
						$download .= '<div id="' . $download_id . '" class="download-box" style="position:static; bottom:30px; width: 100%; color: ' . $data['text_color'] . '">';

							$download .= '<p id="' . $download_id . '-title" class="download-title" style="text-align:center; width: 100%; margin: auto; margin-bottom: 20px; font-size: 20px;">';
								$download .= $data['download_title'];
							$download .= '</p>';
							$download .= '<div id="' . $download_id . '-description" class="download-description" style="text-align:center; width: 100%; margin: 20px auto;">' . $data['download_description'] . '</div>';
							$download .= '<p id="' . $download_id . '-button" class="download-button" style="text-align:center; margin: auto; line-height: 4;">';
								if( $data['demo_url'] != '' ) {
									$download .= '<a id="' . $download_id . '-demo-link" href="' . $data['demo_url'] . '" style="margin: auto 15px; padding: 15px; border: solid ' . $data['text_color'] . ' 2px; border-radius: 5px; color: ' . $data['text_color'] . '">';
										$download .= 'Demo';
									$download .= '</a>';
								}
								$download .= '<a id="' . $download_id . '-download-link" href="' . $data['download_url'] . '" style="margin: auto 15px; padding: 15px; border: solid ' . $data['text_color'] . ' 2px; border-radius: 5px; color: ' . $data['text_color'] . '">';
								$download .= 'Download';
								$download .= '</a>';
							$download .= '</p>';
						$download .= '</div>';
					$download .= '</div>';

					return $download;

				}

			// Slider
				/**
				 * Get Slider
				 * 
				 * @param int   $item_num
				 * @param array $widget_areas_data
				 * @param array $data
				 * 
				 * @return string
				**/
				function shapeshifter_get_post_meta_outputs_slider( $item_num, $widget_areas_data, $data ) {

					$item_num = absint( $item_num );
					$slider_item_num = absint( $data['slider_item_num'] );

					$data['slider_type'] = esc_attr( $data['slider_type'] );
					$data['slider_style_type']['sidecontrols'] = esc_attr( $data['slider_style_type']['sidecontrols'] );
					$data['slider_style_type']['buttons'] = esc_attr( $data['slider_style_type']['buttons'] );
					$data['text_color'] = esc_attr( $data['text_color'] );

					$slider = '';

					$slider .= '<div style="margin:auto; width: 100%;">';

						if( $data['slider_type'] == 'new_posts' ) {

							$slider .= '<div 
								id="shapeshifter-post-meta-slider-new-posts-' . $item_num . '-wrapper" 
								class="shapeshifter-post-meta-slider-new-posts-wrapper slider-pro" 
								data-slider-item-num="' . $slider_item_num . '"
								data-slider-type="' . $data['slider_type'] . '"
								data-slider-style-sidecontrols="' . $data['slider_style_type']['sidecontrols'] . '" 
								data-slider-style-buttons="' . $data['slider_style_type']['buttons']  . '" 
								style="
									color: ' . $data['text_color'] . ';
									height: ' . ( $data['slider_style_type']['buttons'] !== 'buttons' ? 400 : 450 ) . 'px;
									visibility: hidden;
								"
							>';

								$tax_query_post_formats = sse()->get_frontend_manager()->shapeshifter_get_tax_query_post_formats( $data['post_formats_is_display'] );

								$new_posts_args = array(
									'posts_per_page' => $slider_item_num,
									'tax_query' => array(
										$tax_query_post_formats,
									),
								); 

								$posts = get_posts( $new_posts_args );

								$slider .= '<div 
									id="shapeshifter-post-meta-slider-new-posts-' . $item_num . '" 
									class="shapeshifter-post-meta-slider-new-posts sp-slides" 
									data-slider-style-sidecontrols="' . $data['slider_style_type']['sidecontrols'] . '" 
									data-slider-style-buttons="' . $data['slider_style_type']['buttons']  . '" 
								>';

									foreach( $posts as $index => $post ) {
										
										$image_id = get_post_thumbnail_id( $post->ID );
										$thumbnail_image_url = esc_url( get_theme_mod( 'default_thumbnail_image', SSE_ASSETS_URL . 'images/no-img.png' ) );
										$thumbnail_image_url = ( wp_get_attachment_image_src( $image_id, 'full' ) != ''
											? wp_get_attachment_image_src( $image_id, 'full' ) 
											: $thumbnail_image_url
										);
										if( is_array( $thumbnail_image_url ) ) { $thumbnail_image_url = esc_url( $thumbnail_image_url[ 0 ] ); }

										$slider .= $this->shapeshifter_get_post_meta_outputs_slider_pro_format_sp_slide_of_post( 
											$post->post_title,
											sse_get_the_excerpt( $post->post_content, 200 ),
											esc_url_raw( get_permalink( $post->ID ) ), 
											$thumbnail_image_url
										);

									}

								$slider .= '</div>';

							$slider .= '</div>';

						} elseif( $data['slider_type'] == 'popular_posts' ) {

							$slider .= '<div 
								id="shapeshifter-post-meta-slider-popular-posts-' . $item_num . '-wrapper" 
								class="shapeshifter-post-meta-slider-popular-posts-wrapper slider-pro" 
								data-slider-item-num="' . $slider_item_num . '"
								data-slider-type="' . $data['slider_type'] . '"
								data-slider-style-sidecontrols="' . $data['slider_style_type']['sidecontrols'] . '" 
								data-slider-style-buttons="' . $data['slider_style_type']['buttons']  . '" 
								style="
									color: ' . $data['text_color'] . ';
									visibility: hidden;
								"
							>';
							
								$tax_query_post_formats = $this->shapeshifter_get_tax_query_post_formats( $data['post_formats_is_display'] );

								$popular_posts_args = array(

									'post_type' => array(
										'post'
									),
									'post_status' => array(
										'publish'
									),
									'orderby' => 'meta_value_num',
									'meta_key' => 'shapeshifter-views',

									'posts_per_page' => $slider_item_num,
									'tax_query' => array(
										$tax_query_post_formats,
									),
								); 

								$posts = get_posts( $popular_posts_args );

								$slider .= '<div 
									id="shapeshifter-post-meta-slider-popular-posts-' . $item_num . '" 
									class="shapeshifter-post-meta-slider-popular-posts sp-slides" 
									data-slider-style-sidecontrols="' . $data['slider_style_type']['sidecontrols'] . '" 
									data-slider-style-buttons="' . $data['slider_style_type']['buttons']  . '" 
								>';

									foreach( $posts as $index => $post ) {
										
										$image_id = get_post_thumbnail_id( $post->ID );
										$thumbnail_image_url = esc_url( get_theme_mod( 'default_thumbnail_image', SSE_ASSETS_URL . 'images/no-img.png' ) );
										$thumbnail_image_url = ( wp_get_attachment_image_src( $image_id, 'full' ) != ''
											? wp_get_attachment_image_src( $image_id, 'full' ) 
											: $thumbnail_image_url
										);
										if( is_array( $thumbnail_image_url ) ) { $thumbnail_image_url = esc_url( $thumbnail_image_url[ 0 ] ); }

										$slider .= $this->shapeshifter_get_post_meta_outputs_slider_pro_format_sp_slide_of_post( 
											$post->post_title,
											sse_get_the_excerpt( $post->post_content, 200 ),
											esc_url_raw( get_permalink( $post->ID ) ), 
											$thumbnail_image_url
										);

									}

								$slider .= '</div>';
							$slider .= '</div>';

						} elseif( $data['slider_type'] == 'images' ) {
							
						} else {

						}

					$slider .= '</div>';

					return $slider;

				}

				/**
				 * Slider Pro
				 * 
				 * @param sring $title
				 * @param string $description
				 * @param string $permalink
				 * @param string $image_url
				 * 
				 * @return string
				**/
				function shapeshifter_get_post_meta_outputs_slider_pro_format_sp_slide_of_post( $title, $description, $permalink, $image_url ) {

					$slider = '';

					$slider .= '<div class="shapeshifter-post-meta-slider-item sp-slide">';

						$slider .= '<div class="sp-padding">';

							$slider .= '<div 
								class="slide-image" 
								style="
									margin: auto;
									width: 220px;
									height: 220px;
								"
							>';
								$slider .= '<a href="' . esc_url( $permalink ) . '">';
									$slider .= '<canvas 
										style=" 
											width: 200px;
											height: 200px;
											border-radius: 100px;
											background-image: url(' . esc_url( $image_url ) . ');
											background-size: cover;
											background-repeat: no-repeat;
											background-position: center center;
										"
									></canvas>';
								$slider .= '</a>';
							$slider .= '</div>';

							$slider .= '<p
								style="
									text-align: center;
									font-size: 20px;

								"
							>' . esc_html( $title ) . '</p>';

							$slider .= '<p
								style="
									font-size: 10px;
									
								"
							>' . html_entity_decode( $description ) . '</p>';

						$slider .= '</div>';

					$slider .= '</div>';

					return $slider;

				}

		/**
		 * wp_footer
		**/
		function print_in_wp_footer() {

			$link_format = '<link rel="stylesheet" id="%1$s" href="%2$s" type="text/css" media="%3$s">' . PHP_EOL;
			$frontend_css = sse()->get_frontend_css();
			echo '<noscript>';
			foreach ( $frontend_css as $css_handler => $css_data ) {
				printf( $link_format, esc_attr( $css_handler ), esc_url( $css_data['src'] ), esc_attr( isset( $css_data['media'] ) ? $css_data['media'] : 'all' ) );
			}
			echo '</noscript>';

		}


}

