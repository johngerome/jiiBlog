<?php if (!defined('FLUX_ROOT')) exit;

$postsTable 	 = Flux::config('FluxTables.PostsTable');

$sql  = "SELECT COUNT(`post_id`)as numPublishedPost FROM {$server->loginDatabase}.$postsTable ";
$sql .= "WHERE `published` = ? ";
$sth  = $server->connection->getStatement($sql);
$sth->execute(array('1'));

$json['numPublishedPost'] = (int)$sth->fetch()->numPublishedPost;


$sql  = "SELECT COUNT(`post_id`)as numUnPublishedPost FROM {$server->loginDatabase}.$postsTable ";
$sql .= "WHERE `published` = ? ";
$sth  = $server->connection->getStatement($sql);
$sth->execute(array('0'));

$json['numDraftPost'] = (int)$sth->fetch()->numUnPublishedPost;


$sql  = "SELECT COUNT(`post_id`)as total FROM {$server->loginDatabase}.$postsTable";
$sth  = $server->connection->getStatement($sql);
$sth->execute(array('1'));

$json['totalPost'] = (int)$sth->fetch()->total;


header('Content-Type: application/json');
echo json_encode($json);
exit;
?>