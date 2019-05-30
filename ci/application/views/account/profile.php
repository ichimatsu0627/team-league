<div class="container">

    <div class="row">
        <div class="col-lg-6">
            <h3>- Profile -</h3>
            <table class="table table-borderless">
                <tr>
                    <th><?php echo USER_NAME_NAME;?></th>
                    <td><?php echo $member->name;?></td>
                </tr>
                <tr>
                    <th><?php echo MAIL_ADDRESS_NAME;?></th>
                    <td><?php echo $member->email;?></td>
                </tr>
                <tr>
                    <th><?php echo USER_ID_NAME;?></th>
                    <td><?php echo $member->login_id;?></td>
                </tr>
                <tr>
                    <th><?php echo TWITTER_NAME;?></th>
                    <?php if (empty($member->twitter)) { ?>
                        <td>-</td>
                    <?php } else { ?>
                        <td><a href="https://twitter.com/<?php echo str_replace("@", "", $member->twitter);?>"><?php echo $member->twitter;?></a></td>
                    <?php } ?>
                </tr>
                <tr>
                    <th><?php echo DISCORD_NAME;?></th>
                    <td><?php echo $member->discord;?></td>
                </tr>
                <?php foreach($platforms as $id => $platform) { ?>
                    <tr>
                        <th><?php echo $platform->name;?></th>
                        <td><?php echo isset($member->platforms[$id]) && !empty($member->platforms[$id]) ? $member->platforms[$id] : "-";?></td>
                    </tr>
                <?php } ?>
            </table>
            <?php if ($member_id == $member->id) { ?>
                <div class="form-group">
                    <a href="/account/edit_form"><button class="btn btn-primary btn-round"><i class="now-ui-icons ui-2_settings-90"></i> Edit Profile</button></a>
                </div>
            <?php } ?>
        </div>

        <?php if (!empty($teams)) { ?>
            <div class="col-lg-6">
                <h3>- Team -</h3>
                <table class="table table-borderless">
                    <?php foreach($teams as $team) { ?>
                        <tr>
                            <td class="h4"><a href="/team/detail/<?php echo $team->id;?>"><?php echo $team->name; ?></a></td>
                        </tr>
                        <tr>
                            <td class="row">
                                <div class="col-3"><img src="/assets/img/icon.png" class="rounded" width="80" height="80"></div>
                                <p class="col-8"><?php echo $team->description; ?></p>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php foreach($teams as $team) { ?>
                        <tr>
                            <td class="h4"><a href="/team/detail/<?php echo $team->id;?>"><?php echo $team->name; ?></a></td>
                        </tr>
                        <tr>
                            <td class="row">
                                <div class="col-3"><img src="/assets/img/icon.png" class="rounded" width="80" height="80"></div>
                                <p class="col-8"><?php echo $team->description; ?></p>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        <?php } ?>

    </div>
</div>

