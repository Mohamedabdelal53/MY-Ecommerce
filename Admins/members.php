<?php

/*
===================================================
== Manage Members Page                           ==
== You Can Edit | Add | Delete Members From Here ==
===================================================
*/

session_start();
$pageTitle='Members'; // Page Title
if(isset($_SESSION['Username'])){
    include 'init.php';
    $do = isset($_GET['do'])? $_GET['do'] : 'Manage';
    
    // start manage page
    
    if($do == 'Manage'){// Manage Member pages 
        // Select all users except admins
        $query ='';
        $pending='PENDING';
        if(isset($_GET['page']) && $_GET['page'] == 'Pending'){ // Check If There Is Pending Members
            $query = 'AND RegStatus = 0';
        }
        
        $stmt = $db->prepare("SELECT * FROM users WHERE GroupID != 1 $query order by USERID DESC");
        // Execute The Statement
        $stmt->execute();
        // Fetching The Data From Database
        $rows = $stmt->fetchAll(); 
        if(!(empty($rows))){
            
            ?>
        <!-- Manage Members -->
        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Fullname</td>
                        <td>Registed Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                        foreach($rows as $row){ // Loop To Show The Data OF Users 
                            echo "<tr>";
                                echo "<td>" .$row['UserID']. '</td>';
                                echo "<td>";
                                if(empty($row['avatar'])){
                                    echo 'No Image' ;
                                }else{
                                    echo "<img src ='Uploads/Avatar/" .$row['avatar']."'";
                                }
                                echo "</td>";
                                echo "<td>" .$row['Username']. '</td>';
                                echo "<td>" .$row['Email']. '</td>';
                                echo "<td>" .$row['Fullname']. '</td>';
                                echo "<td>" .$row['Date']. '</td>';
                                echo "<td>";
                                echo '<a href="?do=Edit&userid='. $row['UserID'] ."\"".' class="btn btn-success"><i class="fa fa-edit"></i>Edit</a> ';
                                    echo '<a href="?do=Delete&userid='. $row['UserID'] ."\"".' class="btn btn-danger confirm"><i class="fa fa-close"></i>Delete</a> ';
                                    if($row['RegStatus'] == 0){
                                        echo '<a 
                                                href="?do=Activate&userid='. $row['UserID'] ."\"".' 
                                                class="btn btn-info activate">
                                                <i class="fa fa-check"></i> 
                                                Activate
                                                </a>';
                                    }
                                echo "</td>";
                            echo "</tr>";
                        }
                    ?>
        </table>
            </div>
            <?php
            }else{
                echo '<h1 class="container text-center alert alert-info"> NO '; 
                if(isset($_GET['page']) && $_GET['page'] == 'Pending'){echo $pending;} // If This Page Of Pending Members Print Pending In Error Message.
                echo ' MEMBERS</h1>';
            }
            if(!(isset($_GET['page']))){
                echo "<div class='container'><a href='members.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i>ADD New Member</a></div>";
            }
                ?>
        </div>
        
        <?php
    }
    elseif($do=='Add'){//Add members page?>
        <h1 class="text-center">ADD New Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text" 
                                name="username" 
                                class="form-control"  
                                autocomplete="off" 
                                required="required" 
                                placeholder="User Name To Login Into Shop">
                            </input>
                        </div>
                    </div>
                    <!-- End Username Field -->
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="password" 
                                name="password" 
                                class="password form-control" 
                                autocomplete="new-password" 
                                required="required" 
                                placeholder="Password Must Be Hard & Complex">
                            </input>
                            <i class="show-pass fa fa-eye fa-2px"></i>
                        </div>
                    </div>
                    <!-- End Password Field -->
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control" 
                                required="required" 
                                placeholder="Email Must Be Valid">
                            </input>
                        </div>
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Fullname Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Fullname</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text" 
                                name="full" 
                                class="form-control"  
                                required="required" 
                                placeholder="FullName Appear In Your Profile Page">
                            </input>
                        </div>
                    </div>
                    <!-- End Fullname Field -->
                    <!-- Start Avatar Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">User Avatar</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                type="file"
                                name="avatar"
                                class="form-control"
                                required="required"
                                >
                            </input>
                        </div>
                    </div>
                    <!-- End Avatar Field -->
                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input 
                            type="submit" 
                            value="Add Member" 
                            class="btn btn-primary btn-lg">
                        </input>
                    </div>
                    <!-- End Submit Field -->
                    </div>
                </form>
            </div>
<?php }

    elseif($do == 'Insert'){    /*  Insert Member Page  */        

        if($_SERVER['REQUEST_METHOD']=='POST'){   // Check If The Method Of Insert Come From POst Method   
            echo "<h1 class='text-center'>Insert Member</h1>";
            echo "<div class='container'>";
            
            //Upload Files
            $avatar     = $_FILES['avatar'];
            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp  = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];
            
            // List Of Allowed File Typed To Upload
            
            $avatarAllowedExtenstion = array("jpeg","jpg","png","gif");

            // Get Avatar Extenstion
            $avatarExtenstion1 = explode('.',$avatarName);
            $avatarExtenstion = strtolower(end($avatarExtenstion1));

            //Get Variable From Form

            $user = $_POST['username'];
            $Email = $_POST['email'];
            $Fullname = $_POST['full'];
            $pass=$_POST['password'];
            
            $hashpass=sha1($pass);

            $formErrors=array(); // Array To Show Errors
            if(strlen($user)<4){
                $formErrors[]='Username Can\'t Be Less Than <strong>4</strong> Character';
            }
            if(strlen($user)>20){
                $formErrors[]='Username Can\'t Be Greater Than <strong>20</strong> Character';
            }
            if(empty($user)){
                $formErrors[]='Username Can\'t be <strong>empty</strong>';
            }
            if(empty($pass)){
                $formErrors[]='Password Can\'t be <strong>empty</strong>';
            }
            if(empty($Email)){
                $formErrors[]='Email Can\'t be <strong>empty</strong>';
            }
            if(empty($Fullname)){
                $formErrors[]='FullName Can\'t be <strong>empty</strong>';
            }
            if(!(empty($avatar)) && !(in_array($avatarExtenstion, $avatarAllowedExtenstion))){
                $formErrors[] = 'This Extenstion Is Not <strong>Allowed</strong>';
            }
            if(empty($avatar)){
                $formErrors[] = 'Avatar Is <strong>Required</strong>';
            }
            if($avatarSize > 4194304){
                $formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
            }
            // Loop Into Errors And Print It
            foreach($formErrors as $error){
                $theMsg = '<div class="alert alert-danger">'.$error.'</div>';
                redirectHome($theMsg, 'back');
            }
            // Chcek if there is no error
            if(empty($formErrors)){
                // Check Item
                $avatar = rand(0, 1000000) . '_' . $avatarName;
                move_uploaded_file($avatarTmp, 'Uploads\Avatar\\'. $avatar);
                $value = $user;
                $check = checkItem("Username", "users", $value);
                if($check == 1){
                    $theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';
                    redirectHome($theMsg, 'Back');
                }
                // Insert Userinfo To Database
                else{
                    $stmt = $db->prepare("INSERT INTO users
                                            (Username, Password, Fullname, Email, RegStatus,Date, avatar) 
                                        VALUES 
                                            (:zuser, :zpass, :zfull, :zmail, 1, now(), :zavatar)");
                    $stmt->execute(array(
                    
                    'zuser'   => $user,
                    'zpass'   => $hashpass,
                    'zfull'   => $Fullname,
                    'zmail'   => $Email,
                    'zavatar' => $avatar
                
                    ));
                    // Success Message
                    $theMsg = '<div class="alert alert-success">'.$stmt->rowcount() .' Record Inserted</div>';
                    redirectHome($theMsg, 'members.php');// redirect the page to referer page 
                }
            }
        }else{
            // Error Message
            $theMsg = "<div class = 'alert alert-danger' >You Cant Open This Page Directly </div>";
            $seconds = 2;

            echo "<div class='container'>";
                redirectHome($theMsg, null);
            echo "</div>";
        }
        echo "</div>";

    }elseif($do == 'Edit'){
        // Check If Get Request Userid Is Numeric

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']): 0;
        
        // Select All Data Depend On This ID
        
        $stmt=$db->prepare("SELECT * FROM users WHERE  UserID=? LIMIT 1");
        
        // Excute Query
        
        $stmt->execute(array($userid));
        
        //Fetch The Data
        
        $row = $stmt->fetch();
        
        // Row Count
        
        $count = $stmt->rowcount();
        
        // If There Is ID Show The Form
        
        if($count>0){?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text" 
                                name="username" 
                                class="form-control" 
                                value='<?php echo $row['Username']?>' 
                                autocomplete="off" 
                                required="required">
                            </input>
                        </div>
                    </div>
                    <!-- End Username Field -->
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>"></input>
                            <input 
                                type="password" 
                                name="newpassword" 
                                class="form-control" 
                                autocomplete="new-password" 
                                placeholder="Leave Blank if you dont want to change">
                            </input>
                        </div>
                    </div>
                    <!-- End Password Field -->
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control" 
                                value='<?php echo $row['Email']?>' 
                                required="required">
                            </input>
                        </div>
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Fullname Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Fullname</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text" 
                                name="full" 
                                class="form-control" 
                                value='<?php echo $row['Fullname']?>' 
                                required="required">
                            </input>
                        </div>
                    </div>
                    <!-- End Fullname Field -->
                    <!-- Start Avatar Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">User Avatar</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                type="file"
                                name="avatar"
                                value = "<?php echo $row['avatar'] ?>"
                                class="form-control"
                                required="required"
                                >
                            </input>
                        </div>
                    </div>
                    <!-- End Avatar Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input 
                                type="submit" 
                                value="Save" 
                                class="btn btn-primary btn-lg">
                            </input>
                        </div>
                    </div>
                </form>
            </div>
<?php } // If There's No Such ID Show Error Message
        else{
            $theMsg='<div class="alert alert-danger">There\'s No Such ID</div>';
            redirectHome($theMsg, 'members.php');
        }
    }elseif($do == 'Update'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";

            // Uploads Files

            $avatar = $_FILES['avatar'];
            $avatarName = $_FILES['avatar']['name'];
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];
            $avatarSize = $_FILES['avatar']['size'];
            
            $avatarAllowedExtenstion = array('jpg', 'jpeg', 'png', 'gif');

            $avatar_extention1 = explode('.',$avatarName);
            $avatarExtenstion = strtolower(end($avatar_extention1));

            //Get Variable From Form

            $id = $_POST['userid'];
            $user = $_POST['username'];
            $Email = $_POST['email'];
            $Fullname = $_POST['full'];

            $pass= empty($_POST['newpassword'])? $_POST['oldpassword']: sha1($_POST['newpassword']);
            
            $formErrors=array();
            
            if(strlen($user)<4){
                $formErrors[]='Username Can\'t Be Less Than <strong>4</strong> Character';
            }
            if(strlen($user)>20){
                $formErrors[]='Username Can\'t Be Greater Than <strong>20</strong> Character';
            }
            if(empty($user)){
                $formErrors[]='Username Can\'t be <strong>empty</strong>';
            }
            if(empty($Email)){
                $formErrors[]='Email Can\'t be <strong>empty</strong>';
            }
            if(empty($Fullname)){
                $formErrors[]='FullName Can\'t be <strong>empty</strong>';
            }
            if(!(empty($avatar)) && !(in_array($avatarExtenstion, $avatarAllowedExtenstion))){
                $formErrors[] = 'This Extenstion Is Not <strong>Allowed</strong>';
            }
            if(empty($avatar)){
                $formErrors[] = 'Avatar Is <strong>Required</strong>';
            }
            if($avatarSize > 4194304){
                $formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
            }
            
            // Loop Into Errors And Echo It
            
            foreach($formErrors as $error){
                $theMsg = '<div class="alert alert-danger">'.$error.'</div> ';
                redirectHome($theMsg, 'Back', 3);
            }
            
            //Check If There Is No Error To Update The Database
            
            if(empty($formErrors)){
                $stmt2 = $db->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                $stmt2->execute(array($user, $id));
                $count = $stmt2->rowCount();
                if($count == 1){
                    $theMsg =  "<div class='container alert alert-danger'> This User Is Exist</div>";
                    redirectHome($theMsg, 'back');
                }else{
                    $avatar = rand(0,1000000). $avatarName;
                    move_uploaded_file($avatarTmp, 'Uploads\Avatar\\'.$avatar);
                    $stmt = $db->prepare("UPDATE 
                                            users 
                                        SET 
                                            Username = ?, Email = ?, Fullname = ?, Password = ?, avatar = ? 
                                        WHERE 
                                            UserID = ?");
                $stmt->execute(array($user, $Email, $Fullname, $pass, $avatar, $id));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . ' Record Updated</div>';
                redirectHome($theMsg, "members.php",422);
            }
            }
        }else{
            $theMsg = "<div class='alert alert-danger'>You Cant Open This Page Directly</div>";
            redirectHome($theMsg, null);
        }
        echo "</div>";
    }elseif($do == 'Delete'){
        // Delete Member Pages
        
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";
            // Check If Get Request Userid Is Numeric

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']): 0;

            // Select All Data Depend On This ID

            $check = checkItem('UserID', 'users', $userid);

            // If There Is ID Show The Form

            if($check>0){
                $stmt = $db->prepare("DELETE FROM users WHERE UserID = :zid");
                $stmt->bindParam(":zid", $userid);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . ' Record Deleted</div>';
                redirectHome($theMsg, "back");
            }else{
                $theMsg = '<div class="text-center alert alert-danger">This Id Is Not Exist</div>';
                redirectHome($theMsg, "members.php");
            }
            echo '</div>';
    }elseif($do == 'Activate'){
        // Activate Pending Members
        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";
            // Check If Get Request Userid Is Numeric

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']): 0;

            // Select All Data Depend On This ID

            $check = checkItem('UserID', 'users', $userid);

            // If There Is ID Show The Form

            if($check>0){
                $stmt = $db->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                $stmt->execute(array($userid));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . ' Record Activated</div>';
                redirectHome($theMsg, "back");
            }else{
                $theMsg = '<div class="text-center alert alert-danger">This Id Is Not Exist</div>';
                redirectHome($theMsg, "members.php");
            }
            echo '</div>';        
    }
    include $tpl.'footer.php';
}else{
    header("location: index.php");
    exit();
}