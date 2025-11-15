<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'header.inc'; ?>
    <meta name="keywords" content="HTML, Form, Jobs">
    <meta name="author" content="Luu Tri Khoa Tung">
    <title>Job Application Page</title>
    <link rel="stylesheet" href="styles/apply.css">

</head>
<body>

    <?php include 'nav.inc'; ?>
    <header>
        <h1>Career Opportunities in Australia</h1>
        <p>Explore roles that shape the future of technology.</p>
    </header>

    <!-- application form -->
    <br>

    <section id="apply-form">
        <form method="post" action="process_eoi.php" novalidate="novalidate">
            <h2>Application form</h2>
            <section id="job-reference">
                <label for="refnum">Job Reference Numbers</label>
                <select name="reflist[]" id="refnum">
                    <option value="SE24A">SE24A (Software Engineer)</option>
                    <option value="DA24B">DA24B (Data Analyst)</option>
                    <option value="ML24C">ML24C (AIML Engineer)</option>
                    <option value="CE24D">CE24D (Cloud Engineer)</option>
                </select>
            </section>
            <section id="general">
                <section id="name">
                    <section id="first-name">
                        <label for="fname">First Name</label>
                        <input type="text" name="first" id="fname" required pattern="[A-Za-z]{1,20}"
                        maxLength="20"
                        title="Maximum of 20 alphabetical characters">
                    </section>
                    <section id="last-name">
                        <label for="lname">Last Name</label>
                        <input type="text" name="last" id="lname" required pattern="[A-Za-z]{1,20}"
                        maxLength="20"
                        title="Maximum of 20 alphabetical characters">
                    </section>
                </section>
                <section id="general-info">
                    <section id="date-of-birth">
                        <label for="dob">Date of Birth</label>
                        <input type="text" name="dob" id="dob" required pattern="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|[1][0-2])/\d{4}"
                        maxLength="10" placeholder="dd/mm/yyyy">
                    </section>
                    <section id="gender">
                        <label for="gender">Gender</label>
                        <section id="gender-radio">
                            <input type="radio" name="gender" id="male" required="required"><label for="male">Male</label>
                            <input type="radio" name="gender" id="female"><label for="female">Female</label>
                        </section>
                    </section>
                </section>
                <section id="address">
                    <section id="street-address">
                        <label for="street">Street Address</label>
                        <input type="text" name="strtadd" id="street" required pattern=".{1,40}"
                        maxLength="40"
                        title="Maximum of 40 alphabetical characters">
                    </section>
                    <section id="suburb-town">
                        <label for="town">Suburb/town</label>
                        <input type="text" name="subtown" id="town" required pattern=".{1,40}"
                        maxLength="40"
                        title="Maximum of 40 alphabetical characters">
                    </section>
                </section>
                <section id="state-general">
                    <section id="state-selection">
                        <label for="state">State</label>
                        <select name="statelist[]" id="state">
                            <option value="VIC">VIC (Victoria)</option>
                            <option value="NSW">NSW (New South Wales)</option>
                            <option value="QLD">QLD (Queensland)</option>
                            <option value="NT">NT (Northern Territory)</option>
                            <option value="WA">WA (Western Australia)</option>
                            <option value="SA">SA (South Australia)</option>
                            <option value="TAS">TAS (Tasmania)</option>
                            <option value="ACT">ACT (Australian Capital Territory)</option>
                        </select>
                    </section>
                    <section id="post-code">
                        <label for="pcode">Postcode</label>
                        <input type="text" name="postalcode" id="pcode" required pattern="[0-9]{4}"
                        maxLength="4" title="Maximum of 4 digits">
                    </section>
                </section>
                <section id="contact">
                    <section id="email-address">
                        <label for="mail">Email Address</label>
                        <input type="text" name="email" id="mail" required pattern="^[A-Za-z0-9]+([._-][A-Za-z0-9]+)*@[A-Za-z0-9]+([.-][A-Za-z0-9]+)*\.[a-z]{2,}$"
                        title="Must be a valid email address">
                    </section>
                    <section id="phone-number">
                        <label for="pnum">Phone Number</label>
                        <input type="text" name="phone" id="pnum" required pattern="^[0-9 ]{8,12}$"
                        title="Use 8 to 12 digits">
                    </section>
                </section>
                <section id="requirement">
                    <label for="techlist">Required Technical List</label>
                    <section id="skill-cols">
                        <section id="skill-col-1">
                            <p><input type="checkbox" name="skills[]" value="python_programming_language" id="programming" required><label for="programming">Python Programming Language</label></p>
                            <p><input type="checkbox" name="skills[]" value="data_science" id="datasc"><label for="datasc">Data Science</label></p>
                            <p><input type="checkbox" name="skills[]" value="cyber_security" id="cysec"><label for="cysec">Cyber Security</label></p>
                        </section>
                        <section id="skill-col-2">
                            <p><input type="checkbox" name="skills[]" value="project_management" id="project"><label for="project">Project Management</label></p>
                            <p><input type="checkbox" name="skills[]" value="software_development" id="Software"><label for="Software">Software Development</label></p>
                            <p><input type="checkbox" name="skills[]" value="technical_writing" id="techwriting"><label for="techwriting">Technical Writing</label></p>
                        </section>
                    </section>
                </section>
                <section id="other-skill">
                    <label for="otherskill">Other Skills</label>
                    <textarea name="skill" id="otherskill"></textarea>
                </section>
                <button id="submit" name="save_record">Submit</button>
            </section>
        </form>
    </section>

</body>
    <?php include 'footer.inc'; ?>

</html>