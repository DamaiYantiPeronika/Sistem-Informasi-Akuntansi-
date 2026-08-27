<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>
       
            <div class="row">
                <div class="col-lg-8"><?= form_open_multipart('user/edit'); ?>
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label"> Email</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="email" name="email" value="<?= $user['email']; ?>">
                        <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label"> Full Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" value="<?= $user['name']; ?>">
                    <?= form_error('name', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
            </div>
            <div class="form-group row"><label for="name" class="col-sm-2 col-form-label"> Gambar</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-3">
                            <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail">
                        </div>
                        <div class="col-sm-9">
                            <div class="costom-file">
                                <input type="file" class="custom-file-input" id="image" name="image">
                                <label class="custom-file-label" for="image">Pilih Gambar</label>
                                <div class="invalid-feedback">Example invalid form file feedback</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row justify-content-end">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn" style="color: #fff; background-color: #075e42;">Edit</button>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>

