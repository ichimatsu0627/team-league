<div class="container">

    <h3>Create Team</h3>

    <form class="form" action="/team/register" method="POST">
        <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
        <div class="form-group">
            <label for="name"><?php echo TEAM_NAME_NAME;?><small style="color: red;"> ※ required</small></label>
            <input type="text" class="form-control" name="name" maxlength="20" placeholder="<?php echo mb_strtolower(TEAM_NAME_NAME);?> max 20 characters">
        </div>
        <div class="form-group">
            <label for="description"><?php echo TEAM_DESCRIPTION_NAME;?></label>
            <textarea type="text" class="form-control" name="description" maxlength="75" placeholder="<?php echo mb_strtolower(TEAM_DESCRIPTION_NAME);?> max 75 characters"></textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-round">Submit</button>
        </div>
    </form>

</div>
