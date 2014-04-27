<?php
if (!defined('FLUX_ROOT')) exit;  

$categoryTable  = Flux::config('FluxTables.CategoryTable');
$postsTable 	= Flux::config('FluxTables.PostsTable');

$category_id	= (int)$params->get('id');


$sql		= "SELECT `name` FROM {$server->loginDatabase}.$categoryTable WHERE `category_id` = ? LIMIT 1";
$sth		= $server->connection->getStatement($sql);
$sth->execute(array($category_id));
$categories	= $sth->fetchAll();


if(count($categories) < 1)
{
	$json['message'] = Flux::message('CategoryNotFound');
}
else
{
	$sql = "SELECT `post_id` FROM {$server->loginDatabase}.$postsTable WHERE `category_id` = ? ";
	$sth = $server->connection->getStatement($sql);
    $sth->execute(array($category_id));
    $aPost = $sth->fetchAll();

    if(count($aPost) > 0)
    {
	    foreach($aPost as $row)
	    {
	    	$post_id = $row->post_id;
	    }

	    if($post_id)
	    {
	    	$sql = "UPDATE {$server->loginDatabase}.$postsTable set `category_id` = '1' WHERE `post_id` = ?";
	    	$sth = $server->connection->getStatement($sql);
	    	$sth->execute(array($post_id));
	    }
	}

	$sql = "DELETE FROM {$server->loginDatabase}.$categoryTable WHERE `category_id` = ?";
    $sth = $server->connection->getStatement($sql);
    $sth->execute(array($category_id));

	$json['message'] = Flux::message('CategoryDeleted');
}


header('Content-Type: application/json');
echo json_encode($json);
exit;
?>