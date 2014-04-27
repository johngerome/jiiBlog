<?php if (!defined('FLUX_ROOT')) exit;

$categoryTable     = Flux::config('FluxTables.CategoryTable');

$title          = Flux::Message('EditCategory');
$category_id	= (int)$params->get('category_id');


// Get Selected Category
$sql = "SELECT * FROM {$server->loginDatabase}.$categoryTable WHERE `category_id` = ? LIMIT 1";
$sth = $server->connection->getStatement($sql);
$sth->execute(array($category_id));

$category = $sth->fetch();

if( ! $category)
{
	$this->redirect($this->url('blog_categories','index')); 
}

$categoryName 		 = $category->name;
$categoryDescription = $category->description;



?>