<?php get_header(); ?>
		
		<div id="contentwrap">
		
			<div id="infoblock">
			
				<?php if (is_category()) { ?>
					<h2><?php single_cat_title(); ?> Archives</h2>
				<?php } elseif (is_day()) { ?>
					<h2>Archive for <?php the_time('F jS, Y'); ?></h2>
				<?php } elseif (is_month()) { ?>
					<h2>Archive for <?php the_time('F, Y'); ?></h2>
				<?php } elseif (is_year()) { ?>
					<h2>Archive for <?php the_time('Y'); ?></h2>
				<?php } ?>
			
			</div>
			
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<div class="post">
				<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<p class="subtitle"><?php the_time('jS M y') ?>. <?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?></p>
			</div>
			
			<?php endwhile;  else: ?>
			
			<div id="infoblock">
				<h2>Page Not Found</h2>
			</div>
			
			<div class="post">
				<p>Sorry, The page you are looking for cannot be found!</p>
			</div>
			
			<?php endif; ?>
			
			<?php if (mopr_check_pagination()): ?>
			
			<div id="postfoot">
				<p><?php posts_nav_link(' &#183; ', 'Previous Page', 'Next Page'); ?></p>
			</div>
			
			<?php endif; ?>
		
		</div>
		
<?php get_footer(); ?>
