<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';
loadModel(array('datatable', 'blog'));

$blogModel 		= new Blog($server);
$datatable 		= new Datatable($server);



$PostsTable		= Flux::config('FluxTables.PostsTable');
$CategoryTable	= Flux::config('FluxTables.CategoryTable');


if( ! isset($_GET['sEcho']))
{
	$_GET['sEcho'] = 0;
}



$col = array(
		'c.`category_id`'	 => 'category_id',
		'c.`name`'	  		 => 'name',
		'c.description'		 => 'description',
		'c.`slug`'	  		 => 'slug',
		"(SELECT COUNT(post_id) FROM {$server->loginDatabase}.$PostsTable p WHERE p.category_id = c.category_id)"	=> 'posts'
);

// Unsearchable Columns. Usually columns that will not display at the front end.
$usCol = array(
	
);



/* The Magic starts beyond this line */
//Select Column
$aCategories = $datatable->select($col)
				   ->from("$CategoryTable c")
				    ->where($_GET['sSearch'], $usCol)
				    ->order($_GET['iSortCol_0'], $_GET['iSortingCols'])
				    ->limit($_GET['iDisplayStart'], $_GET['iDisplayLength'])
				    ->get();


$resultFilterTotal = $datatable->filterTotal();

$totalCategories   = $datatable->totalRow('category_id');

/*
 * Output
 */
$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => (int)$totalCategories,
	"iTotalDisplayRecords" => (int)$resultFilterTotal,
	"aaData" => array()
);

$json = array();
foreach ($aCategories as $row)
{


	// This is the final data that we will send to the client as Json format
	// NOTE: Lightweight! Portable! (as possible we can call it anytime and anywhere)
	$json[] = array(

		$row->category_id,
		$row->name,
		$row->description,
		$row->slug,
		(int)$row->posts,

	);
}
$output['aaData'] = $json;


header('Content-Type: application/json');
echo json_encode($output);
exit;
?>