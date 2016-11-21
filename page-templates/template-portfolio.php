<?php
/*
Template Name: Portfolio
*/
?>

<?php get_header(); ?>

<div class="container site-main">
	<div id="filters" style="display:none;">
	   <div class="boxed">
    		<?php  
    			 $terms = get_terms("filter");
    			 $count = count($terms);
    			 echo '<span id="filter" class="button">'. __( 'Filter Items' ) .'</span>';
    			 echo '<ul class="nolist" id="portfolio-filters">';
    			 echo '<li><ul class="nolist"><li><a href="#all" title="">'. __( 'All' ) .'</a></li>';
    			 if ( $count > 0 ){
    				
    					foreach ( $terms as $term ) {
    						
    						$termname = strtolower($term->name);
    						$termname = str_replace(' ', '-', $termname);
    						echo '<li><a href="#'.$termname.'" title="" rel="'.$termname.'">'.$term->name.'</a></li>';
    					}
    			 }
    			 echo '</ul></li>';
    			 echo '</ul>';
     		?>
	   </div>
 	</div>

	<section id="portfolio">
		<div class="boxed">
		
			<ul id="portfolio_list" class="list-unstyled row">
				<?php 
				    $portfolio = new WP_Query(array('order' => 'DESC', 'post_type' => 'portfolio', 'posts_per_page' => -1));
				    if($portfolio->have_posts()) : while($portfolio->have_posts()) : $portfolio->the_post();
					    $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full'); 
					   
    					$terms = get_the_terms( $post->ID, 'filter' );
    							
    					if ( $terms && ! is_wp_error( $terms ) ) : 
    						$links = array();
    
    						foreach ( $terms as $term ) 
    						{
    							$links[] = $term->name;
    						}
    						$links = str_replace(' ', '-', $links);	
    						$tax = join( " / ", $links );		
    					else :	
    						$tax = '';	
    					endif;
				?>
    	            
    	            <li class="col-sm-4 one_third filter_item <?php echo strtolower($tax); ?> all">
            			<a href="<?php the_permalink(); ?>">
            			    <div class="hover"><div class="iconhover"></div></div>
                			<img src="<?php echo $featured_image[0]; ?>" alt="" />
            				<h5 class="nbm"><?php the_title(); ?></h5>
        				</a>
        				<!-- <div class="splitter"></div> -->
        				<!-- <ul class="postmeta list-unstyled">	
                        	<li class="date">
                            	<?php // echo $tax; ?>
                        	</li>
                        	<div class="alignright">
                            	<span class="icon-eye-open"></span> <?php echo get_post_views(get_the_ID()); ?>
                                <?php // printLikes(get_the_ID()); ?>
                        	</div>
                        </ul> -->
        			</li>
				<?php endwhile; endif; ?>
			</ul>
			
		</div>
	</section>	
</div>
<?php get_footer(); ?>