<?php
    require_once "conn.php";

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $start_time = $_POST['start_time']; 
        $end_time = $_POST['end_time'];     
        $event_id = uniqid();               

        if ($name != "" && $description != "" && $start_time != "" && $end_time != "") {
            if (strtotime($end_time) <= strtotime($start_time)) {
                echo "End time must be after start time!";
                exit;
            }

            $accessToken = 'eyJraWQiOiI2MjAiLCJhbGciOiJSUzUxMiJ9.eyJzYyI6ImNvbGxhYjogaWRlbnRpdHk6c2NpbS5tZSIsInN1YiI6IjgwNzA2NTk4Nzg4MjI3MTU4MTQiLCJhdWQiOiI2MGU5NzgzMy0wNjc2LTRjZDYtOTIyOC00YTY4ZGE2NTRlNjkiLCJvZ24iOiJwd2QiLCJscyI6ImZkZmFkZjlhLTk0MTAtNGUxOS1hNzFjLWJmODI4YTJmODc4MSIsInR5cCI6ImEiLCJleHAiOjE3MTYzMjY4MDQsImlhdCI6MTcxNjMyMzIwNCwianRpIjoiMDNjODYzYmUtZjhlOC00ZDBlLWJhMWItY2ZhYmE1M2VjMTBhIn0.uPY3sis4CjO-0Du5mT1kHYyuVbR7ckclV4Ave1f6vchwN8rO6TKSlqyogbVgKncAgjadDC9V0siMux9Q4CqSLcINoXgpmD2XNQZv9-Bc1oTUvyadxiBdkZZI1-Q-Z2KD34ZUgBXR9ny2BA3fpbbNmJd2eyOnFNiz6fC0EMLOtJQdxRnoPt_Z2zka6B0Y1yRh_3-08B-cI8qh0Su-rgq03v6JjDwLuRtelkEoF9jTko1wtRlcP9EezVB2OSi9G4cddRFAxZkX0ikgVeVtgdEHkNq-td_b7IR7ruPbky8YRbJ1RxJ6jD7QX4ot0A37hqp2-DUoZD_cFkNc1DFjrWkxsw';
            $organizerKey = '8070659878822715814';
            $apiUrl = "https://api.getgo.com/G2W/rest/v2/organizers/{$organizerKey}/webinars";

            $data = [
                "subject" => $name,
                "description" => $description,
                "times" => [
                    [
                        "startTime" => date('Y-m-d\TH:i:s\Z', strtotime($start_time)),
                        "endTime" => date('Y-m-d\TH:i:s\Z', strtotime($end_time))
                    ]
                ],
                "timeZone" => "America/New_York",
                "eventId" => $event_id
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
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
                    if (isset($responseData['webinarKey'])) {
                        $webinarKey = $responseData['webinarKey'];
                        $sql = "INSERT INTO webinar (`name`, `description`, `start_time`, `end_time`, `event_id`, `webinar_key`) VALUES ('$name', '$description', '$start_time', '$end_time', '$event_id', '$webinarKey')";
                        if (mysqli_query($conn, $sql)) {
                            header("Location: index.php");
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Error: Webinar key not found in the response.";
                    }
                } else {
                    echo "Error: Failed to create webinar on GoToWebinar. Response: " . $response;
                }
            }
            curl_close($ch);
        } else {
            echo "All fields are required!";
        }
    }
?>
