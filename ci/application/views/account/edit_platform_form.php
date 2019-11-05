<div class="container">

    <h3>Platform Edit</h3>

    <form class="form" action="/account/edit_platform" method="POST">
        <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
        <?php foreach($platforms as $id => $platform) { ?>
            <?php if ($id == PLATFORM_ID_STEAM) { ?>
                <?php if (!empty($steam_profile)) { ?>
                    <div class="form-group">
                        <label for="<?php echo $platform->name;?>"><?php echo $platform->name;?></label>
                        <input type="text" readonly class="form-control" name="pf-<?php echo $id; ?>" placeholder="<?php echo $platform->id_name;?>" value="<?php echo str_replace(["https://steamcommunity.com/id/", "/"], "", $steam_profile->profileurl);?>">
                        <input type="hidden" name="steam_avatar" value="<?php echo $steam_profile->avatarfull;?>">
                        <a href="/auth/steam"><img style="margin-top: 20px;" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png"></a>
                    </div>
                <?php } else { ?>
                    <div class="form-group">
                        <label for="<?php echo $platform->name;?>"><?php echo $platform->name;?></label>
                        <input type="text" readonly class="form-control" name="pf-<?php echo $id; ?>" placeholder="<?php echo $platform->id_name;?>" <?php if (isset($member->platforms[$id])) { echo "value=\"".$member->platforms[$id]->pfid."\""; }?>>
                        <a href="/auth/steam"><img style="margin-top: 20px;" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png"></a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="form-group">
                    <label for="<?php echo $platform->name;?>"><?php echo $platform->name;?></label>
                    <input type="text" class="form-control" name="pf-<?php echo $id; ?>" placeholder="<?php echo $platform->id_name;?>" <?php if (isset($member->platforms[$id])) { echo "value=\"".$member->platforms[$id]->pfid."\""; }?>>
                </div>
            <?php } ?>
        <?php } ?>

        <div class="form-group">
            <input type="hidden" name="id" value="<?php echo $member_id;?>">
            <button type="submit" class="btn btn-success btn-round">Submit</button>
        </div>
    </form>

</div>
