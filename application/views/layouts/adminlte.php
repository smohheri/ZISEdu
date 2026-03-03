<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('layouts/partials/head'); ?>
<?php $this->load->view('layouts/partials/navbar'); ?>
<?php $this->load->view('layouts/partials/sidebar'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php
            if (isset($content_view) && $content_view !== '') {
                $this->load->view($content_view);
            }
            ?>
        </div>
    </section>
</div>

<?php $this->load->view('layouts/partials/footer'); ?>
<?php $this->load->view('layouts/partials/scripts'); ?>