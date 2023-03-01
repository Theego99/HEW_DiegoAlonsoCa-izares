<?php

session_start();

if (isset($_SESSION["user_id"])) {

    $mysqli = require __DIR__ . "/account_management/database.php";

    $sql = "SELECT * FROM users
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
} else {
    header("Location: index.html");
}

?>
<!DOCTYPE html>
<html lang="en" style="background-color: #dae7ed;">

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
    <header class="header">
        <img class="logoimage" src="./design/images/NUKEMICHI.png">
        <!-- ヘッダーロゴ -->
        <div class="logo"></div>
        <!-- ハンバーガーメニュー部分 -->
        <div class="nav">
            <!-- ハンバーガーメニューの表示・非表示を切り替えるチェックボックス -->
            <input id="drawer_input" class="drawer_hidden" type="checkbox">

            <!-- ハンバーガーアイコン -->
            <label for="drawer_input" class="drawer_open"><span></span></label>
            <!-- メニュー -->
            <nav class="nav_content">
                <ul class="nav_list">
                    <!-- 三本線のやつ -->
                    <a href="search-page.php" class="musimegame">
                        <img src="./design/images/search.svg">
                        <li class="nav_item">SEARCH</li>
                    </a>
                    <a href="add-route.html" class="arrow">
                        <img src="./design/images/arrow.svg">
                        <li class="nav_item">NEW SHORTCUT</li>
                    </a>

                    <a href="mypage.php" class="hito">
                        <img src="./design/images/user.svg">
                        <li class="nav_item">MY PAGE</li>
                    </a>

                    <a href="index.html" class="tikyuugi">
                        <img src="./design/images/logout.svg">
                        <li class="nav_item">LOG OUT</li>
                    </a>
                </ul>
            </nav>
        </div>
    </header>
    <div class="mypage">
        <?php if (isset($user)) : ?>
            <?php if ($user["profile_pic"] == null) : $pic_url = "design/images/user_icon.jpg"; ?>
                <?php else : $pic_url = $user["profile_pic"]; ?><?php endif; ?>
                <article class="user-box">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <label for="fileToUpload">
                            <div class="profile-pic" style="background-image: url('<?= $pic_url ?>')">
                                <i class=""></i>
                                <span>写真を変更</span>
                            </div>
                        </label>
                        <input type="File" name="fileToUpload" id="fileToUpload"><br>
                        <input class="upload-btn" type="submit" value="アップロード" name="submit">
                    </form>

                    <div class="user-name">
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

    <?php
    if (isset($_FILES["fileToUpload"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"]) && ($_FILES["fileToUpload"]["tmp_name"]) != null) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "ファイルは既にアップロードされています、ファイル名を変更してください。";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "ファイルが大きすぎます。";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo " JPG　・ JPEG　・ PNG ・ GIF ファイルのみです。　";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "プロフィール写真をアップロードできませんでした。";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                // Get the URL of the uploaded file
                $pic_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/' . $target_file;

                // Update the profile_pic column in the users table
                $mysqli = require __DIR__ . "/account_management/database.php";

                $sql = "UPDATE users SET profile_pic = ? WHERE id = {$_SESSION["user_id"]}";

                $stmt = $mysqli->stmt_init();

                if (!$stmt->prepare($sql)) {
                    die("SQL error: " . $mysqli->error);
                }

                $stmt->bind_param(
                    "s",
                    $pic_url
                );

                if ($stmt->execute()) {
                    echo htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " がアップロードされました。";
                    exit;
                } else {
                    die($mysqli->error . " " . $mysqli->errno);
                }
            } else {
                echo "プロフィール写真をアップロードできませんでした。";
            }
        }
    } else {
    }
    ?>


    <script src="./searched_map.js"></script>
</body>