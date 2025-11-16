<?php
// Ensure session is initialized
require_once('init_session.php');

// Check if manager is logged in
$is_manager_logged_in = isset($_SESSION['manager_logged_in']) && $_SESSION['manager_logged_in'] === true;
$manager_name = $is_manager_logged_in ? $_SESSION['manager_name'] : '';
?>

<nav id="manager-nav">
    <div class="nav-container">
        <div class="nav-brand">
            <a href="index.php">
                <img class="logo" src="images/logo.png" alt="ePass Logo">
            </a>
            <span class="manager-badge">👔 HR Portal</span>
        </div>
        
        <ul class="nav-menu">
            <?php if ($is_manager_logged_in): ?>
                <li><a href="manage.php" class="nav-link">Dashboard</a></li>
                <li><a href="#" class="nav-link">Reports</a></li>
                <li class="user-info">
                    <span class="manager-name">👤 <?php echo htmlspecialchars($manager_name); ?></span>
                    <a href="manager-logout.php" class="logout-btn">Logout</a>
                </li>
            <?php else: ?>
                <li><a href="manager-login.php" class="nav-link">Manager Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<style>
#manager-nav {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
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

.nav-brand {
    display: flex;
    align-items: center;
    gap: 15px;
}

.nav-brand .logo {
    height: 40px;
    width: auto;
}

.manager-badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.nav-menu {
    display: flex;
    align-items: center;
    gap: 25px;
    list-style: none;
}

.nav-link {
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: opacity 0.3s;
    padding: 8px 15px;
    border-radius: 6px;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.manager-name {
    color: white;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.15);
    padding: 8px 15px;
    border-radius: 20px;
}

.logout-btn {
    background: #f44336;
    color: white;
    padding: 8px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.logout-btn:hover {
    background: #d32f2f;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .nav-container {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
    }
    
    .nav-menu {
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }
    
    .user-info {
        flex-direction: column;
        width: 100%;
    }
    
    .manager-name,
    .logout-btn {
        width: 100%;
        text-align: center;
    }
}
</style>