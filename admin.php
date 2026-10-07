<?php

require_once "functions.php";

requireAdmin();


$username =
    $_SESSION['username'];

$warning =
    $_GET['warning'] ?? '';

$users =
    getUsers();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Admin Dashboard
</title>

<link rel="stylesheet"
      href="dashboard.css">

</head>


<body>


<div class="layout">


    <aside class="sidebar">


        <div class="sidebar-brand">

            <div class="sidebar-logo">
                IP
            </div>

            <div>

                <strong>
                    IP Admin
                </strong>

                <small>
                    Portal
                </small>

            </div>

        </div>


        <nav>

            <a
                class="active"
                href="admin.php"
            >
                Dashboard
            </a>


            <a
                href="logs.php"
            >
                Login Logs
            </a>


        </nav>


        <div class="sidebar-bottom">

            <a
                class="logout-link"
                href="logout.php"
            >
                Logout
            </a>

        </div>


    </aside>


    <main class="main">

        <?php if ($warning): ?>

            <div class="log-warning">
                <?php
                echo htmlspecialchars(
                    $warning
                );
                ?>
            </div>

        <?php endif; ?>


        <header class="topbar">

            <div>

                <h1>
                    Dashboard
                </h1>

                <p>
                    Welcome back,
                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $username
                        );
                        ?>
                    </strong>
                </p>

            </div>


            <div class="profile">

                <div class="avatar">
                    <?php
                    echo strtoupper(
                        substr(
                            $username,
                            0,
                            1
                        )
                    );
                    ?>
                </div>

                <div>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $username
                        );
                        ?>
                    </strong>

                    <small>
                        <?php
                        echo htmlspecialchars(
                            $_SESSION['role']
                        );
                        ?>
                    </small>

                </div>

            </div>

        </header>


        <section class="cards">


            <div class="stat-card">

                <span>
                    Total Users
                </span>

                <strong>
                    <?php
                    echo count($users);
                    ?>
                </strong>

                <small>
                    Registered accounts
                </small>

            </div>


            <div class="stat-card">

                <span>
                    Your IP
                </span>

                <strong class="ip-text">

                    <?php

                    echo htmlspecialchars(
                        getClientIP()
                    );

                    ?>

                </strong>

                <small>
                    Current connection
                </small>

            </div>


            <div class="stat-card">

                <span>
                    Device
                </span>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        getDevice()
                    );

                    ?>

                </strong>

                <small>
                    Current device
                </small>

            </div>


            <div class="stat-card">

                <span>
                    Browser
                </span>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        getBrowser()
                    );

                    ?>

                </strong>

                <small>
                    Current browser
                </small>

            </div>


        </section>


        <section class="panel">


            <div class="panel-header">

                <div>

                    <h2>
                        System Overview
                    </h2>

                    <p>
                        Your Google Sheets database is connected.
                    </p>

                </div>


                <a
                    href="logs.php"
                    class="primary-button"
                >
                    View Login Logs
                </a>

            </div>


            <div class="info-grid">


                <div>

                    <span>
                        Logged-in User
                    </span>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $username
                        );
                        ?>
                    </strong>

                </div>


                <div>

                    <span>
                        Role
                    </span>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $_SESSION['role']
                        );
                        ?>
                    </strong>

                </div>


                <div>

                    <span>
                        Login Time
                    </span>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $_SESSION['login_time']
                        );
                        ?>
                    </strong>

                </div>


                <div>

                    <span>
                        Operating System
                    </span>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            getOS()
                        );
                        ?>
                    </strong>

                </div>


            </div>


        </section>


    </main>

</div>


</body>

</html>