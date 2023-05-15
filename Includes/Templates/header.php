<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php getTitle() ?></title>
    <link rel="stylesheet" href="<?php echo $css;?>bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo $css;?>font-awesome.min.css"/>
    <link rel="stylesheet" href="<?php echo $css;?>frontend.css"/>
</head>
<body>
    <div class="upper-bar">
        <div class="container">
            <?php if(isset($_SESSION['user'])){?>
                <img class='my-image img-circle' src='OIP (1).jpg' alt='' />
                <div class="btn-group my-info">
                    <span class="btn btn-default dropdown-toggle" data-toggle='dropdown'>
                        <?php echo $_SESSION['user'];?>
                        <span class="caret"></span>
                    </span>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">My Profile</a></li>
                        <li><a href="newads.php">New Item</a></li>
                        <li><a href="profile.php#my-ads">My Items</a></li>
                        <li><a href="logout.php">Log Out</a></li>
                    </ul>
                </div>
                <?php
            }else{
?>
                <span class='pull-right login.php'><a href="login.php?open=login">Login</a> / <a href="login.php?open=signup">Sign Up</a></span>
            <?php
            } ?>
            </div>
    </div>
    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Home Page</a>
        </div>
        <div class="collapse navbar-collapse navbar-right" id="app-nav">
            <ul class="nav navbar-nav">
                <?php 
                $cats = getAll('*','categories','WHERE Parent = 0',NULL,'ID','DESC');
                foreach($cats as $cat){
                    echo 
                    '<li>
                        <a href="categories.php?pageid='.$cat['ID'].'">
                        '.$cat['Name'].'
                        </a>
                    </li>';
                }
                ?>
            </ul>
        </div>
        </div>
    </nav>