<article class="highlights">
	<div class="row">
		<div class="<?php echo ( $count % 2 == 0 ) ? 'col-sm-6' : 'col-sm-6 col-sm-push-6' ?>">
			<img src="<?php the_sub_field( 'image' ); ?>" alt="<?php the_sub_field( 'headline' ); ?>">
		</div>
		<div class="<?php echo ( $count % 2 == 0 ) ? 'col-sm-6' : 'col-sm-6 col-sm-pull-6' ?>">
			<h2>
				<?php the_sub_field( 'headline' ); ?>
			</h2>
			<?php the_sub_field( 'content' ); ?>
		</div>
	</div>
</article>