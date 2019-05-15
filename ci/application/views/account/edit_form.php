<div class="container">

    <h3>Account Edit</h3>

    <form class="form" action="/account/edit" method="POST">
        <div class="form-group">
            <label for="user_id">User id</label>
            <input type="text" class="form-control" name="user_id" placeholder="user id" value="<?php echo $member->user_id;?>">
        </div>
        <div class="form-group">
            <label for="name">Nickname</label>
            <input type="text" class="form-control" name="name" placeholder="nickname" value="<?php echo $member->name;?>">
        </div>
        <div class="form-group">
            <label for="email">email</label>
            <input type="text" class="form-control" name="email" placeholder="email" value="<?php echo $member->email;?>">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Edit</button>
        </div>
    </form>

</div>
