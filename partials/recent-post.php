<?php $thumbnail = abbey_page_media( "", "", false ); ?>
				<div class="thumbnail-post">
					<div class="row">
						<?php if( !empty( $thumbnail ) ) : ?>
							<figure class="col-md-4 post-thumbnail-image"><?php echo $thumbnail; ?></figure>
							<div class="col-md-8 post-thumbnail-content">
						<?php else : ?>
							<div class="col-md-12">
						<?php endif; ?>
								<h4 class="post-thumbnail-title">
									<a href="<?php the_permalink(); ?>" title="<?php esc_html_e( "Continue reading", "abbey-recent-posts" ); ?>">
										<?php the_title(); ?>
									</a>
								</h4>
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