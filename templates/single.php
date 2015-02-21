<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			/*
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 */

//			global $myposttype2;
//			global $myCustomTypeOptions;
//
//			$store_meta = get_post_meta(get_the_ID());
//			$latitude = $store_meta['store_adresse_latitude'][0] ;
//			$longitude = $store_meta['store_adresse_longitude'][0] ;
//			$icon_marker = get_post_meta(get_the_ID(), 'store_url_marker', true );
//			$zoom = get_post_meta(get_the_ID(), 'store_zoom', true );
//
//
//			if($icon_marker =='' || $icon_marker == null){
//				$icon_url = plugin_dir_url(BMDPATH).'images/markers/reperes-activ.png';
//			}
//			else{
//				foreach($icon_marker as $value){
//					$icon_file_url =  $value ;
//				}
//				if($icon_file_url==''){
//					$icon_url = plugin_dir_url(BMDPATH).'images/markers/reperes-activ.png';
//				}
//				else{
//					$icon_url = $icon_file_url;
//				}
//
//			}
//			if($zoom =='' || $zoom==null){
//				$zoom='15';
//			}
//			//  echo 'Localisation : '.$latitude.' / '.$longitude.'<br/>';
//			// var_dump($store_meta);
//
//			//  echo do_shortcode('[googlemap lat="'.$latitude.'" long="'.$longitude.'" marker="'.$icon_url.'" zoom="'.$zoom.'"]');


?>


		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php
			// Post thumbnail.
			twentyfifteen_post_thumbnail();
			?>

			<header class="entry-header">
				<?php
				if ( is_single() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
				endif;
				?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php

				//echo do_shortcode('[googlemap-direction lat="'.$latitude.'" long="'.$longitude.'" marker="'.$icon_url.'" zoom="'.$zoom.'"]');
				//		echo do_shortcode('[googlemap-direction  lat="'.$latitude.'" long="'.$longitude.'" marker="'.$icon_url.'" zoom="'.$zoom.'"]');
					echo do_shortcode('[googlemap-my-direction]');
				/* translators: %s: Name of current post */
				the_content( sprintf(
					__( 'Continue reading %s', 'twentyfifteen' ),
					the_title( '<span class="screen-reader-text">', '</span>', false )
				) );



				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				) );
				?>
			</div><!-- .entry-content -->

			<?php
			// Author bio.
			if ( is_single() && get_the_author_meta( 'description' ) ) :
				get_template_part( 'author-bio' );
			endif;
			?>

			<footer class="entry-footer">
				<?php twentyfifteen_entry_meta(); ?>
				<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-footer -->

			</article><!-- #post-## -->

	<?php

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			// Previous/next post navigation.
			the_post_navigation( array(
				'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentyfifteen' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Next post:', 'twentyfifteen' ) . '</span> ' .
					'<span class="post-title">%title</span>',
				'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentyfifteen' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Previous post:', 'twentyfifteen' ) . '</span> ' .
					'<span class="post-title">%title</span>',
			) );

		// End the loop.
		endwhile;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
