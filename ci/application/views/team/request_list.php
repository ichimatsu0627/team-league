<div class="container">
    <h3>
        Join Requests
    </h3>

    <table class="table col-10">
        <?php foreach($requests as $request) { ?>
            <tr>
                <td class="text-center align-middle"><u><a href="/account/profile/<?php echo $request->t_member_id;?>"><?php echo $members[$request->t_member_id]->name; ?></a></u> から参加リクエストが届いています</td>
                <td><a class="btn btn-round btn-success" href="/team/accept_request/<?php echo $request->id;?>">承認</a></td>
                <td><a class="btn btn-round btn-secondary" href="/team/refuse_request/<?php echo $request->id;?>">却下</a></td>
            </tr>
        <?php } ?>
    </table>
</div>
