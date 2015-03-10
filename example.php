<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$config = array(
    'social' => array(
        'facebook' => array(
            'id' => '{fb-app-id}',
            'secret' => '{fb-app-secret}',
            'redirect' => '{your-site-callback-url}',
            'scope' => array(
                '{scope1}',
                '{scope2}',
                '{scope3}'
            )
        ),
        'vk' => array(
            'id' => '{vk-app-id}',
            'secret' => '{vk_app-secret}',
            'redirect' => '{your-site-callback-url}',
            'scope' => array(
                '{scope1}',
                '{scope2}'
            )
        ),
    )
);

session_start();
require_once "vendor/autoload.php";

\Social\Factory::setConfig($config['social']);
$social = \Social\Factory::factory($_GET['social']);

if (isset($_GET['code'])) {

    $social->setCode($_GET['code']);

    echo "<pre>";
    var_dump($social->getUserData());
    die("</pre>");
}
else {
    header('Location: ' . $social->getLoginUrl());
    exit;
}