<?php
/**
 * Template Name: Projects
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>


   <div class = "container"'>
        <div class = "project"'>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();    

         $args = array(
           'post_type' => 'attachment',
           'category_name' => 'project',
           'numberposts' => -1,
           'post_status' => null,
           'post_parent' => $post->ID
          );

          $attachments = get_posts( $args );
             if ( $attachments ) {
                foreach ( $attachments as $attachment ) {
                   
                   echo '<div class="BW_image">';
                   echo wp_get_attachment_image( $attachment->ID, 'medium' );
                   
                  }
             }

         endwhile; endif; ?>
        </div>


	


        <?php get_footer(); ?>

    <?php endwhile; ?>
