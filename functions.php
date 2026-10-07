<?php

require_once "config.php";


/*
|--------------------------------------------------------------------------
| IP ADDRESS
|--------------------------------------------------------------------------
*/

function getClientIP()
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';


    // Convert IPv4-mapped IPv6 address.
    if (strpos($ip, '::ffff:') === 0) {

        $ip = substr($ip, 7);

    }


    // Local IPv6 loopback.
    if ($ip === '::1') {

        return '127.0.0.1';

    }


    return $ip;
}


/*
|--------------------------------------------------------------------------
| DEVICE
|--------------------------------------------------------------------------
*/

function getDevice()
{
    $userAgent =
        $_SERVER['HTTP_USER_AGENT'] ?? '';


    if (preg_match('/mobile|android|iphone|ipad/i', $userAgent)) {

        return 'Mobile';

    }


    return 'Desktop';
}


/*
|--------------------------------------------------------------------------
| BROWSER
|--------------------------------------------------------------------------
*/

function getBrowser()
{
    $userAgent =
        $_SERVER['HTTP_USER_AGENT'] ?? '';


    if (strpos($userAgent, 'Edg') !== false) {

        return 'Microsoft Edge';

    }


    if (strpos($userAgent, 'Chrome') !== false) {

        return 'Google Chrome';

    }


    if (strpos($userAgent, 'Firefox') !== false) {

        return 'Mozilla Firefox';

    }


    if (strpos($userAgent, 'Safari') !== false) {

        return 'Safari';

    }


    return 'Unknown';
}


/*
|--------------------------------------------------------------------------
| OPERATING SYSTEM
|--------------------------------------------------------------------------
*/

function getOS()
{
    $userAgent =
        $_SERVER['HTTP_USER_AGENT'] ?? '';


    if (stripos($userAgent, 'Windows') !== false) {

        return 'Windows';

    }


    if (stripos($userAgent, 'Android') !== false) {

        return 'Android';

    }


    if (stripos($userAgent, 'iPhone') !== false) {

        return 'iOS';

    }


    if (stripos($userAgent, 'Mac') !== false) {

        return 'macOS';

    }


    if (stripos($userAgent, 'Linux') !== false) {

        return 'Linux';

    }


    return 'Unknown';
}


/*
|--------------------------------------------------------------------------
| GOOGLE SHEETS GET
|--------------------------------------------------------------------------
*/

function googleSheetGet($params = [])
{
    global $GOOGLE_SHEET_URL;
    global $GOOGLE_SHEET_TOKEN;


    $params['token'] =
        $GOOGLE_SHEET_TOKEN;


    $url =
        $GOOGLE_SHEET_URL .
        '?' .
        http_build_query($params);


    $response =
        file_get_contents($url);


    if ($response === false) {

        return [
            'success' => false,
            'error' => 'Unable to contact Google Sheets.'
        ];

    }


    return json_decode(
        $response,
        true
    );
}


/*
|--------------------------------------------------------------------------
| GOOGLE SHEETS POST
|--------------------------------------------------------------------------
*/

function googleSheetPost($data)
{
    global $GOOGLE_SHEET_URL;
    global $GOOGLE_SHEET_TOKEN;


    $data['token'] =
        $GOOGLE_SHEET_TOKEN;


    $options = [

        'http' => [

            'method' => 'POST',

            'header' =>
                "Content-Type: application/json\r\n",

            'content' =>
                json_encode($data),

            'ignore_errors' => true

        ]

    ];


    $context =
        stream_context_create($options);


    $response =
        file_get_contents(
            $GOOGLE_SHEET_URL,
            false,
            $context
        );


    if ($response === false) {

        return [
            'success' => false,
            'error' => 'Unable to contact Google Sheets.'
        ];

    }


    return json_decode(
        $response,
        true
    );
}


/*
|--------------------------------------------------------------------------
| GET USERS
|--------------------------------------------------------------------------
*/

function getUsers()
{
    $result =
        googleSheetGet([
            'action' => 'users'
        ]);


    if (
        !$result ||
        !$result['success']
    ) {

        return [];

    }


    return $result['users'] ?? [];
}


/*
|--------------------------------------------------------------------------
| FIND USER
|--------------------------------------------------------------------------
*/

function findUser($username)
{
    $users =
        getUsers();


    foreach ($users as $user) {

        if (
            strtolower(
                $user['username']
            )
            ===
            strtolower($username)
        ) {

            return $user;

        }

    }


    return null;
}


/*
|--------------------------------------------------------------------------
| WRITE LOGIN LOG
|--------------------------------------------------------------------------
*/

    function writeLog(
        $username,
        $status,
        $action = 'LOGIN'
    )
    {
        $ip =
            getClientIP();


    $data = [

        'action' =>
            'log',

        'timestamp' =>
            date(
                'Y-m-d H:i:s'
            ),

        'username' =>
            $username,

        'ip' =>
            $ip,

        'status' =>
            $status,

        'browser' =>
            getBrowser(),

        'os' =>
            getOS(),

        'device' =>
            getDevice(),

        'userAgent' =>
            $_SERVER['HTTP_USER_AGENT']
            ?? '',

        'log_action' =>
            $action

    ];


    return googleSheetPost(
        $data
    );
}


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

function isLoggedIn()
{
    return isset(
        $_SESSION['username']
    );
}


/*
|--------------------------------------------------------------------------
| REQUIRE LOGIN
|--------------------------------------------------------------------------
*/

function requireLogin()
{
    if (!isLoggedIn()) {

        header(
            'Location: index.php'
        );

        exit;

    }
}


/*
|--------------------------------------------------------------------------
| REQUIRE ADMIN
|--------------------------------------------------------------------------
*/

function requireAdmin()
{
    requireLogin();


    if (
        ($_SESSION['role'] ?? '')
        !==
        'admin'
    ) {

        http_response_code(403);

        die(
            'Access denied.'
        );

    }
}

?>