<?php
if (isset($_POST['user'])) {
    header('location: ./user_sign_in.php');
} elseif (isset($_POST['admin'])) {
    header('location: ./admin_sign_in.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once './includes/header.php' ?>

<body>
    <div class="container mt-5">
        <div class="text-center">
            <p class="display-2">Are you user or admin?</p>
        </div>

        <div class="text-center">
            <form method="post">
                <button name="user" class="btn btn-outline-primary" type="submit">User</button>
                <button name="admin" class="btn btn-outline-secondary" type="submit">Admin</button>
            </form>
        </div>
    </div>
</body>
<?php require_once './includes/script.php' ?>

</html>