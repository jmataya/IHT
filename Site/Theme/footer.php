<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	</div><!-- #main -->

	<footer id="colophon" role="contentinfo">

			<?php
				/* A sidebar in the footer? Yep. You can can customize
				 * your footer with three columns of widgets.
				 */
				if ( ! is_404() )
					get_sidebar( 'footer' );
			?>

			<!--<div id="site-generator">
				<?php do_action( 'twentyeleven_credits' ); ?>
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentyeleven' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'twentyeleven' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'twentyeleven' ), 'WordPress' ); ?></a>
			</div>-->
			<div id="site-generator">
			    <div id="tepper">
    			    <a href="http://tepper.cmu.edu"><img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/themes/iht/images/hosts/tepper-logo.png" /></a>
			    </div>
			    <div id="cmuandheinz">
			        <div id="cmu">
			            <a href="http://www.cmu.edu"><img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/themes/iht/images/hosts/CMU-logo.png" /></a>
		            </div>
		            <div id="heinz">
		                <a href="http://www.heinz.cmu.edu"><img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/themes/iht/images/hosts/heinz-logo.png" /></a>
	                </div>
	            </div>
            </div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
