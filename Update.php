<?php
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "test_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $Reg_Number = $_POST['Reg_Number'];
    $Student_Name = $_POST['Student_Name'];
    $Subject_Names = $_POST['Subject_Name'];
    $Marks = $_POST['Mark'];

    $delete_sql = "DELETE FROM crud WHERE Reg_Number = '$Reg_Number'";
    if ($conn->query($delete_sql) === FALSE) {
        echo "Error deleting records: " . $conn->error;
    }

    for ($i = 0; $i < count($Subject_Names); $i++) {
        $Subject_Name = $Subject_Names[$i];
        $Mark = $Marks[$i];

        $sql = "INSERT INTO crud (Reg_Number, Student_Name, Subject_Name, Mark) VALUES ('$Reg_Number', '$Student_Name', '$Subject_Name', '$Mark')";

        if ($conn->query($sql) === TRUE) {
            // Record inserted successfully
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    header("Location: index.php");
    exit();
}

if (isset($_GET['reg_number'])) {
    $reg_number = $_GET['reg_number'];

    $sql = "SELECT * FROM crud WHERE Reg_Number = '$reg_number'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $Student_Name = $row['Student_Name'];

        $subject_marks = [];
        $sql_subjects = "SELECT Subject_Name, Mark FROM crud WHERE Reg_Number = '$reg_number'";
        $result_subjects = $conn->query($sql_subjects);
        if ($result_subjects->num_rows > 0) {
            while ($subject_row = $result_subjects->fetch_assoc()) {
                $subject_marks[] = $subject_row;
            }
        } else {
            echo "No subjects found for this student.";
        }
    } else {
        echo "Student not found.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Crud Application - Update</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h1>Update Student Record</h1>
                </div>
                <div class="card-body">
                    <form action="update.php" method="post">
                        <input type="hidden" name="Reg_Number" value="<?= $reg_number ?>">
                        <div class="form-group">
                            <label>Student Name</label>
                            <input type="text" name="Student_Name" class="form-control" value="<?= isset($Student_Name) ? $Student_Name : '' ?>" required>
                        </div>
                        <div id="subjectMarkContainer">
                            <?php foreach ($subject_marks as $index => $subject_mark): ?>
                                <div class="form-group row mb-2">
                                    <div class="col-sm-5">
                                        <input type="text" name="Subject_Name[]" class="form-control" value="<?= $subject_mark['Subject_Name'] ?>" placeholder="Enter Subject Name">
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" name="Mark[]" class="form-control" value="<?= $subject_mark['Mark'] ?>" placeholder="Enter Mark">
                                    </div>
                                    <?php if ($index === 0): ?>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-success" onclick="addSubjectMarkFields()">+</button>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-danger" onclick="removeSubjectMarkFields(this)">-</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <br/>
                        <input type="submit" class="btn btn-primary" name="submit" value="Update">
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function addSubjectMarkFields() {
        var container = document.getElementById('subjectMarkContainer');
        var div = document.createElement('div');
        div.className = 'form-group row mb-2';

        div.innerHTML = `
            <div class="col-sm-5">
                <input type="text" name="Subject_Name[]" class="form-control" placeholder="Enter Subject Name">
            </div>
            <div class="col-sm-5">
                <input type="text" name="Mark[]" class="form-control" placeholder="Enter Mark">
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger" onclick="removeSubjectMarkFields(this)">-</button>
            </div>
        `;
        container.appendChild(div);
    }

    function removeSubjectMarkFields(button) {
        var container = document.getElementById('subjectMarkContainer');
        container.removeChild(button.parentElement.parentElement);
    }
</script>
</body>
</html>
