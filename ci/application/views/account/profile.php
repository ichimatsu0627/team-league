<div class="container">
    <h3>Profile</h3>

    <div class="form-group">
        <label><?php echo USER_NAME_NAME;?></label>
        <p><?php echo $member->name;?></p>
    </div>
    <div class="form-group">
        <label><?php echo MAIL_ADDRESS_NAME;?></label>
        <p><?php echo $member->email;?></p>
    </div>
    <div class="form-group">
        <label><?php echo USER_ID_NAME;?></label>
        <p><?php echo $member->login_id;?></p>
    </div>
    <div class="form-group">
        <label><?php echo TWITTER_NAME;?></label>
        <?php if (empty($member->twitter)) { ?>
            <p>-</p>
        <?php } else { ?>
            <p><a href="https://twitter.com/<?php echo str_replace("@", "", $member->twitter);?>"><?php echo $member->twitter;?></a></p>
        <?php } ?>
    </div>
    <div class="form-group">
        <label><?php echo DISCORD_NAME;?></label>
        <p><?php echo $member->discord ?? "-";?></p>
    </div>
    <?php if ($member_id == $member->id) { ?>
        <div class="form-group">
            <label><?php echo PLATFORM_NAME;?></label>
            <ul class="list-inline" style="margin-left: 20px">
                <?php foreach($platforms as $id => $platform) { ?>
                    <li><?php echo $platform->name;?>:<p><?php echo isset($member->platforms[$id]) && !empty($member->platforms[$id]) ? $member->platforms[$id] : "-";?></p></li>
                <?php } ?>
            </ul>
        </div>
        <div class="form-group">
            <a href="/account/edit_form"><button class="btn btn-primary btn-round"><i class="now-ui-icons ui-2_settings-90"></i> Edit Profile</button></a>
        </div>
    <?php } ?>
</div>
