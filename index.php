<?php
ini_set('display_errors', 0);
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$session = new SpotifyWebAPI\Session(
    $_ENV["SPOTIFY_CLIENT_ID"],
    $_ENV["SPOTIFY_CLIENT_SECRET"],
    $_ENV["SPOTIFY_REDIRECT_URL"]
);

$accessToken = $session->getAccessToken();

if (isset($_SESSION['accessToken'])) {
    header('Location: ./list.php');
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
    <title>MyFavoriteSongs for Spotify</title>
</head>
<body>
<div class="container">
    <div class="jumbotron mt-4 p-4">
        <h1 class="display-4">MyFavoriteSongs for Spotify</h1>
        <p class="lead">Spotifyで、よく聴いている曲を可視化します。Spotifyアカウントと、アプリで聴く場合はSpotifyアプリが必要となります。</p>
        <hr class="my-3">
        <p>PHPとWeb APIの勉強用で作成したものです。</p>
        <p><a class="btn btn-outline-dark" href="https://github.com/pgkuroneru/MyFavoriteSongs-for-Spotify" role="button">GitHubで詳細を見る</a></p>
    </div>
    <h2>ログイン</h2>
    <div class="row">
        <div class="col-12 mt-2 mb-5">
            <div class="login text-center">
                <a class="btn btn-primary btn-lg" href="./callback.php">Spotifyでログイン</a>
            </div>
        </div>
    </div>
    <div class="songs-list">
        <h2>サンプル</h2>
        <div class="row row-cols-1 row-cols-md-2">
        <?php for ($item = 0; $item < 4; $item++) { ?>
            <div class="col mb-2">
                <div class="card h-100 p-2">
                    <div class="row no-gutters">
                        <div class="col-sm-12 col-md-5">
                            <img src="./images/no-image.png"
                                 class="card-img-top img-thumbnail" alt="">
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="card-body h-100">
                                <h5 class="card-title"><?php echo $item + 1 ?>. 曲名</h5>
                                <p class="card-text">アーティスト名</p>
                                <div>
                                    <a class="btn btn-primary btn-block" href="#"
                                       class="card-link">Spotifyアプリ</a>
                                    <a class="btn btn-primary btn-block" href="#"
                                       class="card-link">Spotify Web Player</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
</div>
<footer class="mt-3 pt-4 pb-4 text-center bg-light">
    <div class="container">
        <div class="row">
            <div class="col-sm-1 col-md-6">
                <a class="btn btn-outline-dark btn-block" href="https://github.com/pgkuroneru/MyFavoriteSongs-for-Spotify">GitHub</a>
            </div>
        </div>
    </div>
    <p class="mt-4">Made by <a href="https://twitter.com/pgkuroneru">@pgkuroneru</a></p>
</footer>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
