<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Coding Exercise</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
        crossorigin="anonymous"></script>
    <style>
        .table-custom {
            background-color: #f8f9fa;
            color: #000; 
        }
        .table-custom th,
        .table-custom td {
            border-color: #dee2e6;
        }
    </style>
</head>

<body>
    <section>
        <h1 style="text-align: center;margin: 50px 0;">PHP Webinars</h1>
        <div class="container">
        <form action="create_webinar.php" method="post">
            <div class="row">
                <div class="form-group col-lg-3">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group col-lg-3">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" class="form-control" required>
                </div>
                <div class="form-group col-lg-2">
                    <label for="start_time">Start Time</label>
                    <input type="datetime-local" name="start_time" id="start_time" class="form-control" required>
                </div>
                <div class="form-group col-lg-2">
                    <label for="end_time">End Time</label>
                    <input type="datetime-local" name="end_time" id="end_time" class="form-control" required>
                </div>
                <div class="form-group col-lg-2" style="display: grid; align-items: flex-end;">
                    <input type="submit" name="submit" id="submit" class="btn btn-primary">
                </div>
            </div>
        </form>

        </div>
    </section>

    <section style="margin: 50px 0;">
        <div class="container">
            <table class="table table-custom">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">EventID</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                  </tr>
                </thead>
                <tbody>
                    <?php 
                        require_once "conn.php";
                        $sql_query = "SELECT * FROM webinar";
                        if ($webinar = $conn->query($sql_query)) {
                            while ($row = $webinar->fetch_assoc()) { 
                                $Id = $row['id'];
                                $Name = $row['name'];
                                $Description = $row['description'];
                                $EventID = $row['event_id'];
                    ?>
                    
                    <tr class="trow">
                        <td><?php echo $Id; ?></td>
                        <td><?php echo $Name; ?></td>
                        <td><?php echo $Description; ?></td>
                        <td><?php echo $EventID; ?></td>
                        <td><a href="update_webinar.php?id=<?php echo $Id; ?>" class="btn btn-primary">Edit</a></td>
                        <td><a href="delete_webinar.php?webinar_key=<?php echo $row['webinar_key']; ?>" class="btn btn-danger">Delete</a></td>
                    </tr>

                    <?php
                            } 
                        } 
                    ?>
                </tbody>
              </table>
        </div>
    </section>
</body>

</html>
