<?php if (!defined('FLUX_ROOT')) exit;

$postsTable        = Flux::config('FluxTables.PostsTable');
$categoryTable		 = Flux::config('FluxTables.CategoryTable');
$TagsTable 				 = Flux::config('FluxTables.TagsTable');

$title             = Flux::Message('BlogManagement');


// Count Posts
$sql = "SELECT COUNT(post_id) as total FROM {$server->loginDatabase}.$postsTable WHERE `published` = '1'";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$posts = $sth->fetch();

// Count Posts
$sql = "SELECT COUNT(category_id) as total FROM {$server->loginDatabase}.$categoryTable";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$categories = $sth->fetch();


// Count Tags
$sql = "SELECT COUNT(tag_id) as total FROM {$server->loginDatabase}.$TagsTable";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$tags = $sth->fetch();