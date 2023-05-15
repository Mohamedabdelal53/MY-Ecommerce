<?php

/*
===================================================
== Manage Members Page                           ==
== You Can Edit | Add | Delete Members From Here ==
===================================================
*/

session_start();
$pageTitle='Comments';
if(isset($_SESSION['Username'])){
    include 'init.php';
    $do = isset($_GET['do'])? $_GET['do'] : 'Manage';
    
    // start manage page
    
    if($do == 'Manage'){// Manage comment pages 
        
        $stmt = $db->prepare("SELECT 
                                comments.*, items.Name AS item_Name, users.Username
                            FROM 
                                comments
                            INNER JOIN
                                items
                            ON
                                items.item_ID = comments.item_ID
                            INNER JOIN
                                users
                            ON
                                users.UserID = comments.user_ID
                                ORDER BY c_ID DESC");
        // Execute The Statement
        $stmt->execute();
        //Assign to variable
        $comments = $stmt->fetchAll();
        if(!(empty($comments))){

            
            ?>

        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                        foreach($comments as $comment){
                            echo "<tr>";
                                echo "<td>" .$comment['c_ID']. '</td>';
                                echo "<td>" .$comment['comment']. '</td>';
                                echo "<td>" .$comment['item_Name']. '</td>';
                                echo "<td>" .$comment['Username']. '</td>';
                                echo "<td>" .$comment['comment_date']. '</td>';
                                echo "<td>";
                                echo '<a href="comments.php?do=Edit&comid='. $comment['c_ID'] ."\"".' class="btn btn-success"><i class="fa fa-edit"></i> Edit</a> ';
                                    echo '<a href="comments.php?do=Delete&comid='. $comment['c_ID'] ."\"".' class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a> ';
                                    if($comment['status'] == 0){
                                        echo '<a 
                                                href="comments.php?do=Activate&comid='. $comment['c_ID'] ."\"".' 
                                                class="btn btn-info activate">
                                                <i class="fa fa-check"></i> 
                                                Approve
                                                </a>';
                                            }
                                            echo "</td>";
                                            echo "</tr>";
                        }
                    }else{
                        echo "<h1 class='container text-center alert alert-info'>NO COMMENTS TO SHOW </h1>";
                    }
                    ?>
        </table>
            </div>
        </div>
        <?php
    }
    elseif($do == 'Edit'){
        // Check If Get Request Userid Is Numeric

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']): 0;
        
        // Select All Data Depend On This ID
        
        $stmt=$db->prepare("SELECT 
                                *
                            FROM 
                                comments
                            WHERE
                                c_ID = ?");
        
        // Excute Query
        
        $stmt->execute(array($comid));
        
        //Fetch The Data
        
        $row = $stmt->fetch();
        
        // Row Count
        
        $count = $stmt->rowcount();
        
        // If There Is ID Show The Form
        
        if($count>0){?>
            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid ?>" />
                    <!-- Start Comment Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-4">
                            <textarea class="form-control"
                                name="comment"
                                ><?php echo $row['comment']; ?>
                            </textarea>
                        </div>
                    </div>
                    <!-- End Comment Field -->
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
            $theMsg='<div class="container alert alert-danger">There\'s No Such ID</div>';
            redirectHome($theMsg, 'members.php');
        }
    }elseif($do == 'Update'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            
            echo "<h1 class='text-center'>Update comment</h1>";
            echo "<div class='container'>";
            //Get Variable From Form

            $id = $_POST['comid'];
            $comment = $_POST['comment'];
            
            $stmt = $db->prepare("UPDATE 
                                        comments 
                                    SET 
                                        comment = ? 
                                    WHERE 
                                        c_ID = ?");
            $stmt->execute(array($comment,$id));
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . ' Record Updated</div>';
            redirectHome($theMsg, "comments.php");
        }else{
            $theMsg = "<div class='alert alert-danger'>You Cant Open This Page Directly</div>";
            redirectHome($theMsg, null);
        }
        echo "</div>";
    }elseif($do == 'Delete'){
        // Delete Member Pages
        
        echo "<h1 class='text-center'>Delete Comment</h1>";
        echo "<div class='container'>";
            // Check If Get Request Userid Is Numeric

            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']): 0;

            // Select All Data Depend On This ID

            $check = checkItem('c_ID', 'comments', $comid);

            // If There Is ID Show The Form

            if($check>0){
                $stmt = $db->prepare("DELETE FROM comments WHERE c_ID = :zid");
                $stmt->bindParam(":zid", $comid);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . ' Record Deleted</div>';
                redirectHome($theMsg, "comments.php");
            }else{
                $theMsg = '<div class="text-center alert alert-danger">This Id Is Not Exist</div>';
                redirectHome($theMsg, "comments.php");
            }
            echo '</div>';
    }elseif($do == 'Activate'){
        // Activate Pending Members
        echo "<h1 class='text-center'>Approve Comments</h1>";
        echo "<div class='container'>";
            // Check If Get Request Userid Is Numeric

            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']): 0;

            // Select All Data Depend On This ID

            $check = checkItem('c_ID', 'comments', $comid);

            // If There Is ID Show The Form

            if($check>0){
                $stmt = $db->prepare("UPDATE comments SET status = 1 WHERE c_ID = ?");
                $stmt->execute(array($comid));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . ' Record Activated</div>';
                redirectHome($theMsg, "comments.php");
            }else{
                $theMsg = '<div class="text-center alert alert-danger">This Id Is Not Exist</div>';
                redirectHome($theMsg, "comments.php");
            }
            echo '</div>';        
    }
    include $tpl.'footer.php';
}else{
    header("location: index.php");
    exit();
}