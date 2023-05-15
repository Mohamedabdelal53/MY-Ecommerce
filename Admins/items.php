<?php
/*
==========================
======  Items Page =======
==========================
*/

ob_start();
session_start();
$pageTitle = 'Items';
if(isset($_SESSION['Username'])){
    include 'init.php';
    $do=isset($_GET['do'])? $_GET['do']: 'Manage';
    //if page is main page
    if($do=='Manage'){

        $stmt = $db->prepare("SELECT 
                                    items.*, 
                                    categories.Name 
                                AS 
                                    Category_Name ,
                                    users.Username 
                                AS 
                                    Member_name 
                                From 
                                    items
                                INNER JOIN 
                                    categories 
                                ON
                                    categories.ID = items.Cat_ID
                                INNER JOIN 
                                    users 
                                ON
                                    users.UserID = items.Member_ID
                                    ORDER BY item_ID DESC");
        $stmt->execute();
        $items = $stmt->fetchAll();
        if(!(empty($items))){
        
    ?>

    <h1 class="text-center">Manage Items</h1>
    <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                    <td>#ID</td>
                    <td>Item Name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Add_Date</td>
                    <td>Category</td>
                    <td>Username</td>
                    <td>Control Items</td>
                </tr>
                <?php
                

                    foreach($items as $item){
                        echo "<tr>";
                            echo "<td>" .$item['item_ID']. '</td>';
                            echo "<td>" .$item['Name']. '</td>';
                            echo "<td>" .$item['Description']. '</td>';
                            echo "<td>$" .$item['Price']. '</td>';
                            echo "<td>" .$item['Add_Date']. '</td>';
                            echo "<td>" .$item['Category_Name']. '</td>';
                            echo "<td>" .$item['Member_name']. '</td>';
                            echo "<td>";
                                echo '<a href="?do=Edit&item_ID='.$item['item_ID'].'" class="btn btn-success"><i class="fa fa-edit"></i>Edit</a> ';
                                echo '<a href="?do=Delete&item_ID='.$item['item_ID'].'" class="btn btn-danger confirm"><i class="fa fa-close"></i>Delete</a> ';
                                if($item['Approve'] == 0){
                                    echo '<a 
                                            href="?do=Approve&itemid='. $item['item_ID'] ."\"".' 
                                            class="btn btn-info activate">
                                            <i class="fa fa-check"></i> 
                                            Activate
                                        </a>';
                                }
                            echo "</td>";
                        echo "</tr>";
                    }
                }else{
                    echo "<h1 class='container text-center alert alert-info'> NO ITEMS <h1>";
                }
                ?>
    </table>
</div>
<?php
    echo '<div class="container"><a href="?do=Add" class=" btn btn-primary"><i class="fa fa-plus"></i> New Item</a></div>';
    }elseif($do=='Add'){?>
        <h1 class="text-center">ADD New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                type="text"
                                required="required"
                                name="name"
                                class="form-control"
                                placeholder="Name Of The Item">
                            </input>
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text"
                                name="description"
                                required="required"
                                class="form-control"
                                placeholder="Description The Item">
                            </input>
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text"
                                name="price"
                                required="required"
                                class="form-control"
                                placeholder="Price Of The Item">
                            </input>
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Counrtry Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Counrtry</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text"
                                name="country"
                                class="form-control"
                                required="required"
                                placeholder="Country Of Made">
                            </input>
                        </div>
                    </div>
                    <!-- End Country Field -->
                    <!-- Start Status Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-4">
                            <select class="form-control" name="status">
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                    <!-- Start Member Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-4">
                            <select class="form-control" name="member">
                                <option value="0">...</option>
                                <?php
                                $users = getAll("*",'users',NULL,NULL,"UserID",'DESC');
                                foreach($users as $user){
                                    echo "<option value='".$user["UserID"]."'>".$user['Username']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Member Field -->
                    <!-- Start Categories Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-4">
                            <select class="form-control" name="category">
                                <option value="0">...</option>
                                <?php
                                $cats = getAll("*","categories","WHERE Parent = 0",NULL,"ID",'DESC');
                                foreach($cats as $cat){
                                    echo "<option value=' ".$cat['ID']."'>".$cat['Name']."</option>";
                                    $child_cats = getAll("*","categories","WHERE Parent = ".$cat['ID']."",NULL,"ID",'DESC');
                                    foreach($child_cats as $cc){
                                        echo "<option value=' ".$cc['ID']."'>---".$cc['Name']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories Field -->
                    <!-- Start Tags Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text"
                                name="tags"
                                class="form-control"
                                placeholder="Separate Tags By Comma (,)">
                            </input>
                        </div>
                    </div>
                    <!-- End Tags Field -->
                    <!-- Add Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input 
                                type="submit" 
                                value="Add Item" 
                                class="btn btn-primary btn-md">
                            </input>
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
            </div>
            <?php
    }elseif($do=='Insert'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            echo "<h1 class='text-center'>Insert Page</h1>";
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $category = $_POST['category'];
            $tags = $_POST['tags'];

            $formerror = array();
            if(empty($name)){
                $formerror[]='Name Can\'t be <strong>Empty</strong>';
            }
            if(empty($price)){
                $formerror[]='Price Can\'t be <strong>Empty</strong>';
            }
            if($status == 0){
                $formerror[]='You Must Choose The <strong>Status</strong>';
            }
            if($member == 0){
                $formerror[]='You Must Choose The <strong>Member</strong>';
            }
            if($category == 0){
                $formerror[]='You Must Choose The <strong>Category</strong>';
            }
            foreach($formerror as $error){
                echo "<div class='container alert alert-danger'> $error</div>";
            }


            if(empty($formerror)){
                $stmt = $db->prepare("INSERT INTO 
                `items` (`Name`, `Description`, `Price`, `Country_Made`, `Status`, `Add_Date`, `Member_ID`, `Cat_ID`, `Tags`) 
                VALUES (:zname, :zdesc, :zprice, :zcountry, :zstat, now(), :zmember, :zcategory, :ztags)");
                $stmt->execute(array(
                    "zname"=>$name,
                    "zdesc"=>$description,
                    "zprice"=>$price,
                    "zcountry"=>$country,
                    "zstat"=>$status,
                    ":zcategory"=>$category,
                    ":zmember"=>$member,
                    ":ztags"=>$tags
                ));
                $count = $stmt->rowCount();
                if($count>0){
                    $theMsg ="<div class='container alert alert-success'>$count Record Inserted</div>";
                    redirectHome($theMsg,'back');
                }
            }else{
                redirectHome('','back');
            }

        }else{
            // Error Message
            $theMsg = "<div class = 'alert alert-danger' >You Cant Open This Page Directly </div>";
            $seconds = 2;

            echo "<div class='container'>";
                redirectHome($theMsg, 'items.php');
            echo "</div>";
        }

    }elseif($do=='Edit'){
        // Check If Get Request Userid Is Numeric

        $itemID = isset($_GET['item_ID']) && is_numeric($_GET['item_ID']) ? intval($_GET['item_ID']): 0;
        
        // Select All Data Depend On This ID
        
        $stmt=$db->prepare("SELECT * FROM items WHERE  item_ID=?");
        
        // Excute Query
        
        $stmt->execute(array($itemID));
        
        //Fetch The Data
        
        $item = $stmt->fetch();
        
        // Row Count
        
        $count = $stmt->rowcount();
        
        // If There Is ID Show The Form
        
        if($count>0){?>
            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="item_ID" value="<?php echo $itemID ?>" />
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                type="text"
                                required="required"
                                name="name"
                                class="form-control"
                                placeholder="Name Of The Item"
                                value="<?php echo $item['Name'] ?>">
                            </input>
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text"
                                name="description"
                                required="required"
                                class="form-control"
                                placeholder="Description The Item"
                                value="<?php echo $item['Description'] ?>">
                            </input>
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text"
                                name="price"
                                required="required"
                                class="form-control"
                                placeholder="Price Of The Item"
                                value="<?php echo $item['Price'] ?>">
                            </input>
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Counrtry Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Counrtry</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text"
                                name="country"
                                class="form-control"
                                required="required"
                                placeholder="Country Of Made"
                                value="<?php echo $item['Country_Made'] ?>">
                            </input>
                        </div>
                    </div>
                    <!-- End Country Field -->
                    <!-- Start Status Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-4">
                            <select class="form-control" name="status">
                                <option value="1" <?php if($item["Status"]==1){ echo 'selected';} ?>>New</option>
                                <option value="2" <?php if($item["Status"]==2){ echo 'selected';} ?>>Like New</option>
                                <option value="3" <?php if($item["Status"]==3){ echo 'selected';} ?>>Used</option>
                                <option value="4" <?php if($item["Status"]==4){ echo 'selected';} ?>>Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                    <!-- Start Member Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-4">
                            <select class="form-control" name="member">
                                <?php
                                $users = getAll("*","users",NULL,NULL,"UserID");
                                foreach($users as $user){
                                    echo "<option value='".$user["UserID"]."'";
                                    if($item["Member_ID"]==$user["UserID"]){ echo 'selected';}
                                    echo ">".$user['Username']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Member Field -->
                    <!-- Start Categories Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-4">
                            <select class="form-control" name="category">
                                <?php
                                $cats = getAll("*","categories","WHERE Parent = 0",NULL,'ID');
                                foreach($cats as $cat){
                                    echo "<option value=' ".$cat['ID']."'";
                                    if($item["Cat_ID"]==$cat["ID"]){ echo 'selected';}
                                    echo">".$cat['Name']."</option>";
                                    $child_cats = getAll("*","categories","WHERE Parent = ".$cat['ID']."",NULL,"ID",'DESC');
                                    foreach($child_cats as $cc){
                                        echo "<option value=' ".$cc['ID']."'>---".$cc['Name']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories Field -->
                    <!-- Start Tags Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text"
                                name="tags"
                                class="form-control"
                                placeholder="Separate Tags By Comma (,)"
                                value="<?php echo $item['Tags'] ?>">
                            </input>
                        </div>
                    </div>
                    <!-- End Tags Field -->
                    <!-- Add Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input 
                                type="submit" 
                                value="Save Item" 
                                class="btn btn-primary btn-md">
                            </input>
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
                <?php
                $stmt = $db->prepare("SELECT 
                                        comments.*, users.Username
                                    FROM 
                                        comments
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = comments.user_ID
                                    WHERE
                                        item_ID = ?");
                // Execute The Statement
                $stmt->execute(array($itemID));
                //Assign to variable
                $rows = $stmt->fetchAll();
                                    
                if(!(empty($rows))){

                    ?>
            <h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>Comment</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>" .$row['comment']. '</td>';
                                    echo "<td>" .$row['Username']. '</td>';
                                    echo "<td>" .$row['comment_date']. '</td>';
                                    echo "<td>";
                                        echo '<a href="comments.php?do=Edit&comid='. $row['c_ID'] ."\"".' class="btn btn-success"><i class="fa fa-edit"></i> Edit</a> ';
                                        echo '<a href="comments.php?do=Delete&comid='. $row['c_ID'] ."\"".' class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a> ';
                                        if($row['status'] == 0){
                                            echo '<a 
                                                    href="comments.php?do=Activate&comid='. $row['c_ID'] ."\"".' 
                                                    class="btn btn-info activate">
                                                    <i class="fa fa-check"></i> 
                                                    Approve
                                                </a>';
                                        }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
            </div>
            <?php } // If There's No Such ID Show Error Message
        }
        else{
            $theMsg='<div class="container alert alert-danger">There\'s No Such ID</div>';
            redirectHome($theMsg, 'items.php');
        }
    }elseif($do=='Update'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            
            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";
            //Get Variable From Form

            $item_id = $_POST['item_ID'];
            $Name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $category = $_POST['category'];
            $tags = $_POST['tags'];
            
            $formerror = array();

            if(empty($Name)){
                $formerror[]='Name Can\'t be <strong>Empty</strong>';
            }
            if(empty($description)){
                $formerror[]='Description Can\'t Be Empty';
            }
            if(empty($price)){
                $formerror[]='Price Can\'t be <strong>Empty</strong>';
            }
            if($status == 0){
                $formerror[]='You Must Choose The <strong>Status</strong>';
            }
            if($member == 0){
                $formerror[]='You Must Choose The <strong>Member</strong>';
            }
            if($category == 0){
                $formerror[]='You Must Choose The <strong>Category</strong>';
            }

            // Loop Into Errors And Echo It
            
            foreach($formerror as $error){
                $theMsg = '<div class="alert alert-danger">'.$error.'</div> ';
                redirectHome($theMsg, 'Back', 3);
            }
            
            //Check If There Is No Error To Update The Database
            
            if(empty($formerror)){
                $stmt = $db->prepare("  UPDATE 
                                            items 
                                        SET 
                                            Name = ?, 
                                            Description = ?, 
                                            Price = ?, 
                                            Country_Made = ?, 
                                            Status=?, 
                                            Cat_ID= ?, 
                                            Member_ID = ?,
                                            Tags = ?
                                        WHERE 
                                            item_ID = ?");
                $stmt->execute(array($Name, $description, $price, $country, $status, $category, $member, $tags, $item_id));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . ' Record Updated</div>';
                redirectHome($theMsg, "items.php");
            }
        }else{
            $theMsg = "<div class='alert alert-danger'>You Cant Open This Page Directly</div>";
            redirectHome($theMsg, null);
        }
        echo "</div>";
    }elseif($do=='Delete'){
        // Delete Item Pages
        
        echo "<h1 class='text-center'>Delete Item</h1>";
        echo "<div class='container'>";
            // Check If Get Request Userid Is Numeric

            $item_id = isset($_GET['item_ID']) && is_numeric($_GET['item_ID']) ? intval($_GET['item_ID']): 0;

            // Select All Data Depend On This ID

            $check = checkItem('item_ID', 'items', $item_id);

            // If There Is ID Show The Form

            if($check>0){
                $stmt = $db->prepare("DELETE FROM items WHERE item_ID = :zid");
                $stmt->bindParam(":zid", $item_id);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . ' Record Deleted</div>';
                redirectHome($theMsg, "items.php");
            }else{
                $theMsg = '<div class="text-center alert alert-danger">This Id Is Not Exist</div>';
                redirectHome($theMsg, "items.php");
            }
            echo '</div>';


            
        }elseif($do=='Approve'){
            // Activate Pending Members
            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";
            // Check If Get Request ItemID Is Numeric

            $item_ID = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']): 0;

            // Select All Data Depend On This ID

            $check = checkItem('item_ID', 'items', $item_ID);

            // If There Is ID Show The Form

            if($check>0){
                $stmt = $db->prepare("UPDATE 
                                            items 
                                        SET 
                                            Approve = 1 
                                        WHERE 
                                            item_ID = ?");
                $stmt->execute(array($item_ID));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . ' Record Approved</div>';
                redirectHome($theMsg, "items.php");
            }else{
                $theMsg = '<div class="text-center alert alert-danger">This Id Is Not Exist</div>';
                redirectHome($theMsg, "items.php");
            }
            echo '</div>';    
    }
}
    else{
    header("Location : index.php");
    exit();
}
include $tpl.'footer.php';
ob_end_flush();