<?php
/*
* Plugin Name: Abbey Author Widget
* Plugin URI: 
* Description: Use this plugins with my theme
* Author: Rabiu Mustapha
* Author URI: 
* Version: 0.1
* Text Domain: abbey-author-widget
* Github Plugin URI: 
*/

class Abbey_Author_Widget extends WP_Widget {
	public function __construct(){
		//parent::__construct( $this->id, $this->name, array ( $this->description ) );
		parent::__construct( "abbey_author_widget", __( "Abbey Author Widget", "abbey-author-widget"), 
				array( 
					"description" => __( "This widget display the post author info", "abbey-author-widget" )
				) 
		);


	}

	public function widget( $args, $instance ){
		$before_widget = ( isset( $args["before_widget"] ) ) ? $args["before_widget"] : "";
		$after_widget = ( isset( $args["after_widget"] ) ) ? $args["after_widget"] : "";
		$before_title = ( isset( $args["before_title"] ) ) ? $args["before_title"] : "";
		$after_title = ( isset( $args["after_title"] ) ) ? $args["after_title"] : "";

		echo $before_widget.$before_title.apply_filters( "widget_title", $instance["title"] ).$after_title;
		echo $this->content().$after_widget;
	}

	function content(){
		global $post, $authordata;
		$id = $post->ID;
		$author_id = ( is_object( $authordata ) ) ? $authordata->ID : $post->post_author;
		$author_photo = get_avatar( $author_id, 120, "", "", array("class" => "img-circle" ) );
		?>
			<div class="author-photo text-center">
				<?php echo $author_photo; ?>
			</div>
			<div class="author-details text-center">
				<h4 class="author-name"><?php echo $authordata->display_name; ?> </h4>
				<p class="author-role"> <?php echo $this->author_role( $authordata ); ?> </p>
				<p class="author-rating"><?php echo $this->author_rating( $authordata ); ?> </p>
				<summary class="author-bio">
					<p><a data-toggle="collapse" data-target="#author-bio-description" class="clickable"> <?php _e( "Author Bio:", "abbey-author-widget" ); ?></a></p>
					<p id="author-bio-description" class="collapse"><?php echo $authordata->description; ?> </p>
				</summary>
				
				<div>
					<a href="<?php echo get_author_posts_url( $author_id ); ?>" class="btn btn-default" role="button">
						<?php _e( "Author's posts", "abbey-author-widget" ); ?>
					</a>
					<a href="#" class="btn btn-primary" role="button">
						<?php _e( "Author's profile", "abbey-author-widget" ); ?>
					</a>
				</div>
			</div>

			
					<?php
	}

	function author_role( $author_data ){
		if( empty( $author_data->caps ) || !is_array( $author_data->roles ) )
			return; 
		reset( $author_data->roles );
		$roles = $author_data->roles;
		return ucwords( $roles[0] ); 
	}
	function author_rating( $author_data ){
		if( empty( $author_data->allcaps ) || !is_array( $author_data->allcaps ) )
			return;
		$caps = $author_data->allcaps;
		$i = 0;
		$rating = "";
		if( array_key_exists( 'manage_options', $caps ) && $caps[ 'manage_options' ] == 1 )
			$i = 5; 
		elseif( array_key_exists( 'switch_themes', $caps ) && $caps[ 'switch_themes' ] == 1 )
			$i = 4; 
		elseif( array_key_exists( 'moderate_comments', $caps ) && $caps[ 'moderate_comments' ] == 1 )
			$i = 3; 
		elseif( array_key_exists( 'publish_posts', $caps ) && $caps[ 'publish_posts' ] == 1 )
			$i = 2; 
		elseif( array_key_exists( 'read', $caps ) && $caps[ 'read' ] == 1 )
			$i = 1; 
		if( $i > 0 ){
			for( $x = 1; $x <= $i; $x++ ){
				$rating .= "<i class='fa fa-star'></i>";
			}
		}
		return $rating;
	}

	public function form ( $instance ){ 
		$title = ( isset( $instance["title"] ) ) ? $instance["title"] : "Default title";
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
	    	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
	    	name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	     </p>	<?php
	}

	public function update( $new_instance, $old_instance ){
		$instance = array();
		$instance["title"] = ( isset($new_instance["title"] ) ) ? strip_tags( $new_instance["title"] ) : "";
		return $instance;
	}

}

add_action( "widgets_init", function(){
	register_widget( "Abbey_Author_Widget" );
} );