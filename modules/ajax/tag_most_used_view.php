<?php
if (!defined('FLUX_ROOT')) exit;

$tagTable		= Flux::config('FluxTables.TagsTable');


$sql = "SELECT `tag_id`, `name` FROM {$server->loginDatabase}.$tagTable ";
$sql .= "ORDER BY `name`";
$sth = $server->connection->getStatement($sql);
$sth->execute();

$tags = $sth->fetchAll();

$sHtml = '';
if($tags)
{
	$sHtml = '<ul class="list-inline">';
	foreach ($tags as $row)
	{
		$sHtml .= '<li style="padding:5px"><span class="label label-info"><a style="cursor: pointer;" onclick="add_tag(\'' .$row->name. '\')" >' .$row->name. '</a></span></li>';
	}
	$sHtml .= '</ul>';
}


echo $sHtml;
exit;
?>