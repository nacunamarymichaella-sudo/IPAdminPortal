<?php

require_once "functions.php";

requireAdmin();


global $GOOGLE_SHEET_URL;
global $GOOGLE_SHEET_TOKEN;


/*
|--------------------------------------------------------------------------
| Get logs directly from Apps Script
|--------------------------------------------------------------------------
*/

$url =
    $GOOGLE_SHEET_URL .
    "?action=logs&token=" .
    urlencode(
        $GOOGLE_SHEET_TOKEN
    );


/*
|--------------------------------------------------------------------------
| We need a logs endpoint.
|--------------------------------------------------------------------------
*/

$response =
    @file_get_contents(
        $url
    );


$data = [];

if ($response !== false) {

    $data =
        json_decode(
            $response,
            true
        );

}


$logs =
    $data['logs'] ?? [];

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Login Logs
</title>

<link rel="stylesheet"
      href="dashboard.css">


<style>

.logs-panel {

    margin-top: 20px;

    background: white;

    border-radius: 16px;

    padding: 20px;

    overflow-x: auto;

}


.logs-table {

    width: 100%;

    border-collapse: collapse;

    min-width: 1000px;

}


.logs-table th {

    text-align: left;

    padding: 13px;

    background: #f8fafc;

    color: #475569;

    font-size: 13px;

}


.logs-table td {

    padding: 13px;

    border-top: 1px solid #e2e8f0;

    font-size: 13px;

}


.badge {

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: 800;

}


.success {

    background: #dcfce7;

    color: #166534;

}


.failed {

    background: #fee2e2;

    color: #991b1b;

}


.ip {

    font-family: monospace;

    font-weight: 700;

}

</style>

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

<a href="admin.php">
Dashboard
</a>

<a class="active"
   href="logs.php">
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


<header class="topbar">

<div>

<h1>
Login Logs
</h1>

<p>
User login and logout activity
</p>

</div>

</header>


<div class="logs-panel">


<table class="logs-table">


<thead>

<tr>

<th>
Timestamp
</th>

<th>
Username
</th>

<th>
IP Address
</th>

<th>
Status
</th>

<th>
Action
</th>

<th>
Browser
</th>

<th>
OS
</th>

<th>
Device
</th>

</tr>

</thead>


<tbody>


<?php foreach ($logs as $log): ?>


<tr>

<td>
<?= htmlspecialchars(
    $log['timestamp'] ?? ''
) ?>
</td>


<td>
<?= htmlspecialchars(
    $log['username'] ?? ''
) ?>
</td>


<td class="ip">
<?= htmlspecialchars(
    $log['ip'] ?? ''
) ?>
</td>


<td>

<?php

$status =
    strtoupper(
        $log['status'] ?? ''
    );

$class =
    $status === 'SUCCESS'
    ? 'success'
    : 'failed';

?>

<span
    class="badge <?= $class ?>"
>

<?= htmlspecialchars($status) ?>

</span>

</td>


<td>
<?= htmlspecialchars(
    $log['action'] ?? ''
) ?>
</td>


<td>
<?= htmlspecialchars(
    $log['browser'] ?? ''
) ?>
</td>


<td>
<?= htmlspecialchars(
    $log['os'] ?? ''
) ?>
</td>


<td>
<?= htmlspecialchars(
    $log['device'] ?? ''
) ?>
</td>


</tr>


<?php endforeach; ?>


</tbody>

</table>


<?php if (empty($logs)): ?>

<p>
No logs found yet.
</p>

<?php endif; ?>


</div>


</main>

</div>


</body>

</html>