<?php
// Initialize session
require_once('init_session.php');

// Get login status
$is_logged_in = isUserLoggedIn();
$username = $is_logged_in ? getCurrentUsername() : '';
?>

<nav id="user-nav">
    <div class="nav-container">
        <div class="nav-brand">
            <a href="index.php">
                <img class="logo" src="images/logo.png" alt="ePass Logo">
            </a>
        </div>
        
        <ul class="nav-menu">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="about.php" class="nav-link">About</a></li>
            <li><a href="jobs.php" class="nav-link">Jobs</a></li>
            
            <?php if ($is_logged_in): ?>
                <li><a href="dashboard.php" class="nav-link active-link">Dashboard</a></li>
                <li><a href="apply.php" class="nav-link">Apply</a></li>
                <br>
                <li class="user-menu">
                    <span class="username-display">👤 <?php echo htmlspecialchars($username); ?></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </li>
            <?php else: ?>
                <li><a href="login-register.php" class="nav-link login-link">Sign In</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<style>

#user-nav {
    background-color: #0b0625ef;  
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
}

.nav-brand .logo {
    height: 45px;
    width: auto;
}

.nav-menu {
    display: flex;
    align-items: center;
    gap: 20px;
    list-style: none;
    margin: 0;
}

.nav-link {
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 1rem;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.15);
}

.nav-link.active-link {
    background: rgba(255, 255, 255, 0.2);
    font-weight: 600;
}

.login-link {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid white;
}

.login-link:hover {
    background: white;
    color: #667eea;
}

.user-menu {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-left: 2rem;
}

.username-display {
    color: white;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.95rem;
}

.logout-btn {
    background: #bd1004ff;
    color: white;
    padding: 8px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    font-size: 0.95rem;
}

.logout-btn:hover {
    background: #b30202ff;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .nav-container {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
    }
    
    .nav-menu {
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }
    
    .user-menu {
        width: 100%;
        justify-content: center;
        margin-left:0;
        gap:15px;
    }
    
    .username-display {
        width:auto;
        text-align: center;
        justify-content: center;
    }

    .logout-btn {
        width: auto;
        text-align: center;
    }
}
</style>
</nav>
