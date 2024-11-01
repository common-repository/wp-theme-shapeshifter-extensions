<?php 
if( ! class_exists( 'SSE_Post_Type_Link' ) ) {
class SSE_Post_Type_Link {
	
	function __construct() {

		# Add Actions
			$this->add_actions();

	}
		# Add Actions
			function add_actions() {

				# Register Post Type Link
					add_action( 'init', array( $this, 'register_post_type_link' ) );

				# Meta Boxes
					add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

			}


				# Register Post Type Link
					function register_post_type_link() {

						# Post Type "shapeshifter-link"
							$labels = array(
								'name'               => esc_html__( 'Links', 'wpes' ),
								'singular_name'      => esc_html__( 'Link', 'wpes' ),
								'menu_name'          => esc_html__( 'Links', 'wpes' ),
								'name_admin_bar'     => esc_html__( 'Link', 'wpes' ),
								'add_new'            => esc_html__( 'Add new', 'wpes' ),
								'add_new_item'       => esc_html__( 'Add New Link', 'wpes' ),
								'new_item'           => esc_html__( 'New Link', 'wpes' ),
								'edit_item'          => esc_html__( 'Edit Link', 'wpes' ),
								'view_item'          => esc_html__( 'View Link', 'wpes' ),
								'all_items'          => esc_html__( 'All Links', 'wpes' ),
								'search_items'       => esc_html__( 'Search Links', 'wpes' ),
								'parent_item_colon'  => esc_html__( 'Parent Links:', 'wpes' ),
								'not_found'          => esc_html__( 'No Links found.', 'wpes' ),
								'not_found_in_trash' => esc_html__( 'No Links found in Trash.', 'wpes' )
							);

							$args = array(
								'labels'              => $labels,
								'public'              => false,
								'exclude_from_search' => true,
								'publicly_queryable'  => false,
								'show_ui'             => true,
								'show_in_menu'        => true,
								'query_var'           => false,
								'rewrite'             => array( 'slug' => 'shapeshifter-link' ),
								'capability_type'     => 'post',
								'has_archive'         => false,
								'hierarchical'        => false,
								'menu_position'       => null,
								'supports'            => array( 'title', 'editor', 'author' )

							);

							register_post_type( 'shapeshifter-link', $args );
						
						# Taxonomy "shapeshifter-link-category"
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
								'rewrite'           => array( 'slug' => 'shapeshifter-link-category' ),
							);

							register_taxonomy( 'shapeshifter-link-category', array( 'shapeshifter-link' ), $args );
						
					}

				# Meta Boxes
					function add_meta_boxes() {

						
						
					}

}
}
