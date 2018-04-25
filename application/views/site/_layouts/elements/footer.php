<div class = "container">
    <p class = "text-muted"><?php echo $this->config->item('app_copy_right'); ?></p>
    <p class="framework-info text-muted">
        UI rendered in <strong>{elapsed_time}</strong> seconds. <?php echo (ENVIRONMENT === 'development') ? 'CI framework <strong>v' . CI_VERSION . '</strong> UX <strong>' . $this->config->item('app_ui_version') . '</strong>' : '' ?></p>
</div>