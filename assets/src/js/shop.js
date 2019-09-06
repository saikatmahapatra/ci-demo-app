/**
 * ------------------------------------------------------------------------------
 * Controller Specific DOM Interaction (Ready/Load, Click, Hover, Change)
 * ------------------------------------------------------------------------------
 */

var table;
$(domReady);

function domReady() {
    //Index View:
    if (ROUTER_METHOD == 'manage_orders') {
        renderDataTable();
    }
}

/**
 * ------------------------------------------------------------------------------
 * Controller Specific JS Function
 * ------------------------------------------------------------------------------
 */

function renderDataTable() {
    this.table = $('#example1').DataTable({
        processing: true, //Feature control the processing indicator.
        serverSide: true, //Feature control DataTables' server-side processing mode.
        order: [], //Initial no order.
        // Load data for the table's content from an Ajax source
        ajax: {
            url: SITE_URL + ROUTER_DIRECTORY + ROUTER_CLASS + '/render_datatable',
        },
        //Set column definition initialisation properties.
        columnDefs: [{
            targets: [-1], //last column
            orderable: false, //set not orderable
        }, ],
    });
}