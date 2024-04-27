<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container">
        <a class='navbar-brand' href='index.php'>Home</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['email'])) { ?>
                    <li class="nav-item">
                        <a class='nav-link' href='create_post.php'>Create a post</a>
                    </li>
                    <?php
                    if ($_SESSION['user_roleID'] == 1) { ?>
                        <li class="nav-item">
                            <a class='nav-link' href='create_module.php'>Create a module</a>
                        </li>
                        <li class="nav-item">
                            <a class='nav-link' href='accounts.php'>Accounts list</a>
                        </li>
                    <?php } ?>
                    <li class="nav-item dropdown">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" id="dropdownMenuButton" aria-expanded="false"><?php echo $_SESSION['username'];?></button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class='dropdown-item' href='profile.php?user_id=<?php echo $_SESSION['userID'];?>'>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class='dropdown-item' href='index.php?action=sign_out'>Sign out</a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class='nav-link' href='sign_in.php'>Sign in</a>
                    </li>
                    <li class="nav-item">
                        <a class='nav-link' href='sign_up.php'>Sign up</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>