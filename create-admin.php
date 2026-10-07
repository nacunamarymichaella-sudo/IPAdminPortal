<?php

require_once "functions.php";


if (
    $ADMIN_USERNAME === '' ||
    $ADMIN_PASSWORD === ''
) {
    http_response_code(503);
    exit('Set ADMIN_USERNAME and ADMIN_PASSWORD before creating an admin account.');
}

$username = $ADMIN_USERNAME;
$password = $ADMIN_PASSWORD;

$hash =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );


$result =
    googleSheetPost([

        'action' =>
            'create_user',

        'username' =>
            $username,

        'password_hash' =>
            $hash,

        'role' =>
            'admin',

        'status' =>
            'active',

        'created_at' =>
            date(
                'Y-m-d H:i:s'
            )

    ]);


echo "<h2>Admin account created</h2>";

echo "<pre>";

print_r($result);

echo "</pre>";

?>