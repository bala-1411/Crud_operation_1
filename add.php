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

    $Reg_Number = mysqli_real_escape_string($conn, $Reg_Number);
    $Student_Name = mysqli_real_escape_string($conn, $Student_Name);

    $stmt = $conn->prepare("INSERT INTO crud (Reg_Number, Student_Name, Subject_Name, Mark) VALUES (?, ?, ?, ?)");

    $stmt->bind_param("sssi", $Reg_Number, $Student_Name, $subject_name, $mark);

    for ($i = 0; $i < count($Subject_Names); $i++) {
        $subject_name = $Subject_Names[$i];
        $mark = $Marks[$i];

        if ($stmt->execute()) {
            // Record inserted successfully
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();

    echo "<script>alert('Registration successful.'); window.location = 'index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Crud Application - Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script>
        function addSubjectMarkFields() {
            var container = document.getElementById('subjectMarkContainer');
            var div = document.createElement('div');
            div.className = 'form-group row mb-2';

            div.innerHTML = `
                <label for="Subject_Name[]" class="col-sm-2 col-form-label">Subject Name</label>
                <div class="col-sm-4">
                    <input type="text" name="Subject_Name[]" class="form-control" placeholder="Enter Subject Name" required>
                </div>
                <label for="Mark[]" class="col-sm-1 col-form-label">Mark</label>
                <div class="col-sm-3">
                    <input type="text" name="Mark[]" class="form-control" placeholder="Enter Mark" required>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-success" onclick="addSubjectMarkFields()">+</button>
                    <button type="button" class="btn btn-danger" disabled>-</button>
                </div>
            `;
            container.appendChild(div);

            var previousSet = container.lastElementChild.previousElementSibling;
            if (previousSet) {
                var removeButton = previousSet.querySelector('.btn-danger');
                if (removeButton) {
                    removeButton.removeAttribute('disabled');
                }
            }
        }

        function removeSubjectMarkFields(button) {
            var container = document.getElementById('subjectMarkContainer');
            if (container.childElementCount > 1) {
                container.removeChild(button.parentElement.parentElement);
            } else {
                alert('At least one subject is required.');
            }

            if (container.childElementCount === 1) {
                var singleSet = container.firstElementChild;
                var removeButton = singleSet.querySelector('.btn-danger');
                if (removeButton) {
                    removeButton.setAttribute('disabled', 'disabled');
                }
            }
        }

        function validateForm() {
            var subjectInputs = document.getElementsByName('Subject_Name[]');
            var markInputs = document.getElementsByName('Mark[]');
            var regNumberInput = document.getElementsByName('Reg_Number')[0];
            var studentNameInput = document.getElementsByName('Student_Name')[0];

            if (!/^\d+$/.test(regNumberInput.value.trim())) {
                alert('Registration Number must be a number.');
                return false;
            }

            if (!/^[\w\s]+$/.test(studentNameInput.value.trim())) {
                alert('Student Name should not have special characters except space.');
                return false;
            }

            for (var i = 0; i < markInputs.length; i++) {
                if (!/^\d+$/.test(markInputs[i].value.trim())) {
                    alert('Mark should be a number.');
                    return false;
                }
            }

            for (var i = 0; i < subjectInputs.length; i++) {
                if (subjectInputs[i].value.trim() === '' || markInputs[i].value.trim() === '') {
                    alert('Please fill in all subject and mark fields.');
                    return false;
                }
            }

            return true;
        }
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h1>Student Crud Application - Add Student</h1>
                </div>
                <div class="card-body">
                    <form action="add.php" method="post" onsubmit="return validateForm()">
                        <div class="form-group">
                            <label for="Reg_Number">Registration Number</label>
                            <input type="text" name="Reg_Number" class="form-control" placeholder="Enter Registration Number" required pattern="\d+">
                        </div>
                        <div class="form-group">
                            <label for="Student_Name">Student Name</label>
                            <input type="text" name="Student_Name" class="form-control" placeholder="Enter Student Name" required pattern="[\w\s]+">
                        </div>
                        <div id="subjectMarkContainer">
                            <div class="form-group row mb-2">
                                <label for="Subject_Name[]" class="col-sm-2 col-form-label">Subject Name</label>
                                <div class="col-sm-4">
                                    <input type="text" name="Subject_Name[]" class="form-control" placeholder="Enter Subject Name" required>
                                </div>
                                <label for="Mark[]" class="col-sm-1 col-form-label">Mark</label>
                                <div class="col-sm-3">
                                    <input type="text" name="Mark[]" class="form-control" placeholder="Enter Mark" required>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-success" onclick="addSubjectMarkFields()">+</button>
                                    <button type="button" class="btn btn-danger" disabled>-</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary" name="submit">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
