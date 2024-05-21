<?php
    $accessToken = 'eyJraWQiOiI2MjAiLCJhbGciOiJSUzUxMiJ9.eyJzYyI6ImNvbGxhYjogaWRlbnRpdHk6c2NpbS5tZSIsInN1YiI6IjgwNzA2NTk4Nzg4MjI3MTU4MTQiLCJhdWQiOiJmYWQ4NjA3NS01OWEwLTQyYjQtOGNlOS05YTE4NjBlZDM2NGIiLCJvZ24iOiJwd2QiLCJscyI6ImZkZmFkZjlhLTk0MTAtNGUxOS1hNzFjLWJmODI4YTJmODc4MSIsInR5cCI6ImEiLCJleHAiOjE3MTYzMTc5MjksImlhdCI6MTcxNjMxNDMyOSwianRpIjoiNjk3YzYzYWMtZTIzZS00NWJkLTk1YjEtMTgxM2M4NTBhZDczIn0.IgZMA2rJzAEAIFA9P95QPkAi_elonsKGosc0yGehgK2l2TIFQlt3AJSQgjSr0qku92puOH2hiAg54L2NevnR_NweeGwf_CKO4ALV5qa7zIpD6dMFbEUTVUbHRkg7c6XFGgmpKNWTu7iMW4C0F_HQZQa9-UtM5kJ3mhT500E_3nkJN6Za1sBPbxHNd8Oq4wZN1WajkjVhF8jBPjc_TlbhA0JY8IIf0fzj1zGT45KA8uQouAND4YlIAt4hui8i2FQvUzF0BjS5H54KJ41PuQd_Ou3wvGtftjx0EBgxoYmPtXXtbr9TZniSUbk4m_DubZXVfv7SyE-PYMG7-9Sn79Mopg'; // Replace with your actual access token
    $url = 'https://api.getgo.com/identity/v1/Users/me';

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Authorization: Bearer ' . $accessToken
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        die('Error occurred: ' . $error);
    }

    curl_close($ch);

    $responseData = json_decode($response, true);
    if (isset($responseData['organizer_key'])) {
        $organizerKey = $responseData['organizer_key'];
        echo 'Organizer Key: ' . $organizerKey . '<br>';
    } else {
        echo 'Organizer key not found in response';
    }
?>
