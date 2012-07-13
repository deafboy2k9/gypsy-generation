<!--START SUB MENU-->
<div class="main_menu">
		<ul id="nav">
			<li>
				<div class="main_menu_item"><a class="top_menu" href="/blog/cover">cover</a></div>
			</li>
			<li>
				<div class="main_menu_item">
					<span class="yellowbg"><a id="current" class="top_menu" href="/blog">blog</a></span>
				</div>
			</li>
			<li>
				<div class="main_menu_item">
					<a class="top_menu" href="/blog/members">community</a>
				</div>
			</li>
		</ul>
</div>
<!--END TOP MENU-->
<div class="submenu">
	<ul>
		<li>
			<?php
			if(is_archive())
			{
				if(is_category('music')) echo '<span class="yellowbg"><a id="sub_current"'; 
				else echo '<a';
			}
			else
			{
				$category = get_the_category(get_the_id());
				$catname = $category[0]->cat_name;
				//need is single to prevent fashion being highlighted as current
				//category on main blog roll...dont know why though
				if($catname == 'music' && is_single()) echo '<span class="yellowbg"><a id="sub_current"';
				else echo '<a';
			}
			?> class="sub_menu" href="/blog/blog/category/music/">music</a></span>
		</li>
		<li>
			<?php
			if(is_archive())
			{
				if(is_category('fashion')) echo '<span class="yellowbg"><a id="sub_current"'; 
				else echo '<a';
			}
			else
			{
				$category = get_the_category(get_the_id());
				$catname = $category[0]->cat_name;
				//need is single to prevent fashion being highlighted as current
				//category on main blog roll...dont know why though
				if($catname == 'fashion' && is_single()) echo '<span class="yellowbg"><a id="sub_current"';
				else echo '<a';
			}
			?> class="sub_menu" href="/blog/blog/category/fashion/">fashion</a></span>
		</li>
		<li>
			<?php 
			if(is_archive())
			{
				if(is_category('art')) echo '<span class="yellowbg"><a id="sub_current"'; 
				else echo '<a';
			}
			else
			{
				$category = get_the_category(get_the_id());
				$catname = $category[0]->cat_name;
				
				if($catname == 'art' && is_single()) echo '<span class="yellowbg"><a id="sub_current"'; 
				else echo '<a';
			}
			?> class="sub_menu" href="/blog/blog/category/art/">art</a></span>
		</li>
		<li>
			<?php 
			if(is_archive())
			{
				if(is_category('culture')) echo '<span class="yellowbg"><a id="sub_current"'; 
				else echo '<a';
			}
			else
			{
				$category = get_the_category(get_the_id());
				$catname = $category[0]->cat_name;
				
				if($catname == 'culture' && is_single()) echo '<span class="yellowbg"><a id="sub_current"'; 
				else echo '<a';
			}
			?> class="sub_menu" href="/blog/blog/category/culture/">culture</a></span>
		</li>
	</ul>
</div>
<!--END SUB MENU-->