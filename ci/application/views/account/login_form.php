<div class="container">
    <div class="row">
        <div class="card card-signup" data-background-color="black">
            <form class="form" action="/account/login" method="POST" autocomplete="off">
                <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
                <div class="card-header text-center">
                    <h3 class="card-title title-up">Sign in</h3>
                </div>
                <div class="card-body">
                    <div class="input-group no-border">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="now-ui-icons users_circle-08"></i>
                          </span>
                        </div>
                        <input type="text" name="login_id" maxlength="30" value="<?php if (!empty($login_id)) { echo $login_id; } ?>" placeholder="<?php echo mb_strtolower(USER_LOGIN_ID_NAME);?>" class="form-control" >
                    </div>
                    <div class="input-group no-border">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="now-ui-icons text_caps-small"></i>
                            </span>
                        </div>
                        <input type="password" name="password" maxlength="20" value="" placeholder="<?php echo mb_strtolower(PASSWORD_NAME);?>" class="form-control" >
                    </div>
                </div>
                <div class="card-footer text-center" style="padding: 0;">
                    <input class="form-check-input" type="checkbox" name="remember_id" id="remember-id">
                    <label class="form-check-label small" for="remember-id">Remember me</label><br />
                    <input type="submit" value="Sign In" class="btn btn-info btn-round btn-lg"><br />
                    <a href="/account/register_form" class="btn btn-link btn-info">sign up</a>
                </div>
            </form>
        </div>
    </div>
</div>
