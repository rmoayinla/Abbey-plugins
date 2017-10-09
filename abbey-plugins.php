<?php
/*
* Plugin Name: Abbey Plugins
* Plugin URI: 
* Description: Use this plugins with my theme
* Author: Rabiu Mustapha
* Author URI: 
* Version: 0.1
* Text Domain: abbey-plugins
* Github Plugin URI: 
*/

if ( !defined( 'ABSPATH' ) ) exit;

if( !defined ( 'ABBEY_PLUGINS_DIR' ) )
	define( 'ABBEY_PLUGINS_DIR', wp_normalize_path( trailingslashit( plugin_dir_path( __FILE__ ) ) ) );

if( !defined( 'ABBEY_PLUGINS_URL' ) )
	define( 'ABBEY_PLUGINS_URL', wp_normalize_path( trailingslashit( plugin_dir_url( __FILE__ ) ) ) );
