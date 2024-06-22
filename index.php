<?php
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "test_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM crud";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reg_number = $row["Reg_Number"];
        if (!isset($data[$reg_number])) {
            $data[$reg_number] = [
                "Reg_Number" => $reg_number,
                "Student_Name" => $row["Student_Name"],
                "Subjects" => [],
                "Marks" => []
            ];
        }
        $data[$reg_number]["Subjects"][] = $row["Subject_Name"];
        $data[$reg_number]["Marks"][] = $row["Mark"];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Crud Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card mt-5">
                    <div class="card-header">
                        <h1 class="text-center">Student Crud Application</h1>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-success mb-3">
                            <a href="add.php" class="text-light">ADD</a>
                        </button>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Reg_Number</th>
                                        <th scope="col">Student_Name</th>
                                        <?php
                                        $maxSubjects = 0;
                                        foreach ($data as $row) {
                                            $maxSubjects = max($maxSubjects, count($row["Subjects"]));
                                        }
                                        for ($i = 1; $i <= $maxSubjects; $i++) {
                                            echo "<th scope=\"col\">Subject $i</th>";
                                            echo "<th scope=\"col\">Mark $i</th>";
                                        }
                                        ?>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $row) : ?>
                                        <tr>
                                            <td><?= $row["Reg_Number"] ?></td>
                                            <td><?= $row["Student_Name"] ?></td>
                                            <?php
                                            for ($i = 0; $i < $maxSubjects; $i++) {
                                                if (isset($row["Subjects"][$i]) && isset($row["Marks"][$i])) {
                                                    echo "<td>{$row["Subjects"][$i]}</td>";
                                                    echo "<td>{$row["Marks"][$i]}</td>";
                                                } else {
                                                    echo "<td>-</td><td>-</td>";
                                                }
                                            }
                                            ?>
                                            <td>
                                                <button class="btn btn-success">
                                                    <a href="update.php?reg_number=<?= $row["Reg_Number"] ?>"
                                                        class="text-light">Update</a>
                                                </button>
                                                <form action="delete.php" method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="reg_number"
                                                        value="<?= $row["Reg_Number"] ?>">
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
