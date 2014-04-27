<?php
if (!defined('FLUX_ROOT')) exit;

$TagsTable = Flux::config('FluxTables.TagsTable');

$json['success'] = false;

$tag_id = (int)$params->get('id');

if($tag_id)
{
	$sql = "DELETE FROM {$server->loginDatabase}.$TagsTable ";
    $sql .= "WHERE tag_id = ? ";
    $sth = $server->connection->getStatement($sql);
    $sth->execute(array($tag_id));

    $json['success'] = true;
}


header('Content-Type: application/json');
echo json_encode($json);
exit;
?>