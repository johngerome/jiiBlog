<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';
loadHelper(array('url', 'xssClean', 'form'));



$postsTable     = Flux::config('FluxTables.PostsTable');
$tagsTable      = Flux::config('FluxTables.TagsTable');
$postTagsTable  = Flux::config('FluxTables.PostTagsTable');


$title          = Flux::Message('EditPost');
$errorMessage   = '';

$post_id 		= (int)xssClean($params->get('post_id'));


// Get Selected Post
$sql = "SELECT * FROM {$server->loginDatabase}.$postsTable WHERE `post_id` = ? LIMIT 1";
$sth = $server->connection->getStatement($sql);
$sth->execute(array($post_id));

$post = $sth->fetch();

if( ! $post)
{
    $this->redirect($this->url('blog_posts','index')); 
}


$postTitle  		= $post->title;
$content 			= $post->content;
$allowAuthorAlias	= $post->allow_author_alias;
$authorAliasName	= $post->author_alias;
$categoryId 		= $post->category_id;
$showHits 			= $post->show_hits;
$showCategory       = $post->show_category;
$showAuthor         = $post->show_author;
$showCreatedDate    = $post->show_created_date;
$showModifiedDate   = $post->show_modified_date;
$allowComment 		= $post->allow_comment;


// Get Selected Tags
$sql = "SELECT $tagsTable.`name` as tag_name FROM {$server->loginDatabase}.$postTagsTable ";
$sql .= "JOIN  {$server->loginDatabase}.$tagsTable ON $postTagsTable.`tag_id` = $tagsTable.`tag_id` ";
$sql .= "WHERE $postTagsTable.`post_id` = ?";

$sth = $server->connection->getStatement($sql);
$sth->execute(array($post_id));

$tags = $sth->fetchAll();


if(count($_POST))
{
	$postTitle           = xssClean(strip_tags($params->get('postTitle')));
    $content             = xssClean($params->get('postContent'));
    $postPublish         = $params->get('postPublish');
    $allowAuthorAlias    = (int)$params->get('authorAlias');
    $authorAliasName     = $params->get('authorAliasName');
    $categoryId          = $params->get('category');
    $tags                = $params->get('tags');

    $account_id          = $session->account->account_id;

    //Blog Option
    $showHits           = (int)$params->get('blogShowHits');
    $showCategory       = (int)$params->get('blogShowCategory');
    $showAuthor         = (int)$params->get('blogShowAuthor');
    $showCreatedDate    = (int)$params->get('blogShowCreatedDate');
    $showModifiedDate   = (int)$params->get('blogShowModifiedDate');
    $allowComment       = (int)$params->get('allowComment');
    

    if($postTitle === '')
    {
        $errorMessage = Flux::Message('PostTitleIsEmpty');
    }
    elseif($categoryId === '')
    {
        $errorMessage = Flux::Message('CategoryIsEmpty');   
    }
    elseif($allowAuthorAlias)
    {
    	if($authorAliasName === '')
    	{
    		$errorMessage = FLux::Message('AuthorAliasNameIsEmpty');
    	}
    }
    elseif(strlen($authorAliasName) > 45)
    {
        $errorMessage = 'Author Alias is too long';
    }
    
    if( ! $errorMessage)
    {
        $slug   = urlTitle(strtolower($postTitle));      
        
        $sql  = "UPDATE {$server->loginDatabase}.$postsTable ";
        $sql .= "set `title` = ?, `content` = ?, `category_id` = ?, `author_alias` = ?, ";
        $sql .= "`allow_comment` = ?, `published` = ?, `updated_at` = NOW(), `updated_by` = ?, ";
        $sql .= "`allow_author_alias` = ?, `show_hits` = ?, `show_category` = ?, `show_author` = ?, `show_created_date` = ?, `show_modified_date` = ? ";
        $sql .= "WHERE `post_id` =  ?";
        $sth  = $server->connection->getStatement($sql);
        $sth->execute(array($postTitle, $content, $categoryId, $authorAliasName,
        					$allowComment, $postPublish, $account_id,
                            $allowAuthorAlias, $showHits, $showCategory, $showAuthor, $showCreatedDate, $showModifiedDate,
                            $post_id));


       
        //Tags
        // CLear Tags First
        // There are some cases that the tag is empty or invalid so its better to clear them.
        $sql    = "DELETE FROM {$server->loginDatabase}.$postTagsTable WHERE `post_id` = ?";
        $sth    = $server->connection->getStatement($sql);
        $sth->execute(array($post_id));
        
        if($tags){
            $tags = explode(',', $tags);
            foreach ($tags as $tag)
            {
                //tag name is unique
                $sql    = "SELECT `tag_id` FROM {$server->loginDatabase}.$tagsTable WHERE `name` = ? LIMIT 1";
                $sth    = $server->connection->getStatement($sql);
                $sth->execute(array($tag));
                $aTag_id = $sth->fetchAll();
                
                if(count($aTag_id) < 1)
                {
                    $slug = urlTitle(strtolower($tag));    

                    $sql = "INSERT INTO {$server->loginDatabase}.$tagsTable (`name`, `slug`) VALUES (?, ?)";
                    $sth = $server->connection->getStatement($sql);
                    $sth->execute(array($tag, $slug));
   
                    $sql = "SELECT LAST_INSERT_ID() as last_id";
                    $sth = $server->connection->getStatement($sql);
                    $sth->execute();

                    $iTag_id = $sth->fetch()->last_id;

                }
                else
                {

                    foreach($aTag_id as $row)
                    {
                        $iTag_id = $row->tag_id;
                    }

                }

                $sql = "INSERT INTO {$server->loginDatabase}.$postTagsTable (post_id, tag_id) ";
                $sql .= "VALUES (?,  ?) ";
                $sth = $server->connection->getStatement($sql);
                $sth->execute(array($post_id, $iTag_id));
            }
        }


        $session->setMessageData('Post Successfully Updated.');

        $this->redirect($this->url('blog_posts','index'));          
    }
}


?>