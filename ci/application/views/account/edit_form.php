<div class="container">

    <h3>Account Edit</h3>

    <form class="form" action="/account/edit" method="POST">
        <div class="form-group">
            <label for="name"><?php echo USER_NAME_NAME;?></label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo mb_strtolower(USER_NAME_NAME);?>" value="<?php echo $member->name;?>">
        </div>
        <div class="form-group">
            <label for="email"><?php echo MAIL_ADDRESS_NAME;?></label>
            <input type="text" class="form-control" name="email" placeholder="<?php echo mb_strtolower(MAIL_ADDRESS_NAME);?>" value="<?php echo $member->email;?>">
        </div>
        <div class="form-group">
            <label for="login_id"><?php echo USER_ID_NAME;?></label>
            <input type="text" class="form-control" name="login_id" placeholder="<?php echo mb_strtolower(USER_ID_NAME);?>" value="<?php echo $member->login_id;?>">
        </div>
        <div class="form-group">
            <label for="platform"><?php echo PLATFORM_NAME;?></label>
            <ul class="list-inline" style="margin-left: 20px">
            <?php foreach($platforms as $id => $platform) { ?>
                <li><?php echo $platform->name;?>:<input type="text" class="form-control" name="pf-<?php echo $id; ?>" placeholder="<?php echo $platform->id_name;?> if you have." <?php if (isset($member->platforms[$id])) { echo "value=\"".$member->platforms[$id]."\""; }?>></li>
            <?php } ?>
            </ul>
        </div>
        <div class="form-group">
            <input type="hidden" name="id" value="<?php echo $member_id;?>">
            <button type="submit" class="btn btn-success btn-round">Submit</button>
        </div>
    </form>

</div>
