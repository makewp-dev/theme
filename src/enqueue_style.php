<?php
namespace MakeWP\Theme;

/**
 * wp_enqueue_style() style.css
 * WordPress doesn't do this by default.
 * 
 * Doesn't silently fail bc style.css is a required file for every WordPress theme
 */
function enqueue_style()
{
  add_action( 'wp_enqueue_scripts', function(){
    wp_enqueue_style( get_template(), get_stylesheet_uri() );
  } );
}
