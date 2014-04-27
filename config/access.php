<?php

return array(
	'modules' => array(
		'ajax' => array(
			'category_create' => AccountLevel::ADMIN,
			'category_view'	  => AccountLevel::ANYONE,
			'post_view'		  => AccountLevel::ADMIN,
			'tag_create'	  => AccountLevel::ADMIN,
			'tag_delete'	  => AccountLevel::ADMIN,
			'tag_most_used_from_used'	=> AccountLevel::ADMIN,
			'tag_tmp_create'			=>	AccountLevel::ADMIN,
			'tag_tmp_create_from_used'	=>	AccountLevel::ADMIN,
			'tag_tmp_view'	=>	AccountLevel::ADMIN,
			'tag_view'		=>	AccountLevel::ANYONE
		),
        'blog_posts'  => array(
            'index' 	=>  AccountLevel::ADMIN,
            'create' 	=>  AccountLevel::ADMIN,
            'edit' 		=>  AccountLevel::ADMIN,
            'delete' 	=>  AccountLevel::ADMIN,
            'view' 	    =>  AccountLevel::ANYONE,
        ),
         'blog_categories'  => array(
            'index' 	=>  AccountLevel::ADMIN,
            'create' 	=>  AccountLevel::ADMIN,
            'edit' 		=>  AccountLevel::ADMIN,
            'delete' 	=>  AccountLevel::ADMIN,
            'view' 	    =>  AccountLevel::ANYONE,
        ),
        'blog_tags'  => array(
            'index' 	=>  AccountLevel::ADMIN,
            'create' 	=>  AccountLevel::ADMIN,
            'edit' 		=>  AccountLevel::ADMIN,
            'delete' 	=>  AccountLevel::ADMIN,
            'view' 	    =>  AccountLevel::ANYONE,
        ),
        'blog'  => array(
            'index'     =>  AccountLevel::ADMIN,
            'create'    =>  AccountLevel::ADMIN,
            'edit'      =>  AccountLevel::ADMIN,
            'delete'    =>  AccountLevel::ADMIN,
            'view'      =>  AccountLevel::ANYONE,
        ),
	),
)
?>