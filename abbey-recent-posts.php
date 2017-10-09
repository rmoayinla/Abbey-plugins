<?php
/**
* Plugin Name: Abbey Recent Posts 
* Plugin URI: 
* Description: Use this plugins with my theme
* Author: Rabiu Mustapha
* Author URI: 
* Version: 0.1
* Text Domain: abbey-recent-posts
* Github Plugin URI: 
*
*
* Display recent posts in single posts and post_type pages 
*
* The plugin can be tweaked via a plugin admin page to determine recent post no, widget title etc
* the styling for this plugin comes with abbey theme, will work on adding a custom styling css file 
*
*/

class Abbey_Recent_Posts extends WP_Widget{
	
	/**
	 * Class constructor
	 * called when an instance of this class object is instantiated 
	 * this method calls the parent class (WP_Widget) and set the widget name and description 
	 * enqueues styles and scripts for the recent posts plugin
	 * hook into wp_ajax_ methods to load posts via Ajax 
	 *@since: 1.0
	 */
	public function __construct(){
		
		//call the parent widget and provide widget name and description //
		parent::__construct( 
					"abbey_recent_posts", 
					__( "Abbey Recent Posts", "abbey-recent-posts"), 
					array( 
						"description" => __( "This widget display the post author info", "abbey-recent-posts" )
					) 
		);

		//load the main plugin javascript, needed for sending AJAX //
		add_action ( "wp_enqueue_scripts", array ( $this, "enqueJS" ) );

		/**
		 * Hook into wp_ajax_ hooks to load posts via AJAX into popup 
		 */
		add_action ( "wp_ajax_nopriv_abbey_recent_posts", array ( $this, "popup_post" ) );
		add_action ( "wp_ajax_abbey_recent_posts", array ( $this, "popup_post" ) );
	}

	/**
	 * Widget form in the admin dashboard 
	 * All settings fields for the widget have to be provided here
	 * settings can include title for widget, number of posts to show etc 
	 */
	function form( $instance ){
		$title = ( isset( $instance["title"] ) ) ? $instance["title"] : __( "Recent posts", "abbey-recent-posts" );
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>

	    	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
	    		name="<?php echo $this->get_field_name('title'); ?>" type="text" 
	    		value="<?php echo esc_attr( $title ); ?>" 
	    	/>
	     </p>	<?php
	}

	/**
	 * Displays the actual widget content in the front end 
	 *@param: 	$args 		array 		array of argument from the theme display_sidebar 
	 * 			$instance 	array 		array of widget settings set in the admin dashboard 
	 */
	function widget( $args, $instance ){
		if( is_page() ) return; 
		$before_widget = ( isset( $args["before_widget"] ) ) ? $args["before_widget"] : "<aside class='widget abbey_recent_posts_widget'>";
		$after_widget = ( isset( $args["after_widget"] ) ) ? $args["after_widget"] : "</aside>";
		$before_title = ( isset( $args["before_title"] ) ) ? $args["before_title"] : "<h4 class='widget-title'>";
		$after_title = ( isset( $args["after_title"] ) ) ? $args["after_title"] : "</h4>";

		echo $before_widget.$before_title.apply_filters( "widget_title", $instance["title"] ).$after_title;
		echo $this->content().$after_widget;
	}

	function content(){
		global $post;
		$id = $post->ID;
		$post_type = $post->post_type;
		$args = [ 	
					"no_found_rows" => true, 
					"post_type" => $post_type, 
					"posts_per_page" => 3, 
					"post__not_in" => array( $id ), 
					'update_post_term_cache' => false, 
					'update_post_meta_cache' => false, 
					'cache_results' => false
				];
		$recent_posts = new WP_Query( $args );
		//bail if we dont have any recent posts //
		if( !$recent_posts->have_posts() ) return; 
		?>
		<div class="abbey-recent-posts">
			<?php while( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
				<?php load_template( trailingslashit( plugin_dir_path( __FILE__ ) )."partials/thumbnail-post.php", false ); ?>

			<?php 	endwhile; wp_reset_postdata(); ?>
		</div>
		<?php 
	}

	function popup_post(){
		if( empty( $_POST[ "action" ] ) || $_POST[ "action" ] !== "abbey_recent_posts" )
			return;

		$post_type = $_POST[ "arp_post_type" ];
		$post_id = $_POST["arp_post_id"];
		$html = "";

		$popup_post = new WP_Query(["no_found_rows" => true, "post_type" => $post_type, "p" => $post_id ]);
		?>
		<?php if( $popup_post->have_posts() ) : while( $popup_post->have_posts() ):  $popup_post->the_post();?>
			<?php $html = wp_cache_get( $post_id, 'arp_popup_posts' ); ?>
			<?php if( !$html ) : ?>
				<?php ob_start(); ?>
				<div class="single-post-panel">
					<header class="entry-header">
						<h1 class="post-title" itemprop="headline"><?php the_title(); ?></h1>
						<ul class="breadcrumb post-info"><?php abbey_post_info(); ?></ul>
					</header><!-- #page-content-header closes -->

					<section class="post-entry">
						<?php if( has_post_thumbnail() ) : ?>
						<figure class="post-thumbnail" itemprop="image">
							<?php the_post_thumbnail( "large" ); ?> 
						</figure>
						<figcaption class="post-thumbnail-caption">
							<?php the_post_thumbnail_caption(); ?>
						</figcaption>
						<?php endif; ?>

						<article <?php abbey_post_class(); ?> id="post-<?php the_ID(); ?>">
							<?php the_content(); ?>
							<div><?php abbey_post_pagination(); ?> </div>
						</article>
					</section><!-- .post-entry closes -->
				
			</div>
			<?php $html = ob_get_clean(); wp_cache_add( $post_id, $html, "arp_popup_posts" ); ?>
		<?php endif; echo $html; 
		endwhile;  endif; 

		wp_die();

	}
	
	function enqueJs(){
		wp_enqueue_script( "abbey-recent-posts", plugin_dir_url( __FILE__ )."/recent-posts.js", array( "jquery" ), 1.0, true );
	}

	function update( $new_instance, $old_instance ){
		$instance = array();
		$instance["title"] = ( isset($new_instance["title"] ) ) ? strip_tags( $new_instance["title"] ) : "";
		return $instance;
	}
}

add_action( "widgets_init", function(){
	register_widget( "Abbey_Recent_Posts" );
} );