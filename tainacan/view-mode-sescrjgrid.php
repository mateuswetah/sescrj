<?php
	$has_three_metadata = true;

	// In this variation of the view mode, we have to fetch the second metadata
	$metadata_repository = \Tainacan\Repositories\Metadata::get_instance();
	$metadata_objects = [];
	$is_repository_level = !isset($request['collection_id']);
	
	if ( !$is_repository_level ) {
		$collection = tainacan_get_collection([ 'collection_id' => $request['collection_id'] ]);
		$metadata_objects = $metadata_repository->fetch_by_collection(
			$collection,
			[
				'posts_per_page' => 50,
				// 'post_status' => 'publish'
			],
			'OBJECT'
		);
	} else {
		$metadata_objects = $metadata_repository->fetch(
			[ 
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
	
	if ( count($metadata_objects) < 2 ) {
		$has_three_metadata = false;
	}

?>

<?php if ( have_posts() ) : ?>
	<ul id="tainacan-sescrj-grid-container" class="tainacan-sescrj-grid-container">

		<?php $item_index = 0; while ( have_posts() ) : the_post(); ?>
			
			<li class="tainacan-sescrj-grid-item">
				<a href="<?php echo get_item_link_for_navigation(get_permalink(), $item_index); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="sescrj-grid-item-thumbnail">
							<?php the_post_thumbnail( 'tainacan-full-large' ); ?>
							<div class="skeleton"></div> 
						</div>
					<?php else : ?>
						<div class="sescrj-grid-item-thumbnail sescrj-placeholder">
							<div class="sescrj-placeholder--text"><?php _e('Imagem indisponível', 'sescrj'); ?></div> 
						</div>
					<?php endif; ?>

					<?php
						$collection_id = str_replace('_item', '', str_replace('tnc_col_', '', get_post_type()));
						$title_class = 'metadata-title';
					?>

					<div class="<?php echo $title_class; ?>">
						<h3><?php the_title(); ?></h3>
					</div>
					<?php if ( $has_three_metadata ) : ?>
						<div class="metadata-description">
							<p>
								<?php
									foreach($metadata_objects as $metadata_object) {
										if ( $metadata_object->get_metadata_type() !== 'Tainacan\Metadata_Types\Core_Title' ) {
											tainacan_the_metadata([ 'metadata' => $metadata_object ]);
										}
									}
								?>
							</p>
						</div>
					<?php endif; ?>
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
