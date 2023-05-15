<?php
ob_start();
session_start();
if(isset($_SESSION['Username'])){
    $pageTitle = 'Dashboard';
    include 'init.php';
    $numUsers = 5;
    $latestUsers = getLatest("*", "users", "UserID", $numUsers);
    
    $numItems = 6;
    $latestItems = getLatest("*", "items", "item_ID", $numItems);
    
    $numComments = 5;

    /*  Start Dashboard Page  */
    
    ?>
    <div class="home-stats">
        <div class="container text-center">
            <h1 class="text-center">Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <a href="members.php">
                            <i class="fa fa-users"></i>
                            <div class="info">
                                    Total Members
                                    <span>
                                        <?php echo countItems("UserID", "users")  ?>
                                    </span>
                                </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <a href="members.php?do=Manage&page=Pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                                Pending Members
                                <span><?php echo checkItem("RegStatus", "users", "0")  ?></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <a href="items.php">
                            <i class="fa fa-tag"></i>
                            <div class="info">
                                Total Items
                                <span><?php echo countItems("item_ID", "items")  ?></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat  st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            Total Comments
                            <a href="comments.php">
                                <span>
                                    <?php echo countItems("c_ID", "comments")  ?>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i> Latest <?php echo $numUsers ?> Registered Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div class="list-unstyled latest-users">
                            <?php
                            foreach($latestUsers as $user){
                                echo '<li>';
                                    echo $user['Username'];
                                    echo '<a href="members.php?do=Edit&userid='.$user['UserID'] . '">';
                                    echo '<span class="btn btn-success pull-right">';
                                        echo '<i class="fa fa-edit "></i> Edit';
                                    if($user['RegStatus'] == 0){
                                        echo '<a 
                                                href="members.php?do=Activate&userid='. $user['UserID'] ."\"".' 
                                                class="btn btn-info pull-right activate">
                                                <i class="fa fa-close"></i> 
                                                Activate
                                            </a>';
                                    }
                                    echo '</span>';
                                    echo "</a>";
                                echo '</li>';
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest <?php echo $numItems?> Items Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div class="list-unstyled latest-users">
                        <?php
                        if(!(empty($latestItems))){

                            foreach($latestItems as $item){
                                echo '<li>';
                                echo $item['Name'];
                                    echo '<a href="items.php?do=Edit&item_ID='.$item['item_ID'] . '">';
                                    echo '<span class="btn btn-success pull-right">';
                                        echo '<i class="fa fa-edit "></i> Edit';
                                    if($item['Approve'] == 0){
                                        echo '<a 
                                                href="items.php?do=Approve&itemid='. $item['item_ID'] ."\"".' 
                                                class="btn btn-info pull-right activate">
                                                <i class="fa fa-check"></i> 
                                                Activate
                                            </a>';
                                    }
                                    echo '</span>';
                                    echo "</a>";
                                    echo '</li>';
                                }
                            }else{
                                echo "<div class='alert alert-info'> There's No Items To Show </div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Latest Comment  -->
        <div class="row">
            <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments-o"></i> Latest <?php echo $numComments ?> Comments
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                                $stmt = $db->prepare("SELECT 
                                                            comments.*, users.Username
                                                    FROM 
                                                    comments
                                                    INNER JOIN
                                                            users
                                                    ON
                                                            users.UserID = comments.user_ID
                                                    ORDER BY 
                                                            c_ID DESC
                                                            LIMIT $numComments");
                                $stmt->execute();
                                $comments = $stmt->fetchAll();
                                if(!(empty($comments))){

                                    foreach($comments as $comment){
                                        echo '<div class="comment-box">';
                                        echo "<span class='member-n'>" . $comment['Username'] . '</span>';
                                        echo "<p class='member-c'>" . $comment['comment'] . '</p>';
                                        echo '</div>';
                                    }
                                }else{
                                    echo "<div class='alert alert-info'> There's No Comments To Show</div>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- End Latest Comment  -->
            
    
            <?php
    /*  End Dashboard Page  */

    include $tpl.'footer.php';
}else{
    header("location: index.php");
    exit();
}
ob_end_flush();
?>