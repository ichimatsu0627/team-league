<div class="container">
    <h3>Team</h3>

    <div class="row">
        <div class="col-lg-3 row">
            <div class="form-group col-6 col-lg-12">
                <img src="/assets/img/icon.png" class="rounded" width="128" height="128">
            </div>
            <div class="form-group col-6 col-lg-12">
                <label><?php echo TEAM_NAME_NAME;?></label>
                <p><?php echo $team->name;?></p>
                <label><?php echo TEAM_DESCRIPTION_NAME;?></label>
                <p style="font-size: .9rem; min-height: 90px;"><?php echo str_replace(["\r\n", "\n", "\r"], "<br>", $team->description);?></p>
                <?php if ($is_my_team) { ?>
                    <a href="/team/edit_form"><button class="btn btn-primary btn-round"><i class="now-ui-icons ui-2_settings-90"></i> Edit Profile</button></a>
                <?php } ?>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="form-group">
                <label><?php echo TEAM_MEMBERS_NAME;?></label>
                <table class="table text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Rank</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                        <?php foreach($team->members as $team_member_id => $team_member) { ?>
                            <tr>
                                <th><?php echo ++$i;?></th>
                                <td>
                                    <?php echo $team_member->detail->name;?>
                                    <?php if ($team_member->role != 0) { echo " <span style='color: red; font-size: .7rem;'>".TEAM_ROLE_LIST[$team_member->role]."</span>"; } ?>
                                </td>
                                <td><span class="badge badge-info"><?php echo RANK_NAME_LIST[5];?></span></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>