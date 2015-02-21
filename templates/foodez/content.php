<?php

/**

 * The default template for displaying content. Used for both single and index/archive/search.

 */

?>
<div <?php post_class('post'); ?> id="post-<?php the_ID(); ?>">
		<?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it. ?>
        <div class="featured-image-shadow-box">
			<?php
					$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'foodeez_lite_standard_img');
			?>
			<a href="<?php the_permalink(); ?>" class="image">
				<img src="<?php echo $thumbnail[0];?>" alt="<?php the_title(); ?>" class="featured-image alignnon"/>
			</a>
		</div>
		<?php } ?>

        <h1 class="post-title">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php the_title(); ?>
			</a>
		</h1>

		<div class="skepost-meta clearfix">
            <span class="author-name"><i class="fa fa-user">&nbsp;</i><?php _e('','foodeez-lite'); the_author_posts_link(); ?> </span>
			<?php if (has_category()) { ?><span class="category"><i class="fa fa-folder">&nbsp;</i><?php _e('','foodeez-lite');?><?php the_category(','); ?></span><?php } ?>
            <?php the_tags('<span class="tags"><i class="fa fa-tag"></i> ',',','</span>'); ?>
			<span class="date"><i class="fa fa-clock-o">&nbsp;</i><?php _e('','foodeez-lite');?> <?php the_time('F j, Y') ?></span>
            <span class="comments"><i class="fa fa-comments">&nbsp;</i><?php _e('','foodeez-lite');?><?php comments_popup_link(__('No Comments ','foodeez-lite'), __('1 Comment ','foodeez-lite'), __('% Comments ','foodeez-lite')) ; ?></span>
        </div>

		<!-- skepost-meta -->
        <div class="skepost clearfix">
			<?php the_excerpt(); ?> 
			<div class="continue"><a href="<?php the_permalink(); ?>"><?php _e('Read More','foodeez-lite');?></a></div>		  
        </div>
        <!-- skepost -->
</div>
<!-- post -->