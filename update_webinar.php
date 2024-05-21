<?php
    require_once "conn.php";

    if (isset($_POST["name"]) && isset($_POST["description"]) && isset($_POST["start_time"]) && isset($_POST["end_time"])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $organizerKey = '8070659878822715814';

        $sql = "SELECT webinar_key FROM webinar WHERE id=" . $_GET["id"];
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $webinarKey = $row['webinar_key'];

            if (empty($webinarKey)) {
                echo "Webinar key not found in the database.";
                exit;
            }

            $accessToken = 'eyJraWQiOiI2MjAiLCJhbGciOiJSUzUxMiJ9.eyJzYyI6ImNvbGxhYjogaWRlbnRpdHk6c2NpbS5tZSIsInN1YiI6IjgwNzA2NTk4Nzg4MjI3MTU4MTQiLCJhdWQiOiI2MGU5NzgzMy0wNjc2LTRjZDYtOTIyOC00YTY4ZGE2NTRlNjkiLCJvZ24iOiJwd2QiLCJscyI6ImZkZmFkZjlhLTk0MTAtNGUxOS1hNzFjLWJmODI4YTJmODc4MSIsInR5cCI6ImEiLCJleHAiOjE3MTYzMjY4MDQsImlhdCI6MTcxNjMyMzIwNCwianRpIjoiMDNjODYzYmUtZjhlOC00ZDBlLWJhMWItY2ZhYmE1M2VjMTBhIn0.uPY3sis4CjO-0Du5mT1kHYyuVbR7ckclV4Ave1f6vchwN8rO6TKSlqyogbVgKncAgjadDC9V0siMux9Q4CqSLcINoXgpmD2XNQZv9-Bc1oTUvyadxiBdkZZI1-Q-Z2KD34ZUgBXR9ny2BA3fpbbNmJd2eyOnFNiz6fC0EMLOtJQdxRnoPt_Z2zka6B0Y1yRh_3-08B-cI8qh0Su-rgq03v6JjDwLuRtelkEoF9jTko1wtRlcP9EezVB2OSi9G4cddRFAxZkX0ikgVeVtgdEHkNq-td_b7IR7ruPbky8YRbJ1RxJ6jD7QX4ot0A37hqp2-DUoZD_cFkNc1DFjrWkxsw'; 
            $apiUrl = "https://api.getgo.com/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}";

            $data = [
                "subject" => $name,
                "description" => $description,
                "times" => [
                    [
                        "startTime" => date('Y-m-d\TH:i:s\Z', strtotime($start_time)),
                        "endTime" => date('Y-m-d\TH:i:s\Z', strtotime($end_time))
                    ]
                ],
                "timeZone" => "America/New_York"
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
                'Accept: application/json',
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
            } else {
                $responseData = json_decode($response, true);
                if ($httpCode >= 200 && $httpCode < 300) {
                    $sql = "UPDATE webinar SET `name`= '$name', `description`= '$description', `start_time`= '$start_time', `end_time`= '$end_time' WHERE id=" . $_GET["id"];
                    if (mysqli_query($conn, $sql)) {
                        header("location: index.php");
                    } else {
                        echo "Error updating local database: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error updating GoToWebinar: " . $response;
                }
            }
            curl_close($ch);
        } else {
            echo "Webinar not found.";
        }
    } else {
        echo "All fields are required!";
    }
?>



<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <section>
            <h1 style="text-align: center;margin: 50px 0;">Update Webinar</h1>
            <div class="container">
                <?php 
                    $sql_query = "SELECT * FROM webinar WHERE id = " . $_GET["id"];
                    if ($webinar = $conn->query($sql_query)) {
                        while ($row = $webinar->fetch_assoc()) { 
                            $Id = $row['id'];
                            $Name = $row['name'];
                            $Description = $row['description'];
                            $StartTime = $row['start_time'];
                            $EndTime = $row['end_time'];
                ?>
                                <form action="update_webinar.php?id=<?php echo $Id; ?>" method="post">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label for="">Name</label>
                                            <input type="text" name="name" id="name" class="form-control" value="<?php echo $Name ?>" required>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label for="">Description</label>
                                            <input type="text" name="description" id="description" class="form-control" value="<?php echo $Description ?>" required>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="">Start Time</label>
                                            <input type="datetime-local" name="start_time" id="start_time" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($StartTime)) ?>" required>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="">End Time</label>
                                            <input type="datetime-local" name="end_time" id="end_time" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($EndTime)) ?>" required>
                                        </div>
                                        <div class="form-group col-lg-1" style="display: grid;align-items: flex-end;">
                                            <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Update">
                                        </div>
                                    </div>
                                </form>
                <?php 
                        }
                    }
                ?>
            </div>
        </section>
    </body>
</html>
