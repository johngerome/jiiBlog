<?php if ( ! defined('FLUX_ROOT')) exit('No direct script access allowed');

Class Blog
{

	/**
	 *
	 */
	protected $server;
	
	/**
	 *
	 */
	protected $categoryTable;

	/**
	 *
	 */
	protected $postTagsTable;

	/**
	 *
	 */
	protected $tagsTable;

	/**
	 *
	 */
	protected $commentsTable;

	/**
	 *
	 */
	public function __construct($server)
	{
		$this->server 		 = $server;
		$this->categoryTable = Flux::config('FluxTables.CategoryTable');
		$this->postsTable 	 = Flux::config('FluxTables.PostsTable');
		$this->postTagsTable = Flux::config('FluxTables.PostTagsTable');
		$this->tagsTable	 = Flux::config('FluxTables.TagsTable');
		$this->commentsTable = Flux::config('FluxTables.CommentsTable');
	}

	/**
	 *
	 */
	public function postCategory($category_id)
	{

		$sql = "SELECT `name` FROM {$this->server->loginDatabase}.$this->categoryTable WHERE `category_id` = ? ";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute(array($category_id));
			
		return $sth->fetch()->name;
	}

	/**
	 *
	 */
	public function postAuthor($author_id, $author_alias = null, $allow_author_alias = 0)
	{
		// Get the Author
		if(empty($author_alias) == false AND $allow_author_alias == 1)
		{
			$author = $author_alias;
		}
		else
		{
			$author = $this->getUserId($author_id);
		}
		return $author;
	}


	/**
	 *
	 */
	public function postDate($created_at, $updated_at)
	{
		return sprintf('%s', ($updated_at == '0000-00-00 00:00:00') ? date('d-m-Y',strtotime($created_at)):date('d-m-Y',strtotime($updated_at)));
	}

	/**
	 *
	 */
	public function postTags($post_id)
	{
		//Get Tags
		$sql  = "SELECT `name` FROM {$this->server->loginDatabase}.$this->postTagsTable pt ";
		$sql .= "JOIN {$this->server->loginDatabase}.$this->tagsTable tt ON tt.`tag_id` = pt.`tag_id`";
		$sql .= "WHERE `post_id` = ? ";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute(array($post_id));
		return $sth->fetchAll();
	}

	/**
	 *
	 */
	public function postCountComments($post_id)
	{

		$sql  = "SELECT COUNT(`comment_id`) as total FROM {$this->server->loginDatabase}.$this->commentsTable ";
		$sql .= "WHERE `post_id` = ? ";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute(array($post_id));

		return (int)$sth->fetch()->total;
	}

	/**
	 *
	 */
	public function postPublishedFriendlyValue($published)
	{
		return ($published) ? 'Published':'Draft';
	}

	/**
	 *
	 */
	public function getUserId($account_id)
	{
		$sql = "SELECT `userid` FROM {$this->server->loginDatabase}.login WHERE `account_id` = ? ";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute(array($account_id));

		return $sth->fetch()->userid;
	}


}
?>