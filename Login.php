<!DOCTYPE html>
<?php
// includes other files into this page
require_once('vars.php');
require_once('functions.php');
  ?>

<html lang="en" style="background-color: #0077be;">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<script defer src="https://friconix.com/cdn/friconix.js"> </script>
<link rel="stylesheet" href="Login.css">


<head>

  <title>
    <?php echo $title; ?>
  </title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="myName" content="<?php echo $myName; ?>">
  <meta name="Sublime" content="<?php echo $pageEditor ?>">
  <meta name="MAMP PRO" content=" <?php echo $webServer ?>">
  <meta name="D'Andre Johnson's php page" content=" <?php echo $title ?>">
  <meta name="PHP Page" content=" <?php echo $pageName ?>">

  <!-- <link rel="stylesheet" type="text/css" href="includes/php_layout2.css"> -->


</head>

<body>
  <nav>
    <?php
    echo ("| ");
    displayAssignments($assignmentsArray);
    ?>
  </nav>

  <!-- Clock -->
  <?php
  date_default_timezone_set('America/New_York');
  $today = date("m/d/y");
  ?>



<?php


$servername = "localhost";
$username = "johnsodj03";
$password = "B00847413";
$dbname = "johnsodj03";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Sanitize and validate form data
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password_input = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Check if email and password are not empty
    if (empty($email)) {
        echo 'Please enter your email.';
    } elseif (empty($password_input)) {
        echo 'Please enter your password.';
    } else {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'Invalid email format.';
        } else {
            // Prepare the SQL statement using a parameterized query to avoid SQL injection
            $stmt = $conn->prepare("SELECT * FROM Regis WHERE accEmail = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            
            if ($result = $stmt->get_result()) {
                // Check if query execution was successful
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    // Verify the password using password_verify()
                    if (password_verify($password_input, $row['accPassword'])) {
                        // Set session variables

                        $_SESSION['email'] = $row['accEmail'];
                        $_SESSION['isAdmin'] = $row['isAdmin'];
                        $_SESSION['accID'] = $row['accID'];

                        // Update last login time in the database
                        $sql = "UPDATE Regis SET lastlogin = CURRENT_TIMESTAMP WHERE accEmail = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $email);
                        $stmt->execute();

                     // Redirect user to home page or admin page
                      if ($_SESSION['isAdmin'] == 1) {
                        echo '<script>window.location.href = "admin.php";</script>';
                      } else {
                        echo '<script>window.location.href = "lfa.php";</script>';
                      }

                    } else {
                        echo "Incorrect password";
                    }
                } else {
                    echo "User not found";
                }
            } else {
                echo "Error executing query: " . $conn->error;
            }
        }
    }
}
?>
  

  <form method="post">
    <h1 style="text-align:center;color:white;"> Login</h1>
		<label for="email">Email:</label>
		<input type="email" name="email" id="email" required>
		<br>
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" required>
		<br>
		<input type="submit" value="Login">
    <button><a href="forgotPassword.php">Forgot Password</a></button>
	</form>

      


    <a href="download.php?file=functions.php" title="download">Download functions.php</a>;

  </div>
</div>

  <!-- footer-->


  <footer class="transition_color" style="padding-top: 25px;padding-bottom: 75px;">

<div class="icon-row">
  <a href="https://www.linkedin.com/in/dandre-johnson---/" target="_blank"> <i class="fi-snsuxl-linkedin"></i></i></a>
  <a href="mailto:dandre.johnson1221@gmail.com" target="_blank"><i class="fi-xwsuxl-envelope-solid"></i></a>
</div>
<div class="copy-right">
  &copy; Thank you for visiting my Website
</div>

</footer>
</body>

</html>