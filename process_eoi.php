<?php
    require_once 'settings.php';
    session_start();

    $conn = mysqli_connect($host, $user, $pwd, $sql_db);

    if (isset($_POST['save_record'])) {
        
        $jobref = $_SESSION['jobref'];
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

        foreach ($state as $st) {
            $state_selected = $st;
        }
        

        foreach ($skills as $skill) {
            $skills_str = implode(", ", $skill);
            $sql_insert = "INSERT INTO eoi (EOInumber, jobref, Fname, Lname, street, suburb/town, state, postcode, email, phone, skills, otherskills)
                           VALUES (NULL, '$jobref', '$firstname', '$lastname', '$street', '$suburb', '$state_selected', '$postcode', '$email', '$phone', '$skills_str', '$otherskills')";
            $result = mysqli_query($conn, $sql_insert);
            

            if ($result) {
                echo "Record inserted successfully.";
            } else {
                echo "Error inserting record: " . mysqli_error($conn);
            }
        }
    }
?>