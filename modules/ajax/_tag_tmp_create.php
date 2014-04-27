<?php
if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';

load_helper('xssClean');


$tagTable	= Flux::config('FluxTables.TagsTable');


if(count($_POST))
{

	$tag_name 		= xssClean(strip_tags($params->get('tagName')));
	$account_id 	= $session->account->account_id;

	$tags = explode(',', $tag_name);

	$json['success'] 		= false;
	$json['errorMessage']	= '';


	if($tag_name === '')
	{
        $json['errorMessage'] = Flux::Message('TagNameIsEmpty');
    }
    else
    {

    	foreach ($tags as $tag)
    	{
    		
	    	if (strlen($tag) > 45)
			{
				$json['errorMessage'] .= sprintf(Flux::Message('TagNameTooLong'), $tag);
			}
			else
			{

				$sql = "SELECT `name` FROM {$server->loginDatabase}.$tagTable WHERE `name` = ? LIMIT 1";
		    	$sth = $server->connection->getStatement($sql);
				$sth->execute(array($tag));

				$res = $sth->fetch();

				if ($res)
				{
					$json['errorMessage'] .= sprintf(Flux::Message('TagNameIsAlreadyExists'), $tag);
				}
				else
				{

					$sql = "INSERT INTO {$server->loginDatabase}.$tagTable (name, created_by, tmp, created_at) ";
				    $sql .= "VALUES (?, ?, '1', NOW())";
				    $sth = $server->connection->getStatement($sql);
				    $sth->execute(array($tag, $account_id));
    				
    				$json['infoMessage'] = Flux::Message('TagSuccessfullyAdded');
    				$json['success'] = true;
				}
			}
		}

	}

	
header('Content-Type: application/json');
echo json_encode($json);
}
exit;
?>