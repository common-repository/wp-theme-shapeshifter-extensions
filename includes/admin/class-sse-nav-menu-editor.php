<?php
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'SSE_Nav_Menu_Editor' ) ) {
class SSE_Nav_Menu_Editor {

	/**
	 * Static
	**/
		/**
		 * Instance
		 * @var SSE_Nav_Menu_Editor
		**/
		protected static $instance = null;

	/**
	 * Init
	**/
		/**
		 * Public Initialier
		 * @return SSE_Nav_Menu_Editor
		**/
		public static function get_instance()
		{
			if ( null === self::$instance ) self::$instance = new Self();
			return self::$instance;
		}
		/**
		 * Constructor
		**/
		protected function __construct() {

			# Define
				add_action( 'admin_menu', array( $this, 'define_shapeshifter_walker_nav_menu_edit' ) );

			# Add Settings Form
				add_action( 'sse_nav_menu_item_edit', array( $this, 'nav_menu_item_edit' ), 10, 4 );
				add_action( 'wp_update_nav_menu_item', array( $this, 'shapeshifter_update_nav_menu_item' ), 10, 3 );

			# Filter
				add_filter( 'wp_edit_nav_menu_walker', array( $this, 'shapeshifter_edit_nav_menu_walker' ), 10, 2 );

			# Enqueue Scripts
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		}

		# Actions
			# Define
				# Walker Class "SSE_Walker_Nav_Menu_Edit"
				# Requires WP Class "Walker_Nav_Menu_Edit"
					function define_shapeshifter_walker_nav_menu_edit() {

						if( ! class_exists( 'SSE_Walker_Nav_Menu_Edit' ) )
							include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'walkers/class-sse-walker-nav-menu-edit.php' );

					}

			# Settings Form
				function nav_menu_item_edit( $item, $depth = 0, $args = array(), $id = 0, $classes = array() ) { 

					$item_id = absint( $item->ID );
					$item_id_attr = esc_attr( $item->ID );

					# Children Display Type
						$children_type = esc_attr( get_post_meta( $item->ID, '_children_popup_type', true ) );

					# Children Title
						$children_title = esc_attr( wp_strip_all_tags( get_post_meta( $item->ID, '_children_popup_title', true ) ) );

					# Thumbnails
						$thumbnail_urls = get_post_meta( $item_id, '_thumbnail_image_urls', true );
						if( ! is_array( $thumbnail_urls ) ) $thumbnail_urls = array();

						$thumbnail_number = intval( count( $thumbnail_urls ) );

						$thumbnail_urls_str = '';
						if( $thumbnail_number > 0 )
							$thumbnail_urls_str = esc_attr( implode( ',', $thumbnail_urls ) );

					if( $depth === 0 ) { //print_r( $item );
						?>

						<p><a class="button button-primary shapeshifter-nav-menu-custom-edit-popup" href="javascript:void( 0 );"><?php esc_html_e( 'Custom', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a></p>
						<div class="shapeshifter-nav-menu-item-popup-background"></div>
						<div class="shapeshifter-nav-menu-item-custom-edit-settings-wrapper" style="">

							<!-- Children Type -->
								<p class="field-children-type children-type children-type-wide">
									<label for="edit-menu-item-children-type-<?php echo $item_id_attr; ?>">
										<?php esc_html_e( 'Children Type', ShapeShifter_Extensions::TEXTDOMAIN ); ?><br />
										<select
											id="edit-menu-item-children-type-<?php echo $item_id_attr; ?>" 
											class="edit-menu-item-children-type" 
											name="menu-item-children-type[<?php echo $item_id_attr; ?>]" 
										>
											<option value="default" <?php selected( $children_type, 'default' ); ?>><?php esc_html_e( 'Default ( Ignoring Following Settings )', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
											<option value="custom" <?php selected( $children_type, 'custom' ); ?>><?php esc_html_e( 'Custom ( Using Following Settings )', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
										</select>
									</label>
								</p>

							<!-- Children Title -->
								<p class="field-children-title children-title children-title-wide">
									<label for="edit-menu-item-children-title-<?php echo $item_id_attr; ?>">
										<?php esc_html_e( 'Children Title', ShapeShifter_Extensions::TEXTDOMAIN ); ?><br />
										<input type="text" 
											id="edit-menu-item-children-title-<?php echo $item_id_attr; ?>" 
											class="widefat edit-menu-item-children-title" 
											name="menu-item-children-title[<?php echo $item_id_attr; ?>]" 
											value="<?php echo $children_title; ?>"
										/>
									</label>
								</p>

							<!-- Children Description -->
								<p class="field-children-description children-description children-description-wide description">
									<label for="edit-menu-item-description-<?php echo $item_id_attr; ?>">
										<?php esc_html_e( 'Children Description', ShapeShifter_Extensions::TEXTDOMAIN ); ?><br />
										<textarea 
											id="edit-menu-item-description-<?php echo $item_id_attr; ?>" 
											class="widefat edit-menu-item-description" 
											rows="3" cols="20" 
											name="menu-item-description[<?php echo $item_id_attr; ?>]"
										><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
										<span class="description"><?php esc_html_e( 'The description will be displayed if this item has children.', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
									</label>
								</p>

							<!-- Thumbnails -->
								<p class="field-thumbnails thumbnails thumbnails-wide">
									<label for="edit-menu-item-thumbnails-<?php echo $item_id_attr; ?>">
										<?php esc_html_e( 'Thumbnails', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
										<input type="hidden" 
											id="edit-menu-item-thumbnails-<?php echo $item_id_attr; ?>" 
											class="edit-menu-item-thumbnails" 
											name="menu-item-thumbnails[<?php echo $item_id_attr; ?>]" 
											value="<?php echo $thumbnail_urls_str; ?>"
										/>
									</label><br />

									<div id="thumbnail-image-holder-<?php echo $item_id_attr; ?>" class="thumbnail-image-holder">
										<?php if( $thumbnail_number > 0 ) { foreach( $thumbnail_urls as $index => $thumbnail_url ) { ?>
											<img src="<?php echo esc_attr( $thumbnail_url ); ?>" width="100" height="100" style="float: left;">
										<?php } } ?>
									</div>
								</p>

								<p>
									<a class="button button-primary shapeshifter-nav-menu-item-button-set-images" href="javascript:void( 0 );"><?php esc_html_e( 'Set Image', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>
									<span class="shapeshifter-buttons-space"></span>
									<a class="button shapeshifter-nav-menu-item-button-remove-images" href="javascript:void( 0 );"><?php esc_html_e( 'Remove Images', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>
								</p>

							<!-- Close Button -->
								<p><a class="button shapeshifter-nav-menu-item-button-close-popup" href="javascript:void( 0 );"><?php esc_html_e( 'Close', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a></p>

								<pre><?php
									// $item, $depth = 0, $args = array(), $id = 0
										//print_r( $item ); echo PHP_EOL;
										//print_r( $depth ); echo PHP_EOL;
										//print_r( $args ); echo PHP_EOL;
										//print_r( $id ); echo PHP_EOL;
								?></pre>

						</div>

						<?php 
					}
				}

			# Save Nav Menu
				function shapeshifter_update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {

					//print_r( $_POST );

					# Before Save
						if( ! current_user_can( 'manage_options' ) ) {
							return;
						}

					# Children Type
						# Sanitize Type
							$children_type = sanitize_text_field( 
								isset( $_POST['menu-item-children-type'][ $menu_item_db_id ] ) 
								? $_POST['menu-item-children-type'][ $menu_item_db_id ]
								: ''
							);

						# Save Meta
							update_post_meta( $menu_item_db_id, '_children_popup_type', $children_type );

					# Children Title
						# Sanitize Thumbnails
							$children_title = sanitize_text_field( wp_strip_all_tags(
								isset( $_POST['menu-item-children-title'][ $menu_item_db_id ] ) 
								? $_POST['menu-item-children-title'][ $menu_item_db_id ]
								: ''
							) );

						# Save Meta
							update_post_meta( $menu_item_db_id, '_children_popup_title', $children_title );

					# Thumbnails
						# Sanitize Thumbnails
							$thumbnail_data_urls = ( 
								isset( $_POST['menu-item-thumbnails'][ $menu_item_db_id ] ) 
								? $_POST['menu-item-thumbnails'][ $menu_item_db_id ]
								: ''
							);
							$thumbnail_data_urls = preg_split( '/[,]/', $thumbnail_data_urls );
							foreach( $thumbnail_data_urls as $index => $url ) {
								$thumbnail_data_urls[ $index ] = esc_url_raw( $url );
							}

						# Save Meta
							update_post_meta( $menu_item_db_id, '_thumbnail_image_urls', $thumbnail_data_urls );

				}

		# Filters
			# Walker Class Name
				function shapeshifter_edit_nav_menu_walker( $walker_nav_menu_edit, $menu_id ) {

					if( ! class_exists( 'SSE_Walker_Nav_Menu_Edit' ) )
						include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'walkers/class-shapeshifter-walker-nav-menu-edit.php' );

					return 'SSE_Walker_Nav_Menu_Edit';

				}

		# Enqueue Scripts
			function admin_enqueue_scripts( $hook ) {

				if( $hook === "nav-menus.php" ) {

					wp_enqueue_style( 'sse-nav-menu' );
					wp_enqueue_script( 'sse-nav-menu' );

					$data = array();

					wp_localize_script( 'sse-nav-menu', 'shapeshifterNavMenuEditData', $data );

				}

			}

}

}
