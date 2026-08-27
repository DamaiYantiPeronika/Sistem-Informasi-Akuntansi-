<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>
       
            <div class="row">
                <div class="col-lg-6">
                    <?= $this->session->flashdata('message'); ?>
                    <form action="<?= base_url('user/changepassword') ?>" method="post">
                        <div class="form-group"><label for="current_password"class="form-label">Password Lama</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                        <?= form_error('current_password', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="new_password1" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="new_password1" name="new_password1"><?= form_error('new_password1', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="new_password2" class="form-label">Ulangi Password Baru</label>
                    <input type="password" class="form-control" id="new_password2" name="new_password2"><?= form_error('new_password2', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn" style="color: #fff; background-color: #075e42;">Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</section>
</div>