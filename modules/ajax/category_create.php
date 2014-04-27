<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';
load_helper(array('url', 'xssClean'));

$category	= Flux::config('FluxTables.CategoryTable');


if(count($_POST))
{

	$category_name 			= xssClean(strip_tags($params->get('categoryName')));
	$category_parent 		= ($params->get('categoryParent')) ? $params->get('categoryParent'):'0';
	$category_description 	= strip_tags($params->get('categoryDescription'));
	$account_id 			= $session->account->account_id;


	$json['success'] 		= false;
	$json['errorMessage']	= '';


	if($category_name === '')
	{
        $json['errorMessage'] = Flux::Message('CategoryNameIsEmpty');
    }
    else
    {

    	$sql = "SELECT `name` FROM {$server->loginDatabase}.$category WHERE `name` = ? LIMIT 1";
    	$sth = $server->connection->getStatement($sql);
		$sth->execute(array($category_name));

		$res = $sth->fetchAll();

		if (count($res) > 0)
		{
			$json['errorMessage'] = Flux::Message('CategoryNameIsAlreadyExists');
		}
		else
		{
			$slug  = urlTitle(strtolower($category_name));

			$sql = "INSERT INTO {$server->loginDatabase}.$category (name, description, parent_id, slug, created_by, created_at) ";
		    $sql .= "VALUES (?, ?, ?, ?, ?, NOW())";
		    $sth = $server->connection->getStatement($sql);
		    $sth->execute(array($category_name, $category_description, $category_parent, $slug, $account_id));

		    $json['infoMessage'] = Flux::Message('CategorySuccessfullyAdded');
		    $json['success'] = true;

		}
	}

header('Content-Type: application/json');
echo json_encode($json);
}
exit;
?>