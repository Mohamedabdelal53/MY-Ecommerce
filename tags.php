<?php 
ob_start();
$pageTitle='Category';
session_start();
include 'init.php';
?>
<div class="container">
    <div class="row">
        <?php
        if(isset($_GET['name'])&& $_GET['name']!==''){
            $tag = $_GET['name'];
            echo '<h1 class="text-center">'.$tag.'</h1>';
            $tagitems = getAll('*','items',"WHERE Tags Like '%$tag%'",'AND Approve = 1','item_ID');
            if(!(empty($tagitems))){
                foreach($tagitems as $item){
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
                        header('Location: index.php');
                    }
                    ?>
    </div>
</div>
            <?php }else{
                        echo "You Must Enter The Tag Name";
} ?>
<?php include $tpl.'footer.php';
ob_end_flush();
?>