<nav>
<?php
// Initialize session
require_once('init_session.php');

// Get login status
$username = getCurrentUsername();
?>

<nav id="nav-bar">
    <a href="index.php"><img class="logo" src="images/logo.png" alt="logo"></a>
      
    <!-- menu bars -->
    <input type="checkbox" id="nav-toggle">
    <label for="nav-toggle" class="nav-toggle-label">
        <span></span>
        <span></span>
        <span></span>
    </label>       
    
    <ul id="nav-menu">
        <li><a id="nav-link" href="about.php">About</a></li>
        <li><a id="nav-link" href="jobs.php">Careers</a></li>
        <li><a id="nav-link" href="mailto:info@epasssoftware.com.au">Contact</a></li>
        
        <?php if (isUserLoggedIn()): ?>
            <li><a id="nav-link" href="dashboard.php">Dashboard</a></li>
            <li><a id="nav-link" href="apply.php">Apply</a></li>
            <li class="user-menu">
                <span class="username-display">👤 <?php echo htmlspecialchars($username); ?></span>
                <a id="nav-link" href="logout.php" class="nav-button logout-btn">Logout</a>
            </li>
        <?php else: ?>
            <li><a id="nav-link" href="login-register.php" class="nav-button">Sign in</a></li>
        <?php endif; ?>
    </ul>
</nav>

<style>
    .user-menu {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .username-display {
        color: #333;
        font-weight: 600;
        padding: 8px 15px;
        background: #f0f0f0;
        border-radius: 20px;
        font-size: 0.9rem;
    }
    
    .logout-btn {
        background: #d32f2f !important;
        color: white !important;
    }
    
    .logout-btn:hover {
        background: #b71c1c !important;
    }
    
    @media (max-width: 768px) {
        .user-menu {
            flex-direction: column;
            width: 100%;
            gap: 10px;
        }
        
        .username-display {
            width: 100%;
            text-align: center;
        }
    }
</style>