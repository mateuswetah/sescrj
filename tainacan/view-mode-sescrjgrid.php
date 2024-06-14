<?php if ( have_posts() ) : ?>
	<?php
		$metadata_repository = \Tainacan\Repositories\Metadata::get_instance();
		$metadata_objects = [];
		$is_repository_level = !isset($request['collection_id']);
		$has_title_enabled = true; // in_array('title', $view_mode_displayed_metadata); -- Set to true as it would only work in the repository level
		$has_meta = isset($view_mode_displayed_metadata['meta']) && count($view_mode_displayed_metadata['meta']) > 0;

		if ( $has_meta ) {
			if ( !$is_repository_level ) {
				$collection = tainacan_get_collection([ 'collection_id' => $request['collection_id'] ]);
				$metadata_objects = $metadata_repository->fetch_by_collection(
					$collection,
					[
						'posts_per_page' => 50,
						'post__in' => $view_mode_displayed_metadata['meta'],
						// 'post_status' => 'publish'
					],
					'OBJECT'
				);
			} else {
				$metadata_objects = $metadata_repository->fetch(
					[ 
						'post__in' => $view_mode_displayed_metadata['meta'],
						'meta_query' => [
							[
								'key'     => 'collection_id',
								'value'   => 'default',
								'compare' => '='
							]
						],
						// 'post_status' => 'publish',
						'posts_per_page' => 50,
						'include_control_metadata_types' => true
					],
					'OBJECT'
				);
			}
		}
	?>
	<ul id="tainacan-sescrj-grid-container" class="tainacan-sescrj-grid-container">

		<?php $item_index = 0; while ( have_posts() ) : the_post(); ?>
			
			<li class="tainacan-sescrj-grid-item">
				<a href="<?php echo sescrj_get_item_link_for_navigation(get_permalink(), $item_index); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="sescrj-grid-item-thumbnail">
							<?php the_post_thumbnail( 'medium' ); ?>
							<div class="skeleton"></div> 
						</div>
					<?php else : ?>
						<div class="sescrj-grid-item-thumbnail sescrj-placeholder">
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/undefined-image.png'; ?>" alt="<?php _e('Imagem indisponível', 'sescrj'); ?>" /> 
						</div>
					<?php endif; ?>
					<div class="metadata-area">
						<?php if ( $has_title_enabled ): ?>
							<div class="metadata-title">
								<h3><?php the_title(); ?></h3>
							</div>
						<?php endif; ?>
						<?php if ( $has_meta ): ?>
							<div class="metadata-secondary">
								<p>
									<?php
										tainacan_the_metadata(array(
											'exclude_title' => true,
											'metadata__in' => $view_mode_displayed_metadata['meta']
										));
									?>
								</p>
							</div>
						<?php endif; ?>
					<div class="metadata-area">
				</a>
			</li>	
		
		<?php $item_index++; endwhile; ?>
	
	</ul>

<?php else : ?>
	<div class="tainacan-sescrj-grid-container">
		<section class="section">
			<div class="content has-text-gray-4 has-text-centered">
				<p>
					<span class="icon is-large">
						<i class="tainacan-icon tainacan-icon-48px tainacan-icon-items"></i>
					</span>
				</p>
				<p><?php echo __( 'Nenhum item encontrado.','sescrj' ); ?></p>
			</div>
		</section>
	</div>
<?php endif; ?>
