<div class = "container">
    <p class = "text-muted"><?php echo $this->config->item('app_copy_right'); ?></p>
    <p class="framework-info text-muted">
        UI rendered in <strong>{elapsed_time}</strong> seconds. <?php echo (ENVIRONMENT === 'development') ? 'Framework <span class="h5">v' . CI_VERSION . '</span> UI <span class="h5">' . $this->config->item('app_ui_version') . '</span>' : '' ?></p>
</div>