<div class="container">

    <h3>Sign up</h3>

    <form class="form" action="/account/register" method="POST">
        <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
        <div class="form-group">
            <label for="name"><?php echo USER_NAME_NAME;?><small style="color: red;"> ※ required</small></label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo mb_strtolower(USER_NAME_NAME);?>">
        </div>
        <div class="form-group">
            <label for="login_id"><?php echo USER_LOGIN_ID_NAME;?><small style="color: red;"> ※ required</small></label>
            <input type="text" class="form-control" name="login_id" placeholder="<?php echo mb_strtolower(USER_LOGIN_ID_NAME);?>">
        </div>
        <div class="form-group">
            <label for="password"><?php echo PASSWORD_NAME;?><small style="color: red;"> ※ required</small><br /><small>Make sure it's at least 8 characters.</small></label>
            <input type="password" class="form-control" name="password" placeholder="<?php echo mb_strtolower(PASSWORD_NAME);?>">
        </div>
        <div class="form-group">
            <label for="conf_password"><?php echo CONFIRM_PASSWORD_NAME;?><small style="color: red;"> ※ required</small></label>
            <input type="password" class="form-control" name="conf_password" placeholder="<?php echo mb_strtolower(CONFIRM_PASSWORD_NAME);?>">
        </div>
        <div class="form-group">
            <label for="twitter"><?php echo TWITTER_NAME;?></label>
            <input type="text" class="form-control" name="twitter" placeholder="@twitter_id">
        </div>
        <div class="form-group">
            <label for="discord"><?php echo DISCORD_NAME;?></label>
            <input type="text" class="form-control" name="discord" placeholder="discord#1234">
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
