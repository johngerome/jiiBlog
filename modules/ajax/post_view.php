<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';
loadModel(array('datatable', 'blog'));


$blogModel 		= new Blog($server);
$datatable 		= new Datatable($server);

$PostsTable		= Flux::config('FluxTables.PostsTable');
$CategoryTable	= Flux::config('FluxTables.CategoryTable');

( ! isset($_GET['sEcho'])) ? $_GET['sEcho'] = 0:'';


$col = array(
		$PostsTable. '.`title`'	  			  => 'title',
		$PostsTable. '.`post_id`'	  		  => 'post_id',
		$PostsTable. '.`author_id`' 		  => 'author_id',
		$PostsTable. '.`author_alias`' 		  => 'author_alias',
		'login.`userid`'					  => 'userid',
		$CategoryTable. '.`name`' 			  => 'category',
		$CategoryTable. '.`created_at`' 	  => 'created_at', 
		$PostsTable. '.`updated_at`'		  => 'updated_at',
		$PostsTable. '.`allow_author_alias`'  => 'allow_author_alias',
		$PostsTable. '.`published`'  		  => 'published',
		$PostsTable. '.`hits`'  		  	  => 'hits',
);

// Unsearchable Columns. Usually columns that will not display at the front end.
$usCol = array(
	'post_id',
	'author_id',
	'created_at',
	'updated_at',
	'allow_author_alias'
);


/* The Magic starts beyond this line */

//Select Column
$rPost = $datatable->select($col)
				   ->from($PostsTable)
				   ->join(array(
						"$CategoryTable ON $CategoryTable.`category_id` = $PostsTable.`category_id`",
						"login ON login.`account_id` = $PostsTable.`author_id`"
				  		)
				  	 )
				    ->where($_GET['sSearch'], $usCol)
				    ->order($_GET['iSortCol_0'], $_GET['iSortingCols'])
				    ->limit($_GET['iDisplayStart'], $_GET['iDisplayLength'])
				    ->get();


$resultFilterTotal = $datatable->filterTotal();

$totalPosts = $datatable->totalRow('post_id');



/*
 * Output
 */
$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => (int)$totalPosts,
	"iTotalDisplayRecords" => (int)$resultFilterTotal,
	"aaData" => array()
);

$json = array();
foreach ($rPost as $post)
{

	//Tags
	$postTags = '';
	$tags = $blogModel->postTags($post->post_id);
	foreach ($tags as $row)
	{
		$postTags .= sprintf('%s,', $row->name); // this will be exploded at the Client Side (Datatables)
	}

	//Comments
	$postComments = '' .$blogModel->postCountComments($post->post_id);


	// This is the final data that we will send to the client as Json format
	// NOTE: Lightweight! Portable! (as possible we can call it anytime and anywhere)
	$json[] = array(

		$post->post_id,
		$post->title,
		//its better if don't run again a query to get the authors name
		$blogModel->postAuthor($post->author_id, $post->author_alias, $post->allow_author_alias),
		$post->category,
		$postTags,
		(int)$post->hits,
		$postComments,
		$blogModel->postDate($post->created_at, $post->updated_at),
		
		//Invinsible Column Down here!
		$blogModel->postPublishedFriendlyValue($post->published), // Can Add Draft legend word on the title of the post. see at the Client Side (Datatables)

	);
}
$output['aaData'] = $json;


header('Content-Type: application/json');
echo json_encode($output);
exit;
?>