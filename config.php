<?php

session_start();

$GOOGLE_SHEET_URL =
    getenv('GOOGLE_SHEET_URL') ?: '';

$GOOGLE_SHEET_TOKEN =
    getenv('GOOGLE_SHEET_TOKEN') ?: '';

$SITE_NAME =
    "IP Admin Portal";

$ADMIN_USERNAME =
    getenv('ADMIN_USERNAME') ?: '';

$ADMIN_PASSWORD =
    getenv('ADMIN_PASSWORD') ?: '';

?>