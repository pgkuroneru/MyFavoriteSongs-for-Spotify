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

$scopes = array(
    'user-top-read'
);
$authorizeUrl = $session->getAuthorizeUrl(array(
    'scope' => $scopes
));

session_start();
if (isset($_SESSION['token'])) {
$api = new SpotifyWebAPI\SpotifyWebAPI();

$accessToken = $_SESSION['token'];

$api->setAccessToken($accessToken);

$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
if (isset($_GET['limit']) && is_numeric($_GET['limit']) && 0 < $_GET['limit'] && $_GET['limit'] <= 50) {
    $limit = $_GET['limit'];
} else {
    $limit = 10;
}

$time_range = isset($_GET['time_range']) ? $_GET['time_range'] : "medium_term";
switch ($time_range) {
    case 'short_term':
    case 'medium_term':
    case 'long_term':
        break;

    default:
        $time_range = "medium_term";
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
    <div class="mb-4">
        <form method="get">
            <div class="form-inline">
                <div class="form-group ml-2 mb-2">
                    <label>曲数：</label>
                    <select class="custom-select" name="limit">
                        <option selected>曲数を選択</option>
                        <option value="10">10（デフォルト）</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="form-group ml-2 mb-2">
                    <label>集計期間：</label>
                    <select class="custom-select" name="time_range">
                        <option selected>期間を選択</option>
                        <option value="long_term">過去12ヶ月</option>
                        <option value="medium_term">過去6ヶ月（デフォルト）</option>
                        <option value="short_term">過去1ヶ月</option>
                    </select>
                </div>
                <div class="form-group ml-2 mb-2">
                    <button type="submit" class="btn btn-primary">設定</button>
                </div>
            </div>
        </form>
    </div>
    <div class="row row-cols-1 row-cols-md-2">
        <?php
        try {
            $top = json_encode($api->getMyTop('tracks', ['limit' => $limit, 'time_range' => $time_range]));
        } catch (SpotifyWebAPIException $e) {
        ?>
        <p>エラーが発生しました。</p>
        <?php }

        $top_array = json_decode($top);
        for ($item = 0; $item < $limit; $item++) {

        $album_image = $top_array->items[$item]->album->images[0]->url;
        $song_title = ($item + 1) . '.&nbsp;' . $top_array->items[$item]->name;
        $artist_name = $top_array->items[$item]->artists[0]->name;
        $app_uri = $top_array->items[$item]->uri;
        $external_urls = $top_array->items[$item]->external_urls->spotify;
        ?>
            <div class="col mb-2">
                <div class="card h-100 p-2">
                    <div class="row no-gutters">
                        <div class="col-sm-12 col-md-5">
                            <img src="<?php print_r($album_image) ?>"
                                 class="card-img-top img-thumbnail" alt="">
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="card-body h-100">
                                <h5 class="card-title"><?php print_r($song_title) ?></h5>
                                <p class="card-text"><?php print_r($artist_name) ?></p>
                                <div>
                                    <a class="btn btn-primary btn-block" href="<?php print_r($app_uri) ?>"
                                       class="card-link"><img width="22px" src="images/Spotify_Icon_RGB_White.png">&nbsp;Spotifyアプリで聴く</a>
                                    <a class="btn btn-primary btn-block" href="<?php print_r($external_urls) ?>"
                                       class="card-link"><img width="22px" src="images/Spotify_Icon_RGB_White.png">&nbsp;Spotify Web Playerで聴く</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
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
<?php
} else {
    header('Location: ' . $session->getAuthorizeUrl($scopes));
    die();
}
?>