<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/assets/css/adminLogin.css">
</head>

<body>
<div class="container">
    <div class="form-wrapper">
        <?php if (isset($message) && !isset($_SESSION["admin"])): ?>
            <p role="alert"><?= $message ?></p>
        <?php endif; ?>
        <form action="/admin" method="post">
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" maxlength="32" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" minlength="8" maxlength="1000" required>
            </div>
            <div>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?? "" ?>">
                <button type="submit" name="send">Login</button>
                <p class="smaller-text">Note: If you have forgotten your password, please contact the ADMIN so that they can provide you with a new one.</p>
            </div>
        </form>
    </div>
</div>
</body>
</html>