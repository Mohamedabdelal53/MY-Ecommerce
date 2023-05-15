<?php 
session_start();
$noNavbar='';
$pageTitle = 'Login';
if(isset($_SESSION['Username'])){
    header("Location: dashboard.php");
    exit();
}
include 'init.php';


if ($_SERVER['REQUEST_METHOD']=='POST'){
    $username= $_POST["User"];
    $password= $_POST["pass"];
    $hashed = sha1($password);

    $stmt=$db->prepare("    SELECT 
                                UserID, Username, Password
                            FROM 
                                users 
                            WHERE 
                                Username = ? 
                            AND 
                                Password = ? 
                            AND 
                                GroupID = 1
                            LIMIT 1");
    $stmt->execute(array($username,$hashed));
    $row = $stmt->fetch();
    $count = $stmt->rowcount();// الكونت مش بتجيب غير الadmin
    
    if($count>0){
        $_SESSION['Username']=$username;
        $_SESSION['ID']=$row['UserID'];
        header("Location: dashboard.php");
        exit();
    }
}


?>
<form class="login" action="<?php $_SERVER['PHP_SELF']?>" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control"type="text" name="User" placeholder="Username" autocomplete="off"/>
    <input class="form-control"type="password" name="pass" placeholder="Password" autocomplete="new-password"/>
    <input class="btn btn-primary btn-block"type="submit" value="Login"/>

</form>

<?php include $tpl.'footer.php'; ?>