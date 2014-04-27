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
<script src="<?php echo $bpath.'assets/js/jiiBlog.js'; ?>" type="text/javascript"></script>


<script type="text/javascript" charset="utf-8">

	function loadSubmenu()
	{
		$( '#post-submenu' ).fnJiiAjaxGet({
			sAction: 'post-submenu',
			sUrl: 	 '<?php echo $this->url('ajax', 'post_info') ?>'
		});
	};

	$( document ).ready( function() {

		oTable = $( '.postTable' ).dataTable( {
			'bProcessing':   true,
			'bServerSide':   true,
			'bStateSave':    true,
			'sAjaxSource':   "<?php echo $this->url('ajax', 'post_view') ?>",
			//'fnServerData':  fnDataTablesPipeline, <-- Cannot Refresh Column
			'fnRowCallback': function( nRow, aData, iDisplayIndex ) {

				// Title 
				var spTitle = '';
				if( aData[8] == 'Draft' )
				{
					spTitle = '(' + aData[1] + ') - Draft';
				}
				else
				{
					spTitle = aData[1];
				}

				$( 'td:eq(1)', nRow ).html( "<a href=\"<?php echo htmlspecialchars($this->url('post', 'edit', array('post_id' => '') )) ?>" + aData[0] + "\"><b>" + spTitle + "</b></a>" );


				//  Tags
				if ( aData[4] != '' )
				{
					var sfTags = aData[4];
					var sTags  = sfTags.split(',');

					sfTags = '';
					for( var i = 0;i < sTags.length; i++ )
					{
	                   sfTags += '<li><i class="label label-default pull-right post-tags-dt">' + sTags[i] + '</i></li>&nbsp;';
	                }
					$( 'td:eq(4)', nRow ).html( '<ul class="list-inline">' + sfTags + '</ul>' );
				}


				//  Comments
				$( 'td:eq(6)', nRow ).html( '<span class="badge">' +  aData[6] + '</span>' );


				//Action
				var sActionHtml  = "<a href=\"<?php echo htmlspecialchars($this->url('blog_posts', 'edit', array('post_id' => '') )) ?>" + aData[0] + "\" class=\"post-action\"><i class=\"post-action-icon icon-edit-sign\"></i></a>&nbsp;";
					sActionHtml += "<a class=\"delete_row\" id=\"delete_" + aData[0] + "\"><i class=\"post-action-icon icon-remove-sign\"></i></a>";
				$( 'td:eq(8)', nRow ).html ( sActionHtml );

			},
			'aoColumnDefs': [
	            { 'bVisible': false, 'aTargets': [8] },
	            { 'sClass': 'center', 'aTargets': [ 5, 6, 7 ] }
	        ],
	        'aoColumns': [
	            null,
	            null,
	            null,
	            null,
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
	

		loadSubmenu();

		// Delete Post
	    $( '.postTable tbody a.delete_row' ).live( 'click', function() {
	        fnDeleteDTableRow (this, oTable, "<?php echo $this->url('ajax', 'post_delete') ?>", loadSubmenu );
	    });


		// Search Box
		$( 'div.dataTables_filter input' ).unbind( 'keypress keyup' ).bind( 'keyup', function(e) {
			if( e.keyCode == 13 )
			{
				oTable.fnFilter( this.value );
			}
		});

		// Filter by publish
		$( '#post-view-fPublished' ).live( 'click', function() {
			fnResetFilterDom( this );
			oTable.fnFilter( '1', 9, false, true, false, true ); // Filter Published Post
		});
		$( '#post-view-fDraft' ).live( 'click', function() {
			fnResetFilterDom( this );
			oTable.fnFilter( '0', 9, false, true, false, true ); // Filter Draft Post
		});
		$( '#post-view-fAll' ).live( 'click', function() {
			fnResetFilterDom( this );
			fnResetFilter( oTable );
		});

	});// End of Document Ready
</script>

<div id="jiiBlog">
<div class="row">
<div class="col-lg-12">

<h2><i class="icon-file"></i> Posts <a href="<?php echo htmlspecialchars($this->url('blog_posts', 'create')) ?>" class="btn btn-primary"><i class="icon-plus">&nbsp;</i><?php echo Flux::Message('AddNew'); ?></a></h2>
<hr>

<ul class="list-inline" id="post-submenu"></ul>
	
	<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover postTable"  width="100%">  
		<thead>
		<tr id="DtHeader">
			<th width="5%">ID</th>
			<th width="40%">Title</th>   
			<th width="10%">Author</th>
			<th width="10%">Category</th>
			<th width="9%">Tags</th>
			<th width="3%">Hits</th>
			<th width="5%"><i class="icon-comment"></i></th>
			<th width="17%">Date</th>
			<!-- Invinsible! -->
			<th>Published</th>
			<!-- No Data Source ! -->
			<th>Action</th>
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
			<th width="40%">Title</th>   
			<th width="10%">Author</th>
			<th width="10%">Category</th>
			<th width="9%">Tags</th>
			<th width="3%">Hits</th>
			<th width="5%"><i class="icon-comment"></i></th>
			<th width="17%">Date</th>
			<!-- Invinsible! -->
			<th>Published</th>
			<!-- No Data Source ! -->
			<th>Action</th>
		</tr>
		</tfoot>
	</table>

</div>
</div>
</div>