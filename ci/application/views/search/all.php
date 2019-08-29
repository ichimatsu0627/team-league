<div class="container">

    <?php if (!empty($keyword)) { ?>
        <p>Keyword: <span style="color: darkorange; font-weight: bold;"><?php echo $keyword;?></span></p>
    <?php } ?>

    <h3>Members</h3>

    <?php if (!empty($members)) { ?>
        <table class="table col-12 text-center">
            <thead class="thead-dark">
            <tr>
                <th><?php echo USER_ID_NAME;?></th>
                <th><?php echo USER_NAME_NAME;?></th>
                <th><?php echo USER_RATE_NAME;?></th>
                <th><?php echo USER_RATING_NUMERIC_NAME;?></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($members as $member) { ?>
                    <tr>
                        <th><?php echo $member->id;?></th>
                        <td>
                            <a href="/account/profile/<?php echo $member->id;?>"><?php echo $member->name;?></a>
                        </td>
                        <td><span class="badge badge-<?php echo get_rank_class($member->standard_rank);?>"><?php echo $member->standard_rank;?></span></td>
                        <td><?php echo $member->standard_mmr;?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php if (count($members) >= $limit) { ?>
            <form class="text-right col-12" style="margin-bottom: 50px;" action="/search/member" method="POST" name="memberForm">
                <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
                <input type="hidden" name="keyword" value="<?php echo $keyword;?>" />
                <a href="javascript:document.memberForm.submit();">more...</a>
            </form>
        <?php } ?>
    <?php } else { ?>
        <p>No members.</p>
    <?php } ?>


    <h3>Teams</h3>
    <?php if (!empty($teams)) { ?>
        <table class="table col-12 text-center">
            <thead class="thead-dark">
            <tr>
                <th><?php echo TEAM_ID_NAME;?></th>
                <th><?php echo TEAM_NAME_NAME;?></th>
                <th><?php echo TEAM_DESCRIPTION_NAME;?></th>
                <th><?php echo TEAM_MMR_AVR_NAME;?></th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0; ?>
            <?php foreach($teams as $team) { ?>
                <tr>
                    <th><?php echo $team->id;?></th>
                    <td>
                        <a href="/team/detail/<?php echo $team->id;?>"><?php echo $team->name;?></a>
                    </td>
                    <td><?php echo str_replace(["\r\n", "\n", "\r"], "<br>", $team->description);?></td>
                    <td><?php echo $team->standard_mmr_avr; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php if (count($teams) >= $limit) { ?>
            <form class="text-right col-12" style="margin-bottom: 50px;" action="/search/team" method="POST" name="teamForm">
                <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
                <input type="hidden" name="keyword" value="<?php echo $keyword;?>" />
                <a href="javascript:document.teamForm.submit();">more...</a>
            </form>
        <?php } ?>
    <?php } else { ?>
        <p>No teams.</p>
    <?php } ?>
</div>


