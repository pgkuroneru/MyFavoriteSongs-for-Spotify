<?php
ini_set('display_errors', 0);
require 'vendor/autoload.php';

$env = getenv("APP_ENV");
if ($env === "production") {
    $session = new SpotifyWebAPI\Session(
        getenv("SPOTIFY_CLIENT_ID"),
        getenv("SPOTIFY_CLIENT_SECRET"),
        getenv("SPOTIFY_REDIRECT_URL")
    );
} else {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $session = new SpotifyWebAPI\Session(
        $_ENV["SPOTIFY_CLIENT_ID"],
        $_ENV["SPOTIFY_CLIENT_SECRET"],
        $_ENV["SPOTIFY_REDIRECT_URL"]
    );
}

if (isset($_GET['code'])) {
    $session->requestAccessToken($_GET['code']);

    session_start();
    $_SESSION['token'] = $session->getAccessToken();

    header('Location: ./list.php');
} else {
    $scopes = [
        'scope' => [
            'user-top-read'
        ],
    ];

    header('Location: ' . $session->getAuthorizeUrl($scopes));
}
