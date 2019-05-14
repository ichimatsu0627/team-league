<div class="container">
    <h1 class="h1">Team League</h1>
    <div class="row">
        <?php if (isset($t_member) && !empty($t_member)) { ?>
        <div class="card">
            <p><?php echo $t_member->name;?></p>
        </div>
        <?php } ?>
        <ul>
            <li><a href="/login/index"><button class="btn btn-link btn-info">ログイン</button></a></li>
        </ul>
    </div>
</div>
