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
        <p><?php echo $member->user_id;?></p>
    </div>
    <?php if ($member_id == $member->id) { ?>
        <div class="form-group">
            <label><?php echo PLATFORM_NAME;?></label>
            <ul class="list-inline" style="margin-left: 20px">
                <?php foreach($platforms as $id => $platform) { ?>
                    <li><?php echo $platform->name;?>:<p><?php echo isset($member->platforms[$id]) ? $member->platforms[$id] : "-";?></p></li>
                <?php } ?>
            </ul>
        </div>
        <div class="form-group">
            <a href="/account/edit_form"><button class="btn btn-primary btn-round"><i class="now-ui-icons ui-2_settings-90"></i> Edit Profile</button></a>
        </div>
    <?php } ?>
</div>
