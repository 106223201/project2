<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML, Form, Jobs">
    <meta name="author" content="Luu Tri Khoa Tung">
    <link rel="stylesheet" href="styles/apply.css">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Job Application Page</title>
</head>
<body>
    <nav id="nav-bar">
        <a href="index.html"><img class="logo" src ="images/logo.png" alt ="logo"></a></li>
          
        <!-- menu bars -->
          <input type="checkbox" id="nav-toggle">
            <label for="nav-toggle" class="nav-toggle-label">
                <span></span>
                <span></span>
                <span></span>
            </label>
        <ul id="nav-menu">
            <li><a id="nav-link" href="index.html">Home</a></li>
            <li><a id="nav-link" href="about.html">About</a></li>
            <li><a id="nav-link" href="jobs.html">Career</a></li>
            <li><a id="nav-link" href="mailto:info@epasssoftware.com.au">Contact</a></li>
            <li><a id="nav-link" class="nav-button" href="apply.html">Join us</a></li>
        </ul>
    </nav>

    <header>
        <h1>Career Opportunities in Australia</h1>
        <p>Explore roles that shape the future of technology.</p>
    </header>

    <!-- application form -->
    <br>

    <section id="apply-form">
        <form method="post" action="http://mercury.swin.edu.au/it000000/formtest.php">
            <h2>Application form</h2>
            <section id="job-reference">
                <label for="refnum">Job Reference Numbers</label>
                <select name="number" id="refnum">
                    <option value="software-engineer">SE24A</option>
                    <option value="data-analyst">DA24B</option>
                    <option value="aiml-engineer">ML24C</option>
                    <option value="cloud-engineer">CE24D</option>
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
                        <select name="statelist" id="state">
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
                            <p><input type="checkbox" name="prg" id="programming" required><label for="programming">Python Programming Language</label></p>
                            <p><input type="checkbox" name="data" id="datasc"><label for="datasc">Data Science</label></p>
                            <p><input type="checkbox" name="cysec" id="cysec"><label for="cysec">Cyber Security</label></p>
                        </section>
                        <section id="skill-col-2">
                            <p><input type="checkbox" name="manage" id="project"><label for="project">Project Management</label></p>
                            <p><input type="checkbox" name="soft" id="Software"><label for="Software">Software Development</label></p>
                            <p><input type="checkbox" name="writing" id="techwriting"><label for="techwriting">Technical Writing</label></p>
                        </section>
                    </section>
                </section>
                <section id="other-skill">
                    <label for="otherskill">Other Skills</label>
                    <textarea name="skill" id="otherskill"></textarea>
                </section>
                <input type="submit" value="Apply" id="submit">
            </section>
        </form>
    </section>

    <!-- footer -->
    <footer>
      <p>&copy; 2025 ePass Software. All rights reserved.</p>
    </footer>
</body>
</html>