<?php
/*
* Plugin Name: Abbey Extend TinyMce
* Plugin URI: 
* Description: Use this plugins with my theme
* Author: Rabiu Mustapha
* Author URI: 
* Version: 0.1
* Text Domain: abbey-plugins
* Github Plugin URI: 
*/

class Abbey_TinyMce_Extended {

	private $style_formats = array(); 

	private $style_css = "";

	public function __construct(){

		add_filter( 'mce_buttons_2', array( $this, 'abbey_mce_buttons_2' ) );
		
		// Attach callback to 'tiny_mce_before_init'
		add_filter( 'tiny_mce_before_init', array( $this, 'abbey_insert_mce_styles' ) ); 

		add_action( 'init', array( $this, 'abbey_add_editor_styles' ) );
	}

	function abbey_mce_buttons_2( $buttons ) {
		array_unshift($buttons, 'styleselect');
		return $buttons;
	}

	

	function abbey_insert_mce_styles ( $init_array ){
		
		$this->add_mce_styles( array("title" => __( "Highlight", "abbey-plugins" ),"inline" => "mark","classes" => "mark highlight" ) );
		$this->add_mce_styles( array("title" => __( "Delete", "abbey-plugins" ),"inline" => "s","classes" => "delete" ) );
		$this->add_mce_styles( array("title" => __( "Small", "abbey-plugins" ),"inline" => "small","classes" => "small" ) );
		$this->add_mce_styles( array("title" => __( "Initialism", "abbey-plugins" ),"inline" => "abbr","classes" => "initialism" ) );
		$this->add_mce_styles( array("title" => __( "Box", "abbey-plugins" ),"block" => "div","classes" => "well" ) );
		$this->add_mce_styles( array("title" => __( "Row", "abbey-plugins" ),"block" => "div","classes" => "row", "wrapper" => true ) );
		$this->add_mce_styles( array("title" => __( "2 Columns", "abbey-plugins" ),"block" => "div","classes" => "col-md-6", "wrapper" => true, "exact" => false ) );
		$this->add_mce_styles( array("title" => __( "Panels", "abbey-plugins" ),"block" => "div","classes" => "panel panel-default", "wrapper" => true ) );
		$this->add_mce_styles( array("title" => __( "Panels heading", "abbey-plugins" ),"block" => "div","classes" => "panel-heading", "wrapper" => true ) );
		$this->add_mce_styles( array("title" => __( "Panels Body", "abbey-plugins" ),"block" => "div","classes" => "panel-body", "wrapper" => true ) );
		$this->add_mce_styles( array("title" => __( "Panels Footer", "abbey-plugins" ),"block" => "div","classes" => "panel-footer", "wrapper" => true ) );
		$this->add_mce_styles( array("title" => __( "Button", "abbey-plugins" ),"selector" => "a[href]","classes" => "btn btn-default" ) );
		$this->add_mce_styles( array("title" => __( "Large Button", "abbey-plugins" ),"selector" => "a[href]","classes" => "btn btn-default btn-lg" ) );

		$style_formats = apply_filters( "abbey_mce_styles", $this->style_formats );

		$init_array[ "style_formats" ] = json_encode( $style_formats );
		
		return $init_array;
	}

	function add_mce_styles ( $style ) {
		$this->style_formats[] = (array) $style;
	}
	

	function abbey_add_editor_styles() {
		add_editor_style();
	}
	

}

new Abbey_TinyMce_Extended();