<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Directory Helper Example</h1>               
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
        <h4>Read Only Sub Dir</h4>
        
        <?php 
        echo '<pre>';
        print_r($sub_folders); 
        echo '</pre>';
        ?>
            <h4>Read Sub Dir + Files</h4>
        <?php 
        echo '<pre>';
        print_r($read_dir); 
        echo '</pre>';
        ?>
    </div>

</div>