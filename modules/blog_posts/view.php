<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';
load_helper('xssClean');


$PostsTable   = Flux::config('FluxTables.PostsTable');

$slug	= xssClean(trim($params->get('id')));


// Fetch by Slug
if($slug)
{
	$sql = "SELECT * FROM {$server->loginDatabase}.$PostsTable ORDER BY `title` ASC";
	$sth = $server->connection->getStatement($sql);
	$sth->execute();

	
}
else
{
	//fetch all


}



$posts = $sth->fetchAll();

?>