<?php
if (!defined('FLUX_ROOT')) exit; 
include str_replace("\\", "/", dirname(dirname(__FILE__))) .'/init.php';
?>


<link href="<?php echo $bpath.'assets/css/bootstrap.css'; ?>" rel="stylesheet">
<link href="<?php echo $bpath.'assets/vendor/font-awesome/css/font-awesome.min.css'; ?>" rel="stylesheet">
<link href="<?php echo $bpath.'assets/css/style.css'; ?>" rel="stylesheet">

<script>
$(document).ready(function(){
    $.getJSON("<?php echo Flux::config('release_api'); ?>", function(result) {
      $.each(result, function(i, version) {
      	if(version.name != "<?php echo Flux::config('version'); ?>"){
      			$(".js-blog-version").html("jiiBlog <strong>" +version.name + "</strong> is available! Please download the latest version <a href=\"https://github.com/johngerome/jiiBlog/releases/\" target=\"_blank\">here</a> now.").show();
      	}
      	else
      	{
      			$(".js-blog-version").hide();
      	}
      });

    });
});
</script>

<div id="jiiBlog">
<div class="row">
<div class="col-lg-12">
	<div class="alert alert-warning js-blog-version"></div>

	<div class="col-lg-3 col-md-2 big-btn">
		<span class="badge badge-primary big-btn-badge"><?php echo $posts->total ?></span>
		<a href="<?php echo $this->url('blog_posts') ?>">
			<h1><i class="icon-file"></i></h1>
			<b>Posts</b>
		</a>
	</div>
	<div class="col-lg-3 col-md-2 big-btn">
		<span class="badge badge-primary big-btn-badge"><?php echo $categories->total ?></span>
		<a href="<?php echo $this->url('blog_categories') ?>">
			<h1><i class="icon-folder-close"></i></h1>
			<b>Categories</b>
		</a>
	</div>
	<div class="col-lg-3 col-md-2 big-btn">
		<span class="badge badge-primary big-btn-badge"><?php echo $tags->total ?></span>
		<a href="<?php echo $this->url('blog_tags') ?>">
			<h1><i class="icon-tags"></i></h1>
			<b>Tags</b>
		</a>
	</div>
<!-- 	<div class="col-lg-3 col-md-2 big-btn">
		<a href="<?php echo $this->url('blog_comments') ?>">
			<h1><i class="icon-comment"></i></h1>
			<b>Comments</b>
		</a>
	</div> -->
	<div class="col-lg-3 col-md-2 big-btn">
		<a href="<?php echo $this->url('blog_settings') ?>">
			<h1><i class="icon-cog"></i></h1>
			<b>Settings</b>
		</a>
	</div>

</div>	
</div>
</div>