<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <?php flash('register_success') ?>
            <h2>Change Password</h2>
            <p>Please Change your Password</p>
            <form action="<?php echo URLROOT; ?>users/changepassword" method="POST">
                <div class="form-group">
                    <label for="oldpassword">Old Password: <sup>*</sup></label>
                    <input type="password" name="oldpassword" class="form-control form-control-lg <?php echo (!empty($data['oldpassword_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['oldpassword']; ?>">
                    <span class="invalid-feedback"><?php echo $data['oldpassword_err']; ?></span>
                </div>
                
                <div class="form-group">
                    <label for="newpassword">New Password: <sup>*</sup></label>
                    <input type="password" name="newpassword" class="form-control form-control-lg <?php echo (!empty($data['newpassword_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['newpassword']; ?>">
                    <span class="invalid-feedback"><?php echo $data['newpassword_err']; ?></span>
                </div>

                <div class="form-group">
                    <label for="repeatnewpassword">Repeat New Password: <sup>*</sup></label>
                    <input type="password" name="repeatnewpassword" class="form-control form-control-lg <?php echo (!empty($data['repeatnewpassword_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['repeatnewpassword']; ?>">
                    <span class="invalid-feedback"><?php echo $data['repeatnewpassword_err']; ?></span>
                </div>
                
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Change Password" class="btn btn-success btn-block">
                    </div>
                    <div class="col">
                        <a href="<?php echo URLROOT; ?>pages/index" class="btn btn-light btn-block">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>