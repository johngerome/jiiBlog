<?php
if (!defined('FLUX_ROOT')) exit; 

include str_replace("\\", "/", dirname(dirname(__FILE__))) .'/init.php';
   
?>


<link href="<?php echo $bpath.'assets/css/bootstrap.css'; ?>" rel="stylesheet">
<link href="<?php echo $bpath.'assets/css/style.css'; ?>" rel="stylesheet">

<link href="<?php echo $bpath.'assets/vendor/font-awesome/css/font-awesome.min.css'; ?>" rel="stylesheet">
<script src="<?php  echo $bpath.'assets/js/jiiBlog.js'; ?>" type="text/javascript"></script>

<script type="text/javascript">
     $(document).ready(function() {

        $( '#tagSave' ).click( function() {
            $('#tag-error').hide();
            $('#tag-info').hide();

            $.ajax({
                url: '<?php echo $this->url('ajax', 'tag_update') ?>',
                type: 'POST',
                dataType: 'json',
                async: true,
                data: {
                        'tag_id':           '<?php echo $tag_id ?>',
                        'tagName':          $('#tagName').val(),
                        'tagDescription':   $('#tagDescription').val()
                },
                success: function(response)
                { 
                    if( ! response['success'])
                    {
                        $('#tag-error').show().html(response['errorMessage']);
                    }
                    else
                    {
                        window.location.href = '<?php echo $this->url('blog_tags', 'index') ?>';
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


<div id="jiiBlog">

<h2><i class="icon-tag"></i>&nbsp;<?php echo htmlspecialchars(Flux::message('EditTag')) ?></h2>

<div class="form-horizontal">
<div class="row">
<div class="form-group col-lg-12">
    <ul class="list-inline pull-right">
        <li><button type="submit" class="btn btn-primary" id="tagSave"><i class="icon-globe"></i> <?php echo Flux::Message('Update') ?></button></li>
        <li><a href="<?php echo $this->url('blog_tags') ?>"><button type="button" class="btn btn-danger"><i class="icon-remove-sign"></i>&nbsp;<?php echo FLux::Message('Close') ?></button></a></li>
    </ul>
</div>
</div>

<hr>

<div class="alert alert-warning" id="tag-error" style="display: none;"></div>
<div class="alert alert-info" id="tag-info" style="display: none;"></div>

<div class="row">
<div class="col-lg-12">
    <div class="form-group">
    <label for="catName" class="col-lg-3 col-xs-3 control-label"><?php echo Flux::Message('Name'); ?></label>
    <div class="col-lg-9 col-xs-9">
        <input type="text" name="tagName"  id="tagName" class="form-control" value="<?php echo $tagName ?>" autocomplete="off">
    </div>
    </div>

    <div class="form-group">
    <label for="catName" class="col-lg-3 col-xs-3 control-label"><?php echo Flux::Message('Description'); ?></label> 
    <div class="col-lg-9 col-xs-9">
        <textarea name="tagDescription"  id="tagDescription" style="height: 100px;" class="form-control"><?php echo $tagDescription ?></textarea>
        <span class="help-block"><?php echo FLux::Message('DescriptionHelp') ?></span>
    </div> 
    </div>
</div>
</div>


</div>

</div><!--// jiiBlog -->