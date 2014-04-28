<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';
load_helper(array('xssclean', 'url'));


$TagsTable	= Flux::config('FluxTables.TagsTable');


if(count($_POST))
{

	$tag_name 			= xssClean(strip_tags($params->get('tagName')));
	$tag_description 	= xssClean(strip_tags($params->get('tagDescription')));
	$account_id 		= $session->account->account_id;


	$json['success'] 		= false;
	$json['errorMessage']	= '';


	if($tag_name === '')
	{
        $json['errorMessage'] = Flux::Message('TagNameIsEmpty');
    }
    else
    {

    	$sql = "SELECT `name` FROM {$server->loginDatabase}.$TagsTable WHERE `name` = ? LIMIT 1";
    	$sth = $server->connection->getStatement($sql);
		$sth->execute(array($tag_name));

		$res = $sth->fetchAll();

		if (count($res) > 0)
		{
			$json['errorMessage'] = sprintf(Flux::Message('TagNameIsAlreadyExists'), $tag_name);
		}
		else
		{
			$slug  = urlTitle(strtolower($tag_name));

			$sql = "INSERT INTO {$server->loginDatabase}.$TagsTable (name, description, slug, created_by, created_at) ";
		    $sql .= "VALUES (?, ?, ?, ?, NOW())";
		    $sth = $server->connection->getStatement($sql);
		    $sth->execute(array($tag_name, $tag_description, $slug, $account_id));

		    $json['infoMessage'] = Flux::Message('TagSuccessfullyAdded');
		    $json['success'] = true;

		}
	}

header('Content-Type: application/json');
echo json_encode($json);
}
exit;
?>