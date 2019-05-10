<h2>account regist</h2>

<form action="/account/regist" method="POST">
    <table>
        <tr>
            <th>user_id</th>
            <td><input type="text" name="user_id" size="30" maxlength="20"></td>
        </tr>
        <tr>
            <th>name</th>
            <td><input type="text" name="name" size="20"></td>
        </tr>
        <tr>
            <th>email</th>
            <td><input type="text" name="email" size="100"></td>
        </tr>
        <tr>
            <th>password</th>
            <td><input type="password" name="password" size="20"></td>
        </tr>
        <tr>
            <th>confirm password</th>
            <td><input type="password" name="conf_password" size="20"></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="regist"></td>
        </tr>
    </table>
</form>
