		<div id="search">
			<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
			<fieldset>
				<input type="text" size="14" value="<?php the_search_query(); ?>" name="s" id="s" />
				<input type="submit" id="searchsubmit" value="Search" />
			</fieldset>
			</form>
		</div>
		
		<div class="aduity"><?php mopr_ad('bottom'); ?></div>

		<div id="footerwrap">
			
			<div id="footer">
				
				<p><a href="#header">Top</a> | <a href="?nomobile">View Full Version</a></p>
				<p>Powered By <a href="http://wordpress.org">WordPress</a> and <a href="http://mobilepress.co.za">MobilePress</a>.</p>
				
			</div>
			
		</div>
	
	</body>
</html>