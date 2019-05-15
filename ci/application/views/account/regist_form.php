<div class="container">

    <h3>Account Regist</h3>

    <form class="form" action="/account/regist" method="POST">
        <div class="form-group">
            <label for="user_id">User id</label>
            <input type="text" class="form-control" name="user_id" placeholder="user id">
        </div>
        <div class="form-group">
            <label for="name">Nickname</label>
            <input type="text" class="form-control" name="name" placeholder="nickname">
        </div>
        <div class="form-group">
            <label for="email">email</label>
            <input type="text" class="form-control" name="email" placeholder="email">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="password">
        </div>
        <div class="form-group">
            <label for="conf_password">Confirm password</label>
            <input type="password" class="form-control" name="conf_password" placeholder="confirm password">
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="check">
                <span class="form-check-sign"></span>
                Check me out
            </label>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

</div>
