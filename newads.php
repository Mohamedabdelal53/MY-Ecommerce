<?php 
ob_start();
session_start();
$pageTitle = 'Create New Ad';
include 'init.php';
if(isset($_SESSION['user'])){
        ?>

<h1 class="text-center">Create New Ad</h1>
<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Create New Ad</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                        <!-- Start Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-10 col-md-9">
                                <input
                                    type="text"
                                    required="required"
                                    name="name"
                                    class="form-control live-name"
                                    placeholder="Name Of The Item">
                                </input>
                            </div>
                        </div>
                        <!-- End Name Field -->
                        <!-- Start Description Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-10 col-md-9">
                                <input
                                    type="text"
                                    name="description"
                                    required="required"
                                    class="form-control live-desc"
                                    placeholder="Description The Item">
                                </input>
                            </div>
                        </div>
                        <!-- End Description Field -->
                        <!-- Start Price Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Price</label>
                            <div class="col-sm-10 col-md-9">
                                <input 
                                    type="text"
                                    name="price"
                                    required="required"
                                    class="form-control live-price"
                                    placeholder="Price Of The Item">
                                </input>
                            </div>
                        </div>
                        <!-- End Price Field -->
                        <!-- Start Counrtry Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Counrtry</label>
                            <div class="col-sm-10 col-md-9">
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
                            <label class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-10 col-md-9">
                                <select class="form-control" name="status" required>
                                    <option value="0">...</option>
                                    <option value="1">New</option>
                                    <option value="2">Like New</option>
                                    <option value="3">Used</option>
                                    <option value="4">Very Old</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Status Field -->
                        <!-- Start Categories Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Category</label>
                            <div class="col-sm-10 col-md-9">
                                <select class="form-control" name="category" required>
                                    <option value="0">...</option>
                                    <?php
                                    // function getAll($field,$table, $where=NULL,$option=NULL, $order, $ordering='DESC'){
                                    $cats = getAll('*','categories',NULL,NULL,'ID');
                                    foreach($cats as $cat){
                                        echo "<option value=' ".$cat['ID']."'>".$cat['Name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Categories Field -->
                        <!-- Start Tags Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Tags</label>
                            <div class="col-sm-10 col-md-9">
                                <input 
                                    type="text"
                                    name="tags"
                                    class="form-control"
                                    placeholder="Separate Tags By Comma (,)">
                                </input>
                            </div>
                        </div>
                        <!-- End Tags Field -->
                        <!-- Start Image Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Tags</label>
                            <div class="col-sm-10 col-md-9">
                                <input 
                                    type="file"
                                    name="image"
                                    class="form-control">
                                </input>
                            </div>
                        </div>
                        <!-- End Image Field -->
                        <!-- Add Submit Field -->
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-3 col-sm-10">
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
                    <div class="col-md-4">
                        <div class='thumbnaill item-box live-preview'>
                        <span class='price-tag'>$0</span>
                            <img class='img-responsive' src='defult.jpeg' alt='' />
                            <div class='caption'>
                            <h3>Title</h3>
                            <p>Description</p>
                            </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
else{
    header("Location: login.php?open=login");
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
    $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

    $image     = $_FILES['image'];
    $imageName = $_FILES['image']['name'];
    $imageSize = $_FILES['image']['size'];
    $imageTmp  = $_FILES['image']['tmp_name'];
    $imageType = $_FILES['image']['type'];

    $formerrors = array(); // Check Errors
    if(strlen($name)<4){
        $formerrors[] = "Item Title At Least 4 Characters";
    }
    if(strlen($desc)<10){
        $formerrors[] = "Item Description At Least 10 Characters";
    }
    if(strlen($country)<2){
        $formerrors[] = "Item Title At Least 4 Characters";
    }
    if(empty($price)){
        $formerrors[] = "Item Price Mustn't Be Empty";
    }
    if(empty($status)){
        $formerrors[] = "Item Status Mustn't Be Empty";
    }
    if(empty($category)){
        $formerrors[] = "Item Category Mustn't Be Empty";
    }
    
    
    if(empty($formerrors)){ // If No Errors Add The Item To Data_Base
        $image = rand(0, 1000000) . '_' . $imageName;
        move_uploaded_file($imageTmp, 'Uploads\Avatar\\'. $image);
        $stmt = $db->prepare("INSERT INTO items 
                                                (`Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Status`, `Cat_ID`, `Member_ID`, `Tags`, `item_IMG`)
                                            VALUES
                                                (:zname, :zdesc, :zprice, now(), :zcountry, :zstatus, :zcategory, :zmemID, :ztags, :zimg)");
        $stmt->execute(array(
            "zname"=>$name,
            "zdesc"=>$desc,
            "zprice"=>$price,
            "zcountry"=>$country,
            "zstatus"=>$status,
            "zcategory"=>$category,
            "zmemID"=>$_SESSION['UserID'],
            "ztags"=>$tags,
            'zimg' => $image
        ));
        $count = $stmt->rowCount();
        if($count>0){
            echo "<div class='text-center alert alert-success'>Your Item Is Inserted </div>";
        }
    }else{
        foreach($formerrors as $error){
            echo "<div class='text-center container alert alert-danger'>".$error."</div>"; // Print The Error Message
        }
    }
}

include $tpl.'footer.php';
ob_end_flush();
?>