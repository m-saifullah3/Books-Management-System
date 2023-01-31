<?php require_once './database/connection.php'; ?>

<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
} else {
    header('location: ./access.php');
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $book_id = $_GET['id'];
} else {
    header('location: ./admin_index.php');
}

$sql = "SELECT * FROM `books` WHERE `book_id` = $book_id";
$result = $conn->query($sql);
$book = $result->fetch_assoc();

$book_name = $book['book_name'];
$book_author = $book['book_author'];
$book_publish_year = $book['book_publish_year'];
$book_price = $book['book_price'];
$book_description = $book['book_description'];

$error = $success = '';

if (isset($_POST['edit_book'])) {
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
                $sql = "UPDATE `books` SET `book_name`='$book_name',`book_description`='$book_description',`book_author`='$book_author',`book_picture`='$new_name',`book_price`='$book_price',`book_publish_year`='$book_publish_year' WHERE `book_id`='$book_id'";
                if ($conn->query($sql)) {
                    $success = 'Book has been successfully Updated!';
                } else {
                    $error = 'Book has failed to update!';
                }
            } else {
                $error = 'Book has failed to update!';
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
</style>

<body>
    <!-- ======= Navbar ======= -->
    <?php require_once './includes/navbar.php' ?>
    <!-- End Navbar -->

    <main id="main">
        <section id="featured-services" class="featured-services section-bg">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-lg-4 col-md-6 mx-auto">
                        <div class="card-header text-end mb-2">
                            <a href="admin_index.php" class="btn btn-secondary">Back</a>
                        </div>
                        <div class="icon-box border border-dark">
                            <p class="text-danger"><?php echo $error; ?></p>
                            <p class="text-success"><?php echo $success; ?></p>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?id=<?php echo $book_id; ?>" method="post" enctype="multipart/form-data">
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
                                <?php echo $book_description; ?>
                                </textarea>

                                <input type="submit" class="btn btn-primary mt-2" name="edit_book" value="Update Book">
                            </form>
                        </div>
                    </div>
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
<script>
    let input = document.getElementById("inputTag");
    let imageName = document.getElementById("imageName")

    input.addEventListener("change", () => {
        let inputImage = document.querySelector("input[type=file]").files[0];
        imageName.innerText = inputImage.name;
    })
</script>

</html>