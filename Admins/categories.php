<?php

/*
===================================================
==  Category Page                                ==
===================================================
*/


ob_start();
session_start();
$pageTitle = 'Categories'; // => Page Title
if(isset($_SESSION['Username'])){ 
    include 'init.php';
    $do = isset($_GET['do'])? $_GET['do'] : 'Manage';
    if($do == 'Manage'){
        $sort = "ASC"; // The Method Of Arrange
        $sort_array = array('ASC','DESC');
        if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
            $sort = $_GET['sort'];
        }
        $stmt = $db->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort");
        $stmt->execute();
        $cats = $stmt->fetchAll();
        if(!(empty($cats))){

            ?>
        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-edit"></i> Manage Categories
                    <div class="option pull-right">
                        <i class="fa fa-sort"></i> Ordering:[
                            <a class="<?php if($sort=='ASC'){echo 'Active';} ?>" href="?sort=ASC">ASC</a> |
                            <a class="<?php if($sort=='DESC'){echo 'Active';} ?>" href="?sort=DESC">DESC</a>]
                            <i class="fa fa-eye"></i> View:[
                                <span class="Active" data-view="full">Full</span>
                        <span data-view="classic">Classic</span>]
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    foreach($cats as $cat){
                        echo "<div class='cat'>";
                        echo "<div class='hidden-button'>";
                        echo "<a href='?do=Edit&catid=".$cat['ID']." 'class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>";
                        echo "<a href='?do=Delete&catid=".$cat['ID']." ' class='btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>";
                            echo "</div>";
                            echo '<h3> Name: ' .$cat['Name'] . '</h3>';
                            echo '<div class="full-view">';
                            echo"<p>";if($cat['Description']==''){echo 'This Category Has No Description<br>';}else{echo $cat['Description'] . "<br>";} echo"</p>";
                            if($cat['Visibility']==1){echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>';}
                            if($cat['Allow_Comment']==1){ echo '<span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>';}
                            if($cat['Allow_Ads']){echo '<span class="advertises"><i class="fa fa-close"></i> Ads Disabled</span>';}
                            echo '</div>';
                            // Get Child Cats
                            $ChildCats = getAll('*','categories',"WHERE Parent ={$cat['ID']}",NULL,'ID','ASC');
                            if(!(empty($ChildCats))){
                                echo '<h4 class="child-head">Child Categories</h4>';
                                echo "<ul class='list-unstyled child-cats'>";
                                foreach($ChildCats as $c){
                                    echo '<li class="child-link">
                                            <a href="?do=Edit&catid='.$c['ID'].'">'.$c['Name'].'</a>
                                            <a href="?do=Delete&catid='.$c['ID'].'"class="show-delete confirm"">Delete</a>
                                            </li>';
                                }
                                echo '</ul>';
                            }
                            echo "</div>";
                            echo '<hr>';
                        }
                    }else{
                        echo '<h1 class="container text-center alert alert-info"> NO CATEGORIES TO SHOW </h1>';
                    }
                        ?>
                </div>
            </div>
            <?php 
            echo '<div class="container"><a class="btn btn-primary" href="?do=Add"><i class="fa fa-plus"></i>ADD New Category</a></div>';?>
        </div>

        <?php


    }elseif($do=='Add'){//Add Categories page?>
        <h1 class="text-center">ADD New Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control"  
                                autocomplete="off" 
                                required="required"  
                                placeholder="Name Of The Category">
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
                                class="form-control"  
                                placeholder="Description The Category">
                            </input>
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Ordering Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-4">
                            <input 
                                type="text" 
                                name="ordering" 
                                class="form-control" 
                                placeholder="Number To Arrange The Categories">
                            </input>
                        </div>
                    </div>
                    <!-- End Ordering Field -->
                    <!-- Start Categorey type Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent ?</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
                                    $allcats = getAll('*','categories', 'Where Parent = 0',NULL, 'ID');
                                    foreach($allcats as $cat){
                                        echo '<option value="'.$cat['ID'].'">'.$cat['Name'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categorey type Field -->
                    <!-- Start Visibility Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Visible</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="vis-yes"type="radio" name="visibility" value="0" checked />
                                <label for="vis-yes">Yes</label>
                                <input id="vis-no"type="radio" name="visibility" value="1" />
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Visibility Field -->
                    <!-- Start Commenting Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="com-yes"type="radio" name="commenting" value="0" checked />
                                <label for="com-yes">Yes</label>
                                <input id="com-no"type="radio" name="commenting" value="1" />
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Commenting Field -->
                    <!-- Start Ads Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="ads-yes"type="radio" name="ads" value="0" checked />
                                <label for="ads-yes">Yes</label>
                                <input id="ads-no"type="radio" name="ads" value="1" />
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn btn-primary btn-lg"></input>
                        </div>
                    </div>
                </form>
            </div>


<?php }elseif($do == 'Insert'){
        // Insert Categories 
        if($_SERVER['REQUEST_METHOD']=='POST'){            
            echo "<h1 class='text-center'>Insert Categories</h1>";
            echo "<div class='container'>";
            //Get Variable From Form

            $name = $_POST['name'];
            $description = $_POST['description'];
            $ordering = $_POST['ordering'];
            $parent = $_POST['parent'];
            $visibility = $_POST['visibility'];
            $commenting=$_POST['commenting'];
            $ads=$_POST['ads'];
            
            // Check if there is no error
            if(!(empty($name))){
                // Check Category
                $value = $name;
                $check = checkItem("Name", "categories", $value);
                if($check == 1){
                    $theMsg = '<div class="alert alert-danger">Sorry This Category Is Exist</div>';
                    redirectHome($theMsg, 'Back');
                }
                // Insert Category To Database
                else{

                    $stmt = $db->prepare("INSERT INTO `categories` (`Name`, `Description`, `Parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES (:zname, :zdescription, :zparent, :zordering, :zvisibility, :zcomment, :zads)");
                    $stmt->execute(array(
                    'zname' => $name,
                    'zdescription' => $description,
                    'zordering' => $ordering,
                    'zparent' => $parent,
                    'zvisibility' => $visibility,
                    'zcomment' => $commenting,
                    'zads' => $ads,
                
                    ));
                    // Success Message
                    $theMsg = '<div class="alert alert-success">'.$stmt->rowcount() .' Record Inserted</div>';
                    redirectHome($theMsg, 'categories.php');// redirect the page to referer page 
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
        }
    elseif($do == 'Edit'){
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']): 0;
        
        // Select All Data Depend On This ID
        
        $stmt=$db->prepare("SELECT * FROM categories WHERE  ID=? ");
        
        // Excute Query
        
        $stmt->execute(array($catid));
        
        //Fetch The Data
        
        $cat = $stmt->fetch();
        
        // Row Count
        
        $count = $stmt->rowcount();
        
        // If There Is ID Show The Form
        
        if($count>0){?>
            <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update&ID=<?php echo $cat['ID'] ?>" method="POST">
                        <input type="hidden" name="catid" value="<?php echo $catid; ?>"/>
                        <!-- Start Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input 
                                    type="text" 
                                    name="name" 
                                    class="form-control"  
                                    value="<?php echo $cat['Name']; ?>" 
                                    required="required" 
                                    placeholder="Name Of The Category">
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
                                    class="form-control"
                                    value="<?php echo $cat['Description']; ?>" 
                                    placeholder="Description The Category">
                                </input>
                            </div>
                        </div>
                        <!-- End Description Field -->
                        <!-- Start Ordering Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-md-4">
                                <input 
                                    type="text" 
                                    name="ordering" 
                                    class="form-control" 
                                    value = "<?php echo $cat['Ordering']; ?>"
                                    placeholder="Number To Arrange The Categories">
                                </input>
                            </div>
                        </div>
                        <!-- End Ordering Field -->
                        <!-- Start Categorey type Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Parent ?</label>
                            <div class="col-sm-10 col-md-4">
                                <select name="parent">
                                    <option value="0">None</option>
                                    <?php
                                        $allcats = getAll('*','categories', 'Where Parent = 0',NULL, 'ID');
                                        foreach($allcats as $maincat){
                                            echo '<option value="'.$maincat['ID'].'"';
                                            if($maincat['ID']==$cat['Parent']){ echo "selected"; }
                                            echo ">".$maincat['Name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Categorey type Field -->
                        <!-- Start Visibility Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Visible</label>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="vis-yes"type="radio" name="visibility" value="0"  <?php if($cat['Visibility']==0){echo 'Checked';} ?>/>
                                    <label for="vis-yes">Yes</label>
                                    <input id="vis-no"type="radio" name="visibility" value="1" <?php if($cat['Visibility']==1){echo 'Checked';}?> />
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Visibility Field -->
                        <!-- Start Commenting Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Commenting</label>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="com-yes"type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment']==0){echo 'Checked';} ?> />
                                    <label for="com-yes">Yes</label>
                                    <input id="com-no"type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment']==1){echo 'Checked';} ?>/>
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Commenting Field -->
                        <!-- Start Ads Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Ads</label>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="ads-yes"type="radio" name="ads" value="0" <?php if($cat['Allow_Ads']==0){echo 'Checked';} ?>/>
                                    <label for="ads-yes">Yes</label>
                                    <input id="ads-no"type="radio" name="ads" value="1" <?php if($cat['Allow_Ads']==1){echo 'Checked';} ?>/>
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Ads Field -->
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save" class="btn btn-primary btn-lg"></input>
                            </div>
                        </div>
                    </form>
                </div>

<?php } // If There's No Such ID Show Error Message
        else{
            $theMsg='<div class="alert alert-danger">There\'s No Such ID</div>';
            redirectHome($theMsg, 'back');
        }
    }elseif($do == 'Update'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo "<h1 class='text-center'>Update Categories</h1>";
            echo "<div class='container'>";
            //Get Variable From Form

            $id = $_POST['catid'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $Ordering = $_POST['ordering'];
            $Visibility = $_POST['visibility'];
            $commenting = $_POST['commenting'];
            $Ads = $_POST['ads'];
            $parent = $_POST['parent'];
            
            $formErrors=array();
            
            if(strlen($name)<4){
                $formErrors[]='Name Can\'t Be Less Than <strong>4</strong> Character';
            }
            if(strlen($name)>20){
                $formErrors[]='Name Can\'t Be Greater Than <strong>20</strong> Character';
            }
            if(empty($name)){
                $formErrors[]='Name Can\'t be <strong>empty</strong>';
            }
            
            // Loop Into Errors And Echo It
            
            foreach($formErrors as $error){
                $theMsg = '<div class="alert alert-danger">'.$error.'</div> ';
                redirectHome($theMsg, 'Back', 3);
                
            }
            
            //Check If There Is No Error To Update The Database
            if(empty($formErrors)){
                $stmt = $db->prepare("UPDATE categories SET Name = ?, Parent = ?, Description = ?, Ordering = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ? WHERE ID = $id");
                $stmt->execute(array($name, $parent, $description, $Ordering, $Visibility, $commenting, $Ads));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . ' Record Updated</div>';
                redirectHome($theMsg, "categories.php");
            }
            }else{
                $theMsg = '<div class="alert alert-danger">You Cant Open This Page Directly</div>';
                redirectHome($theMsg);
            }
        }
    elseif($do=='Delete'){
        echo "<h1 class='text-center'>Delete Category </h1>";
        $id = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']): 0;
        $check = checkItem("ID",'categories',$id);
        if($check>0){
            $stmt=$db->prepare("DELETE FROM categories WHERE ID = ?");
            $stmt->execute(array($id));
            $count=$stmt->rowCount();
            if($count>0){
                $theMsg = "<div class=' container alert alert-success'>" . $stmt->rowcount() . ' Record Deleted</div>';
                redirectHome($theMsg, "categories.php");
            }
        }
    }
}
    else{
        header("Location : index.php");
        exit();
    }
include  $tpl.'footer.php';
ob_end_flush();
?>