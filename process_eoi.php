<?php
    session_start();
    require_once('settings.php');

    function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Prevent direct access - redirect if form wasn't submitted
    if (!isset($_POST['save_record'])) {
    header("Location: apply.php");
    exit();
}


    $conn = mysqli_connect($host, $user, $pwd, $sql_db);

    if (isset($_POST['save_record'])) {
    
        $jobref = $_POST['reflist'];
        $firstname = sanitizeInput($_POST['first']);
        $lastname = sanitizeInput($_POST['last']);
        $street = sanitizeInput($_POST['strtadd']);
        $suburb = sanitizeInput($_POST['subtown']);
        $state = $_POST['statelist'];
        $postcode = sanitizeInput($_POST['postalcode']);
        $email = sanitizeInput($_POST['email']);
        $phone = sanitizeInput($_POST['phone']);
        $skills = $_POST['skills'];
        $otherskills = sanitizeInput($_POST['skill']);

        $state_select = implode($state);
        $jobref_select = implode($jobref);
        $skills_select = implode(", ", $skills);

        $query = "INSERT INTO eoi (jobref, Fname, Lname, street, suburbtown, state, postcode, email, phone, skills, otherskills) VALUES ('$jobref_select', '$firstname', '$lastname', '$street', '$suburb', '$state_select', '$postcode', '$email', '$phone', '$skills_select', '$otherskills')";
        $result = mysqli_query($conn, $query);
            
        if ($result) {
            echo "Record inserted successfully.<br><a href='index.php'>Back to Home Page</a>";
        } else {
            echo "Error inserting record: " . mysqli_error($conn);
        }

    }
    mysqli_close($conn);

?>