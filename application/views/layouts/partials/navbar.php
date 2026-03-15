<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo site_url(); ?>" class="nav-link">Home</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-user-circle fa-lg mr-1"></i>
                <span class="d-none d-md-inline"><?php echo html_escape($this->session->userdata('nama_lengkap')); ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <li class="user-header bg-primary">
                    <i class="fas fa-user-circle fa-4x mb-2"></i>
                    <p>
                        <?php echo html_escape($this->session->userdata('nama_lengkap')); ?>
                        <small><?php echo ucfirst(html_escape($this->session->userdata('role'))); ?></small>
                    </p>
                </li>
                <li class="user-footer">
                    <a href="<?php echo site_url('logout'); ?>" class="btn btn-default btn-flat float-right">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>