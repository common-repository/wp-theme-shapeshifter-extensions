<?php 
if( ! class_exists( 'ShapeShifter_Extensions_Post_Type_Feed' ) ) {
class ShapeShifter_Extensions_Post_Type_Feed {
	
	function __construct() {

		# Add Actions
			$this->add_actions();

	}
		# Add Actions
			function add_actions() {

				# Register Post Type Feed
					add_action( 'init', array( $this, 'register_post_type_feed' ) );

				# Meta Boxes
					add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

			}


				# Register Post Type Feed
					function register_post_type_feed() {

						# Post Type "shapeshifter-feed"
							$labels = array(
								'name'               => esc_html__( 'Feeds', 'wpes' ),
								'singular_name'      => esc_html__( 'Feed', 'wpes' ),
								'menu_name'          => esc_html__( 'Feeds', 'wpes' ),
								'name_admin_bar'     => esc_html__( 'Feed', 'wpes' ),
								'add_new'            => esc_html__( 'Add new', 'wpes' ),
								'add_new_item'       => esc_html__( 'Add New Feed', 'wpes' ),
								'new_item'           => esc_html__( 'New Feed', 'wpes' ),
								'edit_item'          => esc_html__( 'Edit Feed', 'wpes' ),
								'view_item'          => esc_html__( 'View Feed', 'wpes' ),
								'all_items'          => esc_html__( 'All Feeds', 'wpes' ),
								'search_items'       => esc_html__( 'Search Feeds', 'wpes' ),
								'parent_item_colon'  => esc_html__( 'Parent Feeds:', 'wpes' ),
								'not_found'          => esc_html__( 'No Feeds found.', 'wpes' ),
								'not_found_in_trash' => esc_html__( 'No Feeds found in Trash.', 'wpes' )
							);

							$args = array(
								'labels'              => $labels,
								'public'              => false,
								'exclude_from_search' => true,
								'publicly_queryable'  => false,
								'show_ui'             => true,
								'show_in_menu'        => true,
								'query_var'           => false,
								'rewrite'             => array( 'slug' => 'shapeshifter-feed' ),
								'capability_type'     => 'post',
								'has_archive'         => false,
								'hierarchical'        => false,
								'menu_position'       => null,
								'supports'            => array( 'title', 'editor', 'author' )

							);

							register_post_type( 'shapeshifter-feed', $args );
						
						# Taxonomy "shapeshifter-feed-category"
							$labels = array(
								'name'              => esc_html__( 'Categories', 'wpes' ),
								'singular_name'     => esc_html__( 'Category', 'wpes' ),
								'search_items'      => esc_html__( 'Search Categories', 'wpes' ),
								'all_items'         => esc_html__( 'All Categories', 'wpes' ),
								'parent_item'       => esc_html__( 'Parent Category', 'wpes' ),
								'parent_item_colon' => esc_html__( 'Parent Category:', 'wpes' ),
								'edit_item'         => esc_html__( 'Edit Category', 'wpes' ),
								'update_item'       => esc_html__( 'Update Category', 'wpes' ),
								'add_new_item'      => esc_html__( 'Add New Category', 'wpes' ),
								'new_item_name'     => esc_html__( 'New Category Name', 'wpes' ),
								'menu_name'         => esc_html__( 'Category', 'wpes' ),
							);

							$args = array(
								'hierarchical'      => true,
								'labels'            => $labels,
								'show_ui'           => true,
								'show_admin_column' => true,
								'query_var'         => false,
								'rewrite'           => array( 'slug' => 'shapeshifter-feed-category' ),
							);

							register_taxonomy( 'shapeshifter-feed-category', array( 'shapeshifter-feed' ), $args );
						
					}

				# Meta Boxes
					function add_meta_boxes() {

						
						
					}

}
}

