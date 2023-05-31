<?php
require_once ("templates/navigation.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Register</title>
</head>
<body>
<div class="container">
<div class="form-wrapper">
<h1 class="form-title">Register</h1>
<?php
if(isset($message)) {
    echo '<p role="alert">' .$message. '</p>';
}
?>
<p class="form-info">If you already have an account, <a href="/login/">please login instead</a>.</p>


<form method="post" action="/register/">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" class="form-control" required minlength="3" maxlength="60">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" required minlength="8" maxlength="1000">
    </div>
    <div class="form-group">
        <label for="repeat_password">Repeat Password</label>
        <input type="password" id="repeat_password" name="repeat_password" class="form-control" required minlength="8" maxlength="1000">
    </div>
    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" class="form-control" required minlength="10" maxlength="120">
    </div>
    <div class="form-group">
        <label for="city">City</label>
        <input type="text" id="city" name="city" class="form-control" required minlength="3" maxlength="40">
    </div>
    <div class="form-group">
        <label for="postal_code">Postal Code</label>
        <input type="text" id="postal_code" name="postal_code" class="form-control" required minlength="4" maxlength="20">
    </div>
    <div class="form-group">
        <label for="country">Country</label>
        <select id="country" name="country" class="form-control">
            <?php
            foreach($countries as $country) {
                $selected = ($country["code"] === "pt") ? "selected" : "";
                echo '<option value="' . $country["code"] . '" ' . $selected . '>' . $country["country"] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label>
            <input type="checkbox" name="agrees" required>
            I agree with the terms and conditions
        </label>
    </div>
    <div class="form-group">
        <button type="submit" name="send" class="btn btn-primary">Register</button>
    </div>
</form>
</div>
</div>
</body>
</html>