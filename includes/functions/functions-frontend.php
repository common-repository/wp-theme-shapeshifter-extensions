<?php

#
# Post Format
#
	if ( ! function_exists( 'sse_get_tax_query_post_formats' ) ) {
		/**
		 * Get Excerpt From Post Content
		 * 
		 * @param string $post_content
		 * @param int    $excerpt_length
		 * 
		 * @return string
		**/
		function sse_get_tax_query_post_formats() {

			$post_formats = wp_parse_args( $post_formats, 
				array(
					'standard' => true,
					'aside' => true,
					'gallery' => true,
					'image' => true,
					'link' => true,
					'quote' => true,
					'status' => true,
					'video' => true,
					'audio' => true,
					'chat' => true,
				)
			);

			$post_format_terms = array();
			if( $post_formats['standard'] != '' ) {
				
				foreach( $post_formats as $post_format => $text ) {

					if( $post_format == 'standard' ) continue;
					if( $text == '' )
						$post_format_terms[] = esc_attr( 'post-format-' . $post_format );

				}

				return array(
					'taxonomy' => 'post_format',
					'field'	=> 'slug',
					'terms'	=> $post_format_terms,
					'operator' => 'NOT IN'
				);

			} else {

				foreach( $post_formats as $post_format => $text ) {

					if( $post_format == 'standard' ) continue;
					if( $text != '' ) 
						$post_format_terms[] = esc_attr( 'post-format-' . $post_format );

				}

				return array(
					'taxonomy' => 'post_format',
					'field'	=> 'slug',
					'terms'	=> $post_format_terms,
				);

			}

		}
	}

#
# URL
#
	if ( ! function_exists( 'sse_get_current_url_by_query_for_sns_share' ) ) {
		/**
		 * Get Excerpt From Post Content
		 * 
		 * @param string $post_content
		 * @param int    $excerpt_length
		 * 
		 * @return string
		**/
		function sse_get_current_url_by_query_for_sns_share() {

			global $post;
			global $wp_query;

			// Get URL
			if( is_search() || is_paged() || is_404() || is_attachment() ) {
				return false;
			} elseif( is_home() || is_front_page() ) {
				$permalink_url = urlencode( esc_url( SITE_URL ) );
			} elseif( function_exists( 'is_woocommerce' ) && is_woocommerce() ) { // WooCommerce
				if( is_shop() ) { // The main shop
					$permalink_url = urlencode( esc_url( get_post_type_archive_link( 'product' ) ) );
				} elseif( is_product_taxonomy() ) { // タクソノミーページ
					if( is_product_category() ) { // A product category
						$permalink_url = urlencode( esc_url( get_term_link( $wp_query->queried_object->term_id, $wp_query->queried_object->taxonomy ) ) );
					} elseif( is_product_tag() ) { // A product tag
						$permalink_url = urlencode( esc_url( get_term_link( $wp_query->queried_object->term_id, $wp_query->queried_object->taxonomy ) ) );
					}
				} elseif( is_product() ) { // A single product
					$permalink_url = urlencode( esc_url( get_permalink( $post->ID ) ) );
				}
			} elseif( function_exists( 'is_woocommerce' ) && is_cart() ) { // The cart
				return false;
			} elseif( function_exists( 'is_woocommerce' ) && is_checkout() ) { // The checkout
				return false;
			} elseif( function_exists( 'is_woocommerce' ) && is_account_page() ) { // Customer account
				return false;
			} elseif( function_exists( 'is_bbpress' ) && is_bbpress() ) { // bbPress
				$permalink_url = urlencode( esc_url( get_permalink( $post->ID ) ) );
			} elseif( is_singular() ) {
				$permalink_url = urlencode( esc_url( get_permalink( $post->ID ) ) );
			} elseif( is_archive() ) {
				if( is_category() || is_tag() || is_tax() ) {
					$permalink_url = urlencode( esc_url( get_term_link( $wp_query->queried_object->term_id, $wp_query->queried_object->taxonomy ) ) );
				} elseif( is_author() ) {
					$permalink_url = urlencode( esc_url( get_author_posts_url( $wp_query->queried_object->data->ID ) ) );
				} elseif( is_date() ) {
					if( is_year() ) {
						$permalink_url = urlencode( esc_url( get_year_link( $wp_query->query['year'] ) ) );
					} elseif( is_month() ) {
						$permalink_url = urlencode( esc_url( get_month_link( $wp_query->query['year'], $wp_query->query['monthnum'] ) ) );
					} elseif( is_day() ) {
						$permalink_url = urlencode( esc_url( get_day_link( $wp_query->query['year'], $wp_query->query['monthnum'], $wp_query->query['day'] ) ) );
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
			return $permalink_url;

		}
	}

#
# Excerpt
#
	if ( ! function_exists( 'sse_get_the_excerpt' ) ) {
		/**
		 * Get Excerpt From Post Content
		 * 
		 * @param string $post_content
		 * @param int    $excerpt_length
		 * 
		 * @return string
		**/
		function sse_get_the_excerpt( $post_content, $excerpt_length = 200 ) {

			// Remove Spaces, Line Breaks, HTML Tags, Shortcodes
			$the_excerpt = preg_replace( '/\[[^\]]+]/i', '', $post_content );
			$the_excerpt = wp_strip_all_tags( $the_excerpt );
			$the_excerpt = str_replace( array( "\n", "\r", '　', '"' ), '', $the_excerpt );
			$the_excerpt = mb_ereg_replace( "/[^a-zA-Z0-9]\s[^a-zA-Z0-9]/i", '', $the_excerpt );
			return mb_substr( $the_excerpt, 0, $excerpt_length );
			
		}
	}



