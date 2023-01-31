<?php require_once 'database/connection.php'; ?>

<?php
session_start();
$error = $email = "";

if (isset($_POST['signin'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if (empty($email)) {
        $error = "Please enter your E-mail!";
    } elseif (empty($password)) {
        $error = "Please enter your Password!";
    } else {
        $new_password = md5($password);
        $sql = "SELECT `admin_id` FROM `admins` WHERE `admin_email` = '$email' AND `admin_password` = '$new_password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $_SESSION['admin_id'] = $user['admin_id'];
            header('location: ./admin_index.php');
        } else {
            $error = "Invalid Combination!";
        }
    }
}
?>


<html lang="en">

<head>
    <title>Sign In</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body class="bg-light">
    <div class="login-page">
        <div class="container">
            <div class="row mx-auto">
                <div class="col-lg-6 offset-lg-1 mx-auto pt-5 mt-5">
                    <h3 class="mb-3">Admin Login</h3>
                    <div class="card-header text-end border rounded">
                        <a href="access.php" class="btn btn-outline-secondary">Back</a>
                    </div>
                    <div class="bg-white shadow rounded">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="pt-3 pb-5 px-5">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="row g-4">
                                        <div class="text-danger"><?php echo $error; ?></div>
                                        <div class="text-success"></div>
                                        <div class="col-12">
                                            <label>Email</label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="bi bi-person-fill"></i></div>
                                                <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" placeholder="Enter Email">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label>Password</label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="bi bi-lock-fill"></i></div>
                                                <input type="password" class="form-control" name="password" placeholder="Enter Password">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" name="signin" class="btn btn-primary px-4 mt-2">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>