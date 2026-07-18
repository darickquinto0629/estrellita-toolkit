<?php


add_action( 'init', 'x_cpt_quotes', 0 );


function x_cpt_quotes() {
    register_post_type( 'quotes',
        array(
            'labels' => array(
                'name' => __( 'Quotes' ),
                'singular_name' => __( 'Quotes' ),
                'add_new' => __( 'Add New Quotes Content' ),
                'add_new_item' => __( 'Add New Quotes Content' ),
                'edit' => __( 'Edit Quotes Cotnent' ),
                'edit_item' => __( 'Edit Quotes Content' ),
                'new_item' => __( 'New Quotes Content' ),
                'view' => __( 'View Quotes Content' ),
                'view_item' => __( 'View Quotes Content' ),
                'search_items' => __( 'Search Quotes Content' ),
                'not_found' => __( 'No Quotes Content found' ),
                'not_found_in_trash' => __( 'No Quotes Content found in Trash' ),
                'parent' => __( 'Quotes Content Resource' ),
            ),
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'supports' => array( 'title', 'editor', 'custom-fields', 'thumbnail', 'excerpt', 'revisions' ),
            'public' => true,
            'menu_position' => 18
        )
    );
}



add_action( 'init', 'x_cpt_training_forms', 0 );


function x_cpt_training_forms() {
    register_post_type( 'training_forms',
        array(
            'labels' => array(
                'name' => __( 'Training Forms' ),
                'singular_name' => __( 'Training Forms' ),
                'add_new' => __( 'Add New Training Forms' ),
                'add_new_item' => __( 'Add New Training Forms' ),
                'edit' => __( 'Edit Training Forms Cotnent' ),
                'edit_item' => __( 'Edit Training Forms' ),
                'new_item' => __( 'New Training Forms' ),
                'view' => __( 'View Training Forms' ),
                'view_item' => __( 'View Training Forms' ),
                'search_items' => __( 'Search Training Forms' ),
                'not_found' => __( 'No Training Forms found' ),
                'not_found_in_trash' => __( 'No Training Forms found in Trash' ),
                'parent' => __( 'Training Forms Resource' ),
            ),
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'supports' => array( 'title', 'editor', 'custom-fields', 'thumbnail', 'excerpt', 'revisions' ),
            'public' => true,
            'menu_position' => 19
        )
    );
}



add_action( 'init', 'x_cpt_training_notifications', 0 );


function x_cpt_training_notifications() {
    register_post_type( 'gtt_notifications',
        array(
            'labels' => array(
                'name' => __( 'Notifications' ),
                'singular_name' => __( 'Notifications' ),
                'add_new' => __( 'Add New Notifications' ),
                'add_new_item' => __( 'Add New Notifications' ),
                'edit' => __( 'Edit Quotes Cotnent' ),
                'edit_item' => __( 'Edit Notifications' ),
                'new_item' => __( 'New Notifications' ),
                'view' => __( 'View Notifications' ),
                'view_item' => __( 'View Notifications' ),
                'search_items' => __( 'Search Notifications' ),
                'not_found' => __( 'No Notifications found' ),
                'not_found_in_trash' => __( 'No Notifications found in Trash' ),
                'parent' => __( 'Notifications Resource' ),
            ),
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'supports' => array( 'title', 'editor', 'custom-fields', 'thumbnail', 'excerpt', 'revisions' ),
            'public' => true,
            'menu_position' => 20
        )
    );
}



add_action( 'init', 'x_cpt_customerquote', 0 );


function x_cpt_customerquote() {
    register_post_type( 'customerquote',
        array(
            'labels' => array(
                'name' => __( 'Customer Quotes' ),
                'singular_name' => __( 'Customer Quotes' ),
                'add_new' => __( 'Add New Customer Quotes Content' ),
                'add_new_item' => __( 'Add New Customer Quotes Content' ),
                'edit' => __( 'Edit Customer Quotes Cotnent' ),
                'edit_item' => __( 'Edit Customer Quotes Content' ),
                'new_item' => __( 'New Customer Quotes Content' ),
                'view' => __( 'View Customer Quotes Content' ),
                'view_item' => __( 'View Customer Quotes Content' ),
                'search_items' => __( 'Search Customer Quotes Content' ),
                'not_found' => __( 'No Customer Quotes Content found' ),
                'not_found_in_trash' => __( 'No Customer Quotes Content found in Trash' ),
                'parent' => __( 'Customer Quotes Content Resource' ),
            ),
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'supports' => array( 'title', 'editor', 'custom-fields', 'excerpt',  ),
            'public' => true,
            'menu_position' => 20,
            //'rewrite' => array('slug' => 'customerquote'),
        )
    );
}

