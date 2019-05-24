<div class="container">

    <h3>Account Regist</h3>

    <form class="form" action="/account/regist" method="POST">
        <div class="form-group">
            <label for="name"><?php echo USER_NAME_NAME;?><small style="color: red;"> ※ required</small></label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo mb_strtolower(USER_NAME_NAME);?>">
        </div>
        <div class="form-group">
            <label for="email"><?php echo MAIL_ADDRESS_NAME;?><small style="color: red;"> ※ required</small></label>
            <input type="text" class="form-control" name="email" placeholder="<?php echo mb_strtolower(MAIL_ADDRESS_NAME);?>">
        </div>
        <div class="form-group">
            <label for="platform"><?php echo PLATFORM_NAME;?></label>
            <ul class="list-inline" style="margin-left: 20px">
            <?php foreach($platforms as $id => $platform) { ?>
                <li><?php echo $platform->name;?>:<input type="text" class="form-control" name="pf-<?php echo $id; ?>" placeholder="<?php echo $platform->id_name;?>"></li>
            <?php } ?>
        </div>
        <div class="form-group">
            <label for="login_id"><?php echo USER_ID_NAME;?><small style="color: red;"> ※ required</small></label>
            <input type="text" class="form-control" name="login_id" placeholder="<?php echo mb_strtolower(USER_ID_NAME);?>">
        </div>
        <div class="form-group">
            <label for="password"><?php echo PASSWORD_NAME;?><small style="color: red;"> ※ required</small><br /><small>Make sure it's at least 8 characters.</small></label>
            <input type="password" class="form-control" name="password" placeholder="<?php echo mb_strtolower(PASSWORD_NAME);?>">
        </div>
        <div class="form-group">
            <label for="conf_password"><?php echo CONFIRM_PASSWORD_NAME;?><small style="color: red;"> ※ required</small></label>
            <input type="password" class="form-control" name="conf_password" placeholder="<?php echo mb_strtolower(CONFIRM_PASSWORD_NAME);?>">
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="check">
                <span class="form-check-sign"></span>
                Check me out
            </label>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-round">Submit</button>
        </div>
    </form>

</div>
