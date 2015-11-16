<?php
require_once 'v/headers.php';

?>
<div id="login">
<fieldset>
<legend>Login</legend>
<form method="post" action="";>
<?php if(isset($loginHint)) {
echo '<div class="loginHint">'.$loginHint.'</div>';
 } ?>
<label for="userLogin">User name</label>
<input type="text" name="userLogin" placeholder="type user name here" autofocus="true" maxlength="30"></input>
<label for="userPW">Password</label>
<noscript><input type="hidden" name="script" value="no"></input></noscript>
<input type="text" name="userPW" placeholder="type your password here"></input>
<input type="submit" name="loginBtn" value="Login"></input>
</form>
</fieldset>
<a href="?knocking">Knocking on the door</a>
</div>
