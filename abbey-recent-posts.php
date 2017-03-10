<?php
/*
* Plugin Name: Abbey Recent Posts 
* Plugin URI: 
* Description: Use this plugins with my theme
* Author: Rabiu Mustapha
* Author URI: 
* Version: 0.1
* Text Domain: abbey-recent-posts
* Github Plugin URI: 
*/

class Abbey_Recent_Posts extends WP_Widget{
	public function __construct(){
		parent::__construct( "abbey_recent_posts", __( "Abbey Recent Posts", "abbey-recent-posts"), 
				array( 
					"description" => __( "This widget display the post author info", "abbey-recent-posts" )
				) 
		);
		add_action ( "wp_enqueue_scripts", array ( $this, "enqueJS" ) );

		add_action ( "wp_ajax_nopriv_abbey_recent_posts", array ( $this, "popup_post" ) );

		add_action ( "wp_ajax_abbey_recent_posts", array ( $this, "popup_post" ) );
	}
	function form( $instance ){
		$title = ( isset( $instance["title"] ) ) ? $instance["title"] : "Default title";
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
	    	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
	    	name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	     </p>	<?php
	}
	function widget( $args, $instance ){
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
		$args = [ 	"no_found_rows" => true, "post_type" => $post_type, 
					"posts_per_page" => 3, "post__not_in" => array( $id ), 
					'update_post_term_cache' => false, 'update_post_meta_cache' => false
				];
		$recent_posts = new WP_Query( $args );
		?>
		<?php if( $recent_posts->have_posts() ) : ?>
			<div class="abbey-recent-posts">
			<?php while( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
				<div class="thumbnail thumbnail-post">
					<div class="row">
						<?php if( has_post_thumbnail() ) : ?>
							<figure class="col-md-4 post-thumbnail-image"><?php the_post_thumbnail(); ?></figure>
							<div class="col-md-8 post-thumbnail-content">
						<?php else : ?>
							<div class="col-md-12">
						<?php endif; ?>
								<h4 class="post-thumbnail-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								<time><?php the_time( get_option( 'date_format' ) ); ?></time>
							</div>
						
						<footer class='icons-footer'>
							<button class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="fa fa-ellipsis-v"></span></button>
							<ul class="dropdown-menu">
								<li> 
									<a class='popup-post' data-post-id="<?php the_ID(); ?>" data-post-type="<?php echo get_post_type(); ?>" 
												data-url = "<?php echo admin_url( "admin-ajax.php" )?>" href="" >
										<?php _e( "View in popup", "abbey-recent-posts" ); ?> 
									</a>
								</li>
							</ul>
						</footer>
					</div><!--.row closes -->
				</div><!-- thumbnail post closes-->
			<?php 	endwhile; wp_reset_postdata(); ?>
			</div>
			<?php endif;
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