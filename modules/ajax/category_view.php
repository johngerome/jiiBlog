<?php
if (!defined('FLUX_ROOT')) exit;

$category	= Flux::config('FluxTables.CategoryTable');

$categoryId  = (int)$params->get('cat_id');

$sql = "SELECT `category_id`, `name` FROM {$server->loginDatabase}.$category ORDER BY `category_id`";
$sth = $server->connection->getStatement($sql);
$sth->execute();

$categories = $sth->fetchAll();

if($categories)
{
	echo '<select name="category" class="form-control">';
	foreach ($categories as $row)
	{
		$selected = ($categoryId == $row->category_id) ? 'selected="selected"':'';
		echo '<option value="' .$row->category_id. '" ' .$selected. '>' .$row->name. '</option>';
	}
	echo '</select>';
}
else
{
	echo Flux::Message('NoCategoryFound');
}

exit;
?>