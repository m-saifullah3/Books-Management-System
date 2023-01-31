<?php require_once './database/connection.php'; ?>

<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
} else {
    header('location: ./access.php');
}
?>

<?php
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $book_id = $_GET['id'];
}
?>

<!-- show books -->
<?php
$sql = "SELECT * FROM `books`";
$result = $conn->query($sql);
$books = $result->fetch_all(MYSQLI_ASSOC);
?>

<!-- show review -->
<?php
$error = $review = '';
if (isset($_POST['submit'])) {
    $review = htmlspecialchars($_POST['review']);
    if (empty($review)) {
        $error = 'Review is empty!';
    } else {
        $sql = "INSERT INTO `ratings`(`rating_text`, `user_id`, `book_id`) VALUES ('$review','$id','$book_id')";
        $result = $conn->query($sql);
    }
}
?>

<style>
    .scrollbar {
        height: 300px;
        width: 100%;
        overflow: auto;
        padding: 0 10px;
    }

    #scrollbar6::-webkit-scrollbar {
        width: 12px;
    }

    #scrollbar6::-webkit-scrollbar-track {
        border-radius: 8px;
        background-color: #95a5a6;
        border: 1px solid #cacaca;
    }

    #scrollbar6::-webkit-scrollbar-thumb {
        border-radius: 8px;
        background-color: #2c3e50;
    }
</style>


<!DOCTYPE html>
<html lang="en">

<!-- ======= Header ======= -->
<?php require_once './includes/header.php'; ?>
<!-- End Header -->

<body>
    <!-- ======= Navbar ======= -->
    <?php require_once './includes/navbar.php' ?>
    <!-- End Navbar -->

    <main id="main">
        <section id="featured-services" class="featured-services section-bg">
            <div class="container">
                <div class="row no-gutters">
                    <?php foreach ($books as $book) { ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="icon-box border border-dark">
                                <div class="text-center icon"><img style="width: 200px;" src="books_pictures/<?php echo $book['book_picture']; ?>"></div>
                                <h3 class="text-bold"><?php echo $book['book_name']; ?></h3>
                                <p class="fw-light">By: <?php echo $book['book_author']; ?></p>
                                <p class="fw-lighter">Published in <strong><?php echo $book['book_publish_year']; ?></strong> | Price Rs: <strong><?php echo $book['book_price']; ?></strong></p>
                                <p style="max-height: 100px;" class="description scrollbar" id="scrollbar6">"<?php echo $book['book_description']; ?>"</p>
                                <hr>
                                <?php
                                $book_id = $book['book_id'];
                                $sql = "SELECT `rating_text` FROM `ratings` WHERE `user_id` = '$id' AND `book_id` = '$book_id'";
                                $result = $conn->query($sql);
                                $rating = $result->fetch_assoc();

                                if ($rating) { ?>
                                    <strong>Your Review:</strong>
                                    <p><?php echo $rating['rating_text']; ?></p>
                                <?php  } else { ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?id=<?php echo $book['book_id']; ?>" method="post">
                                        <label for="review">Leave a review:</label>
                                        <p class="text-danger"><?php echo $error; ?></p>
                                        <textarea class="form-control" name="review" id="review" cols="40" rows="4"></textarea>
                                        <input type="submit" value="Submit" name="submit" class="btn btn-outline-primary mt-2">
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    <?php }; ?>
                </div>
            </div>
        </section>
    </main>


    <!-- ======= Footer ======= -->
    <?php require_once './includes/footer.php' ?>
    <!-- End Footer -->
</body>

<!-- ======= Script ======= -->
<?php require_once './includes/script.php' ?>
<!-- End Script -->

</html>