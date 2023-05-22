<?php 
ob_start();
session_start();
$pageTitle = 'Show Item';
include 'init.php';
if(isset($_GET['item_ID'])){
    // Check If Get Request Userid Is Numeric
        
    $itemID = isset($_GET['item_ID']) && is_numeric($_GET['item_ID']) ? intval($_GET['item_ID']): 0;

    // Select All Data Depend On This ID

    $stmt=$db->prepare("SELECT 
                            items.*,
                            categories.Name AS category_name,
                            users.Username AS Member_name
                        FROM
                            items
                        INNER JOIN
                            categories
                        ON
                            categories.ID = items.Cat_ID
                        INNER JOIN
                            users
                        ON
                            users.UserID = items.Member_ID
                        WHERE
                            item_ID=?
                        AND
                            Approve = 1");

    // Excute Query

    $stmt->execute(array($itemID));

    
    // Row Count
    
    $count = $stmt->rowcount();
    
    // If There Is ID Show The Form
    if($count>0){
        //Fetch The Data
        
        $item = $stmt->fetch();
        ?>
        <h2 class="container text-center alert alert-info">Item Details</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                <img class='img-responsive img-thumbnail center-block' src='Uploads\Avatar\<?php echo $item['item_IMG']; ?>' alt='' />
                </div>
                <div class="col-md-9 item-info">
                    <h2><?php echo $item['Name']; ?></h2>
                    <p><?php echo $item['Description']; ?></p>
                    <ul class='list-unstyled'>
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Added Date: </span><?php echo $item['Add_Date']; ?>
                        </li>
                        <li>
                            <i class="fa fa-money fa-fw"></i>
                            <span>Price:   </span>$<?php echo $item['Price']; ?>
                        </li>
                        <li>
                            <i class="fa fa-building fa-fw"></i>
                            <span>Made In: </span><?php echo $item['Country_Made']; ?>
                        </li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Category </span>: <a href="categories.php?pageid=<?php echo $item['Cat_ID'];?>"><?php echo $item['category_name']; ?></a>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>Added BY </span>: <a href="#"> <?php echo $item['Member_name']; ?></a>
                        </li>
                        <li class='tag-item'>
                            <i class="fa fa-user fa-fw"></i>
                            <span>Tags </span>:
                            <?php
                            $alltags = explode(",", $item['Tags']);
                            foreach($alltags as $tag){
                                $tag = str_replace(' ','',$tag);
                                $lowertag = strtolower($tag);
                                if(!empty($tag)){
                                    echo "<a href='tags.php?name={$lowertag}'> ".$tag."</a> |";
                                }
                            }
                            ?>
                        </li>
                </ul>
                </div>
            </div>
            <hr class='custom-hr'>
            <!-- Start Add Comment -->
            <?php if(isset($_SESSION['user'])){

            ?>
            <div class="row">
                <div class="col-md-offset-3">
                    <div class="add-comment col-sm-10 col-md-4">
                        <h3>Add Your Comment</h3>
                        <form action='<?php echo $_SERVER['PHP_SELF'].'?item_ID='.$item['item_ID'].''?>' method='POST'>
                            <input name='comment' class='form-control' required></input>
                            <input class='btn btn-primary Add-Comment' type="submit" value="Add Comment" />
                        </form>
                        <?php
                        if($_SERVER['REQUEST_METHOD']=='POST'){
                            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                            $user    = $_SESSION['UserID'];
                            $itemid  = $item['item_ID'];
                            if(!empty($comment)){
                                $stmt = $db->prepare("INSERT INTO comments (`comment`, `status`, `comment_date`, `item_ID`,`user_ID`)Values(:zcomment, 0, now(), :zitemid, :zuserid)");
                                $stmt->execute(array(
                                    'zcomment'=>$comment,
                                    'zitemid'=>$itemid,
                                    'zuserid'=>$user
                                ));
                                $count = $stmt->rowCount();
                                if($count>0){
                                    echo "<div class='text-center container alert alert-success'>Comment Added</div>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            }else{
                echo '<div class="container text-center alert alert-info"><a href="login.php?open=login">Login Or Register To Add Comment</a></div>';
            }
            ?>
            <!-- End Add Comment -->
            <hr class='custom-hr'>
            <?php
                $stmt = $db->prepare("SELECT 
                                comments.*, users.Username, users.avatar
                            FROM 
                                comments
                            INNER JOIN
                                users
                            ON
                                users.UserID = comments.user_ID
                            WHERE
                                item_ID = ?
                            AND
                                status = 1
                                ORDER BY c_ID DESC");
        // Execute The Statement
        $stmt->execute(array($itemID));
        //Assign to variable
        $comments = $stmt->fetchAll();
        ?>
                <?php
                foreach($comments as $comment){
                    ?>
                    <div class="comment-box">
                        <div class="row">
                            <div class="col-md-2 text-center">
                            <img class='img-responsive img-thimbnail img-circle center-block' src='Admins\Uploads\Avatar\<?php if(strlen($user_avatar['avatar'])>0){
                                echo $user_avatar['avatar'];
                                }else{
                                    echo 'defult.jpeg';
                                } ?>' alt='' />
                            <?php echo $comment['Username']; ?>
                        </div>
                        <div class="col-md-10">
                            <p class="lead">
                            <?php echo $comment['comment']; ?>
                            </p>
                        </div>
                        </div>
                    </div>
                    <hr class='custom-hr'>
                <?php
                }
                ?>
<?php
    }else{
        $errormsg = "<div class='container text-center alert alert-danger'>There's No Such ID Or This Item Is Waiting Approve</div>";
        redirectHome($errormsg, 'index.php');
    }
?>
    <?php
    }else{
        header('location: index.php');
    }
include $tpl.'footer.php';
ob_end_flush();
?>