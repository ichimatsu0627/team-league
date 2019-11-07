<div class="container">

    <h5>Teams <label id="toggle-label" for="toggle-input"><u>▼</u></label></h5>
    <input type="checkbox" id="toggle-input">

    <form class="toggle_show col-12" action="/search/team" method="POST">
        <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
        <div class="form-group row">
            <label for="name" class="col-12 col-lg-2 col-form-label text-left">Keyword</label>
            <input type="text" class="col-11 col-lg-6 form-control" name="keyword" placeholder="Keyword" value="<?php if (isset($conditions["keyword"])) { echo $conditions["keyword"]; } ?>">
        </div>
        <div class="form-group row">
            <label for="name" class="col-12 col-lg-2 col-form-label text-left">MMR</label>
            <input type="text" class="col-5 col-lg-3 form-control" name="mmr_from" placeholder="from" value="<?php if (isset($conditions["mmr_from"])) { echo $conditions["mmr_from"]; } ?>">
            <input type="text" class="col-5 col-lg-3 form-control" name="mmr_to" placeholder="to" value="<?php if (isset($conditions["mmr_to"])) { echo $conditions["mmr_to"]; } ?>">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-outline-success btn-round">Search</button>
        </div>
    </form>

    <p class="small">
        <?php foreach($conditions as $condition_key => $condition) { ?>
            <?php if (in_array($condition_key, ["mmr_from", "mmr_to"])) { continue; } ?>
            <?php if (is_array($condition)) { ?>
                <?php echo $condition_key; ?>: <span style="color: darkorange; font-weight: bold;"><?php echo implode(", ",$condition);?></span><br>
            <?php } else { ?>
                <?php echo $condition_key; ?>: <span style="color: darkorange; font-weight: bold;"><?php echo $condition;?></span><br>
            <?php } ?>
        <?php } ?>
    </p>

    <?php if (!empty($teams)) { ?>
        <table class="table col-12 text-center">
            <thead class="thead-dark">
            <tr>
                <th><?php echo TEAM_ID_NAME;?></th>
                <th><?php echo TEAM_NAME_NAME;?></th>
                <th class="sp-d-none"><?php echo TEAM_DESCRIPTION_NAME;?></th>
                <th><?php echo TEAM_MMR_AVR_NAME;?></th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0; ?>
            <?php foreach($teams as $team) { ?>
                <?php
                  $description = str_replace(["\r\n", "\n", "\r"], " ", $team->description);
                  if (mb_strlen($description) > 30) {
                      $description = substr($description, 0, 30)."...";
                  }
                ?>
                <tr>
                    <th><?php echo $team->id;?></th>
                    <td>
                        <a href="/team/detail/<?php echo $team->id; ?>"><?php echo $team->name;?></a>
                    </td>
                    <td class="sp-d-none"><?php echo $description; ?></td>
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
                                <a class="page-link" href="javascript:document.prevbtn.submit();">Prev</a>
                            </form>
                        </li>
                    <?php } else { ?>
                        <li class="page-item disabled">
                            <span class="page-link">Prev</span>
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
                                    <a class="page-link" href="javascript:document.fixnumbtn<?php echo $i; ?>.submit();"><?php echo $i;?></a>
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
