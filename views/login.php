<?php
require_once ("templates/navigation.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Login</title>
</head>
<body>
<div class="container">
<div class="form-wrapper">
<h1 class="form-title">Login</h1>
<?php
if(isset($message)) {
    echo '<p role="alert">' .$message. '</p>';
}
?>
<p class="form-info">Not registered? <a href="/register/">Register here</a>.</p>
<form method="post" action="/login/">
    <div class="form-group">
        <label class="form-label">
            Email
            <input type="email" name="email" required class="form-control">
        </label>
    </div>
    <div class="form-group">
        <label class="form-label">
            Password
            <input type="password" name="password" required minlength="8" maxlength="1000" class="form-control">
        </label>
    </div>
    <div class="form-button">
        <button type="submit" name="send" class="form-control">Login</button>
    </div>
</form>
</div>
    <div>
</body>
</html>