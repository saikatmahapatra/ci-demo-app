<!DOCTYPE html>
    <head>
        <?php echo $el_html_head; ?>
        <!-- Bootstrap Core CSS -->
        <link href="<?php echo base_url('node_modules/bootstrap/dist/css/bootstrap.min.css');?>" rel="stylesheet">
        <!-- Font Awesome Icons -->        
        <link href="<?php echo base_url('node_modules/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet">    
        
        <!-- Custom CSS -->
        <link href="<?php echo base_url('assets/dist/css/styles.min.css');?>" rel="stylesheet">
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'); ?>"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'); ?>"></script>
        <![endif]-->
    </head>

    <body data-controller="<?php echo $this->router->class; ?>" data-method="<?php echo $this->router->method; ?>">
        
		<nav class="navbar navbar-expand-md navbar-dark bg-primary fixed-top">            
			<?php echo $el_navbar; ?>
        </nav>
		
		<main role="main" class="container">
            <?php echo $maincontent; ?>            			
        </main> <!-- /container -->
		
		
		<footer class="footer">
            <?php echo $el_footer; ?>
        </footer>

        <button class="btn btn-primary scrollup" data-toggle="tooltip" data-placement="left" data-original-title="Scroll to top"><i aria-hidden="true" class="fa fa-arrow-up"></i></button>
	
		<!-- jQuery -->    
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo base_url('node_modules/jquery/dist/jquery.min.js'); ?>" type="text/javascript"><\/script>')</script>
        <!-- Bootstrap dependency popper.js -->
        <script src="<?php echo base_url('node_modules/popper.js/dist/umd/popper.min.js'); ?>"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="<?php echo base_url('node_modules/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>               
        	
        <!--Application Specific JS Loading Through Controllers-->
        <?php echo isset($app_js) ? $app_js : ''; ?>
    </body>
</html>
