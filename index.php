<?php
    session_start();
    require_once "authenticate/login.php";

    // If this page was requested for first time, username and password in html form won't be set
    if(!isset($_POST['username']) || !isset($_POST['pass'])){
        // session_destroy();
        $link = 0;
        if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true)
            $link = 'Dashboard'; // More informative link
        else
            $link = 'Login'; // More informative link
        echo <<< _END
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll & Attendance System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css"> <style>
        body {
            background-color: #f8f9fa; /* Light background */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #007bff !important; /* Primary blue */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }
        .navbar-toggler-icon {
            background-color: white;
        }
        .container-fluid {
            padding: 30px;
        }
        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        footer {
            background-color: #343a40; /* Dark footer */
            color: white;
            padding: 20px 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        /* Basic card styling - you might want to enhance this in your CSS */
        .thecard {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        .thecard__side {
            transition: transform 0.5s ease-in-out;
        }
        .thecard__side--back {
            transform: rotateY(180deg);
        }
        .thecard:hover .thecard__side--front {
            transform: rotateY(-180deg);
        }
        .thecard:hover .thecard__side--back {
            transform: rotateY(0deg);
        }
        .img-fluid {
            width: 100%;
            height: auto;
            display: block;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">Payroll & Attendance</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{$link}</a>
                </li>
            </ul>
            <form method='get' class="form-inline my-2 my-lg-0" action="https://www.google.com/search" target="_blank">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name='q'>
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-4 login-container">
                <h2 class="text-center mb-4">Login</h2>
                <form method='POST' action='' enctype='multipart/form-data'>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type='text' name='username' class="form-control" id="username" placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type='password' name='pass' class="form-control" id="password" placeholder="Enter password" required>
                    </div>
                    <button type='submit' class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>

        </div>

    <footer class="bg-dark text-white py-3 mt-4">
        <div class="container text-center">
            <p>&copy; <?php echo date("Y"); ?> All rights Reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnF5bo9wynKf5qBvBMEpi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwizr9YRtIUlqvG6QIyK1fL9C/tT" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>
_END;
    }

    else{
        $conn = mysqli_connect($hostname, $username, $password, $database);
        if(!$conn)
            die("Error while connectine. Try later. <br>".mysqli_connect_error());

        $currentUserName = trim($_POST['username']);
        $currentUserPass = trim($_POST['pass']);

        $user_query = "SELECT * FROM auth WHERE username='{$currentUserName}' AND pass='{$currentUserPass}'";
        $user_result = mysqli_query($conn, $user_query);

        if(!$user_result){
            die("Error matching credentials. Please try later.<br>".mysqli_error($conn));
            header('Refresh:01; url="index.php"');
            // mysqli_close($conn);

        }
        else if(mysqli_num_rows($user_result) == 0){
            // echo "Username and password doesn't match.<br>";
            echo '<script>alert("Username and password doesn\'t match")</script>';
            header('Refresh:01; url="index.php"');
            // mysqli_close($conn);
        }
        else{
            $_SESSION['user'] = $currentUserName;
            $_SESSION['loggedIn'] = true;
            $_SESSION['isAdmin'] = false;
            $_SESSION['isHr'] = false;
            // echo "Successfully Logged In. Redirecting to Profile.<br>";
            echo '<script>alert("Successfully Logged In. Click ok to go to profile page.")</script>';
            $data = mysqli_fetch_row($user_result);
            $isAdmin = $data[2];
            // print_r($data);
            $isHr = $data[3];

            if($isAdmin == "yes"){
                $_SESSION['isAdmin'] = true;
                header('Refresh:01; url=admin/adminProfile.php');
                exit();
            }
            else if($isHr == "yes"){
                $_SESSION['isHr'] = true;
                header('Refresh:01; url=admin/adminProfile.php');
                exit();
            }
            else {
                $_SESSION['isAdmin'] = false;
                $_SESSION['isHr'] = false;
                header('Refresh:01; url=user/userLoginImage.php');
                exit();
            }
        }
        // mysqli_close($conn);

    }
?>