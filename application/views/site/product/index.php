<div class="btn-group">
    <a href="#" id="list" class="btn btn-dark btn-sm"><span class="glyphicon glyphicon-th-list">
        </span>List</a> <a href="#" id="grid" class="btn btn-dark btn-sm"><span
            class="glyphicon glyphicon-th"></span>Grid</a>
</div>
<br>
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
<div id="products" class="row list-group">        
    <?php
    if (isset($products) && sizeof($products) > 0) {
        foreach ($products as $key => $product) {
            ?>
            <div class="item  col-xs-3 col-lg-3">
                <div class="thumbnail">
                    <img class="group list-group-image" src="<?php echo base_url('assets/dist/img/prod.png'); ?>" alt="" />
                    <div class="caption">
                        <h4 class="group inner list-group-item-heading"><?php echo isset($product['product_name']) ? $product['product_name'] : 'Untitled'; ?></h4>
                        <p><?php echo isset($product['category_name']) ? '<i class="glyphicon glyphicon-tag text-warning"></i>' . $product['category_name'] : ''; ?></p>
                        <p class="group inner list-group-item-text">
                            <?php echo isset($product['product_description']) ? word_limiter($product['product_description'], 20) : 'No details available !'; ?>
                        </p>
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="muted"><?php echo isset($product['product_mrp']) ? '<del>&#8377; ' . $product['product_mrp'] . '</del>' : '&#8377; 00.00'; ?></p>
                                <p class="lead"><?php echo isset($product['product_price']) ? '&#8377; ' . $product['product_price'] : '&#8377; 00.00'; ?></p>
                            </div>
                            <div class="col-xs-12">
								<a href="<?php echo site_url('product/index/');?>" class="btn btn-info btn-block">Details</a>
								<a href="<?php echo site_url('order/add_to_cart/'. $product['id']);?>" class="btn btn-primary btn-block">Add to cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>

<script>
    $(document).ready(function () {
        $('#list').click(function (event) {
            event.preventDefault();
            $('#products .item').addClass('list-group-item');
        });
        $('#grid').click(function (event) {
            event.preventDefault();
            $('#products .item').removeClass('list-group-item');
            $('#products .item').addClass('grid-group-item');
        });
    });
</script>