<?php
if (!defined('FLUX_ROOT')) exit;

$tag		= Flux::config('FluxTables.TagsTable');
$tagsItem 	= '';

$sql = "SELECT `tag_id`, `name` FROM {$server->loginDatabase}.$tag WHERE `tmp` = '1' ORDER BY `tag_id`";
$sth = $server->connection->getStatement($sql);
$sth->execute();

$tags = $sth->fetchAll();

if($tags)
{
	foreach ($tags as $row)
	{
		$tagsItem .= sprintf('<li><span class="label label-default">%s <a onclick="delete_tag(\'%d\')" style="cursor: pointer;">x</a></span></li>', $row->name, (int)$row->tag_id);
	}
}

echo '<ul class="list-inline"> ' .$tagsItem. ' </ul>';
exit;
?>