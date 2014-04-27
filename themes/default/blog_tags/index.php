<?php
if (!defined('FLUX_ROOT')) exit; 

include str_replace("\\", "/", dirname(dirname(__FILE__))) .'/init.php';
   
?>


<link href="<?php echo $bpath.'assets/css/bootstrap.css'; ?>" rel="stylesheet">
<link href="<?php echo $bpath.'assets/css/dataTables.bootstrap.css'; ?>" rel="stylesheet">
<link href="<?php echo $bpath.'assets/vendor/font-awesome/css/font-awesome.min.css'; ?>" rel="stylesheet">

<link href="<?php echo $bpath.'assets/css/style.css'; ?>" rel="stylesheet">

<script src="<?php echo $bpath.'assets/js/jquery.dataTables.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $bpath.'assets/js/dataTables.bootstrap.js'; ?>" type="text/javascript"></script>
<script src="<?php  echo $bpath.'assets/js/jiiBlog.js'; ?>" type="text/javascript"></script>


<script type="text/javascript" charset="utf-8">
	function afterDelete()
	{

	}

	$( document ).ready( function() {

		oTable = $( '.postTable' ).dataTable( {
			'bProcessing':   true,
			'bServerSide':   true,
			'bStateSave':    true,
			'sAjaxSource':   "<?php echo $this->url('ajax', 'tags_view') ?>",
			'fnRowCallback': function( nRow, aData, iDisplayIndex ) {

				//Actions
				if( aData[0] != 1 )
				{
					var sActionHtml  = "<a href=\"<?php echo htmlspecialchars($this->url('blog_tags', 'edit', array('tag_id' => '') )) ?>" + aData[0] + "\" class=\"post-action\"><i class=\"post-action-icon icon-edit-sign\"></i></a>&nbsp;";
						sActionHtml += "<a class=\"delete_row\" id=\"delete_" + aData[0] + "\"><i class=\"post-action-icon icon-remove-sign\"></i></a>";
					$( 'td:eq(5)', nRow ).html ( sActionHtml );
				}
			},
			'aoColumns': [
				null,
	            null,
	            null,
	            null,
	            null,
	            { 'mData': null }
	        ],
	        'oLanguage': {
				'sSearch': 		'Search all:',
				'sZeroRecords': 'No Data found'
			}

		});
	

		// Delete Post
	    $( '.postTable tbody a.delete_row' ).live( 'click', function() {
	        fnDeleteDTableRow (this, oTable, "<?php echo $this->url('ajax', 'tag_delete') ?>", afterDelete );
	    });


		// Search Box
		$( 'div.dataTables_filter input' ).unbind( 'keypress keyup' ).bind( 'keyup', function(e) {
			if( e.keyCode == 13 )
			{
				oTable.fnFilter( this.value );
			}
		});

	});// Document Ready
</script>

<div id="jiiBlog">
<div class="row">
<div class="col-lg-12">

<h2><i class="icon-tags"></i> <?php echo Flux::Message('Tags') ?> <a href="<?php echo htmlspecialchars($this->url('blog_tags', 'create')) ?>" class="btn btn-primary"><i class="icon-plus">&nbsp;</i><?php echo Flux::Message('AddNew'); ?></a></h2>
<hr>
	
	<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover postTable"  width="100%">  
		<thead>
		<tr id="DtHeader">
			<th width="5%">ID</th>
			<th width="40%">Name</th> 
			<th width="40%">Description</th> 
			<th width="20%">Slug</th>
			<th width="5%">Posts</th>
			<!-- No Data Source ! -->
			<th>Actions</th>
		</tr>
		</thead>

		<tbody>
			<tr>
				<td colspan="3" class="dataTables_empty">Loading data from server</td>
			</tr>
		</tbody>

		<tfoot>
		<tr id="DtFooter">
			<th width="5%">ID</th>
			<th width="40%">Name</th>
			<th width="40%">Description</th> 
			<th width="20%">Slug</th>
			<th width="5%">Posts</th>
			<!-- No Data Source ! -->
			<th>Actions</th>
		</tr>
		</tfoot>
	</table>


</div>
</div>
</div>