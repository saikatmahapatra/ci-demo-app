<!--<h2 class="page-header"><?php echo isset($page_heading)? $page_heading:'Untitled Page'; ?></h2>-->
<?php echo $breadcrumbs; ?>
<div class="row">
<div class="col-12">
    <?php
        // Show server side messages
        if (isset($alert_message)) {
            $html_alert_ui = '';
            $html_alert_ui.='<div class="alert-container">';
            $html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable">';
            $html_alert_ui.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html_alert_ui.=$alert_message;
            $html_alert_ui.='</div>';
            $html_alert_ui.='</div>';
            echo $html_alert_ui;
        }
    ?>
    <div class="card">
        <div class="card-header">Data Table</div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="cms-datatable" class="table">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Content Type</th>
                            <th scope="col">Text/Content</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Content Type</th>
                            <th scope="col">Text/Content</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
</div>
</div>
