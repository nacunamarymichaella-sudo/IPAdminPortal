<?php

require_once "functions.php";


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header(
        "Location: index.php"
    );

    exit;

}


$username =
    trim(
        $_POST['username'] ?? ''
    );


$password =
    $_POST['password'] ?? '';

function redirectWithLoginError($message, $username)
{
    $logResult =
        writeLog(
            $username,
            'FAILED',
            'LOGIN'
        );

    if (
        !$logResult ||
        empty($logResult['success'])
    ) {
        $message .= ' This attempt could not be recorded.';
    }

    header(
        'Location: index.php?error=' .
        urlencode($message)
    );

    exit;
}


if (
    $username === '' ||
    $password === ''
) {
    redirectWithLoginError(
        'Please enter your username and password.',
        $username
    );
}


if (
    $username !== $ADMIN_USERNAME ||
    $password !== $ADMIN_PASSWORD
) {
    redirectWithLoginError(
        'Invalid username or password.',
        $username
    );
}


/*
|--------------------------------------------------------------------------
| LOGIN SUCCESS
|--------------------------------------------------------------------------
*/
session_regenerate_id(true);

$_SESSION['username'] =
    $ADMIN_USERNAME;


$_SESSION['role'] =
    'admin';


$_SESSION['login_time'] =
    date(
        'Y-m-d H:i:s'
    );

$logResult =
    writeLog(
        $username,
        'SUCCESS',
        'LOGIN'
    );

if (
    !$logResult ||
    empty($logResult['success'])
) {
    header(
        'Location: admin.php?warning=' .
        urlencode('Login succeeded, but the activity log could not be saved.')
    );
} else {
    header(
        "Location: admin.php"
    );
}

exit;

?>