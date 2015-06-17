<?php
/**
 * Template Name: Projects
 */

get_header(); ?>


   <div class = "project_container">
       	<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				

			<?php endwhile; // end of the loop. ?>
 
   </div>   


	


        <?php get_footer(); ?>


