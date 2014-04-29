<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';
loadHelper(array('xssClean'));
loadModel(array('blog'));

$blogModel 		= new Blog($server);

$PostsTable   = Flux::config('FluxTables.PostsTable');

$post_id	= xssClean(trim($params->get('post_id')));


// Fetch by Slug
if($post_id)
{
	$sql = "SELECT * FROM {$server->loginDatabase}.$PostsTable WHERE `post_id` = ?";
	$sth = $server->connection->getStatement($sql);
	$sth->execute(array($post_id));

}
else
{
	//fetch all

	$sql = "SELECT * FROM {$server->loginDatabase}.$PostsTable ORDER BY `title` ASC";
	$sth = $server->connection->getStatement($sql);
	$sth->execute();
	
}

	$posts = $sth->fetchAll();

?>