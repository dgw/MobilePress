<?php get_header(); ?>
		
		<div id="contentwrap">
		
			<div id="infoblock">
			
				<h2>Latest Blog Posts</h2>
			
			</div>
			
			<?php $access_key = 1; ?>
			<?php if (have_posts()): while (have_posts()): the_post(); ?>
			
			<div class="post">
				<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" accesskey="<?php echo $access_key; $access_key++; ?>"><?php the_title(); ?></a></h2>
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
			
			<div id="indexpostfoot">
				<p><?php posts_nav_link(' &#183; ', 'Previous Page', 'Next Page'); ?></p>
			</div>
			
			<?php endif; ?>
			
			<div id="pageblock">
				<h2>Blog Pages</h2>
			</div>
			
			<div class="page">
				
				<ol id="pages">
					<?php wp_list_pages('title_li='); ?>
				</ol>
				
			</div>
		
		</div>
		
<?php get_footer(); ?>
