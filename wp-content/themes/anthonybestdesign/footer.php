<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package anthonybestdesign
 */
?>

<?php if( !is_page('703') ) :?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
           
		<div class="site-info">
                    <div class="footerline">
   
                    <?php anthonybestdesign_social_menu(); ?>
                    <a href="/legal">Legal</a>
			<span class="sep"> | </span>
			<?php printf( __( '&copy 2015 Anthony Best Design LTD'), 'anthonybestdesign' ); ?>
                        </div> 
                         
		</div><!-- .site-info -->
                
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php endif;?>
<?php wp_footer(); ?>

</body>
</html>
