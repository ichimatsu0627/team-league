<div class="container">

    <h5>Members</h5>

    <form class="col-12" action="/search/member" method="POST">
        <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
        <div class="form-group row">
            <label for="name" class="col-12 col-lg-2 col-form-label text-left">Keyword</label>
            <input type="text" class="col-11 col-lg-6 form-control" name="keyword" placeholder="Keyword" value="<?php if (isset($conditions["keyword"])) { echo $conditions["keyword"]; } ?>">
        </div>
        <div class="form-group row sp-d-none">
            <label for="ranks" class="col-2 col-form-label text-left"><?php echo USER_RANK_NAME;?></label>
            <div class="ranks-wrapper col-lg-9">
                <?php foreach(RANK_DETAIL_LIST as $key => $rank) { ?>
                    <?php
                      $checked = "";
                      if (isset($conditions["ranks"]) && in_array($rank, $conditions["ranks"]))
                      {
                          $checked = 'checked="checked"';
                      }
                    ?>
                    <div class="col-3 col-lg-3 form-check-inline">
                        <input class="form-check-input" type="checkbox" value="<?php echo $rank;?>" name="ranks[]" id="ranks<?php echo $key;?>" <?php echo $checked;?>>
                        <label class="form-check-label" for="ranks<?php echo $key;?>"><?php echo $rank;?></label>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="form-group row pc-d-none">
            <label for="ranks" class="col-12 col-form-label text-left"><?php echo USER_RANK_NAME;?></label>
            <div class="col-10">
                <select name="ranks[]" class="form-control">
                    <option value="">-</option>
                    <?php foreach(RANK_DETAIL_LIST as $key => $rank) { ?>
                      <?php
                        $selected = '';
                        if (isset($conditions["ranks"]) && in_array($rank, $conditions["ranks"]))
                        {
                            $selected = 'selected="selected"';
                        }
                      ?>
                      <option value="<?php echo $rank;?>" <?php echo $selected ;?>><?php echo $rank; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-outline-success btn-round">Search</button>
        </div>
    </form>

    <p>
        <?php foreach($conditions as $condition_key => $condition) { ?>
            <?php if (is_array($condition)) { ?>
                <?php echo $condition_key; ?>: <span style="color: darkorange; font-weight: bold;"><?php echo implode(", ",$condition);?></span><br>
            <?php } else { ?>
                <?php echo $condition_key; ?>: <span style="color: darkorange; font-weight: bold;"><?php echo $condition;?></span><br>
            <?php } ?>
        <?php } ?>
    </p>

    <?php if (!empty($members)) { ?>
        <table class="table col-12 text-center">
            <thead class="thead-dark"  style="width: 100%">
            <tr>
                <th><?php echo USER_ID_NAME;?></th>
                <th><?php echo USER_NAME_NAME;?></th>
                <th><?php echo USER_RANK_NAME;?></th>
                <th><?php echo USER_RATING_NUMERIC_NAME;?></th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0; ?>
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
        <?php if ($all > DEFAULT_PAGER_PER) { ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">

                    <?php if($page > 1) { ?>
                        <li class="page-item">
                            <form action="/search/member/<?php echo ($page-1);?>" method="POST" name="prevbtn">
                                <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
                                <?php if (isset($conditions["keyword"])) { ?><input type="hidden" name="keyword" value="<?php echo $conditions["keyword"];?>" /><?php } ?>
                                <?php if (isset($conditions["tweet"])) { ?><input type="hidden" name="tweet" value="<?php echo $conditions["tweet"];?>" /><?php } ?>
                                <?php if (isset($conditions["discord"])) { ?><input type="hidden" name="discord" value="<?php echo $conditions["discord"];?>" /><?php } ?>
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
                                <form action="/search/member/<?php echo $i;?>" method="POST" name="fixnumbtn<?php echo $i ?>">
                                    <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
                                    <?php if (isset($conditions["keyword"])) { ?><input type="hidden" name="keyword" value="<?php echo $conditions["keyword"];?>" /><?php } ?>
                                    <?php if (isset($conditions["tweet"])) { ?><input type="hidden" name="tweet" value="<?php echo $conditions["tweet"];?>" /><?php } ?>
                                    <?php if (isset($conditions["discord"])) { ?><input type="hidden" name="discord" value="<?php echo $conditions["discord"];?>" /><?php } ?>
                                    <a class="page-link" href="javascript:document.fixnumbtn<?php echo $i ?>.submit();"><?php echo $i;?></a>
                                </form>
                            </li>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($page < ceil($all / DEFAULT_PAGER_PER)) { ?>
                        <li class="page-item">
                            <form action="/search/member/<?php echo ($page+1);?>" method="POST" name="nextbtn">
                                <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
                                <?php if (isset($conditions["keyword"])) { ?><input type="hidden" name="keyword" value="<?php echo $conditions["keyword"];?>" /><?php } ?>
                                <?php if (isset($conditions["tweet"])) { ?><input type="hidden" name="tweet" value="<?php echo $conditions["tweet"];?>" /><?php } ?>
                                <?php if (isset($conditions["discord"])) { ?><input type="hidden" name="discord" value="<?php echo $conditions["discord"];?>" /><?php } ?>
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
        <p>No members.</p>
    <?php } ?>
</div>
