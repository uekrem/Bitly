<?php

$conn = new mysqli("localhost", "root", "", "bitly_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $long_url = $_POST["long_url"];

    $check_sql = "SELECT short_code FROM bitly_tb WHERE long_url = '$long_url'";
    $result = $conn->query($check_sql);
    session_start();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $short_code = $row["short_code"];
        $short_url = "http://uekrem.com/$short_code";
        $message = "Current shortened extension: <a href='$long_url' target=_blank >$short_url</a>";
        $_SESSION['link'] = $message;
    } else {
        $short_code = generateRandomString(6);
        $short_url = "http://uekrem.com/$short_code";

        $sql = "INSERT INTO bitly_tb (long_url, short_code) VALUES ('$long_url', '$short_code')";
        if ($conn->query($sql) === TRUE) {
            $message = "New shortened extension: <a href='$long_url' target=_blank >$short_url</a>";
            $_SESSION['link'] = $message;
        } else {
            $message =  "Hata: " . $sql . "<br>" . $conn->error;
            $_SESSION['link'] = $message;
        }
    }
}

function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Extension Shortening Service</title>
    <link rel="stylesheet" href="bitly.css">
</head>
<body>
    <main>
        <h2>Extension Shortening Service</h2>
        <form method="post" action="">
            <div class="mb-3">
                <label for="exampleInputText" class="form-label">URL</label>
                <input type="text" class="form-control" name="long_url" id="exampleInputText" aria-describedby="textHelp" required>
                <div id="textHelp" class="form-text">Copy the extension you want to shorten here</div>
            </div>
            <button type="submit" class="btn btn-primary">Get your link</button>
        </form>
        <p><?php echo $_SESSION['link'];session_destroy();?></p>
    </main>
</body>
</html>
