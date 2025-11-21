<!-- Author: Luu Tri Khoa Tung, Kenzie Duong Nguyen -->

<?php
session_start();
require_once('settings.php');

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login-register.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Prevent direct access - redirect if form wasn't submitted
if (!isset($_POST['save_record'])) {
    header("Location: apply.php");
    exit();
}

// Check if user is logged in
// $user_id = getCurrentUserId();

// Connect to database
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create EOI table if it doesn't exist
$createTableQuery = "CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    jobref VARCHAR(10) NOT NULL,
    Fname VARCHAR(20) NOT NULL,
    Lname VARCHAR(20) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    street VARCHAR(40) NOT NULL,
    suburbtown VARCHAR(40) NOT NULL,
    state VARCHAR(3) NOT NULL,
    postcode VARCHAR(4) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(12) NOT NULL,
    skills TEXT NOT NULL,
    otherskills TEXT,
    status ENUM('New', 'Under Review', 'Interview Scheduled', 'Accepted', 'Rejected') DEFAULT 'New',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_jobref (jobref),
    INDEX idx_status (status)
)";

mysqli_query($conn, $createTableQuery);

// Initialize error array
$errors = [];

// Sanitization function
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate and sanitize Job Reference
if (empty($_POST['reflist']) || !is_array($_POST['reflist'])) {
    $errors[] = "Job reference number is required.";
} else {
    $jobref = sanitize_input($_POST['reflist'][0]);
    $valid_refs = ['SE24A', 'DA24B', 'ML24C', 'CE24D'];
    if (!in_array($jobref, $valid_refs)) {
        $errors[] = "Invalid job reference number.";
    }
}

// Validate First Name
if (empty($_POST['first'])) {
    $errors[] = "First name is required.";
} else {
    $firstname = sanitize_input($_POST['first']);
    if (!preg_match("/^[A-Za-z]{1,20}$/", $firstname)) {
        $errors[] = "First name must be 1-20 alphabetical characters only.";
    }
}

// Validate Last Name
if (empty($_POST['last'])) {
    $errors[] = "Last name is required.";
} else {
    $lastname = sanitize_input($_POST['last']);
    if (!preg_match("/^[A-Za-z]{1,20}$/", $lastname)) {
        $errors[] = "Last name must be 1-20 alphabetical characters only.";
    }
}

// Validate Date of Birth
if (empty($_POST['dob'])) {
    $errors[] = "Date of birth is required.";
} else {
    $dob = sanitize_input($_POST['dob']);
    if (!preg_match("/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/", $dob)) {
        $errors[] = "Date of birth must be in dd/mm/yyyy format.";
    } else {
        // Convert to MySQL date format and validate
        $dob_parts = explode('/', $dob);
        $dob_mysql = $dob_parts[2] . '-' . $dob_parts[1] . '-' . $dob_parts[0];
        
        // Check if date is valid
        if (!checkdate($dob_parts[1], $dob_parts[0], $dob_parts[2])) {
            $errors[] = "Invalid date of birth.";
        }
        
        // Check age (between 15 and 80 years old)
        $birth_date = new DateTime($dob_mysql);
        $today = new DateTime();
        $age = $today->diff($birth_date)->y;
        
        if ($age < 15 || $age > 80) {
            $errors[] = "Age must be between 15 and 80 years.";
        }
    }
}

// Validate Gender
if (empty($_POST['gender'])) {
    $errors[] = "Gender is required.";
} else {
    $gender = sanitize_input($_POST['gender']);
    // if (!in_array($gender, ['male', 'female'])) {
    //     $errors[] = "Invalid gender selection.";
    // }
}

// Validate Street Address
if (empty($_POST['strtadd'])) {
    $errors[] = "Street address is required.";
} else {
    $street = sanitize_input($_POST['strtadd']);
    if (strlen($street) > 40) {
        $errors[] = "Street address must be maximum 40 characters.";
    }
}

// Validate Suburb/Town
if (empty($_POST['subtown'])) {
    $errors[] = "Suburb/town is required.";
} else {
    $suburb = sanitize_input($_POST['subtown']);
    if (strlen($suburb) > 40) {
        $errors[] = "Suburb/town must be maximum 40 characters.";
    }
}

// Validate State
if (empty($_POST['statelist']) || !is_array($_POST['statelist'])) {
    $errors[] = "State is required.";
} else {
    $state = sanitize_input($_POST['statelist'][0]);
    $valid_states = ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'];
    if (!in_array($state, $valid_states)) {
        $errors[] = "Invalid state selection.";
    }
}

// Validate Postcode
if (empty($_POST['postalcode'])) {
    $errors[] = "Postcode is required.";
} else {
    $postcode = sanitize_input($_POST['postalcode']);
    if (!preg_match("/^[0-9]{4}$/", $postcode)) {
        $errors[] = "Postcode must be exactly 4 digits.";
    // } else {
    //     // Validate postcode matches state
    //     $postcode_ranges = [
    //         'VIC' => [3000, 3999, 8000, 8999],
    //         'NSW' => [1000, 2999],
    //         'QLD' => [4000, 4999, 9000, 9999],
    //         'NT' => [800, 899],
    //         'WA' => [6000, 6999],
    //         'SA' => [5000, 5999],
    //         'TAS' => [7000, 7999],
    //         'ACT' => [200, 299, 2600, 2618]
    //     ];
        
        if (isset($state) && isset($postcode_ranges[$state])) {
            $valid_postcode = false;
            $ranges = $postcode_ranges[$state];
            
            if (count($ranges) == 2) {
                if ($postcode >= $ranges[0] && $postcode <= $ranges[1]) {
                    $valid_postcode = true;
                }
            } else {
                if (($postcode >= $ranges[0] && $postcode <= $ranges[1]) || 
                    ($postcode >= $ranges[2] && $postcode <= $ranges[3])) {
                    $valid_postcode = true;
                }
            }
            
            if (!$valid_postcode) {
                $errors[] = "Postcode does not match selected state.";
            }
        }
    }
}

// Validate Email
if (empty($_POST['email'])) {
    $errors[] = "Email address is required.";
} else {
    $email = sanitize_input($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address format.";
    }
}

// Validate Phone Number
if (empty($_POST['phone'])) {
    $errors[] = "Phone number is required.";
} else {
    $phone = sanitize_input($_POST['phone']);
    if (!preg_match("/^[0-9 ]{8,12}$/", $phone)) {
        $errors[] = "Phone number must be 8-12 digits or spaces.";
    }
}

// Validate Skills
if (empty($_POST['skills']) || !is_array($_POST['skills'])) {
    $errors[] = "At least one technical skill is required.";
} else {
    $valid_skills = ['python_programming_language', 'data_science', 'cyber_security', 
                     'project_management', 'software_development', 'technical_writing'];
    $selected_skills = [];
    
    foreach ($_POST['skills'] as $skill) {
        $skill = sanitize_input($skill);
        if (in_array($skill, $valid_skills)) {
            $selected_skills[] = $skill;
        }
    }
    
    if (empty($selected_skills)) {
        $errors[] = "Invalid skill selection.";
    } else {
        $skills_select = implode(", ", $selected_skills);
    }
}

// Validate Other Skills
$otherskills = "";
if (isset($_POST['skill'])) {
    $otherskills = sanitize_input($_POST['skill']);
}

// If there are errors, display them
if (!empty($errors)) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Application Error</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                padding: 20px;
                max-width: 800px;
                margin: 0 auto;
            }
            .error-container {
                background-color: white;
                border-radius: 10px;
                padding: 30px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            h1 {
                color: #d32f2f;
                border-bottom: 3px solid #d32f2f;
                padding-bottom: 10px;
            }
            ul {
                list-style-type: none;
                padding: 0;
            }
            li {
                background-color: #ffebee;
                margin: 10px 0;
                padding: 15px;
                border-left: 4px solid #d32f2f;
                border-radius: 5px;
            }
            .back-button {
                display: inline-block;
                margin-top: 20px;
                padding: 12px 30px;
                background-color: #3f51b5;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s;
            }
            .back-button:hover {
                background-color: #303f9f;
            }
        </style>
    </head>
    <body>
        <div class='error-container'>
            <h1>⚠️ Application Errors</h1>
            <p>Please correct the following errors and resubmit your application:</p>
            <ul>";
    
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    
    echo "</ul>
            <a href='apply.php' class='back-button'>← Go Back to Application Form</a>
        </div>
    </body>
    </html>";
    
    mysqli_close($conn);
    exit();
}

// If no errors, insert into database using prepared statement
$query = "INSERT INTO eoi (user_id, jobref, Fname, Lname, dob, gender, street, suburbtown, state, postcode, email, phone, skills, otherskills) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "isssssssssssss", 
    $user_id, $jobref, $firstname, $lastname, $dob_mysql, $gender, 
    $street, $suburb, $state, $postcode, $email, $phone, 
    $skills_select, $otherskills);

if (mysqli_stmt_execute($stmt)) {
    $eonumber = mysqli_insert_id($conn);
    
    // Display success page
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Application Successful</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: linear-gradient(135deg, #e2e2e2, #b7c7fd);
                padding: 20px;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .success-container {
                background-color: white;
                border-radius: 15px;
                padding: 40px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.3);
                max-width: 600px;
                text-align: center;
            }
            .success-icon {
                font-size: 80px;
                margin-bottom: 20px;
            }
            h1 {
                color: #39a13dff;
                margin-bottom: 15px;
            }
            .eo-number {
                background-color: #e8f5e9;
                padding: 20px;
                border-radius: 10px;
                margin: 20px 0;
                border: 2px solid #4caf50;
            }
            .eo-number strong {
                font-size: 24px;
                color: #0a0a0aff;
            }
            p {
                color: #666;
                line-height: 1.6;
                margin: 15px 0;
            }
            .button-group {
                margin-top: 30px;
                display: flex;
                gap: 15px;
                justify-content: center;
            }
            .button {
                display: inline-block;
                padding: 12px 30px;
                text-decoration: none;
                border-radius: 5px;
                transition: all 0.3s;
                font-weight: 600;
            }
                
            .home-button {
                background-color: #3f51b5;
                color: white;
            }
            .home-button:hover {
                background-color: #303f9f;
                transform: translateY(-2px);
            }
            .jobs-button {
                background-color: white;
                color: #3f51b5;
                border: 2px solid #3f51b5;
            }
            .jobs-button:hover {
                background-color: #f5f5f5;
                transform: translateY(-2px);
            }

            .dashboard-link {
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class='success-container'>
            <div class='success-icon'>✅</div>
            <h1>Application Submitted Successfully!</h1>
            <p>Thank you for your interest in joining ePass!</p>
            
            <div class='eo-number'>
                <p>Your Expression of Interest Number is:</p>
                <strong>EOI-" . str_pad($eonumber, 6, '0', STR_PAD_LEFT) . "</strong>
            </div>
            
            <p>Please save this number for your records. You will need it to track your application status.</p>
            <p>We will review your application and contact you within 5-7 business days.</p>
            
            <div class='button-group'>

                <a href='index.php' class='button home-button'>🏠 Back to Home</a>
                <a href='jobs.php' class='button jobs-button'>💼 View More Jobs</a>
            </div>
            <br>
            <strong><a href='dashboard.php' class='dashboard-link'>← Back to Dashboard</a></strong>

        </div>
    </body>
    </html>";
    
} else {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Database Error</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; }
            .error { background: white; padding: 30px; border-radius: 10px; max-width: 600px; margin: 0 auto; }
            h1 { color: #d32f2f; }
        </style>
    </head>
    <body>
        <div class='error'>
            <h1>❌ Database Error</h1>
            <p>Sorry, there was an error processing your application. Please try again later.</p>
            <a href='apply.php' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background: #3f51b5; color: white; text-decoration: none; border-radius: 5px;'>← Go Back</a>
        </div>
    </body>
    </html>";
    error_log("Database error: " . mysqli_error($conn));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>