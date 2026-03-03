<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open(isset($form_action) ? $form_action : 'users/store'); ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form User</h3>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <?php if (validation_errors()): ?>
            <div class="alert alert-warning"><?php echo validation_errors(); ?></div>
        <?php endif; ?>

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control"
                value="<?php echo set_value('nama_lengkap', isset($row->nama_lengkap) ? $row->nama_lengkap : ''); ?>"
                required>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control"
                value="<?php echo set_value('username', isset($row->username) ? $row->username : ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                value="<?php echo set_value('email', isset($row->email) ? $row->email : ''); ?>">
        </div>
        <div class="form-group">
            <label>Password <?php echo isset($row->id) ? '(Kosongkan jika tidak diubah)' : ''; ?></label>
            <input type="password" name="password" class="form-control" <?php echo isset($row->id) ? '' : 'required'; ?>>
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <?php $role = set_value('role', isset($row->role) ? $row->role : 'operator'); ?>
                <option value="super_admin" <?php echo ($role === 'super_admin') ? 'selected' : ''; ?>>Super Admin
                </option>
                <option value="amil" <?php echo ($role === 'amil') ? 'selected' : ''; ?>>Amil</option>
                <option value="operator" <?php echo ($role === 'operator') ? 'selected' : ''; ?>>Operator</option>
            </select>
        </div>
        <div class="form-group">
            <label>Status</label>
            <?php $is_active = (int) set_value('is_active', isset($row->is_active) ? $row->is_active : 1); ?>
            <select name="is_active" class="form-control">
                <option value="1" <?php echo ($is_active === 1) ? 'selected' : ''; ?>>Aktif</option>
                <option value="0" <?php echo ($is_active === 0) ? 'selected' : ''; ?>>Nonaktif</option>
            </select>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo site_url('users'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<?php echo form_close(); ?>
