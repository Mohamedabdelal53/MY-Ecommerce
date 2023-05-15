<?php 
ob_start();
session_start();
$pageTitle = 'Home';
include 'init.php';
?>

<div class="container">
    <h1 class='text-center'>
        All Items
    </h1>
    <div class="row">
        <?php
        $items = getAll('*','items',NULL,NULL,"item_ID"); // This Function Will Show All Of Items
        if(!(empty($items))){
            foreach($items as $item){
                echo "<div class='col-sm-6 col-md-4'>";
                        echo "<div class='thumbnail item-box'>";
                        echo "<span class='price-tag'>$".$item['Price']."</span>";
                        if($item['Approve']==0){
                            echo '<span class="Approve-Status">Waiting Approve</span>';
                        }
                        echo "<img class='img-responsive' src='Uploads\Avatar\\".$item['item_IMG']."' alt='' />";
                        echo "<div class='caption'>";
                        echo "<h3><a href='items.php?item_ID=".$item['item_ID']."'>".$item['Name']."</a></h3>";
                        echo "<p>".$item['Description']."</p>";
                        echo "<div class='date'>".$item['Add_Date']."</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
                ?>
    </div>
</div>

<?php
include $tpl.'footer.php';
ob_end_flush();
?>