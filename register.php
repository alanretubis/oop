<?php
require_once 'core/init.php';

try{

    if(Input::exists()){
        if(Token::check(Input::get('token'))){
            $user = new User();
            $salt = Hash::salt(32);

            try{

                $user->create(array(
                    'username' => 'johndoe',
                    'password' => Hash::make('password', $salt),
                    'salt' => $salt,
                    'first_name' => 'John',
                    'surname' => 'Doe',
                    'active' => 1
                ));

            }catch(Exception $e){
                die($e->getMessage());
            }

            Redirect::to('index.php');
        }
    }

}catch(Exception $ex){
	echo $ex->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <form method="post">
            <div class="container">
                <h1>Sign Up</h1>
                <p>Please fill in this form to create an account.</p>
                <hr>
                <input type = "hidden" name ="token" value ="<?php echo Token::generate(); ?>">
                <label for="username"><b>Username:</b></label><br>
                <input type="text" placeholder="Enter Username" name="username" required>
                <br>

                <label for="firstname"><b>Firstname:</b></label><br>
                <input type="text" placeholder="Enter Firstname" name="firstname" required>
                <br>

                <label for="surname"><b>Surname:</b></label><br>
                <input type="text" placeholder="Enter Surname" name="surname" required>
                <br>

                <label for="psw"><b>Password:</b></label><br>
                <input type="password" placeholder="Enter Password" name="password" required>
                <br>

                <label for="psw-repeat"><b>Repeat Password:</b></label><br>
                <input type="password" placeholder="Repeat Password" name="confirm-password" required>
                <br>

                <div class="clearfix">
                <button type="button" class="cancelbtn">Cancel</button>
                <button type="submit" class="signupbtn">Sign Up</button>
                </div>
            </div>
        </form>
    </body>
</html>
