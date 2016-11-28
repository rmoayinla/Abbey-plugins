<?php

class Register_Post_Type{
	
	$args = array(
      'label' => 'Movie Reviews',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'movie-reviews'),
        'query_var' => true,
        'menu_icon' => 'dashicons-video-alt',
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'trackbacks',
            'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',)
        );

	public function __construct(){
		add_action( 'init', array( $this, 'init' );

	}

	private function init(){
		register_post_type( 'movie-reviews', $this->args );
	}
}