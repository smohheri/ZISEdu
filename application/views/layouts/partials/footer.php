<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $appVersion = config_item('app_version') ? config_item('app_version') : 'v1.0.0'; ?>
<footer class="main-footer">
    <strong>&copy; <?php echo date('Y'); ?> ZISEdu.</strong>
    All rights reserved.
    <span class="float-right text-muted">Versi <?php echo html_escape($appVersion); ?></span>
</footer>
