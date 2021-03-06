<?php
/**
 * Template functions used for posts.
 *
 * @package storefront
 */

if ( ! function_exists( 'storefront_post_header' ) ) {
	/**
	 * Display the post header with a link to the single post
	 * @since 1.0.0
	 */
	function storefront_post_header() { ?>
		<style>
			#header-single-post .single-article {
				text-align: left;
				color: black;
				font-size: 26px;
				margin-bottom: 0;
			}
		</style>
		<div id="header-single-post">
			<div class="row">
				<div class="col-xs-12 pull-left">
					<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>

					<?php
					/* translators: used between list items, there is a space after the comma */
					$categories_list = get_the_category_list( __( ', ', 'storefront' ) );

					if ( $categories_list && storefront_categorized_blog() ) : ?>
						<span><?php echo wp_kses_post( $categories_list ); ?></span>
					<?php endif; // End if categories ?>

					<?php
					/* translators: used between list items, there is a space after the comma */
					$tags_list = get_the_tag_list( '', __( ', ', 'storefront' ) );

					if ( $tags_list ) : ?>
						<span class="tags-links"><?php echo wp_kses_post( $tags_list ); ?></span>
					<?php endif; // End if $tags_list ?>

					<?php endif; // End if 'post' == get_post_type() ?>

					<?php /*if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) :
					<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'storefront' ), __( '1 Comment', 'storefront' ), __( '% Comments', 'storefront' ) ); ?></span>
				endif;*/ ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<?php
					if ( is_single() ) {
						//storefront_posted_on();
						the_title( '<h1 class="entry-title single-article" itemprop="name headline">', '</h1>' );
					} else {
						if ( 'post' == get_post_type() ) {
							storefront_posted_on();
						}

						the_title( sprintf( '<h1 class="entry-title" itemprop="name headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' );
					}
					?>
				</div>
			</div>
		</div>
		<div class="blog-post-line"></div>
		<?php
	}
}

if ( ! function_exists( 'storefront_post_content' ) ) {
	/**
	 * Display the post content with a link to the single post
	 * @since 1.0.0
	 */
	function storefront_post_content() {
		?>
		<div class="entry-content" itemprop="articleBody">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'full', array( 'itemprop' => 'image' ) );
		}
		?>
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'storefront' ) ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'storefront' ),
				'after'  => '</div>',
			) );
		?>
		</div><!-- .entry-content -->
		<?php
	}
}

if ( ! function_exists( 'storefront_post_meta' ) ) {
	/**
	 * Display the post meta
	 * @since 1.0.0
	 */
	function storefront_post_meta() {
		?>
		<aside class="entry-meta">
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>

			<?php
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( __( ', ', 'storefront' ) );

			if ( $categories_list && storefront_categorized_blog() ) : ?>
				<span class="cat-links"><?php echo wp_kses_post( $categories_list ); ?></span>
			<?php endif; // End if categories ?>

			<?php
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', __( ', ', 'storefront' ) );

			if ( $tags_list ) : ?>
				<span class="tags-links"><?php echo wp_kses_post( $tags_list ); ?></span>
			<?php endif; // End if $tags_list ?>

			<?php endif; // End if 'post' == get_post_type() ?>

			<?php /*if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) :
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'storefront' ), __( '1 Comment', 'storefront' ), __( '% Comments', 'storefront' ) ); ?></span>
			endif;*/ ?>
		</aside>
		<?php
	}
}

if ( ! function_exists( 'storefront_paging_nav' ) ) {
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function storefront_paging_nav() {
		global $wp_query;

		$big 		= 999999999; 					// need an unlikely integer
		$translated = __( 'Page', 'storefront' );	// Supply translatable string

		echo '<nav class="storefront-pagination">';

		echo wp_kses_post( paginate_links( array(
			'base'					=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'				=> '?paged=%#%',
			'current'				=> max( 1, get_query_var( 'paged' ) ),
			'prev_text'				=> __( '&larr;', 'storefront' ),
			'next_text'				=> __( '&rarr;', 'storefront' ),
			'type'					=> 'list',
			'total'					=> $wp_query->max_num_pages,
			'before_page_number'	=> '<span class="screen-reader-text">' . esc_attr( $translated ) . ' </span>',
		) ) );

		echo '</nav>';
	}
}

if ( ! function_exists( 'storefront_post_nav' ) ) {
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function storefront_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="navigation post-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'storefront' ); ?></h1>
			<div class="nav-links">
				<?php
					previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'storefront' ) );
					next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'storefront' ) );
				?>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}

if ( ! function_exists( 'storefront_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function storefront_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s" itemprop="datePublished">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			_x( 'Posted on %s', 'post date', 'storefront' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			_x( 'by %s', 'post author', 'storefront' ),
			'<span class="vcard author"><span class="fn" itemprop="author"><a class="url fn n" rel="author" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span></span>'
		);

		echo apply_filters( 'storefront_single_post_posted_on_html', '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>', $posted_on, $byline );

	}
}
