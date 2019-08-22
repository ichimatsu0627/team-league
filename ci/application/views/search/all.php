<div class="container">
    <h3>Members</h3>

    <?php if (!empty($members)) { ?>
        <table class="table col-10 text-center">
            <thead class="thead-dark">
            <tr>
                <th>No.</th>
                <th><?php echo USER_NAME_NAME;?></th>
                <th><?php echo USER_RATE_NAME;?></th>
                <th><?php echo USER_RATING_NUMERIC_NAME;?></th>
            </tr>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                <?php foreach($members as $member) { ?>
                    <tr>
                        <th><?php echo ++$i;?></th>
                        <td>
                            <a href="/account/profile/<?php echo $member->id;?>"><?php echo $member->name;?></a>
                        </td>
                        <td><span class="badge badge-<?php echo get_rank_class($member->standard_rank);?>"><?php echo $member->standard_rank;?></span></td>
                        <td><?php echo $member->standard_mmr;?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No members.</p>
    <?php } ?>


    <h3>Teams</h3>
    <?php if (!empty($teams)) { ?>
        <table class="table col-10 text-center">
            <thead class="thead-dark">
            <tr>
                <th>No.</th>
                <th><?php echo TEAM_NAME_NAME;?></th>
                <th><?php echo TEAM_DESCRIPTION_NAME;?></th>
                <th><?php echo TEAM_RATING_NUMERIC_NAME;?></th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0; ?>
            <?php foreach($teams as $team) { ?>
                <tr>
                    <th><?php echo ++$i;?></th>
                    <td>
                        <a href="/team/detail/<?php echo $team->id;?>"><?php echo $team->name;?></a>
                    </td>
                    <td><?php echo str_replace(["\r\n", "\n", "\r"], "<br>", $team->description);?></td>
                    <td><?php echo $team->standard_mmr_avr; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No teams.</p>
    <?php } ?>
</div>


