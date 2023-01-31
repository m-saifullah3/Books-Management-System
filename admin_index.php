<?php require_once './database/connection.php'; ?>

<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
} else {
    header('location: ./access.php');
}
?>

<!-- show reviews -->
<?php
$sql = "SELECT * FROM `ratings`";
$result = $conn->query($sql);
$reviews = $result->fetch_all(MYSQLI_ASSOC);
foreach ($reviews as $review) {
}
?>

<!-- show books -->
<?php
$sql = "SELECT * FROM `books`";
$result = $conn->query($sql);
$books = $result->fetch_all(MYSQLI_ASSOC);
?>

<!-- add books -->
<?php
$error = $success = $book_name = $book_author = $book_publish_year = $book_price = $book_description = '';
if (isset($_POST['add_book'])) {
    $book_name = htmlspecialchars($_POST['book_name']);
    $book_author = htmlspecialchars($_POST['book_author']);
    $book_publish_year = htmlspecialchars($_POST['book_publish_year']);
    $book_price = htmlspecialchars($_POST['book_price']);
    $book_description = htmlspecialchars(trim($_POST['book_description']));

    if (empty($book_name)) {
        $error = "Please enter book name!";
    } elseif (empty($book_author)) {
        $error = "Please enter author name!";
    } elseif (empty($book_publish_year)) {
        $error = "Please enter the year book was published in!";
    } elseif (empty($book_price)) {
        $error = "Please enter book price!";
    } elseif (empty($book_description)) {
        $error = "Please enter book description!";
    } elseif ($_FILES['book_picture']['error'] != 0) {
        $error = 'Please attach your file!';
    } else {
        $file_name = $_FILES['book_picture']['name'];
        $file_temp_name = $_FILES['book_picture']['tmp_name'];
        $file_extension_array = explode('.', $file_name);
        $file_extension = strtolower(end($file_extension_array));

        $allowed_extensions = ['png', 'jpeg', 'jpg'];

        if (in_array($file_extension, $allowed_extensions)) {

            $new_name = "B-" . rand(1, 1000) . "-" . microtime(true) . "-" . $file_name;

            $upload_folder = './books_pictures/' . $new_name;
            if (move_uploaded_file($file_temp_name, $upload_folder)) {
                $sql = "INSERT INTO `books`(`book_name`, `book_description`, `book_author`, `book_picture`, `book_price`, `book_publish_year`) VALUES ('${book_name}','${book_description}','${book_author}','${new_name}','${book_price}','${book_publish_year}')";
                if ($conn->query($sql)) {
                    $success = 'Book has been successfully added!';
                    $book_name = $book_author = $book_publish_year = $book_description = $book_price = '';
                    header('location: admin_index.php');
                } else {
                    $error = 'Book has failed to add!';
                }
            } else {
                $error = 'Book has failed to add!';
            }
        } else {
            $error = 'File format is not allowed!';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<!-- ======= Header ======= -->
<?php require_once './includes/header.php'; ?>
<!-- End Header -->
<script src="https://use.fontawesome.com/3a2eaf6206.js"></script>
<style>
    #file_input {
        text-align: center;
        padding: 3%;
        border: thin solid black;
    }

    #book_picture {
        display: none;
    }

    #inputTag {
        display: none;
    }

    #book_picture_label {
        cursor: pointer;
    }

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

<body>
    <!-- ======= Navbar ======= -->
    <?php require_once './includes/navbar.php' ?>
    <!-- End Navbar -->

    <main id="main">
        <section id="featured-services" class="featured-services section-bg">
            <div class="container">
                <div class="row no-gutters">
                    <?php foreach ($books as $book) { ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="icon-box border border-dark">
                                <div class="text-center icon"><img style="width: 200px; height: 320px;" src="books_pictures/<?php echo $book['book_picture']; ?>"></div>
                                <h3 class="text-bold"><?php echo $book['book_name']; ?></h3>
                                <p class="fw-light">By: <?php echo $book['book_author']; ?></p>
                                <p class="fw-lighter">Published in <strong><?php echo $book['book_publish_year']; ?></strong> | Price Rs: <strong><?php echo $book['book_price']; ?></strong></p>
                                <p style="max-height: 100px;" class="description scrollbar" id="scrollbar6">"<?php echo $book['book_description']; ?>"</p>
                                <hr>
                                <div class="text-center">
                                    <a href="./edit_book.php?id=<?php echo $book['book_id']; ?>" class="btn btn-info">Edit Book</a>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="deleteBook(<?php echo $book['book_id']; ?>)">Delete Book</button>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>

                    <!-- Add Book -->
                    <div class="col-lg-4 col-md-6">
                        <div class="icon-box border border-dark">
                            <p class="text-danger"><?php echo $error; ?></p>
                            <p class="text-success"><?php echo $success; ?></p>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                                <div id="file_input">
                                    <label for="inputTag" id="book_picture_label">
                                        Select Image <br />
                                        <i class="fa fa-2x fa-camera"></i>
                                        <input id="inputTag" name="book_picture" type="file" accept="image/*">
                                        <br />
                                        <span class="text-success" id="imageName"></span>
                                    </label>
                                </div>

                                <label class="mt-2" for="book_name">Book Name:</label>
                                <input type="text" name="book_name" class="form-control" value="<?php echo $book_name; ?>">

                                <label for="book_author">Book Author:</label>
                                <input type="text" name="book_author" class="form-control" value="<?php echo $book_author ?>">

                                <div class="row row-cols-2">
                                    <div class="col">
                                        <label for="book_author">Book Published In:</label>
                                        <input class="form-control" name="book_publish_year" type="number" value="<?php echo $book_publish_year; ?>">
                                    </div>

                                    <div class="col">
                                        <label for="book_author">Book Price:</label>
                                        <input class="form-control" name="book_price" type="number" value="<?php echo $book_price; ?>">
                                    </div>
                                </div>

                                <label for="book_description">Description:</label>
                                <textarea name="book_description" class="form-control" name="book_description" id="book_description" cols="50" rows="5">
                                <?php echo $book_description; ?></textarea>

                                <input type="submit" class="btn btn-primary mt-2" name="add_book" value="Add Book">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container table-responsive">
                <table class="table table-hover caption-top">
                    <hr>
                    <caption>Reviews</caption>
                    <thead>
                        <tr class="table-secondary">
                            <th>User Name</th>
                            <th>User Email</th>
                            <th>Book</th>
                            <th>Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review) {
                            $review_book_id = $review['book_id'];
                            $review_user_id = $review['user_id'];
                        ?>
                            <tr>
                                <td class="col-2" id="userName"><?php $sql = "SELECT `user_name` FROM `users` WHERE `user_id` = ${review_user_id}";
                                                                $result = $conn->query($sql);
                                                                $review_user = $result->fetch_assoc();
                                                                echo $review_user['user_name'] ?></td>
                                <td class="col-2" id="userEmail"><?php $sql = "SELECT `user_email` FROM `users` WHERE `user_id` = ${review_user_id}";
                                                                    $result = $conn->query($sql);
                                                                    $review_user = $result->fetch_assoc();
                                                                    echo $review_user['user_email'] ?></td>
                                <td class="col-2" id="bookName"><?php $sql = "SELECT `book_name` FROM `books` WHERE `book_id` = ${review_book_id}";
                                                                $result = $conn->query($sql);
                                                                $review_book = $result->fetch_assoc();
                                                                echo $review_book['book_name'] ?></td>
                                <td id="review"><?php echo $review['rating_text'] ?></td>
                            </tr>
                        <?php }; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- ======= Footer ======= -->
    <?php require_once './includes/footer.php' ?>
    <!-- End Footer -->
</body>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h1 class="modal-title fs-5 text-white" id="deleteModalLabel">Delete User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this book?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="" class="btn btn-danger" id="btn-delete">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- ======= Script ======= -->
<?php require_once './includes/script.php' ?>
<script>
    function deleteBook(id) {
        btnDelete = document.getElementById('btn-delete');
        btnDelete.setAttribute('href', 'delete_book.php?id=' + id);
    }
</script>
<script>
    let input = document.getElementById("inputTag");
    let imageName = document.getElementById("imageName")

    input.addEventListener("change", () => {
        let inputImage = document.querySelector("input[type=file]").files[0];
        imageName.innerText = inputImage.name;
    })
</script>
<!-- End Script -->


</html>