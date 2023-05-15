<?php 
ob_start();
session_start();
$pageTitle = 'Profile';
include 'init.php';
if(isset($_SESSION['user'])){
    $getUser = $db->prepare("SELECT * FROM users WHERE Username = ?");
    $getUser->execute(array($_SESSION['user']));
    $info = $getUser->fetch();
    $count = $getUser->rowCount();
    $userid = $info['UserID'];
    $_SESSION['UserID'] = $info['UserID'];
    $_SESSION['avatar'] = $info['avatar'];
    if($count>0){
        ?>

<h1 class="text-center">My Profile</h1>
<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul>
                    <li>
                        <i class="fa fa-unlock-alt fa-fw"></i>
                        <span>Login Name</span>: <?php echo $info['Username'] ?> 
                    </li>
                    <li>
                        <i class="fa fa-envelope-o fa-fw"></i>
                        <span>Email</span>: <?php echo $info['Email'] ?> 
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Fullname</span>: <?php echo $info['Fullname'] ?> 
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Register Date</span>: <?php echo $info['Date'] ?> 
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Favourite Category</span>: 
                    </li>
                </ul>
                <a href="#" class="btn btn-primary">Edit Infromation</a>
            </div>
        </div>
    </div>
</div>
<div id= 'my-ads' class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Items</div>
            <div class="panel-body">
                <?php
                $items = getAll('*','items','WHERE Member_ID='.$userid.'',NULL,'item_ID');
                if(!(empty($items))){
                    echo '<div class="row">';
                        foreach($items as $item){
                            echo "<div class='col-sm-6 col-md-4'>";
                            echo "<div class='thumbnail item-box'>";
                            if($item['Approve']==0){
                                echo '<span class="Approve-Status">Waiting Approve</span>';
                            }
                            echo "<span class='price-tag'>$".$item['Price']."</span>";
                            echo "<img class='img-responsive' src='Uploads\Avatar\\".$item['item_IMG']."' alt='' />";
                            echo "<div class='caption'>";
                            echo "<h3><a href='items.php?item_ID=".$item['item_ID']."'>".$item['Name']."</a></h3>";
                            echo "<p>".$item['Description']."</p>";
                            echo "<div class='date'>".$item['Add_Date']."</div>";
                            if($item['Approve']==0){
                                echo "<div>This Item Isn't Allowed</div>";
                            }
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                        echo '</div>';
                }else{
                    echo "There's No Ads To Show , Create <a href='newads.php'>New Ads</a>";
                }
                ?>
    </div>
</div>
</div>
</div>
</div>
<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
                <?php
                $comments = getAll("comment",'comments','WHERE user_ID = '.$userid.'',NULL,'c_ID','DESC');
                if(!(empty($comments))){
                    foreach($comments as $comment){
                        echo '<p>'.$comment['comment'].'</p>';
                    }
                }else{
                    echo "There's No Comments To Show";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
}else{
    echo '<h4 class="alert alert-danger text-center container">This Email Is Deleted</h4>';
}
}else{
    header("Location: login.php?open=login");
}
include $tpl.'footer.php';
ob_end_flush();
?>