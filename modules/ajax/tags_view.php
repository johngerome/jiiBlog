<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';
loadModel(array('datatable', 'blog'));


$blogModel 		= new Blog($server);
$datatable 		= new Datatable($server);


$PostTagsTable	= Flux::config('FluxTables.PostTagsTable');
$TagsTable		= Flux::config('FluxTables.TagsTable');


if( ! isset($_GET['sEcho']))
{
	$_GET['sEcho'] = 0;
}



$col = array(
		't.`tag_id`'	 	 => 'tag_id',
		't.`name`'	  		 => 'name',
		't.description'		 => 'description',
		't.`slug`'	  		 => 'slug',
		"(SELECT COUNT(post_id) FROM {$server->loginDatabase}.$PostTagsTable pt WHERE pt.tag_id = t.tag_id)"	=> 'posts'
);

// Unsearchable Columns. Usually columns that will not display at the front end.
$usCol = array(
	
);


/* The Magic starts beyond this line */
$tags = $datatable->select($col)
				  ->from("$TagsTable t")
				  ->where($_GET['sSearch'], $usCol)
				  ->order($_GET['iSortCol_0'], $_GET['iSortingCols'])
				  ->limit($_GET['iDisplayStart'], $_GET['iDisplayLength'])
				  ->get();


$resultFilterTotal = $datatable->filterTotal();

$totalTags   = $datatable->totalRow('tag_id');

/*
 * Output
 */
$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => (int)$totalTags,
	"iTotalDisplayRecords" => (int)$resultFilterTotal,
	"aaData" => array()
);

$json = array();
foreach ($tags as $row)
{


	// This is the final data that we will send to the client as Json format
	// NOTE: Lightweight! Portable! (as possible we can call it anytime and anywhere)
	$json[] = array(

		$row->tag_id,
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