<?php if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('jii_lib_dir'). 'jiiSystem/jiilib.php';
loadHelper(array('url', 'xssclean', 'form'));


$postsTable         = Flux::config('FluxTables.PostsTable');
$tagsTable          = Flux::config('FluxTables.TagsTable');
$postTagsTable      = Flux::config('FluxTables.PostTagsTable');


$title              = Flux::Message('AddNewPost');
$errorMessage       = '';
$showHits           = 1;
$showCategory       = 1;
$showAuthor         = 1;
$showCreatedDate    = 1;
$showModifiedDate   = 1;
$allowComment       = 1;


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
         $errorMessage = Flux::Message('AuthorAliasIsTooLong');
    }
    
    if( ! $errorMessage)
    {
        $slug  = urlTitle(strtolower($postTitle));

        $sql = "SELECT `slug` FROM {$server->loginDatabase}.$postsTable WHERE `slug` = ? LIMIT 1";
        $sth = $server->connection->getStatement($sql);
        $sth->execute(array($slug));

        $res = $sth->fetch();

        if ($res)
        {
            $sql = "SELECT MAX(post_id) as max_id FROM {$server->loginDatabase}.$postsTable";
            $sth = $server->connection->getStatement($sql);
            $sth->execute(array($slug));

            $max_id = $sth->fetch()->max_id + 1;

            $slug = $slug .'-'.$max_id;
        }

        
        $sql = "INSERT INTO {$server->loginDatabase}.$postsTable (title, content, category_id, author_alias, author_id, ";
        $sql .= "allow_comment, slug, published, created_at, updated_at, updated_by, ";
        $sql .= "allow_author_alias, show_hits, show_category, show_author, show_created_date, show_modified_date) ";
        $sql .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), '0000:00:00 00:00:00', '0', ?, ?, ?, ?, ?, ?)"; 
        $sth = $server->connection->getStatement($sql);
        $sth->execute(array($postTitle, $content, $categoryId, $authorAliasName, $account_id,
                            $allowComment, $slug, $postPublish,
                            $allowAuthorAlias, $showHits, $showCategory, $showAuthor, $showCreatedDate, $showModifiedDate ));


        // Get the last Post Id
        $sql = "SELECT LAST_INSERT_ID() as last_id";
        $sth = $server->connection->getStatement($sql);
        $sth->execute();
        $post_id = $sth->fetch()->last_id;


        //Tags
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
                    
                    $sql = "INSERT INTO {$server->loginDatabase}.$tagsTable (`name`, `slug`, `created_by`, `created_at`) VALUES (?, ?, ?, NOW())";
                    $sth = $server->connection->getStatement($sql);
                    $sth->execute(array($tag, $slug, $account_id));
   
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

        $session->setMessageData('Post Successfully Published.');

        $this->redirect($this->url('blog_posts','index'));          
    }
}

?>