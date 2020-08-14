<?php
$scriptName = explode('/',$_SERVER['SCRIPT_NAME']);
$scriptName = end($scriptName);
?>
<div class="sidebar" data-background-color="black" data-active-color="success">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="../" class="simple-text">
                <img src="../themes/flixer/assets/images/logo.png" width="200">
            </a>
        </div>

        <ul class="nav">
            <li class="<?php echo ($scriptName=='dashboard.php')?'active':''?>">
                <a href="dashboard.php">
                    <i class="ti-panel"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='users.php')?'active':''?>">
                <a href="users.php">
                    <i class="ti-user"></i>
                    <p>Users</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='categories.php')?'active':''?>">
                <a href="categories.php">
                    <i class="ti-view-list"></i>
                    <p>Categories</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='films.php')?'active':''?>">
                <a href="films.php">
                    <i class="ti-video-clapper"></i>
                    <p>Films</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='videos.php')?'active':''?>">
                <a href="videos.php">
                    <i class="ti-video-clapper"></i>
                    <p>Videos</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='episodes.php')?'active':''?>">
                <a href="episodes.php">
                    <i class="ti-video-clapper"></i>
                    <p>Episodes</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='iepisodes.php')?'active':''?>">
                <a href="iepisodes.php">
                    <i class="ti-video-clapper"></i>
                    <p>IMDB Episodes</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='actors.php')?'active':''?>">
                <a href="actors.php">
                    <i class="ti-star"></i>
                    <p>Actors</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='codes.php')?'active':''?>">
                <a href="codes.php">
                    <i class="ti-ticket"></i>
                    <p>Codes</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='pages.php')?'active':''?>">
                <a href="pages.php">
                    <i class="ti-file"></i>
                    <p>Pages</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='membership_subscription.php')?'active':''?>">
                <a href="membership_subscription.php">
                    <i class="ti-palette"></i>
                    <p>Subscriptions</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='themes.php')?'active':''?>">
                <a href="themes.php">
                    <i class="ti-palette"></i>
                    <p>Themes</p>
                </a>
            </li>
            <li class="<?php echo ($scriptName=='settings.php')?'active':''?>">
                <a href="settings.php">
                    <i class="ti-settings"></i>
                    <p>Settings</p>
                </a>
            </li>
        </ul>
    </div>
</div>
