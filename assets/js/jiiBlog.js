(function ( $ ) {


$.fn.fnJiiAjaxGet = function( aOptions ) {
	var $this   = $(this);
	var aOption = $.extend({
		sAction: 	'',
        sUrl: 		'',
        sType: 		'GET',
        sDataType: 'json',
        bAsync: 	true,
        bCache: 	false,
        bDebug: 	false
    }, aOptions );

    $.ajax({
        url: 		aOption.sUrl,
        type: 		aOption.sType,
        dataType: 	aOption.sDataType,
        async: 		aOption.bAsync,
        cache: 		aOption.bCache,
        success: function( response )
        {
        	/*
        	*
        	*/
        	if( aOption.sAction == 'post-submenu' )
        	{
        		var sHtml = '<li><a id="post-view-fAll" class="cursor-pointer selected-filter">All(' + response['totalPost'] + ')</a></li>';
            	sHtml	+= '<li><a id="post-view-fPublished" class="cursor-pointer">Published(' + response['numPublishedPost'] + ')</a></li>';
            	sHtml 	+= '<li><a id="post-view-fDraft" class="cursor-pointer">Draft(' + response['numDraftPost'] + ')</a></li>';
               	$this.html( sHtml );
        	}

        	/*
        	*
        	*/	
        	if( aOption.sAction == '' )
        	{

        	}
        },
        error: function( response )
        {
        	if( bDebug == true )
        	{
        		console.log('error send request. Element:' + this);
        	}
        }
    });
   return this;
};

}( jQuery ));

var oTable;
var oCache = {
    iCacheLower: -1
};



// function fnSetKey( aoData, sKey, mValue )
// {
//     for ( var i=0, iLen=aoData.length ; i<iLen ; i++ )
//     {
//         if ( aoData[i].name == sKey )
//         {
//             aoData[i].value = mValue;
//         }
//     }
// };

// function fnGetKey( aoData, sKey )
// {
//     for ( var i=0, iLen=aoData.length ; i<iLen ; i++ )
//     {
//         if ( aoData[i].name == sKey )
//         {
//             return aoData[i].value;
//         }
//     }
//     return null;
// };

// function fnDataTablesPipeline ( sSource, aoData, fnCallback ) {
//     var iPipe = 5; /* Ajust the pipe size */
     
//     var bNeedServer = false;
//     var sEcho = fnGetKey(aoData, 'sEcho');
//     var iRequestStart = fnGetKey(aoData, 'iDisplayStart');
//     var iRequestLength = fnGetKey(aoData, 'iDisplayLength');
//     var iRequestEnd = iRequestStart + iRequestLength;
//     oCache.iDisplayStart = iRequestStart;
     
//     /* outside pipeline? */
//     if ( oCache.iCacheLower < 0 || iRequestStart < oCache.iCacheLower || iRequestEnd > oCache.iCacheUpper )
//     {
//         bNeedServer = true;
//     }
     
//     /* sorting etc changed? */
//     if ( oCache.lastRequest && !bNeedServer )
//     {
//         for( var i=0, iLen=aoData.length ; i<iLen ; i++ )
//         {
//             if ( aoData[i].name != 'iDisplayStart' && aoData[i].name != 'iDisplayLength' && aoData[i].name != 'sEcho' )
//             {
//                 if ( aoData[i].value != oCache.lastRequest[i].value )
//                 {
//                     bNeedServer = true;
//                     break;
//                 }
//             }
//         }
//     }
     
//     /* Store the request for checking next time around */
//     oCache.lastRequest = aoData.slice();
     
//     if ( bNeedServer )
//     {
//         if ( iRequestStart < oCache.iCacheLower )
//         {
//             iRequestStart = iRequestStart - (iRequestLength*(iPipe-1));
//             if ( iRequestStart < 0 )
//             {
//                 iRequestStart = 0;
//             }
//         }
         
//         oCache.iCacheLower = iRequestStart;
//         oCache.iCacheUpper = iRequestStart + (iRequestLength * iPipe);
//         oCache.iDisplayLength = fnGetKey( aoData, 'iDisplayLength' );
//         fnSetKey( aoData, 'iDisplayStart', iRequestStart );
//         fnSetKey( aoData, 'iDisplayLength', iRequestLength*iPipe );
         
//         $.getJSON( sSource, aoData, function ( json ) {
//             /* Callback processing */
//             oCache.lastJson = jQuery.extend(true, {}, json);
             
//             if ( oCache.iCacheLower != oCache.iDisplayStart )
//             {
//                 json.aaData.splice( 0, oCache.iDisplayStart-oCache.iCacheLower );
//             }
//             json.aaData.splice( oCache.iDisplayLength, json.aaData.length );
             
//             fnCallback(json)
//         } );
//     }
//     else
//     {
//         json = jQuery.extend(true, {}, oCache.lastJson);
//         json.sEcho = sEcho; /* Update the echo for each response */
//         json.aaData.splice( 0, iRequestStart-oCache.iCacheLower );
//         json.aaData.splice( iRequestLength, json.aaData.length );
//         fnCallback(json);
//         return;
//     }
// };

function fnResetFilter( oTable )
{
    var oSettings = oTable.fnSettings();

    for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++)
    {
        oSettings.aoPreSearchCols[iCol].sSearch = '';
    }

    oSettings.oPreviousSearch.sSearch = '';
    oTable.fnDraw();
}


function fnGetSelected( oTable )
{
    var aReturn = new Array();
    var aTrs = oTable.fnGetNodes();
     
    for ( var i=0 ; i<aTrs.length ; i++ )
    {
        if ( $(aTrs[i]).hasClass('row_selected') )
        {
            aReturn.push( aTrs[i] );
        }
    }
    //alert(aReturn[0]);
    return aReturn;
};

function fnDeleteDTableRow ( obj, oDataTable, sUrl, fnCallBackSuccess ) {
    var bRet = confirm("You are about to permanently delete the selected items? 'Cancel' to stop, 'OK' to delete.");
    if (bRet == true) {
        // get the row that contains the delete btn
        row = obj.parentNode.parentNode;
        // get the record id from the btn id "delete_1"
        idAll = obj.id.split("_");
             
        id = idAll[1];
        //add class to the selected row
        $(row).addClass('row_selected');

        //make the ajax call to delete the record from db
        $.ajax({
           type: "GET",
           url:  sUrl,
           data:  {
                'id': id
           },
           success: function(response) {
                var anSelected = fnGetSelected( oDataTable );
                oDataTable.fnDeleteRow( anSelected[0] );

                fnCallBackSuccess();
           }
        });

    }      
};

function fnToogleDoviv( oTarget, iSpeed )
{
    var oEleTarget   = oTarget;
    var toogleSpeed = iSpeed;

    if( ! toogleSpeed)
    {
        toogleSpeed = 100;
    }

    $( oEleTarget ).toggle( toogleSpeed );
};

//POST 
function fnResetFilterDom( obj )
{
    $('#post-view-fAll').removeClass('selected-filter');
    $('#post-view-fPublished').removeClass('selected-filter');
    $('#post-view-fDraft').removeClass('selected-filter');
    $( obj ).addClass('selected-filter');
};