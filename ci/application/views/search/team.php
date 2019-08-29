<div class="container">

    <p>
        <?php foreach($conditions as $condition_key => $condition) { ?>
            <?php echo $condition_key; ?>: <span style="color: darkorange; font-weight: bold;"><?php echo $condition;?></span><br>
        <?php } ?>
    </p>

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
        <?php if ($all > DEFAULT_PAGER_PER) { ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">

                    <?php if($page > 1) { ?>
                        <li class="page-item">
                            <form action="/search/team/<?php echo ($page-1);?>" method="POST" name="prevbtn">
                                <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
                                <?php if (isset($conditions["keyword"])) { ?><input type="hidden" name="keyword" value="<?php echo $conditions["keyword"];?>" /><?php } ?>
                                <a class="page-link" href="javascript:document.prevbtn.submit();">Prewv</a>
                            </form>
                        </li>
                    <?php } else { ?>
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    <?php } ?>

                    <?php for($i = $start; $i <= ($start + 2); $i++) { ?>
                        <?php if ($page == $i) { ?>
                            <li class="page-item active">
                                <span class="page-link">
                                    <?php echo $i;?><span class="sr-only">(current)</span>
                                </span>
                            </li>
                        <?php } else if ($i > ceil($all / DEFAULT_PAGER_PER)) { ?>
                            <?php break; ?>
                        <?php } else { ?>
                            <li class="page-item">
                                <form action="/search/team/<?php echo $i;?>" method="POST" name="fixnumbtn<?php echo $i ?>">
                                    <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
                                    <?php if (isset($conditions["keyword"])) { ?><input type="hidden" name="keyword" value="<?php echo $conditions["keyword"];?>" /><?php } ?>
                                    <a class="page-link" href="javascript:document.fixnumbtn<?php echo $i ?>.submit();"><?php echo $i;?></a>
                                </form>
                            </li>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($page < ceil($all / DEFAULT_PAGER_PER)) { ?>
                        <li class="page-item">
                            <form action="/search/team/<?php echo ($page+1);?>" method="POST" name="nextbtn">
                                <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
                                <?php if (isset($conditions["keyword"])) { ?><input type="hidden" name="keyword" value="<?php echo $conditions["keyword"];?>" /><?php } ?>
                                <a class="page-link" href="javascript:document.nextbtn.submit();">Next</a>
                            </form>
                        </li>
                    <?php } else { ?>
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        <?php } ?>
    <?php } else { ?>
        <p>No teams.</p>
    <?php } ?>
</div>
