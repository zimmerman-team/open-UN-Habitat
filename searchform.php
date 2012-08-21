<form action="<?php bloginfo('siteurl'); ?>" id="searchform" method="get">
		<p>
        	<label for="s" class="screen-reader-text">Search for:</label>
        	<input type="text" id="s" name="s" value="<?php echo trim( get_search_query() ); ?>" class="inputbox"/>
        	<input type="submit" value="Search" id="searchsubmit" class="button" />
        </p>
</form>