<?php
if (!defined('FLUX_ROOT')) exit; 

include str_replace("\\", "/", dirname(dirname(__FILE__))) .'/init.php';
   
?>
>

<link href="<?php echo $bpath.'assets/css/bootstrap.css'; ?>" rel="stylesheet">
<link href="<?php echo $bpath.'assets/css/style.css'; ?>" rel="stylesheet">
<link href="<?php echo $bpath.'assets/css/bootstrap-tagsinput.css'; ?>" rel="stylesheet">

<script src="<?php echo $bpath.'assets/js/bootstrap-tagsinput.js'; ?>" type="text/javascript" ></script>

<link href="<?php echo $bpath.'assets/vendor/font-awesome/css/font-awesome.min.css'; ?>" rel="stylesheet">
<script src="<?php echo $bpath.'assets/vendor/tinymce/js/tinymce/tinymce.min.js'; ?>" type="text/javascript" ></script>


<script type="text/javascript">

    function load_categories()
    {
        $('#LoadCategories').load('<?php echo $this->url('ajax', 'category_view', array('cat_id' => $categoryId)) ?>');
    };

    function load_most_used_tags()
    {
        $('#most-used-tags').load('<?php echo $this->url('ajax', 'tag_most_used_view') ?>');
    };

    function category_resetFields()
    {
        $('#category-info').hide().html();
        $('#category-error').hide().html();
    };

    function tag_resetFields()
    {
        $('#tag-info').hide().html();
        $('#tag-error').hide().html();
    };


    function add_tag(tag_name)
    {
        $('#tags').tagsinput('add', tag_name);
    };

    function delete_tag(tag_id)
    {
        $.ajax({
            url: '<?php echo $this->url('ajax', 'tag_delete') ?>',
            type: 'POST',
            dataType: 'json',
            async: true,
            data:{
                    'tagId':   tag_id,
                },
            success: function(response)
            { 
                if(response['success'])
                {
                    load_tags();
                }
            },
            error: function(response)
            {
                console.log('error send request ');
            }
        });
    };

     function author_alias()
    {
        var authr_allow = $('#authorAlias').attr('checked') ? 1:0;

        if(authr_allow != 1)
        {
            $('#authorAliasNameCon').hide();
        }
        else
        {
            $('#authorAliasNameCon').show();
        }
    };

    function allow_comment()
    {
        var allow = $('#allowComment').attr('checked') ? 1:0;

        if(allow == 1)
        {
            $('#commentOption').show();
        }
        else
        {
            $('#commentOption').hide();
        }
    };

    $(document).ready(function() {

        load_categories();

        $('#tags').tagsinput({
            confirmKeys: [13, 188],
        });

        <?php if($tags): ?>
        <?php foreach($tags as $row): ?>
            add_tag('<?php echo $row->tag_name ?>');
        <?php endforeach; ?>
        <?php endif; ?>

        load_most_used_tags();

        author_alias();
        allow_comment();

        $('.toggle-div').click(function() {
            var idTo_Show   = '#' + $(this).data('toggle-target');
            var toogleSpeed = $(this).data('toggle-speed');

            if( ! toogleSpeed)
            {
                toogleSpeed = 100;
            }

            $(idTo_Show).toggle(toogleSpeed);
        });

        $('#authorAlias').click(function() {
            author_alias();
        });

        $('#allowComment').click(function(){
            allow_comment();
        });

        $('#category-add-submit').click(function() {
            category_resetFields();

            $.ajax({
                url: '<?php echo $this->url('ajax', 'category_create') ?>',
                type: 'POST',
                dataType: 'json',
                async: true,
                data:{
                        'categoryName':   $('#categoryName').val(),
                        'categoryParent': $('#categoryParent').val()
                    },
                
                success: function(response)
                { 
                    if( ! response['success'])
                    {
                        $('#category-error').show().html(response['errorMessage']);
                    }
                    else
                    {
                        $('#category-info').show().html(response['infoMessage']);
                        $('#categoryName').val('');
                        load_categories();
                    }
                },
                error: function(response)
                {
                    console.log('error send request ');
                }
            });
        });


        $('#tag-add-submit').click(function() {
            tag_resetFields();

            $.ajax({
                url: '<?php echo $this->url('ajax', 'tag_tmp_create') ?>',
                type: 'POST',
                dataType: 'json',
                async: true,
                data:{
                        'tagName':   $('#tagName').val(),
                    },
                success: function(response)
                {
                    if( ! response['success'])
                    {
                        console.log(response['errorMessage']);
                    }
                    else
                    {
                        console.log(response['infoMessage']);
                        $('#tagName').val('');
                        load_tags();
                    }
                },
                error: function(response)
                {
                    console.log('error send request ');
                }
            });
        });

    }); //End Document

</script>




<script type="text/javascript">
tinymce.init({
    mode : "specific_textareas",
    editor_selector : "jiiEditor",
    theme: "modern",
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true
});
</script>


<div id="jiiBlog">

<h2><i class="icon-file"></i>&nbsp;<?php echo htmlspecialchars(Flux::message('EditPost')) ?></h2>


<form action="<?php echo $this->url('blog_posts', 'edit', array('post_id' => $post->post_id)) ?>" method="post" class="form-horizontal" autocomplete="off">
<div class="row">
  <div class="form-group col-lg-12">
        <ul class="list-inline pull-right">
            <li><button type="submit" name="postPublish" value="1" class="btn btn-primary" id="post-published"><i class="icon-globe"></i> <?php echo Flux::Message('UpdateAndPublish') ?></button></li>
            <li><button type="submit" name="postPublish" value="0" class="btn"><i class="icon-save"></i>&nbsp;<?php echo FLux::Message('SaveAsDraft') ?></button></li>
            <li><a href="<?php echo $this->url('blog_posts') ?>"><button type="button" class="btn btn-danger"><i class="icon-reply"></i>&nbsp;<?php echo FLux::Message('Cancel') ?></button></a></li>
        </ul>
  </div>
</div>

<hr>

<?php if ($errorMessage): ?>
<div class="row">
<div class="col-lg-12">
    <div class="alert alert-warning"id="post-error">
        <?php echo $errorMessage ?>
    </div>
</div>
</div>
<?php endif ?>

<div class="row">
<div class="col-lg-12">
    <div class="form-group">
    <div class="col-lg-12">
        <input type="text" name="postTitle"  id="postTitle" class="form-control" placeholder="<?php echo Flux::Message('PostTitle') ?>" value="<?php echo $postTitle ?>">
    </div>
    </div>
</div>
</div>

<div class="row">
<div class="col-lg-12">
    <div class="form-group">
    <div class="col-lg-12">
        <textarea name="postContent"  id="postContent" style="height: 500px;" class="form-control jiiEditor"><?php echo $content ?></textarea>
    </div> 
    </div>
</div>
</div>

<!-- Category -->
<div class="row">
<div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading toggle-div" data-toggle-target="toggle-category-container">
      <h3 class="panel-title"><?php echo Flux::Message('Category') ?></h3>
    </div>
    <div class="panel-body" id="toggle-category-container">
        <div class="form-group">
            <div class="col-lg-12">
                <div id="LoadCategories"></div>
            </div>
        </div>

        <a class="toggle-div" data-toggle-target="add-new-category"><?php echo Flux::Message('Plus_AddNewCategory') ?></a>

        <div style="display: none; margin-top: 10px;" id="add-new-category">
            <div class="alert alert-warning"id="category-error" style="display: none;"></div>
            <div class="alert alert-info"id="category-info" style="display: none;"></div>

            <div class="form-group">
                <label for="categoryName" class="col-lg-3 col-xs-4 control-label"><?php echo Flux::Message('CategoryName'); ?></label>
                <div class="col-lg-9 col-xs-8">
                    <input type="text" class="form-control" name="categoryName" id="categoryName" placeholder="<?php echo Flux::Message('CategoryName') ?>">
                </div>
            </div>

            <div class="row">
            <div class="col-xs-12">
                <input type="button" class="btn btn-default pull-right" id="category-add-submit" value="<?php echo Flux::Message('AddNewCategory') ?>">
            </div>
            </div>
        </div><!--// add-new-category-->
    </div>
    </div>
</div><!--// col-md-6 -->

<!-- Tags -->
<div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading toggle-div" data-toggle-target="show-tags-container">
      <h3 class="panel-title"><?php echo Flux::Message('Tags') ?></h3>
    </div>
    <div class="panel-body" id="show-tags-container" style="padding: 40px;">
        <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
              <input type="text" class="form-control col-xs-12" name="tags" id="tags">
            </div>
        </div>
        </div>
        <p><?php echo Flux::Message('TagsSeparateWithCommas') ?></p>
        <div class="row">
        <div class="col-xs-12">
            <a class="toggle-div" data-toggle-target="show-most-used-tag"><?php echo Flux::Message('ChooseFromTheMostUsedTags') ?></a>

            <div style="display: none; margin-top: 10px;" id="show-most-used-tag">
                <div class="well" id="most-used-tags"></div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div><!--// col-md-6 -->
</div><!--// row -->

<!-- Author -->
<div class="row">
<div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading toggle-div" data-toggle-target="show-author-container">
      <h3 class="panel-title"><?php echo Flux::Message('Author') ?></h3>
    </div>
    <div class="panel-body" id="show-author-container">

        <div class="form-group">
            <label for="authorAlias" class="col-lg-3 col-xs-6 control-label"><?php echo FLux::Message('AuthorAlias'); ?></label>
            <div class="col-lg-9 col-xs-6">
                <input type="checkbox" name="authorAlias" id="authorAlias"  value="1" <?php echo set_checkbox($allowAuthorAlias) ?>> Yes
            </div>
        </div>
        <div class="form-group" id="authorAliasNameCon">
            <label for="authorAliasName" class="col-lg-3 col-xs-4 control-label"><?php echo FLux::Message('Alias'); ?></label>
            <div class="col-lg-9 col-xs-8">
              <input type="text" name="authorAliasName" class="form-control col-xs-12 disabled" id="authorAliasName" value="<?php echo $authorAliasName ?>">
            </div>
        </div>

    </div>
    </div>
</div><!--// col-md-6 -->

<!-- Blog Option -->
<div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading toggle-div" data-toggle-target="show-blog_option-container">
      <h3 class="panel-title"><?php echo Flux::Message('BlogOption') ?></h3>
    </div>
    <div class="panel-body" id="show-blog_option-container">
        <div class="form-group">
            <label for="blogShowHits" class="col-lg-6 col-xs-6 control-label"><?php echo FLux::Message('ShowHits'); ?></label>
            <div class="col-lg-6 col-xs-6">
                <input type="checkbox" name="blogShowHits" id="blogShowHits" value="1" <?php echo set_checkbox($showHits) ?>> Yes
            </div>
        </div>
        <div class="form-group">
            <label for="blogShowCategory" class="col-lg-6 col-xs-6 control-label"><?php echo FLux::Message('ShowCategory'); ?></label>
            <div class="col-lg-6 col-xs-6">
                <input type="checkbox" name="blogShowCategory" id="blogShowCategory" value="1" <?php echo set_checkbox($showCategory) ?>> Yes
            </div>
        </div>
        <div class="form-group">
            <label for="blogShowAuthor" class="col-lg-6 col-xs-6 control-label"><?php echo FLux::Message('ShowAuthor'); ?></label>
            <div class="col-lg-6 col-xs-6">
                <input type="checkbox" name="blogShowAuthor" id="blogShowAuthor" value="1" <?php echo set_checkbox($showAuthor) ?>> Yes
            </div>
        </div>
        <div class="form-group">
            <label for="blogShowCreatedDate" class="col-lg-6 col-xs-6 control-label"><?php echo FLux::Message('ShowCreatedDate'); ?></label>
            <div class="col-lg-6 col-xs-6">
                <input type="checkbox" name="blogShowCreatedDate" id="blogShowCreatedDate" value="1" <?php echo set_checkbox($showCreatedDate) ?>> Yes
            </div>
        </div>
        <div class="form-group">
            <label for="blogShowModefiedDate" class="col-lg-6 col-xs-6 control-label"><?php echo FLux::Message('ShowModifiedDate'); ?></label>
            <div class="col-lg-6 col-xs-6">
                <input type="checkbox" name="blogShowModifiedDate" id="blogShowModifiedDate" value="1"  <?php echo set_checkbox($showModifiedDate) ?>> Yes
            </div>
        </div>
        <div class="form-group">
            <label for="allowComment" class="ccol-lg-6 col-xs-6 control-label"><?php echo FLux::Message('AllowComment'); ?></label>
            <div class="col-lg-6 col-xs-6">
                <input type="checkbox" id="allowComment" name="allowComment" value="1" <?php echo set_checkbox($allowComment) ?>> Yes
            </div>
        </div>
    </div>
    </div>
</div><!--// col-md-6 -->
</div><!--// row -->

</form>

</div><!--// jiiBlog -->