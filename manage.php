<!-- Consulted Claude AI on the interface and icons used in this project. -->

<?php
require_once('init_session.php');
require_once('settings.php');

// Check if manager is logged in
if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) {
    header("Location: manager-login.php");
    exit();
}

$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$manager_name = $_SESSION['manager_name'];
$manager_id = $_SESSION['manager_id'];

// Initialize variables
$applications = [];
$message = "";
$query_type = "";
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Validate sort column
$valid_sort_columns = ['EOInumber', 'jobref', 'Fname', 'Lname', 'status', 'created_at', 'email', 'state', 'postcode'];
if (!in_array($sort_by, $valid_sort_columns)) {
    $sort_by = 'created_at';
}

// Handle different queries
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    // List ALL EOIs
    if (isset($_GET['query']) && $_GET['query'] == 'all') {
        $query = "SELECT * FROM eoi ORDER BY $sort_by $sort_order";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $applications = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $query_type = "All Applications";
            
            // Log activity
            $log_query = "INSERT INTO manager_activity_log (manager_id, action_type, action_details) VALUES (?, 'query', 'Viewed all EOIs')";
            $log_stmt = mysqli_prepare($conn, $log_query);
            mysqli_stmt_bind_param($log_stmt, "i", $manager_id);
            mysqli_stmt_execute($log_stmt);
            mysqli_stmt_close($log_stmt);
        }
    }
    
    // List EOIs by Job Reference
    if (isset($_GET['query']) && $_GET['query'] == 'by_job' && !empty($_GET['jobref'])) {
        $jobref = mysqli_real_escape_string($conn, trim($_GET['jobref']));
        $query = "SELECT * FROM eoi WHERE jobref = ? ORDER BY $sort_by $sort_order";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $jobref);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $applications = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $query_type = "Applications for Job: " . htmlspecialchars($jobref);
        mysqli_stmt_close($stmt);
        
        // Log activity
        $log_query = "INSERT INTO manager_activity_log (manager_id, action_type, action_details) VALUES (?, 'query', ?)";
        $log_stmt = mysqli_prepare($conn, $log_query);
        $log_details = "Viewed EOIs for job: $jobref";
        mysqli_stmt_bind_param($log_stmt, "is", $manager_id, $log_details);
        mysqli_stmt_execute($log_stmt);
        mysqli_stmt_close($log_stmt);
    }
    
    // Search by Applicant Name
    if (isset($_GET['query']) && $_GET['query'] == 'by_name') {
        $fname = isset($_GET['fname']) ? mysqli_real_escape_string($conn, trim($_GET['fname'])) : '';
        $lname = isset($_GET['lname']) ? mysqli_real_escape_string($conn, trim($_GET['lname'])) : '';
        
        if (!empty($fname) || !empty($lname)) {
            $where_conditions = [];
            $params = [];
            $types = "";
            
            if (!empty($fname)) {
                $where_conditions[] = "Fname LIKE ?";
                $params[] = "%$fname%";
                $types .= "s";
            }
            if (!empty($lname)) {
                $where_conditions[] = "Lname LIKE ?";
                $params[] = "%$lname%";
                $types .= "s";
            }
            
            $where_clause = implode(" AND ", $where_conditions);
            $query = "SELECT * FROM eoi WHERE $where_clause ORDER BY $sort_by $sort_order";
            $stmt = mysqli_prepare($conn, $query);
            
            if (!empty($params)) {
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $applications = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            
            $search_terms = [];
            if (!empty($fname)) $search_terms[] = "First Name: " . htmlspecialchars($fname);
            if (!empty($lname)) $search_terms[] = "Last Name: " . htmlspecialchars($lname);
            $query_type = "Search Results for " . implode(", ", $search_terms);
            
            // Log activity
            $log_query = "INSERT INTO manager_activity_log (manager_id, action_type, action_details) VALUES (?, 'query', ?)";
            $log_stmt = mysqli_prepare($conn, $log_query);
            $log_details = "Searched by name: $fname $lname";
            mysqli_stmt_bind_param($log_stmt, "is", $manager_id, $log_details);
            mysqli_stmt_execute($log_stmt);
            mysqli_stmt_close($log_stmt);
        }
    }
}

// Handle POST requests (Delete and Update Status)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Delete EOIs by Job Reference
    if (isset($_POST['delete_by_job']) && !empty($_POST['delete_jobref'])) {
        $jobref = mysqli_real_escape_string($conn, trim($_POST['delete_jobref']));
        
        // Get count first
        $count_query = "SELECT COUNT(*) as count FROM eoi WHERE jobref = ?";
        $count_stmt = mysqli_prepare($conn, $count_query);
        mysqli_stmt_bind_param($count_stmt, "s", $jobref);
        mysqli_stmt_execute($count_stmt);
        $count_result = mysqli_stmt_get_result($count_stmt);
        $count_row = mysqli_fetch_assoc($count_result);
        $delete_count = $count_row['count'];
        mysqli_stmt_close($count_stmt);
        
        if ($delete_count > 0) {
            $delete_query = "DELETE FROM eoi WHERE jobref = ?";
            $delete_stmt = mysqli_prepare($conn, $delete_query);
            mysqli_stmt_bind_param($delete_stmt, "s", $jobref);
            
            if (mysqli_stmt_execute($delete_stmt)) {
                $message = "Successfully deleted {$delete_count} application(s) for job reference: " . htmlspecialchars($jobref);
                
                // Log activity
                $log_query = "INSERT INTO manager_activity_log (manager_id, action_type, action_details) VALUES (?, 'delete', ?)";
                $log_stmt = mysqli_prepare($conn, $log_query);
                $log_details = "Deleted $delete_count EOIs for job: $jobref";
                mysqli_stmt_bind_param($log_stmt, "is", $manager_id, $log_details);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);
            } else {
                $message = "Error deleting applications.";
            }
            mysqli_stmt_close($delete_stmt);
        } else {
            $message = "No applications found for job reference: " . htmlspecialchars($jobref);
        }
    }
    
    // Update Status
    if (isset($_POST['update_status']) && !empty($_POST['eonumber']) && !empty($_POST['new_status'])) {
        $eonumber = (int)$_POST['eonumber'];
        $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
        
        $valid_statuses = ['New', 'Under Review', 'Interview Scheduled', 'Accepted', 'Rejected'];
        if (in_array($new_status, $valid_statuses)) {
            $update_query = "UPDATE eoi SET status = ? WHERE EOInumber = ?";
            $update_stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($update_stmt, "si", $new_status, $eonumber);
            
            if (mysqli_stmt_execute($update_stmt)) {
                $message = "Status updated successfully for EOI-" . str_pad($eonumber, 6, '0', STR_PAD_LEFT);
                
                // Log activity
                $log_query = "INSERT INTO manager_activity_log (manager_id, action_type, action_details) VALUES (?, 'update', ?)";
                $log_stmt = mysqli_prepare($conn, $log_query);
                $log_details = "Updated status for EOI $eonumber to: $new_status";
                mysqli_stmt_bind_param($log_stmt, "is", $manager_id, $log_details);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);
            } else {
                $message = "Error updating status.";
            }
            mysqli_stmt_close($update_stmt);
        }
    }
}

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'New' THEN 1 ELSE 0 END) as new_count,
    SUM(CASE WHEN status = 'Under Review' THEN 1 ELSE 0 END) as review_count,
    SUM(CASE WHEN status = 'Interview Scheduled' THEN 1 ELSE 0 END) as interview_count,
    SUM(CASE WHEN status = 'Accepted' THEN 1 ELSE 0 END) as accepted_count,
    SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected_count
    FROM eoi";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Manager Dashboard - ePass</title>
    <link rel="stylesheet" href="styles/manage.css">
</head>
<body>
    <?php include 'manager-nav.inc'; ?>
    
    <br>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>HR Manager Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($manager_name); ?>!</p>
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
                    <p>Interview Scheduled</p>
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
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <!-- Query Forms -->
        <div class="query-section">
            <h2>🔍 Query Applications</h2>
            
            <div class="query-grid">
                <!-- List All EOIs -->
                <div class="query-card">
                    <h3>View All Applications</h3>
                    <p>Display all Expression of Interest submissions</p>
                    <form method="GET" action="manage.php">
                        <input type="hidden" name="query" value="all">
                        <br><br><br>
                        <button type="submit" class="btn btn-primary">View All</button>
                    </form>
                </div>
                
                <!-- Search by Job Reference -->
                <div class="query-card">
                    <h3>Search by Job Reference</h3>
                    <form method="GET" action="manage.php">
                        <input type="hidden" name="query" value="by_job">
                        <select name="jobref" required>
                            <option value="">-- Select Job --</option>
                            <option value="SE24A">SE24A (Software Engineer)</option>
                            <option value="DA24B">DA24B (Data Analyst)</option>
                            <option value="ML24C">ML24C (AIML Engineer)</option>
                            <option value="CE24D">CE24D (Cloud Engineer)</option>
                        </select>
                        <br>
                        <br><br>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                
                <!-- Search by Name -->
                <div class="query-card">
                    <h3>Search by Applicant Name</h3>
                    <form method="GET" action="manage.php">
                        <input type="hidden" name="query" value="by_name">
                        <input type="text" name="fname" placeholder="First Name">
                        <input type="text" name="lname" placeholder="Last Name">
                        <br>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                
                <!-- Delete by Job Reference -->
                <div class="query-card danger">
                    <h3>Delete Applications by Job</h3>
                    <p class="warning">⚠️ This action cannot be undone!</p>
                    <form method="POST" action="manage.php" onsubmit="return confirm('Are you sure you want to delete all applications for this job?');">
                        <select name="delete_jobref" required>
                            <option value="">-- Select Job --</option>
                            <option value="SE24A">SE24A (Software Engineer)</option>
                            <option value="DA24B">DA24B (Data Analyst)</option>
                            <option value="ML24C">ML24C (AIML Engineer)</option>
                            <option value="CE24D">CE24D (Cloud Engineer)</option>
                        </select>
                        <br>
                        <button type="submit" name="delete_by_job" class="btn btn-danger">Delete All</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Results Section -->
        <?php if (!empty($applications)): ?>
            <div class="results-section">
                <div class="results-header">
                    <h2><?php echo htmlspecialchars($query_type); ?></h2>
                    <p><?php echo count($applications); ?> application(s) found</p>
                </div>
                
                <!-- Sort Options -->
                <div class="sort-options">
                    <label>Sort by:</label>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'EOInumber', 'order' => 'ASC'])); ?>" 
                       class="<?php echo ($sort_by == 'EOInumber') ? 'active' : ''; ?>">EOI Number</a>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'jobref', 'order' => 'ASC'])); ?>"
                       class="<?php echo ($sort_by == 'jobref') ? 'active' : ''; ?>">Job Ref</a>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'Fname', 'order' => 'ASC'])); ?>"
                       class="<?php echo ($sort_by == 'Fname') ? 'active' : ''; ?>">First Name</a>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'Lname', 'order' => 'ASC'])); ?>"
                       class="<?php echo ($sort_by == 'Lname') ? 'active' : ''; ?>">Last Name</a>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'state', 'order' => 'ASC'])); ?>"
                       class="<?php echo ($sort_by == 'state') ? 'active' : ''; ?>">State</a>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'postcode', 'order' => 'ASC'])); ?>"
                       class="<?php echo ($sort_by == 'postcode') ? 'active' : ''; ?>">Zipcode</a>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'status', 'order' => 'ASC'])); ?>"
                       class="<?php echo ($sort_by == 'status') ? 'active' : ''; ?>">Status</a>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'created_at', 'order' => 'DESC'])); ?>"
                       class="<?php echo ($sort_by == 'created_at') ? 'active' : ''; ?>">Date</a>
                </div>
                
                <!-- Applications Table -->
                <div class="table-container">
                    <table class="applications-table">
                        <thead>
                            <tr>
                                <th>EOI Number</th>
                                <th>Job Ref</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>State</th>
                                <th>Zipcode</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td><strong>EOI-<?php echo str_pad($app['EOInumber'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                    <td><?php echo htmlspecialchars($app['jobref']); ?></td>
                                    <td><?php echo htmlspecialchars($app['Fname'] . ' ' . $app['Lname']); ?></td>
                                    <td><?php echo htmlspecialchars($app['email']); ?></td>
                                    <td><?php echo htmlspecialchars($app['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($app['state']); ?></td>
                                    <td><?php echo htmlspecialchars($app['postcode']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $app['status'])); ?>">
                                            <?php echo htmlspecialchars($app['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($app['created_at'])); ?></td>
                                    <td>
                                        <form method="POST" action="manage.php" class="inline-form">
                                            <input type="hidden" name="eonumber" value="<?php echo $app['EOInumber']; ?>">
                                            <select name="new_status" required>
                                                <option value="">Change Status</option>
                                                <option value="New">New</option>
                                                <option value="Under Review">Under Review</option>
                                                <option value="Interview Scheduled">Interview Scheduled</option>
                                                <option value="Accepted">Accepted</option>
                                                <option value="Rejected">Rejected</option>
                                            </select>
                                            <button type="submit" name="update_status" class="btn btn-sm">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        <p>&copy; 2025 ePass Software. All rights reserved.</p>
    </div>            
                  
</body>
</html>

<?php mysqli_close($conn); ?>