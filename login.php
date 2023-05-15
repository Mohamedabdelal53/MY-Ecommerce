<?php
ob_start();
if(isset($_GET['open']) && $_GET['open']=='login'){
    $pageTitle='Login';
}
elseif(isset($_GET['open']) && $_GET['open']=='signup'){
    $pageTitle='Sign Up';
}else{
    header("location: index.php"); // To Return The User To Home Page If The User Open The File Direct
}
session_start();
if(isset($_SESSION['user'])){
    header("Location: index.php");
}
include 'init.php'; 
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['login'])){

        $username = $_POST['username'];
        $Password = $_POST['password'];
        $hashpass = sha1($Password);
        $stmt=$db->prepare("SELECT Username, Password FROM users WHERE Username=? AND Password=? AND RegStatus = 1");
        $stmt->execute(array($username, $hashpass));
        $count = $stmt->rowCount();
        if($count>0){
            $_SESSION['user']=$username;
            header("Location: index.php");
            exit();
            
        }else{
            echo "<div class='container text-center alert alert-danger'>Please Check The Validation Of Your Account</div>";
        }
    }

    if(isset($_POST['signup'])){// SignUp
        $formerrors = array(); // array of errors
        
        $filtereduser = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        
        $filteredemail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        $password = $_POST['password'];
        $password_again = $_POST['password-again'];
        
        if(strlen($filtereduser)<4){
            $formerrors[] = "Username Can't Be Less Than 4 Letter ";
        }
        if($password !== $password_again){
            $formerrors[] = 'Please Enter The Same Password';
        }
        if(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) != true){
            $formerrors[] = 'This Email Not Valid';
        }
        if(empty($formerrors)){

            $username = $filtereduser;
            $email = $filteredemail;
            $checkuser = checkItem("Username", 'users', $username);
            $hashedpass = sha1($password);
            if($checkuser!=1){
                    $stmt = $db->prepare("INSERT INTO users (Username, Password, Email, RegStatus, Date) VALUES (:zusername, :zpassword, :zemail, 0, now())");
                    $stmt->execute(array(
                        'zusername'=>$username,
                        'zpassword'=>$hashedpass,
                        'zemail'=>$email
                ));
                $count = $stmt->rowCount();
                if($count>0){
                    $success = 'Congrats Know Your Account Must Be Active By Admin';
                }
        }else{
            echo "<div class='container text-center alert alert-danger'>Sorry This Username Is Exist</div>";
        }
}
}
}
?>
    <div class="container login-page">
<?php if(isset($_GET['open']) && $_GET['open']=='login'){ ?>
        <!-- Start Login Form -->
        <h1 class='text-center'>
            Login
        </h1>
    <form class='login' action="<?php $_SERVER['PHP_SELF']?>" method="POST" >
    <div class="input-container">
        <input 
        class='form-control' 
        type="text" 
        name='username' 
        placeholder='Username'
        required
        autocomplete='off' />
    </div>
    <div class="input-container">
        <input 
        class='form-control' 
        type="password" 
        name='password' 
        placeholder='Password'
        required
        autocomplete='new-password' />
    </div>
    <input 
    class='btn btn-primary btn-block' 
    type="submit" 
    name = 'login'
    value='Login' />
</form>
<div class='container text-center'>
    <a href="login.php?open=signup">Create Account</a>
</div>
<!-- End Login Form -->
<?php 
} ?>
    <?php if(isset($_GET['open']) && $_GET['open']=='signup'){ ?>
        <!-- Start Signup Form -->
        <h1 class='text-center'>
            Signup
        </h1>
        <form class='signup' action="<?php $_SERVER['PHP_SELF']?>" method="POST" >
            <div class="input-container">
                <input 
                class='form-control' 
                type="text" 
                name='username' 
                pattern = ".{4,}"
                title="Username Must Be Morethan 4 Chars"
                required
                placeholder='Username'
                autocomplete='off' 
            />
        </div>
        <div class="input-container">
            <input 
            class='form-control' 
            type="password" 
            name='password' 
            minlength = "4"
            placeholder='Password'
            required
            autocomplete='new-password' />
        </div>
        <div class="input-container">
            <input 
            class='form-control' 
            type="password" 
            name='password-again' 
            minlength = "4"
            required
            placeholder='Password Again'
            autocomplete='new-password' />
        </div>
        <div class="input-container">
            <input 
            class='form-control' 
            type="email"
            name='email' 
            required
            placeholder='Email' />
        </div>
        <input 
        class='btn btn-success btn-block' 
            type="submit"
            name = 'signup'
            value='SignUp' />
        </div>
    </form>
    <div class="the-errors text-center">
        <?php 
        if(!(empty($formerrors))){
            foreach($formerrors as $error){
                echo '<div class="container text-center alert alert-danger">'.$error.'</div>';
            }
        }
        if(isset($success)){
            echo "<div class='container alert alert-success text-center'>".$success."</div>";
        }
        ?>
    </div>
    <!-- End Signup Form -->
    <?php
        }
        ?>
            </div>


<?php include $tpl.'footer.php'; 
ob_end_flush();
?>