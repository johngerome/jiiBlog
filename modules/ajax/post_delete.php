<?php
if (!defined('FLUX_ROOT')) exit;  

$postsTable = Flux::config('FluxTables.PostsTable');

$post_id	= (int)$params->get('id');


$sql		= "SELECT `title` FROM {$server->loginDatabase}.$postsTable WHERE post_id = ? LIMIT 1";
$sth		= $server->connection->getStatement($sql);
$sth->execute(array($post_id));
$posts		= $sth->fetchAll();


if(count($posts) < 1)
{
	$json['message'] = Flux::message('PostNotFound');
}
else
{
	$sql = "DELETE FROM {$server->loginDatabase}.$postsTable WHERE `post_id` = ?";
    $sth = $server->connection->getStatement($sql);
    $sth->execute(array($post_id));

	$json['message'] = Flux::message('PostDeleted');
}


header('Content-Type: application/json');
echo json_encode($json);
exit;
?>