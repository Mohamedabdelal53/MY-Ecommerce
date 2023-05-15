<?php 
ob_start();
$pageTitle='Category';
session_start();
include 'init.php';
if(isset($_GET['pageid'])){
    ?>
<div class="container">
    <h1 class='text-center'>
        Show Category Items
    </h1>
    <div class="row">
        <?php
        // function getAll($field,$table, $where=NULL,$option=NULL, $order, $ordering='DESC'){
        $items = getAll('*','items','WHERE Cat_ID = '.$_GET['pageid'].'','AND Approve = 1','item_ID'); // this function will show the ads which is approved by admin
        if(!(empty($items))){
            foreach($items as $item){
                echo "<div class='col-sm-6 col-md-4'>";
                    echo "<div class='thumbnail item-box'>";
                    echo "<span class='price-tag'>$".$item['Price']."</span>";
                        echo "<img class='img-responsive' src='Uploads\Avatar\\".$item['item_IMG']."' alt='' />";
                        echo "<div class='caption'>";
                        echo "<h3><a href='items.php?item_ID=".$item['item_ID']."'>".$item['Name']."</a></h3>";
                        echo "<p>".$item['Description']."</p>";
                        echo "<div class='date'>".$item['Add_Date']."</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                }else{
                    echo '<h3 class="container text-center alert alert-info">No Items to Show</h3>';
                }
                ?>
    </div>
</div>
<?php }else{
    header('Location: index.php');
} ?>
<?php include $tpl.'footer.php';
ob_end_flush();
?>