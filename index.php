<?php

require_once "functions.php";


if (isLoggedIn()) {

    header(
        "Location: admin.php"
    );

    exit;

}


$error = $_GET['error'] ?? '';

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    IP Admin Portal - Login
</title>

<link rel="stylesheet"
      href="login.css">

</head>


<body>


<div class="login-background">

    <div class="login-card">


        <div class="brand">

            <div class="brand-icon">
                IP
            </div>

            <h1>
                IP Admin
            </h1>

            <p>
                Management Portal
            </p>

        </div>


        <?php if ($error): ?>

            <div class="error-message">

                <?php
                echo htmlspecialchars(
                    $error
                );
                ?>

            </div>

        <?php endif; ?>


        <form
            action="login.php"
            method="POST"
        >


            <label>
                Username
            </label>

            <input
                type="text"
                name="username"
                placeholder="Enter your username"
                required
                autocomplete="username"
            >


            <label>
                Password
            </label>

            <div class="password-box">

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                >

                <button
                    type="button"
                    onclick="togglePassword()"
                >
                    Show
                </button>

            </div>


            <button
                class="login-button"
                type="submit"
            >
                SIGN IN
            </button>


        </form>


        <div class="login-footer">

            Secure Administration Portal

        </div>


    </div>

</div>


<script>

function togglePassword()
{

    const password =
        document.getElementById(
            "password"
        );


    if (
        password.type ===
        "password"
    ) {

        password.type =
            "text";

    } else {

        password.type =
            "password";

    }

}

</script>


</body>

</html>