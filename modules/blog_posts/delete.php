<?php
if (!defined('FLUX_ROOT')) exit;  

$postsTable = Flux::config('FluxTables.PostsTable');

$post_id	= (int)$params->get('post_id');

$sql		= "SELECT `title` FROM {$server->loginDatabase}.$postsTable WHERE post_id = ? LIMIT 1";
$sth		= $server->connection->getStatement($sql);
$sth->execute(array($post_id));
$posts		= $sth->fetchAll();


if(count($posts) < 1)
{
	$session->setMessageData(Flux::message('PostNotFound'));
}
else
{
	$sql = "DELETE FROM {$server->loginDatabase}.$postsTable WHERE `post_id` = ?";
    $sth = $server->connection->getStatement($sql);
    $sth->execute(array($post_id));

	$session->setMessageData(Flux::message('PostDeleted'));
}


$this->redirect($this->url('blog_posts', 'index'));
?>