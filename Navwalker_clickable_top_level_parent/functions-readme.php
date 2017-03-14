<?php
/** This is not a complete functions.php, just the function you should add to your child-theme or parent-theme to enqueue the js and css files needed.

/* ENQUEUE THE CORRECT STYLESHEETS TO THE CHILD/PARENT THEME OF YOUR LIKING */ 
add_action( 'wp_enqueue_scripts', 'nw_enqueue_styles' );

    function nw_enqueue_styles() {
        wp_enqueue_style( 'child-nw-styles', get_stylesheet_directory_uri() . '/css/wp-navwalker-addon.css', array());
        wp_enqueue_style( 'child-nw-styles', get_stylesheet_directory_uri() . '/css/font-awesome.min.css', array());
        wp_enqueue_script( 'child-nw-scripts', get_stylesheet_directory_uri() . '/js/wp-navwalker-tp.js', array(), '0.1.0', true );
    }


/* Call the bootstrap-wp-navwalker.php from the child-theme, if you are modifying the parent theme keep this commented or just dont copy it :p */
//require get_stylesheet_directory() . '/inc/bootstrap-wp-navwalker.php';


/* The changes to the bootstrap-wp-navwalker.php by Edward McIntyre are around line 92.