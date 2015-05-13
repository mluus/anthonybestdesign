<?php
/**
 * Template Name: Projects
 */

get_header(); ?>

<p class ="project-head"> Anthony has completed projects around the globe - click the images to see more.</p>

   <div class = "project_container">
       	<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				

			<?php endwhile; // end of the loop. ?>
 
   </div>   


	


        <?php get_footer(); ?>


