<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';
load_helper(array('url', 'xssClean'));

$category	= Flux::config('FluxTables.CategoryTable');


if(count($_POST))
{

	$category_id 			= (int)$params->get('category_id');
	$category_name 			= xssClean(strip_tags($params->get('categoryName')));
	$category_parent 		= ($params->get('categoryParent')) ? $params->get('categoryParent'):'0';
	$category_description 	= strip_tags($params->get('categoryDescription'));


	$json['success'] 		= false;
	$json['errorMessage']	= '';


	if($category_name === '')
	{
        $json['errorMessage'] = Flux::Message('CategoryNameIsEmpty');
    }
    else
    {

    	$sql = "SELECT `name` FROM {$server->loginDatabase}.$category WHERE `name` = ? AND `category_id` != ? LIMIT 1";
    	$sth = $server->connection->getStatement($sql);
		$sth->execute(array($category_name, $category_id));

		$res = $sth->fetch();

		if ($res)
		{
			$json['errorMessage'] = Flux::Message('CategoryNameIsAlreadyExists');
		}
		else
		{

			$sql = "UPDATE {$server->loginDatabase}.$category set `name` = ?, `description` = ?, `parent_id` = ? WHERE `category_id` = ?";
		    $sth = $server->connection->getStatement($sql);
		    $sth->execute(array($category_name, $category_description, $category_parent, $category_id));

		    $json['infoMessage'] = Flux::Message('CategorySuccessfullyUpdated');
		    $session->setMessageData(Flux::Message('CategorySuccessfullyUpdated'));
		    $json['success'] 	 = true;

		}
	}

header('Content-Type: application/json');
echo json_encode($json);
}
exit;
?>