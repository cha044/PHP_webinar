<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Registration</title>
    </head>
    <body>
        <h2>User Registration Form</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="firstName">First Name:</label><br>
            <input type="text" id="firstName" name="firstName" required><br>
            <label for="lastName">Last Name:</label><br>
            <input type="text" id="lastName" name="lastName" required><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="source">Source:</label><br>
            <input type="text" id="source" name="source"><br>
            <button type="submit">Register</button>
        </form>

    <?php
        require_once "conn.php"; 
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $email = $_POST["email"];
            $source = $_POST["source"];
        
            if (empty($firstName) || empty($lastName) || empty($email)) {
                echo "Error: Please fill in all required fields.";
                exit;
            }
        
            $sql = "INSERT INTO users (firstName, lastName, email, source) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $firstName, $lastName, $email, $source);
        
            if (!$stmt->execute()) {
                echo "Error: Unable to save user data to database.";
                exit;
            }
        
            $organizerKey = 'YOUR_ORGANIZER_KEY';
            $webinarKey = 'YOUR_WEBINAR_KEY';
            $registerUrl = "https://api.getgo.com/G2W/rest/v2/organizers/$organizerKey/webinars/$webinarKey/registrants";
            $accessToken = 'YOUR_ACCESS_TOKEN';
        
            $data = array(
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'source' => $source
            );
        
            $jsonData = json_encode($data);
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $registerUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Authorization: Bearer $accessToken"
            ));
        
            $response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        
            if ($statusCode == 201) {
                echo "Success: User registered for the webinar!";
            } else {
                echo "Error: Failed to register user for the webinar. HTTP Status Code: $statusCode";
            }
        } else {
            header("Location: register_user.php");
            exit;
        }
    ?>

    </body>
</html>
