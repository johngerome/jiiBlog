<?php if (!defined('FLUX_ROOT')) exit;

$TagsTable  = Flux::config('FluxTables.TagsTable');

$title          = Flux::Message('EditTag');
$tag_id			= (int)$params->get('tag_id');



// Get Selected Tag
$sql = "SELECT * FROM {$server->loginDatabase}.$TagsTable WHERE `tag_id` = ? LIMIT 1";
$sth = $server->connection->getStatement($sql);
$sth->execute(array($tag_id));
$tag = $sth->fetch();

if( ! $tag )
{
	$this->redirect($this->url('blog_tags','index')); 
}

$tagName 		 = $tag->name;
$tagDescription  = $tag->description;


?>