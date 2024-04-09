<?php
// Kết nối tới cơ sở dữ liệu
require_once 'connect.php'; // File kết nối đến database

// Xử lý thêm sinh viên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $class = $_POST['class'];

    $sql = "INSERT INTO students (name, email, class) VALUES ('$name', '$email', '$class')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Add student information successfully.</p>";
    } else {
        echo "<p>Lỗi: " . $conn->error . "</p>";
    }
}

// Xử lý sửa sinh viên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $class = $_POST['class'];

    $sql = "UPDATE students SET name='$name', email='$email', class='$class' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Successfully updated student information.</p>";
    } else {
        echo "<p>Lỗi: " . $conn->error . "</p>";
    }
}

// Xử lý xóa sinh viên
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM students WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Successfully deleted student.</p>";

        // Kiểm tra xem danh sách sinh viên có trống không
        $check_empty = "SELECT COUNT(*) as count FROM students";
        $result = $conn->query($check_empty);
        $row = $result->fetch_assoc();
        $count = $row['count'];

        // Nếu danh sách sinh viên trống, thiết lập lại auto_increment cho ID
        if ($count == 0) {
            $reset_auto_increment = "ALTER TABLE students AUTO_INCREMENT = 1";
            $conn->query($reset_auto_increment);
        }
    } else {
        echo "<p>Lỗi: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students management</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
    <h2>Students management</h2>

    <!-- Form thêm sinh viên -->
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="name"><br>

        <label>Email:</label>
        <input type="text" name="email"><br>

        <label>Class:</label>
        <input type="text" name="class"><br>

        <button type="submit" name="add">Add student</button>
    </form>

    <h2>Student list</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Class</th>
            <th>Function</th>
        </tr>
        <?php
        // Hiển thị danh sách sinh viên
        $sql = "SELECT * FROM students";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['class'] . "</td>";
                echo "<td><a href='?delete=" . $row['id'] . "'>Delete</a> | <a href='#' class='edit-link' data-id='" . $row['id'] . "'>Edit</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>There are no students.</td></tr>";
        }
        ?>
    </table>

    <!-- Form sửa sinh viên -->
    <div class="edit-form">
        <h2>Student editing</h2>
        <form method="post" action="">
            <input type="hidden" name="id" id="edit-id">
            <label>Name:</label>
            <input type="text" name="name" id="edit-name"><br>
            <label>Email:</label>
            <input type="text" name="email" id="edit-email"><br>
            <label>Class:</label>
            <input type="text" name="class" id="edit-class"><br>
            <button type="submit" name="edit">Update</button>
        </form>
    </div>

    <script>
        // JavaScript để hiển thị biểu mẫu sửa khi nhấp vào liên kết "Sửa"
        const editLinks = document.querySelectorAll('.edit-link');
        const editForm = document.querySelector('.edit-form');
        const editIdInput = document.getElementById('edit-id');
        const editNameInput = document.getElementById('edit-name');
        const editEmailInput = document.getElementById('edit-email');
        const editClassInput = document.getElementById('edit-class');

        editLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const studentId = this.getAttribute('data-id');
                const studentName = this.parentNode.parentNode.childNodes[1].textContent;
                const studentEmail = this.parentNode.parentNode.childNodes[2].textContent;
                const studentClass = this.parentNode.parentNode.childNodes[3].textContent;

                editIdInput.value = studentId;
                editNameInput.value = studentName;
                editEmailInput.value = studentEmail;
                editClassInput.value = studentClass;

                editForm.style.display = 'block';
            });
        });
    </script>

    <?php
    // Đóng kết nối
    $conn->close();
    ?>
</body>
</html>
