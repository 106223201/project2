<?php
require_once('init_session.php');
require_once('settings.php');

// Check if user is logged in
if (!isUserLoggedIn()) {
    header("Location: login-register.php");
    exit();
}

$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = getCurrentUserId();
$username = getCurrentUsername();
$user_email = $_SESSION['user_email'];

// Handle claiming unclaimed applications
if (isset($_POST['claim_eoi']) && $user_id) {
    $eoi_to_claim = (int)$_POST['claim_eoi'];
    
    $claim_query = "UPDATE eoi SET user_id = ? WHERE EOInumber = ? AND user_id IS NULL AND email = ?";
    $claim_stmt = mysqli_prepare($conn, $claim_query);
    mysqli_stmt_bind_param($claim_stmt, "iis", $user_id, $eoi_to_claim, $user_email);
    
    if (mysqli_stmt_execute($claim_stmt)) {
        $claim_message = "Application EOI-" . str_pad($eoi_to_claim, 6, '0', STR_PAD_LEFT) . " has been linked to your account!";
    }
    mysqli_stmt_close($claim_stmt);
}

// Get user's applications
$query = "SELECT * FROM eoi WHERE user_id = ? ORDER BY EOInumber DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$applications = [];
if ($result) {
    $applications = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
mysqli_stmt_close($stmt);

// Get unclaimed applications with user's email
$unclaimed_query = "SELECT * FROM eoi WHERE user_id IS NULL AND email = ? ORDER BY EOInumber DESC";
$unclaimed_stmt = mysqli_prepare($conn, $unclaimed_query);
mysqli_stmt_bind_param($unclaimed_stmt, "s", $user_email);
mysqli_stmt_execute($unclaimed_stmt);
$unclaimed_result = mysqli_stmt_get_result($unclaimed_stmt);
$unclaimed_applications = [];
if ($unclaimed_result) {
    $unclaimed_applications = mysqli_fetch_all($unclaimed_result, MYSQLI_ASSOC);
}
mysqli_stmt_close($unclaimed_stmt);

// Get statistics for this user
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'New' THEN 1 ELSE 0 END) as new_count,
    SUM(CASE WHEN status = 'Under Review' THEN 1 ELSE 0 END) as review_count,
    SUM(CASE WHEN status = 'Interview Scheduled' THEN 1 ELSE 0 END) as interview_count,
    SUM(CASE WHEN status = 'Accepted' THEN 1 ELSE 0 END) as accepted_count,
    SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected_count
    FROM eoi WHERE user_id = ?";
$stats_stmt = mysqli_prepare($conn, $stats_query);
mysqli_stmt_bind_param($stats_stmt, "i", $user_id);
mysqli_stmt_execute($stats_stmt);
$stats_result = mysqli_stmt_get_result($stats_stmt);
$stats = mysqli_fetch_assoc($stats_result);
mysqli_stmt_close($stats_stmt);

// Ensure all stats have values (even if 0)
if (!$stats) {
    $stats = [
        'total' => 0,
        'new_count' => 0,
        'review_count' => 0,
        'interview_count' => 0,
        'accepted_count' => 0,
        'rejected_count' => 0
    ];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - ePass</title>
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
    <?php include 'user-nav.inc'; ?>
    
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-content">
                <h1>👤 My Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($username); ?>!</p>
            </div>
            <a href="apply.php" class="apply-btn-header">+ New Application</a>
        </div>
        
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-icon">📊</div>
                <div class="stat-info">
                    <h3><?php echo $stats['total']; ?></h3>
                    <p>Total Applications</p>
                </div>
            </div>
            <div class="stat-card new">
                <div class="stat-icon">🆕</div>
                <div class="stat-info">
                    <h3><?php echo $stats['new_count']; ?></h3>
                    <p>New</p>
                </div>
            </div>
            <div class="stat-card review">
                <div class="stat-icon">👀</div>
                <div class="stat-info">
                    <h3><?php echo $stats['review_count']; ?></h3>
                    <p>Under Review</p>
                </div>
            </div>
            <div class="stat-card interview">
                <div class="stat-icon">💼</div>
                <div class="stat-info">
                    <h3><?php echo $stats['interview_count']; ?></h3>
                    <p>Interview</p>
                </div>
            </div>
            <div class="stat-card accepted">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <h3><?php echo $stats['accepted_count']; ?></h3>
                    <p>Accepted</p>
                </div>
            </div>
            <div class="stat-card rejected">
                <div class="stat-icon">❌</div>
                <div class="stat-info">
                    <h3><?php echo $stats['rejected_count']; ?></h3>
                    <p>Rejected</p>
                </div>
            </div>
        </div>
        
        <!-- Profile Info Card -->
        <div class="profile-section">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">👤</div>
                    <div class="profile-info">
                        <h2><?php echo htmlspecialchars($username); ?></h2>
                        <p><?php echo htmlspecialchars($user_email); ?></p>
                    </div>
                </div>
                <div class="profile-stats">
                    <div class="profile-stat">
                        <span class="stat-label">Member Since</span>
                        <span class="stat-value"><?php echo date('M Y', $_SESSION['login_time'] ?? time()); ?></span>
                    </div>
                    <div class="profile-stat">
                        <span class="stat-label">Total Applications</span>
                        <span class="stat-value"><?php echo $stats['total']; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Applications Section -->
        <div class="applications-section">
            <div class="section-header">
                <h2>📋 My Applications</h2>
                <?php if (empty($applications)): ?>
                    <span class="empty-badge">No applications yet</span>
                <?php else: ?>
                    <span class="count-badge"><?php echo count($applications); ?> application(s)</span>
                <?php endif; ?>
            </div>
            
            <?php if (empty($applications)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📝</div>
                    <h3>No Applications Yet</h3>
                    <p>You haven't submitted any job applications. Start your journey with us!</p>
                    <a href="jobs.php" class="btn btn-primary">Browse Jobs</a>
                </div>
            <?php else: ?>
                <div class="applications-grid">
                    <?php foreach ($applications as $app): ?>
                        <div class="application-card">
                            <div class="card-header">
                                <div class="job-info">
                                    <h3><?php echo htmlspecialchars($app['jobref']); ?></h3>
                                    <span class="eoi-number">EOI-<?php echo str_pad($app['EOInumber'], 6, '0', STR_PAD_LEFT); ?></span>
                                </div>
                                <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $app['status'])); ?>">
                                    <?php echo htmlspecialchars($app['status']); ?>
                                </span>
                            </div>
                            
                            <div class="card-body">
                                <div class="info-row">
                                    <span class="label">Name:</span>
                                    <span class="value"><?php echo htmlspecialchars($app['Fname'] . ' ' . $app['Lname']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Email:</span>
                                    <span class="value"><?php echo htmlspecialchars($app['email']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Phone:</span>
                                    <span class="value"><?php echo htmlspecialchars($app['phone']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Location:</span>
                                    <span class="value"><?php echo htmlspecialchars($app['suburbtown'] . ', ' . $app['state'] . ' ' . $app['postcode']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Skills:</span>
                                    <span class="value"><?php echo htmlspecialchars($app['skills']); ?></span>
                                </div>
                                <?php if (!empty($app['otherskills'])): ?>
                                <div class="info-row">
                                    <span class="label">Other Skills:</span>
                                    <span class="value"><?php echo htmlspecialchars($app['otherskills']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-footer">
                                <span class="date">
                                    📅 Applied: <?php echo isset($app['created_at']) ? date('M j, Y', strtotime($app['created_at'])) : 'N/A'; ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>