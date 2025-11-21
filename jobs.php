<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'header.inc'; ?>
    <meta name="keywords" content="Job Descriptions, Careers, Opportunities">
    <meta name="author" content="Kenzie Duong Nguyen">
    <title>Job Descriptions</title>
    <link rel="stylesheet" href="styles/jobs.css">

</head>

<body>

    <?php include 'nav.inc'; ?>

    <header>
        <h1>Career Opportunities in Australia</h1>
        <p>Explore roles that shape the future of technology.</p>
    </header>

        <br>
    <div class="jobs-container">
        <main>
        <?php
        // Database connection
        require_once 'settings.php';
        
            // Fetch all jobs
            $sql = "SELECT * FROM jobs ORDER BY job_id";
                    $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $jobCounter = 1;
            
            while ($job = mysqli_fetch_assoc($result)) {
                $jobId = $job['job_id'];
                
                // Fetch responsibilities for this job
                $sqlResp = "SELECT responsibility_text FROM job_responsibilities 
                           WHERE job_id = ? ORDER BY display_order";
                $stmtResp = mysqli_prepare($conn, $sqlResp);
                mysqli_stmt_bind_param($stmtResp, "i", $jobId);
                mysqli_stmt_execute($stmtResp);
                $resultResp = mysqli_stmt_get_result($stmtResp);
                $responsibilities = [];
                while ($row = mysqli_fetch_assoc($resultResp)) {
                    $responsibilities[] = $row['responsibility_text'];
                }
                mysqli_stmt_close($stmtResp);
                
                // Fetch essential qualifications
                $sqlEssential = "SELECT qualification_text FROM job_qualifications 
                                WHERE job_id = ? AND is_essential = TRUE ORDER BY display_order";
                $stmtEssential = mysqli_prepare($conn, $sqlEssential);
                mysqli_stmt_bind_param($stmtEssential, "i", $jobId);
                mysqli_stmt_execute($stmtEssential);
                $resultEssential = mysqli_stmt_get_result($stmtEssential);
                $essentialQuals = [];
                while ($row = mysqli_fetch_assoc($resultEssential)) {
                    $essentialQuals[] = $row['qualification_text'];
                }
                mysqli_stmt_close($stmtEssential);
                
                // Fetch preferable qualifications
                $sqlPreferable = "SELECT qualification_text FROM job_qualifications 
                                 WHERE job_id = ? AND is_essential = FALSE ORDER BY display_order";
                $stmtPreferable = mysqli_prepare($conn, $sqlPreferable);
                mysqli_stmt_bind_param($stmtPreferable, "i", $jobId);
                mysqli_stmt_execute($stmtPreferable);
                $resultPreferable = mysqli_stmt_get_result($stmtPreferable);
                $preferableQuals = [];
                while ($row = mysqli_fetch_assoc($resultPreferable)) {
                    $preferableQuals[] = $row['qualification_text'];
                }
                mysqli_stmt_close($stmtPreferable);
                
                // Format salary
                $salaryFormatted = '$' . number_format($job['salary_min']) . ' - $' . 
                                  number_format($job['salary_max']) . ' per year';
                
                // Output job section
                echo '<section id="job-box">';
                echo '<input type="checkbox" id="job' . $jobCounter . '" class="job-toggle">';
                echo '<label for="job' . $jobCounter . '" class="job-header">';
                echo '<div class="job-header-content">';
                echo '<h2>' . htmlspecialchars($job['title']) . '</h2>';
                echo '<span class="ref-number">Reference: ' . htmlspecialchars($job['reference']) . '</span>';
                echo '<div class="job-meta">';
                echo '<span>📍 ' . htmlspecialchars($job['location']) . '</span>';
                echo '<span>💼 ' . htmlspecialchars($job['employment_type']) . '</span>';
                echo '</div>';
                echo '<div class="salary">Salary: ' . $salaryFormatted . '</div>';
                echo '</div>';
                echo '<span class="toggle-icon">▼</span>';
                echo '</label>';
                
                echo '<div class="job-details">';
                echo '<div class="reports-to">Reports to: ' . htmlspecialchars($job['reports_to']) . '</div>';
                
                echo '<div class="job-description">';
                echo '<p>' . htmlspecialchars($job['description']) . '</p>';
                echo '</div>';
                
                echo '<div class="job-content">';
                
                // Key Responsibilities
                if (!empty($responsibilities)) {
                    echo '<h3>Key Responsibilities</h3>';
                    echo '<ol>';
                    foreach ($responsibilities as $resp) {
                        echo '<li>' . htmlspecialchars($resp) . '</li>';
                    }
                    echo '</ol>';
                }
                
                echo '<h3>Required Qualifications, Skills, and Attributes</h3>';
                
                // Essential Qualifications
                if (!empty($essentialQuals)) {
                    echo '<h4>Essential</h4>';
                    echo '<ul>';
                    foreach ($essentialQuals as $qual) {
                        echo '<li>' . htmlspecialchars($qual) . '</li>';
                    }
                    echo '</ul>';
                }
                
                // Preferable Qualifications
                if (!empty($preferableQuals)) {
                    echo '<h4>Preferable</h4>';
                    echo '<ul>';
                    foreach ($preferableQuals as $qual) {
                        echo '<li>' . htmlspecialchars($qual) . '</li>';
                    }
                    echo '</ul>';
                }
                
                echo '</div>';
                echo '<a href="apply.php" class="apply-btn">Apply Now</a>';
                echo '</div>';
                echo '</section>';
                
                $jobCounter++;
            }
        } else {
            echo '<p class="error">Error loading job listings. Please try again later.</p>';
            if (mysqli_error($conn)) {
                error_log("Database error: " . mysqli_error($conn));
            }
        }
        
        // Close connection
        mysqli_close($conn);
        ?>
        </main>



        <!-- ASIDE: WHY EPASS -->
        <aside class="float-item">
            <h2>Why ePass?</h2>
            
            <div class="why-item">
                <div class="icon">🚀</div>
                <h3>Innovation First</h3>
                <p>Work on cutting-edge projects that push the boundaries of technology.</p>
            </div>

            <div class="why-item">
                <div class="icon">💰</div>
                <h3>Competitive Compensation</h3>
                <p>Industry-leading salaries, equity packages, and comprehensive benefits.</p>
            </div>

            <div class="why-item">
                <div class="icon">🌍</div>
                <h3>Remote Flexibility</h3>
                <p>Work from anywhere with our hybrid and remote-first policies.</p>
            </div>

            <div class="why-item">
                <div class="icon">📚</div>
                <h3>Growth & Learning</h3>
                <p>Continuous learning opportunities, mentorship programs, and conference budgets.</p>
            </div>

            <div class="why-item">
                <div class="icon">🤝</div>
                <h3>Inclusive Culture</h3>
                <p>Diverse team from around the world.</p>
            </div>

            <div class="why-item">
                <div class="icon">⚖️</div>
                <h3>Work-Life Balance</h3>
                <p>Generous PTO, wellness programs, and a culture that respects your time off work.</p>
            </div>
        </aside>

    </div>

  <!-- Job descriptions were generated by ChatGPT -->


</body>
    <?php include 'footer.inc'; ?>

</html>