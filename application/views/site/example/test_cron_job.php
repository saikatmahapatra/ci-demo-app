<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<div class="row heading-container">
    <div class="col-12">
        <h1 class="page-title"><?php echo isset($page_title)? $page_title:'Page Heading'; ?></h1>
    </div>
</div><!--/.heading-container-->

<div class="row">
    <div class="col-md-12">
		<?php
		// Show server side flash messages
		if (isset($alert_message)) {
			$html_alert_ui = '';                
			$html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$alert_message.'</div>';
			echo $html_alert_ui;
		}
        ?>
        <p>Command in cPanel</p>
        <p>/usr/local/bin/php /home/xyzportal/public_html/webportal/index.php example send_mail_cron_job</p>
    </div>
</div>
