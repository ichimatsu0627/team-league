<div class="container">
    <div class="row">
        <div class="card card-signup" data-background-color="black">
            <form class="form" action="/account/login" method="POST">
                <div class="card-header text-center">
                    <h3 class="card-title title-up">Login</h3>
                </div>
                <div class="card-body">
                    <div class="input-group no-border">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="now-ui-icons users_circle-08"></i>
                          </span>
                        </div>
                        <input type="text" name="user_id" maxlength="30" value="" placeholder="<?php echo mb_strtolower(USER_ID_NAME);?>" class="form-control" >
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
                <div class="card-footer text-center">
                    <input type="submit" value="Login" class="btn btn-info btn-round btn-lg"><br />
                    <a href="/account/regist_form" class="btn btn-link btn-info">create account</a>
                </div>
            </form>
        </div>
    </div>

</div>
