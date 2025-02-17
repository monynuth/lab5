<?php
define("HOST", "127.0.0.1");
define("USER", "root");
define("PWD", "");
define("DB", "m1");

try {
    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DB, USER, PWD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Insert Data
if (isset($_POST['insert'])) {
    $name = $_POST['name'];
    $sex = $_POST['sex'];
    $score = $_POST['score'];

    $sql = "INSERT INTO tbl_student (stname, stsex, score) VALUES (:name, :sex, :score)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['name' => $name, 'sex' => $sex, 'score' => $score]);

    header("Location: index.php");
    exit();
}

// Delete Data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM tbl_student WHERE stid = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);

    header("Location: index.php");
    exit();
}

// Update Data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $sex = $_POST['sex'];
    $score = $_POST['score'];

    $sql = "UPDATE tbl_student SET stname = :name, stsex = :sex, score = :score WHERE stid = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id, 'name' => $name, 'sex' => $sex, 'score' => $score]);

    header("Location: index.php");
    exit();
}

// Fetch Data
$sql = "SELECT * FROM tbl_student";
$stmt = $conn->prepare($sql);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    

</head>
<body>

    <h2>Add Student</h2>
    <form action="" method="POST">
        <input type="text" name="name" placeholder="Student Name" required>
        <select name="sex">
            <option value="M">Male</option>
            <option value="F">Female</option>
        </select>
        <input type="number" name="score" placeholder="Score" required>
        <button type="submit" name="insert">Add Student</button>
    </form>

    <h2>Student List</h2>
    <table class="table-container">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Sex</th>
            <th>Score</th>
            <th>Action</th>
        </tr>
        <?php foreach ($students as $row) { ?>
            <tr>
                <td><?= $row['stid'] ?></td>
                <td><?= $row['stname'] ?></td>
                <td><?= $row['stsex'] ?></td>
                <td><?= $row['score'] ?></td>
                <td>
                    <a href="index.php?edit=<?= $row['stid'] ?>">Edit</a> |
                    <a href="index.php?delete=<?= $row['stid'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <?php
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $sql = "SELECT * FROM tbl_student WHERE stid = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <h2>Edit Student</h2>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?= $student['stid'] ?>">
            <input type="text" name="name" value="<?= $student['stname'] ?>" required>
            <select name="sex">
                <option value="M" <?= $student['stsex'] == 'M' ? 'selected' : '' ?>>Male</option>
                <option value="F" <?= $student['stsex'] == 'F' ? 'selected' : '' ?>>Female</option>
            </select>
            <input type="number" name="score" value="<?= $student['score'] ?>" required>
            <button type="submit" name="update">Update Student</button>
        </form>
    <?php } ?>

</body>
</html>