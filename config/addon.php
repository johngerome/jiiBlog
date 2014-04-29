<?php
return array(

	'LimitItem' 			=> 4,		// Default value on how many items to show on home page.
	'CommentLimit'			=> 5,		// Default Value on How Many Comments will show on every post.	
	
	// - Menu and sub-menu items. (displayed on left nav & content sub menu)
	'MenuItems' => array(
		'jiiBlog' => array(
            'Blog Management'      => array('module' => 'blog'),
		)
	),
	

	// Don't touch beyond this line. for DEVELOPERS ONLY
	'jii_lib_dir'	=> dirname(dirname(__FILE__)). '/lib/',

	'release_api'		  =>  'https://api.github.com/repos/johngerome/jiiBlog/releases',
	'version'					=>	'pre-v0.1-beta', //Current Version

    'FluxTables' => array(
        'PostsTable'    => 'jii_blog_posts',
        'CategoryTable'	=> 'jii_blog_categories',
        'CommentsTable' => 'jii_blog_comments',
        'TagsTable'     => 'jii_blog_tags',
        'PostTagsTable' => 'jii_blog_post_tags'
    )
);
?>