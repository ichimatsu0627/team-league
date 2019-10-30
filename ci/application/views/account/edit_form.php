<div class="container">

    <h3>Account Edit</h3>

    <form class="form col-12" action="/account/edit" method="POST">
        <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
        <div class="form-group">
            <label for="name"><?php echo USER_NAME_NAME;?></label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo mb_strtolower(USER_NAME_NAME);?>" value="<?php echo $member->name;?>">
        </div>
        <div class="form-group">
            <label for="login_id"><?php echo USER_ID_NAME;?></label>
            <input type="text" class="form-control" name="login_id" placeholder="<?php echo mb_strtolower(USER_ID_NAME);?>" value="<?php echo $member->login_id;?>">
        </div>
        <div class="form-group">
            <label for="twitter"><?php echo TWITTER_NAME;?></label>
            <input type="text" class="form-control" name="twitter" placeholder="@twitter_id" value="<?php echo $member->twitter ?? "-";?>">
        </div>
        <div class="form-group">
            <label for="discord"><?php echo DISCORD_NAME;?></label>
            <input type="text" class="form-control" name="discord" placeholder="discord#1234" value="<?php echo $member->discord ?? "-";?>">
        </div>
        <div class="form-group">
            <input type="hidden" name="id" value="<?php echo $member_id;?>">
            <button type="submit" class="btn btn-success btn-round">Submit</button>
        </div>
    </form>

    <div class="col-12">
        <a href="/account/edit_platform_form"><button class="btn btn-primary btn-round"><i class="now-ui-icons ui-2_settings-90"></i> Platform</button></a>
    </div>

</div>
