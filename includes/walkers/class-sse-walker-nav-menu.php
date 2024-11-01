<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'SSE_Walker_Nav_Menu' ) ) {
class SSE_Walker_Nav_Menu extends Walker_Nav_Menu {

	private $nav_menu_settings;

	function start_lvl( &$output, $depth = 0, $args = array() ) {

		$indent = str_repeat( "\t", $depth );
		$output .= "\n{$indent}<ul class=\"sub-menu\">\n";

	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {

		$indent = str_repeat( "\t", $depth );
		$output .= "{$indent}</ul>\n";

	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		# By Wordpress
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			/**
			* Filters the arguments for a single nav menu item.
			*
			* @since 4.4.0
			*
			* @param array  $args  An array of arguments.
			* @param object $item  Menu item data object.
			* @param int    $depth Depth of menu item. Used for padding.
			*/
				$args = apply_filters( 'shapeshifter_nav_menu_item_args', $args, $item, $depth );

			/**
			* Filters the CSS class(es) applied to a menu item's list item element.
			*
			* @since 3.0.0
			* @since 4.1.0 The `$depth` parameter was added.
			*
			* @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
			* @param object $item    The current menu item.
			* @param array  $args    An array of wp_nav_menu() arguments.
			* @param int    $depth   Depth of menu item. Used for padding.
			*/
				$class_names = join( ' ', apply_filters( 'shapeshifter_nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			/**
			* Filters the ID applied to a menu item's list item element.
			*
			* @since 3.0.1
			* @since 4.1.0 The `$depth` parameter was added.
			*
			* @param string $menu_id The ID that is applied to the menu item's `<li>` element.
			* @param object $item    The current menu item.
			* @param array  $args    An array of wp_nav_menu() arguments.
			* @param int    $depth   Depth of menu item. Used for padding.
			*/
				$id = apply_filters( 'shapeshifter_nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
				$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

				$output .= $indent . '<li' . $id . $class_names .'>';

				$atts = array();
				$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
				$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
				$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
				$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

			/**
			* Filters the HTML attributes applied to a menu item's anchor element.
			*
			* @since 3.6.0
			* @since 4.1.0 The `$depth` parameter was added.
			*
			* @param array $atts {
			*     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
			*
			*     @type string $title  Title attribute.
			*     @type string $target Target attribute.
			*     @type string $rel    The rel attribute.
			*     @type string $href   The href attribute.
			* }
			* @param object $item  The current menu item.
			* @param array  $args  An array of wp_nav_menu() arguments.
			* @param int    $depth Depth of menu item. Used for padding.
			*/
				$atts = apply_filters( 'shapeshifter_nav_menu_link_attributes', $atts, $item, $args, $depth );

				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}

			/** This filter is documented in wp-includes/post-template.php */
				$title = apply_filters( 'shapeshifter_the_title', $item->title, $item->ID );

			/**
			* Filters a menu item's title.
			*
			* @since 4.4.0
			*
			* @param string $title The menu item's title.
			* @param object $item  The current menu item.
			* @param array  $args  An array of wp_nav_menu() arguments.
			* @param int    $depth Depth of menu item. Used for padding.
			*/
				$title = apply_filters( 'shapeshifter_nav_menu_item_title', $title, $item, $args, $depth );

				$item_output = $args->before;
				$item_output .= '<a'. $attributes .'>';
				$item_output .= $args->link_before . $title . $args->link_after;
				$item_output .= '</a>';
				$item_output .= $args->after;

			/**
			* Filters a menu item's starting output.
			*
			* The menu item's starting output only includes `$args->before`, the opening `<a>`,
			* the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
			* no filter for modifying the opening and closing `<li>` for a menu item.
			*
			* @since 3.0.0
			*
			* @param string $item_output The menu item's starting HTML output.
			* @param object $item        Menu item data object.
			* @param int    $depth       Depth of menu item. Used for padding.
			* @param array  $args        An array of wp_nav_menu() arguments.
			*/
				$output .= apply_filters( 'shapeshifter_walker_nav_menu_start_el', $item_output, $item, $depth, $args );

		# ShapeShifter Optional Data
			# If this item has children
				$has_children = ( bool ) $args->walker->has_children;

			# Children Display Type
				$children_type = esc_attr( get_post_meta( $item->ID, '_children_popup_type', true ) );
			# Thumbnails
				# URLs
					$thumbnail_image_urls = get_post_meta( $item->ID, '_thumbnail_image_urls', true );
					if( ! isset( $thumbnail_image_urls ) ) $thumbnail_image_urls = null;
				# Slider Size
					if( $depth === 0 ) {

						$image_width = 300;
						$image_height = 200;

					//} else if( $depth === 1 ) {
					} else {

						$image_width = 100;
						$image_height = 75;

					}

		# ShapeShifter HTML
		# Check if the item has children
			if( $has_children && $children_type === 'custom' ) {
				$output .= '<div class="shapeshifter-nav-menu-item-inner-wrapper nav-menu-item-inner-wrapper-depth-' . esc_attr( $depth ) . '">';

					$output .= '<div class="shapeshifter-nav-menu-item-inner nav-menu-item-inner-depth-' . esc_attr( $depth ) . '">';

					# Title Description
						$children_title = esc_html( get_post_meta( $item->ID, '_children_popup_title', true ) );
						$children_description = esc_html( $item->description );

						if( $children_title != '' || $children_description != '' ) {

							$output .= '<div class="shapeshifter-nav-menu-item-children-title-description">';
							if( ! empty( $children_title ) ) {
								$output .= '<p class="shapeshifter-nav-menu-item-children-title">';
									$output .= $children_title;
								$output .= '</p>';
							}
							if( ! empty( $children_description ) ) {
								$output .= '<p class="shapeshifter-nav-menu-item-children-description">';
									$output .= $children_description;
								$output .= '</p>';
							}
							$output .= '</div>';

						}

					# Images
						$images = '';
						if( ! empty( $thumbnail_image_urls[ 0 ] ) ) {
							$images .= '<div class="shapeshifter-nav-menu-item-thumbnail-images">';
								$images .= '<div class="slider-pro shapeshifter-nav-menu-item-thumbnail-slider-wrapper"><div class="sp-slides shapeshifter-nav-menu-item-thumbnail-slides">';
								foreach( $thumbnail_image_urls as $index => $thumbnail_image_url ) {
									$images .= '<div class="sp-slide">';
										$images .= '<img src="' . esc_url( $thumbnail_image_url ) . '" class="shapeshifter-nav-menu-thumbnail-image" width="' . esc_attr( $image_width ) . '" height="' . esc_attr( $image_height ) . '">';
									$images .= '</div>';
								}
								$images .= '</div></div>';
							$images .= '</div>';
						}
						$output .= $images;


					# List Wrapper
						$output .= '<div class="shapeshifter-nav-menu-item-children-wrapper">';

			}

	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {

		$has_children = ( bool ) $args->walker->has_children;
		# Children Display Type
			$children_type = esc_attr( get_post_meta( $item->ID, '_children_popup_type', true ) );

		if( $has_children && $children_type === 'custom' ) {
			$output .= '</div></div></div>';
		}
		$output .= "</li>\n";

	}

}
	
}
