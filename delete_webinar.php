<?php
    require_once "conn.php";

    $organizerKey = '8070659878822715814'; 
    $webinarKey = isset($_GET["webinar_key"]) ? $_GET["webinar_key"] : null;

    if (!$webinarKey) {
        echo "Error: Webinar key is missing.";
        exit;
    }

    echo "Webinar Key: $webinarKey"; 

    $deleteUrl = "https://api.getgo.com/G2W/rest/v2/organizers/$organizerKey/webinars/$webinarKey";

    $accessToken = 'eyJraWQiOiI2MjAiLCJhbGciOiJSUzUxMiJ9.eyJzYyI6ImNvbGxhYjogaWRlbnRpdHk6c2NpbS5tZSIsInN1YiI6IjgwNzA2NTk4Nzg4MjI3MTU4MTQiLCJhdWQiOiI2MGU5NzgzMy0wNjc2LTRjZDYtOTIyOC00YTY4ZGE2NTRlNjkiLCJvZ24iOiJwd2QiLCJscyI6ImZkZmFkZjlhLTk0MTAtNGUxOS1hNzFjLWJmODI4YTJmODc4MSIsInR5cCI6ImEiLCJleHAiOjE3MTYzMjY4MDQsImlhdCI6MTcxNjMyMzIwNCwianRpIjoiMDNjODYzYmUtZjhlOC00ZDBlLWJhMWItY2ZhYmE1M2VjMTBhIn0.uPY3sis4CjO-0Du5mT1kHYyuVbR7ckclV4Ave1f6vchwN8rO6TKSlqyogbVgKncAgjadDC9V0siMux9Q4CqSLcINoXgpmD2XNQZv9-Bc1oTUvyadxiBdkZZI1-Q-Z2KD34ZUgBXR9ny2BA3fpbbNmJd2eyOnFNiz6fC0EMLOtJQdxRnoPt_Z2zka6B0Y1yRh_3-08B-cI8qh0Su-rgq03v6JjDwLuRtelkEoF9jTko1wtRlcP9EezVB2OSi9G4cddRFAxZkX0ikgVeVtgdEHkNq-td_b7IR7ruPbky8YRbJ1RxJ6jD7QX4ot0A37hqp2-DUoZD_cFkNc1DFjrWkxsw';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $deleteUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken" 
    ));

    $response = curl_exec($ch);

    if ($response === false) {
        echo "Error: " . curl_error($ch);
    } else {
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($statusCode == 204) {
            header("Location: index.php"); 
            exit;
        } elseif ($statusCode == 404) {
            echo "Error: Webinar not found. HTTP Status Code: $statusCode";
            echo "\nWebinar Key: $webinarKey";
            echo "\nOrganizer Key: $organizerKey";
        } else {
            echo "Error: Failed to delete webinar. HTTP Status Code: $statusCode";
            echo "\nResponse: " . $response;
        }
    }

    curl_close($ch);
?>
