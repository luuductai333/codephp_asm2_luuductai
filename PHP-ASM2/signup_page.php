<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="stylesheet" rel="signup.css">

</head>

<body class="flex items-center justify-center min-h-screen bg-gray-300">
  <div class="p-8 max-w-md w-full bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Register</h2>
    <form action="signup_page.php" method="post">
      <div class="mb-4">
        <label for="fullname" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
        <input id="fullname" name="fullname" type="text" placeholder="Enter your full name" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500" required>
      </div>
      <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
        <input id="email" name="email" type="email" placeholder="Enter your email" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500" required>
      </div>
      <div class="mb-6">
        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
        <input id="password" name="password" type="password" placeholder="Enter your password" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500" required>
      </div>
      <div class="mb-6">
        <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
        <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm your password" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500" required>
      </div>

      <button type="submit" name="button_regist" class="w-full bg-indigo-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline hover:bg-indigo-600">Sign Up</button>

      <button type="submit" name="button_login" class="w-full bg-red-500 mt-5 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline hover:bg-indigo-600">Sign In</button>
    </form>
  </div>

  <?php
  // Database connection settings
  $_servername = "localhost";
  $_username = "root";
  $_password = "";
  $_dbname = "php_asm2";
  $conn = new mysqli($_servername, $_username, $_password, $_dbname);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['button_regist'])) {
      $email = $_POST['email'];
      $fullname = $_POST['fullname'];
      $password = $_POST['password'];
      $confirm_password = $_POST['confirm_password'];
      
      // Check if the email already exists
      $query = "SELECT * FROM users WHERE email = '$email'";
      $result = $conn->query($query);

      if ($result->num_rows > 0) {
        // Email already exists, display error
        echo '<script>alert("Email already exists!");</script>';
      } else {
        // Check if password and confirm password match
        if ($password !== $confirm_password) {
          echo '<script>alert("Passwords do not match!");</script>';
        } else {
          // Hash password
          $hash_password  = password_hash($password, PASSWORD_DEFAULT);

          // Insert user into database
          $stmt = $conn->prepare("INSERT INTO users (fullname, password, email) VALUES (?, ?, ?)");
          $stmt->bind_param("sss", $fullname, $hash_password, $email);

          if ($stmt->execute()) {
            echo '<script>alert("New user created successfully!");</script>';
            header("Location: signin_page.php");
            exit(); // Make sure to exit after redirecting
          } else {
            echo '<script>alert("Error: ' . $stmt->error . '");</script>';
          }

          $stmt->close();
        }
      }
    } elseif (isset($_POST['button_login'])) {
      // Redirect to sign-in page
      header("Location: signin_page.php");
      exit(); // Make sure to exit after redirecting
    }
  }

  $conn->close();
  ?>
</body>

</html>
