<?php
    require_once('settings.php');
    session_start();

    $conn = mysqli_connect($host, $user, $pwd, $sql_db);

    if (isset($_POST['save_record'])) {
    
        $jobref = $_POST['reflist'];
        $firstname = $_POST['first'];
        $lastname = $_POST['last'];
        $street = $_POST['strtadd'];
        $suburb = $_POST['subtown'];
        $state = $_POST['statelist'];
        $postcode = $_POST['postalcode'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $skills = $_POST['skills'];
        $otherskills = $_POST['skill'];

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