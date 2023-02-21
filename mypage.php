<?php

session_start();

if (isset($_SESSION["user_id"])) {

    $mysqli = require __DIR__ . "/account_management/database.php";

    $sql = "SELECT * FROM users
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="en" style="background-color: #d9d9d9;">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nukemichi maker hp</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="./sanitize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <script src="./validate_coordenates.js"></script>
    <script src="./map.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1QGR-mlSKkyr4m-yQ2acRX-evJ4OILbA&callback=initMap"></script>
    <!--ファビコンの設定-->
    <link rel="shortcut icon" href="./design/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="./design/images/apple-touch-icon.png">
</head>


<body>
    <div class="mypage:">
        <?php if (isset($user)) : ?>
            <article class="user-box">
                <img class="user-icon" src="design/images/user_icon.jpg" alt="ユーザー画像">
                <div class="user-name">
                    <h2>user name:</h2>
                    <h1><?= htmlspecialchars($user["name"]) ?></h1>
                    <p><?= htmlspecialchars($user["email"]) ?></p>
                </div>
            </article>

        <?php else :
            header('Location: ./account_management/logout.php');
            exit;
        ?>
        <?php endif; ?>
    </div>
    <div class="myroutes">
        <h1 class="title">My nukemichi</h1>
        <?php
        // 検索処理
        require "myroutes.php";

        // 結果を表示
        if (count($results) > 0) {
            foreach ($results as $r) {
                printf(
                    "<div class=\"search-results-container\">
          <div class=\"search-result\">
            <h1 class=\"route-name\">%s</h1>
            <p class=\"result-location\">%s</p>
            <p class=\"result-comment\">%s</p>
            <p id=\"result-point_a\" style=\"display:none;\"> %s</p>
            <p id=\"result-point_b\" style=\"display:none;\"> %s</p>
          </div>
        </div>",
                    $r["shortcut_name"],
                    $r["address"],
                    $r["comments"],
                    $r["point_a"],
                    $r["point_b"],
                );
            }
        } else {
            echo "まだ抜け道を登録していません";
        }
        ?>
    </div>
    <script src="./searched_map.js"></script>
    <!-- reset password -->
   <a href="./add-route.html" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
    </a>

</body>
