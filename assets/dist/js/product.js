/**
 * ------------------------------------------------------------------------------
 * Controller Specific DOM Interaction (Ready/Load, Click, Hover, Change)
 * ------------------------------------------------------------------------------
 */
 var table;
$(domReady);
function domReady(){	
	//Index View:
	if(ROUTER_METHOD == 'index'){
		renderDataTable();
	}
	
	//Add, Edit View:
	if(ROUTER_METHOD == 'add' || ROUTER_METHOD == 'edit'){
		initCKEditor();
	}
}
$('.btn-delete-file').on('click', deleteUploadedFiles);







/**
 * ------------------------------------------------------------------------------
 * Controller Specific JS Function
 * ------------------------------------------------------------------------------
 */
function initCKEditor(){
	if(typeof CKEDITOR != 'undefined'){
		// Replace the <textarea id="editor1"> with a CKEditor
		// instance, using default configuration.
		CKEDITOR.replace('product_description');
		//bootstrap WYSIHTML5 - text editor
		//$(".textarea").wysihtml5();
	}
}
		
		
function renderDataTable(){
	table = $('#product-datatable').DataTable({
		/*dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		],
		iDisplayLength: 10,*/
		processing: true, //Feature control the processing indicator.
		serverSide: true, //Feature control DataTables' server-side processing mode.
		order: [], //Initial no order.
		// Load data for the table's content from an Ajax source
		ajax: {
			url: SITE_URL+ROUTER_DIRECTORY+ROUTER_CLASS+'/render_datatable',
		},
		//Set column definition initialisation properties.
		columnDefs: [
			{
				targets: [-1], //last column
				orderable: false, //set not orderable
			},
		],
	});
}


function deleteUploadedFiles(e){
	e.preventDefault();
	var that = $(this);
	var alert_msg = '';
	var data_row = that.parents('.file-container');
	var clickedBtn = that;    
	alert_msg += 'Are you sure you want to delete this file?\n';
	var conf = confirm(alert_msg);
	
	if (conf == true) {
		var upload_id = that.attr('data-upload_id');
		var file_path = that.attr('data-path');
		
		var XHR = new Ajax();
		XHR.type ='POST';
		XHR.url = SITE_URL+ROUTER_DIRECTORY+ROUTER_CLASS+'/delete_file';
		XHR.data = {id: upload_id, file_path: file_path};
		var promise = XHR.init();		
		promise.done(function(response){
			if (response == 'success') {
				data_row.remove();
			}
		});
		promise.fail(function(){
			alert("Failed");
		});
		promise.always(function(){
			clickedBtn.html('Delete');
		});
		
	} else {
		return false;
	}
}
