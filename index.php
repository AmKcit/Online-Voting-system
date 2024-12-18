<?php
ob_start(); // Start output buffering at the very beginning of the script

require_once('admin/inc/config.php');

function redirectTo($location) {
    header("Location: $location");
    exit;
}

function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sign_up_btn'])) {
        $su_username = sanitizeInput($_POST['su_username']);
        $su_contact_no = sanitizeInput($_POST['su_contact_no']);
        $su_password = sanitizeInput($_POST['su_password']);
        $su_retype = sanitizeInput($_POST['su_retype']);
        $su_role = 'voter';

        if ($su_password === $su_retype) {
            if (preg_match("/^\d{10}$/", $su_contact_no)) {
                $stmt = $db->prepare("SELECT * FROM users WHERE contact_no = ?");
                $stmt->bind_param("s", $su_contact_no);
                $stmt->execute();

                if ($stmt->get_result()->num_rows === 0) {
                    $hashed_password = sha1($su_password);
                    $insert_stmt = $db->prepare("INSERT INTO users (username, contact_no, password, user_role) VALUES (?, ?, ?, ?)");
                    $insert_stmt->bind_param("ssss", $su_username, $su_contact_no, $hashed_password, $su_role);

                    if ($insert_stmt->execute()) {
                        redirectTo("index.php?sign-up=1&registered=1");
                    } else {
                        echo "<div class='alert alert-danger text-center'>Error occurred while creating account.</div>";
                    }
                } else {
                    redirectTo("?sign-up=1&duplicate=1");
                }
            } else {
                echo "<div class='alert alert-danger text-center'>Invalid contact number format.</div>";
            }
        } else {
            redirectTo("?sign-up=1&invalid=1");
        }
    }

    if (isset($_POST['loginBtn'])) {
        $contact_no = sanitizeInput($_POST['contact_no']);
        $password = sanitizeInput($_POST['password']);
        $hashed_password = sha1($password);

        $stmt = $db->prepare("SELECT * FROM users WHERE contact_no = ? AND password = ?");
        $stmt->bind_param("ss", $contact_no, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            session_start();
            $data = $result->fetch_assoc();
            $_SESSION['user_role'] = $data['user_role'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['user_id'] = $data['id'];

            if ($data['user_role'] === "Admin") {
                $_SESSION['key'] = "AdminKey";
                redirectTo("admin/index.php?Homepage=1");
            } else {
                $_SESSION['key'] = "VotersKey";
                redirectTo("voters/index.php");
            }
        } else {
            redirectTo("index.php?invalid_access=1");
        }
    }
}

ob_end_flush(); // End output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .main {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }
        .side-image img {
            height: 100%;
            object-fit: cover;
        }
        .text {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            border-radius: 10px;
            color: black;
            font-family: 'Arial', sans-serif;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .text p {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .text i {
            font-size: 20px;
            font-style: italic;
            color: #ffeb3b;
        }
        form {
            padding: 40px;
        }
        header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-control {
            border-radius: 20px;
        }
        .btn {
            border-radius: 20px;
        }
        .signin span {
            font-size: 0.9rem;
        }
        .signin a {
            color: #007bff;
            text-decoration: none;
        }
        .signin a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container main">
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-6 side-image">
                    <img src="./assets/images/votelogo (1).jpeg" alt="Voting Community" class="img-fluid">
                    <div class="text">
                        <p>Join the community of Voting <i>- Only-Votes</i></p>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="col-md-6 right">
                    <?php if (isset($_GET['sign-up'])) { ?>
                       
                        <form method="POST" action="">
                            <header>Create Account</header>
                            <div class="mb-3">
                                <label for="su_username" class="form-label">Username</label>
                                <input type="text" id="su_username" name="su_username" class="form-control" required autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label for="su_contact_no" class="form-label">Contact Number</label>
                                <input type="text" id="su_contact_no" name="su_contact_no" class="form-control" required pattern="\d{10}" title="Please enter a valid 10-digit contact number">
                            </div>
                            <div class="mb-3">
                                <label for="su_password" class="form-label">Password</label>
                                <input type="password" id="su_password" name="su_password" class="form-control" required minlength="6" title="Password must be at least 6 characters">
                            </div>
                            <div class="mb-3">
                                <label for="su_retype" class="form-label">Retype Password</label>
                                <input type="password" id="su_retype" name="su_retype" class="form-control" required>
                            </div>
                            <button type="submit" name="sign_up_btn" class="btn btn-primary w-100">Sign Up</button>
                            <div class="text-center mt-3">
                                <span>Already have an account? <a href="index.php">Log in here</a></span>
                            </div>
                        </form>
                    <?php } else { ?>
                        <!-- Login Form -->
                        <form method="POST" action="">
                            <header>Login</header>
                            <div class="mb-3">
                                <label for="contact_no" class="form-label">Contact Number</label>
                                <input type="text" id="contact_no" name="contact_no" class="form-control" required autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" name="loginBtn" class="btn btn-primary w-100">Login</button>
                            <div class="text-center mt-3">
                                <span>Don't have an account? <a href="?sign-up=1">Sign Up</a></span>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>

            <!-- Feedback Messages -->
            <?php if (isset($_GET['registered'])) { ?>
                <div class='alert alert-success text-center'>Your account has been created successfully.</div>
            <?php } elseif (isset($_GET['invalid'])) { ?>
                <div class='alert alert-danger text-center'>Password mismatch, please try again.</div>
            <?php } elseif (isset($_GET['not_registered'])) { ?>
                <div class='alert alert-warning text-center'>Sorry, you are not registered!</div>
            <?php } elseif (isset($_GET['invalid_access'])) { ?>
                <div class='alert alert-danger text-center'>Invalid Username or Password!</div>
            <?php } elseif (isset($_GET['duplicate'])) { ?>
                <div class='alert alert-warning text-center'>Contact number already registered.</div>
            <?php } ?>
        </div>
    </div>

    
 

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNl4K9l7p3oG4F3z6KSU3Tcc7T+DNX8m+G2qK5A4Uazs2g5M5X1fQq8WZoQY8d" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhG81Y6e6DZ4E9hmb8yyhL1+2KQm49j8eIW3zFkwKa0p8mH04uCwA1fZ4d3" crossorigin="anonymous"></script>
</body>
</html>
