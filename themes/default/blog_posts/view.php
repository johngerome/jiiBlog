<?php
if (!defined('FLUX_ROOT')) exit; 

include str_replace("\\", "/", dirname(dirname(__FILE__))) .'/init.php';
   
?>

<link href="<?php echo $bpath.'assets/css/bootstrap.css'; ?>" rel="stylesheet">

<div id="jiiBlog">
<div class="posts">
	<?php foreach($posts as $post): ?>
	<div class="post">
		<h1 class="post__title">lorem Ipsum Dolor</h1>
		<p class="post__contents">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo, provident, placeat assumenda ratione aliquid recusandae optio dolores laborum praesentium molestiae. Veritatis, quidem quos eaque saepe deserunt dolore nam amet fuga!</p>
		<div class="post__info">
			<ul class="list-inline">
				<li>Author: <?php echo $blogModel->postAuthor($post->author_id, $post->author_alias, $post->allow_author_alias); ?></li>
			</ul>
		</div>
	</div>
	<?php endforeach; ?>
</div>
</div>

