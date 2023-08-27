<?php
require_once 'core/init.php';

$init_configs = parse_ini_file("APPS.ini");

$page_title = $init_configs['SITE_NAME'];
$business = $init_configs['SITE_SUBSTITLE'];
$site_name = $init_configs['SITE_NAME'];
$msg = "";
try{
if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array('tag_name' => 'Username', 'required' => true),
			'password' => array('tag_name' => 'Password', 'required' => true),
			));

		if($validation->passed()){
			$user = new User();

      $db = DB::getInstance();

			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);

			if($login){
        
        Redirect::to('index.php');
        
			}else{
				$msg ='Sorry, logging in failed.';
			}

		}else{
			foreach ($validation ->errors() as $error) {
				// echo $error, '<br>';
        $msg .= "*".$error."<br/>";
			}
		}
	}
}
}catch(Exception $ex){
  echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo strtoupper($page_title); ?> | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <script type="text/javascript">
  
window.onload = initClock;
 
function initClock() {
  var now = new Date();
  var hr  = now.getHours();
  var min = now.getMinutes();
  var sec = now.getSeconds();
  if (min < 10) min = "0" + min;  // insert a leading zero
  if (sec < 10) sec = "0" + sec;

  var suf;
  if(hr > 11)
  {
    suf = "PM";
  }
  else
  {
  suf = "AM";
  }
  if(hr > 12){
  document.getElementById('time').innerHTML
        = hr%12 + ":" + min + ":" + sec + " "+suf;
    }else
    {
       document.getElementById('time').innerHTML
        = hr + ":" + min + ":" + sec +" "+ suf;
    }
  setTimeout('initClock()', 500);
} 

</script>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index.php" style="color: blue;"><b><?php echo strtoupper($site_name); ?></b></a> 
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <h4 class="login-box-msg" style="float:left; margin-left:30px; margin-top:16px; color:red">
      <b> 
        <?php 
          echo date_create("",timezone_open("Asia/Manila"))->format("F d, Y");
        ?>
      </b>| <span id="time" /></span>
    </h4> 
    <p class="login-box-msg">Please sign in to start</p>
    <form method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name ="username" id ="username" autocomplete="off">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name ="password" id ="password" autocomplete="off">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name='remember' id="remember"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <input type = "hidden" name ="token" value ="<?php echo Token::generate(); ?>">
          <button type="submit" class="btn btn-primary btn-block btn-flat" value = "Log In">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!-- <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div> -->
    <!-- /.social-auth-links -->

    <!-- <a href="#">I forgot my password</a><br>
    <a href="register.php" class="text-center">Register a new membership</a> -->
  </div>

  <?php
      if($msg != "")
      {
        echo "<p style=\"color:red;font-style: oblique;\"><b>".$msg."</b></p>";
      }
  ?>

  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
</body>
</html>
