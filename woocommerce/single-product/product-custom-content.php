<article>
	<div class="row">
		<div class="col-sm-3">
			<?php  ?>
			<img src="<?php the_sub_field( 'image' ); ?>" alt="<?php the_sub_field( 'headline' ); ?>">
		</div>
		<div class="col-sm-9">
			<h2>
				<?php the_sub_field( 'headline' ); ?>
			</h2>
			<?php the_sub_field( 'content' ); ?>
		</div>
	</div>
</article>