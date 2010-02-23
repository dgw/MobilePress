<?php get_header(); ?>
		
		<div id="contentwrap">
		
			<div id="infoblock">
			
				<h2>Search for "<?php the_search_query(); ?>"</h2>
			
			</div>
			
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<div class="post">
				<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<p class="subtitle"><?php the_time('jS M y') ?>. <a href="<?php the_permalink() ?><?php mopr_check_permalink(); ?>comments=true"><?php comments_number('0 Comments', '1 Comment', '% Comments' ); ?></a></p>
			</div>
			
			<?php endwhile;  else: ?>
			
			<h2>Page Not Found</h2>
			<p>Sorry, The page you are looking for cannot be found!</p>
			
			<?php endif; ?>
			
			<?php if (mopr_check_pagination()): ?>
			
			<div id="postfoot">
				<p><?php posts_nav_link(' &#183; ', 'Previous Page', 'Next Page'); ?></p>
			</div>
			
			<?php endif; ?>
		
		</div>
		
<?php get_footer(); ?>
