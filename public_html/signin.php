<?php
session_start();
require_once "../config.php";
require_once "functions.php";
$titleHtml = 'Вход';

$dbcon = dbConnect($config);
$errors = [];
$userInfo = array( 'username' => '',);//razobrat;

if ( isset( $_POST['do_post'] ) ){
	if ($_POST['username'] !="" ){
		$userInfo['username'] = trim($_POST['username']); 
	}
	else {
		$errors []= "Pls enter username";
	}
	
	if ($_POST['passwrd'] =="" ){
		$errors []= "Pls enter password";
	}
}
else {$errors [] = ' ';}

if (empty($errors) && $dbcon){
	//username in db check
	$result = getSelect($dbcon, ['id','password','email'], 'cn_user', 'username', $userInfo['username'], "LIMIT 1");
	//var_dump($result);
	if ($result || empty ($result)) {
		if (count($result)!=0){
			foreach ($result[0] as $key => $value) {
				$userInfo[$key] = $value;
			}
		}else {
			$errors []= "Error: User not found";
		}	
	} else {
			$errors []= "Error: User not found";
	}
//print_r($userInfo);
	if (isset($userInfo['password']) && $userInfo['password'] != ""){
		if ( !password_verify( $_POST['passwrd'] , $userInfo['password'])){
			$errors []= "Похоже, что вы использовали неверный пароль. Обратитесь к администратору";
		}
	}else {
			$errors []= "We have a problem with autorization";
	}

}



if (empty($errors)){

	//echo ("валидация успешна");
	$_SESSION['auth_user'] = $userInfo['id'];
	$_SESSION['username'] = $userInfo['username'];
	$errors [] = ' ';
	header("Location: ./");
		exit(); 
	//print_r($_SESSION);
}

require_once "head.php";
?>

<br>
<div id="result_form"><?= $errors [0] ?></div> 


<form method="post" class='signinform'>
  	
  <p>
 	<input placeholder='Input Username' type="username" name="username" class="username" value="<?= $userInfo['username']; ?>"/>
 </p>
 <p>
 	<input placeholder='Input Password' type="password" name="passwrd" class="passwrd" />
 </p>
 
 <p>
 	<input type="submit"  value="Login" name="do_post" />
 </p>
  </form>
  <br>
  <br>
  <br>
  <br>
<p style = "text-align: center;">
  		Forget a password? <a href="reset.php">reset password</a>
</p>
</body>
</html>
