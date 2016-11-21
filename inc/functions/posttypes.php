<?php

// Custom Post Type
function create_my_post_types()
{
    // Slider
	register_post_type( 'Slider',
		array(
			'labels' 		=> array(
			'name' 			=> __('Slides', 'venture'),
			'singular_name' => __('Slide', 'venture'),
			'add_new' 		=> __('Add New Slide', 'venture'),
			'add_new_item' 	=> __('Add New Slide', 'venture'),
			'edit' 			=> __('Edit', 'venture'),
			'edit_item' 	=> __('Edit Slide', 'venture'),
			'view' 			=> __('View', 'venture'),
			'view_item' 	=> __('View Slide', 'venture'),
		),
			'public' 		=> true,
			'supports' 		=> array('title','editor','thumbnail'),
		)
	);

	// Portfolio
	register_post_type( 'Portfolio',
		array(
			'labels' 		=> array(
			'name' 			=> __('Portfolio', 'venture'),
			'singular_name' => __('Portfolio', 'venture'),
			'add_new' 		=> __('Add New Project', 'venture'),
			'add_new_item' 	=> __('Add New Project', 'venture'),
			'edit' 			=> __('Edit', 'venture'),
			'edit_item' 	=> __('Edit Project', 'venture'),
			'view' 			=> __('View', 'venture'),
			'view_item' 	=> __('View Project', 'venture'),
		),

			'public' 		=> true,
			'supports' 		=> array('title','editor','thumbnail'),
		)
	);

	// Clients
	register_post_type( 'Clients',
		array(
			'labels' 		=> array(
			'name' 			=> __('Clients', 'venture'),
			'singular_name' => __('Clients', 'venture'),
			'add_new' 		=> __('Add New Client', 'venture'),
			'add_new_item' 	=> __('Add New Client', 'venture'),
			'edit' 			=> __('Edit', 'venture'),
			'edit_item' 	=> __('Edit Client', 'venture'),
			'view' 			=> __('View', 'venture'),
			'view_item' 	=> __('View Client', 'venture'),
		),

			'public' 		=> true,
			'supports' 		=> array('title','editor','thumbnail'),
		)
	);
}
add_action( 'init', 'create_my_post_types' );

//Portfolio  Filters
function filter_init() {
	// Initialize New Taxonomy Labels
    $labels = array(
        'name' => _x( 'Filters', 'taxonomy general name', 'venture' ),
        'singular_name' => _x( 'Filter', 'taxonomy singular name', 'venture' ),
        'search_items' =>  __( 'Search Types', 'venture' ),
        'all_items' => __( 'All Filters', 'venture' ),
        'parent_item' => __( 'Parent Filter', 'venture' ),
        'parent_item_colon' => __( 'Parent Filter:', 'venture' ),
        'edit_item' => __( 'Edit Filters', 'venture' ),
        'update_item' => __( 'Update Filter', 'venture' ),
        'add_new_item' => __( 'Add New Filter', 'venture' ),
        'new_item_name' => __( 'New Filter Name', 'venture' ),
    );
    // Custom taxonomy for filters
    register_taxonomy('filter', array('portfolio'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'filter' ),
    ));
}
add_action( 'init', 'filter_init' );