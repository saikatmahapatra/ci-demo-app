<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Examples Documentation</h1>               
    </div>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
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
    </div>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">        
        <ul>
            <li><a href="<?php echo site_url('ci_example/form_helper');?>" class="">Form Helper</a></li>
            <li><a href="<?php echo site_url('ci_example/dom_pdf_gen_pdf');?>" class="">DOM PDF Generate</a></li>
            <li><a href="<?php echo site_url('ci_example/date_helper');?>" class="">Date Helper</a></li>
            <li><a href="<?php echo site_url('ci_example/directory_helper');?>" class="">Directory Helper</a></li>
        </ul>   
    </div>

</div>