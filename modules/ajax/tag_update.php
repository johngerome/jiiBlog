<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';
load_helper(array('xssclean', 'url'));

$TagsTable	= Flux::config('FluxTables.TagsTable');


if(count($_POST))
{

	$tag_id 			= (int)$params->get('tag_id');
	$tag_name 			= xssClean(strip_tags($params->get('tagName')));
	$tag_description 	= strip_tags($params->get('tagDescription'));


	$json['success'] 		= false;
	$json['errorMessage']	= '';


	if($tag_name === '')
	{
        $json['errorMessage'] = Flux::Message('TagNameIsEmpty');
    }
    else
    {

    	$sql = "SELECT `name` FROM {$server->loginDatabase}.$TagsTable WHERE `name` = ? AND `tag_id` != ? LIMIT 1";
    	$sth = $server->connection->getStatement($sql);
		$sth->execute(array($tag_name, $tag_id));

		$res = $sth->fetch();

		if ($res)
		{
			$json['errorMessage'] = sprintf(Flux::Message('TagNameIsAlreadyExists'), $tag_name);
		}
		else
		{

			$sql = "UPDATE {$server->loginDatabase}.$TagsTable set `name` = ?, `description` = ? WHERE `tag_id` = ?";
		    $sth = $server->connection->getStatement($sql);
		    $sth->execute(array($tag_name, $tag_description, $tag_id));

		    $json['infoMessage'] = Flux::Message('TagSuccessfullyUpdated');
		    $session->setMessageData(Flux::Message('TagSuccessfullyUpdated'));
		    $json['success'] 	 = true;

		}
	}

header('Content-Type: application/json');
echo json_encode($json);
}
exit;
?>