<?php
session_start();
require_once '../../model/clientRegDb.php';

// --- 1. SESSION CHECK ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    header("Location: ../views/login.php");
    exit;
}

$client_id = $_SESSION['user_id'];
$message = '';

// --- 2. FETCH CLIENT DETAILS (FOR SIDEBAR) ---
// We need this so the Sidebar looks exactly like the Dashboard
$db = new mydb();
$conn = $db->createConObject();

$clientName = "Client";
$clientAvatar = null;

$stmt = $conn->prepare("SELECT full_name, username, profile_picture FROM clients WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $nameFromDB = !empty($row['full_name']) ? $row['full_name'] : $row['username'];
        if (!empty($nameFromDB)) $clientName = $nameFromDB;
        $clientAvatar = $row['profile_picture'];
    }
    $stmt->close();
}

// --- 3. HANDLE FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $budget_type = $_POST['budget_type'];
    $budget_amount = $_POST['budget_amount'];

    if (empty($title) || empty($description) || empty($budget_amount)) {
        $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Please fill in all fields.</div>';
    } else {
        $sql = "INSERT INTO job_postings (client_id, title, description, category, budget_type, budget_amount, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("issssd", $client_id, $title, $description, $category, $budget_type, $budget_amount);
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Job posted successfully!</div>';
                $_POST = array(); 
            } else {
                $message = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        } else {
            $message = '<div class="alert alert-danger">Database error: ' . $conn->error . '</div>';
        }
    }
}
$conn->close();

$categories = ['Web Development', 'Mobile App', 'UI/UX Design', 'Data Science', 'Writing', 'Marketing'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job - Freelance.Pro</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/post_job.css">     
    <link rel="stylesheet" href="../css/sidebar.css">

    <style>
        .sidebar { display: flex; flex-direction: column; justify-content: space-between; padding-bottom: 20px; }
        .nav-menu { flex-grow: 1; }
        
        .sidebar-footer {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .user-mini-profile { display: flex; align-items: center; gap: 10px; text-decoration: none; overflow: hidden; }
        .user-avatar-circle {
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--neon-primary, #0aff99); color: #000;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1rem; flex-shrink: 0;
        }
        .user-avatar-circle img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
        .user-info-text { display: flex; flex-direction: column; }
        .user-info-text h4 { font-size: 0.9rem; font-weight: 600; margin: 0; color: #fff; }
        .user-info-text span { font-size: 0.75rem; color: #8892b0; }
        .logout-icon { color: #ff4444; font-size: 1.4rem; padding: 5px; transition: 0.3s; }
        .logout-icon:hover { transform: translateX(3px); }
    </style>
</head>
<body>

<aside class="sidebar">
    <div>
        <div class="logo-text">FREELANCE<span class="logo-dot">.PRO</span></div>
        
        <ul class="nav-menu">
            <li class="nav-item"><a href="client_dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> <span>Dashboard</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-search"></i> <span>Browse Talent</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-briefcase"></i> <span>My Postings</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-file-contract"></i> <span>Contracts</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-comment-dots"></i> <span>Messages</span></a></li>
            <li class="nav-item"><a href="client_profile.php" class="nav-link"><i class="fas fa-user"></i> <span>Profile</span></a></li>
        </ul>
    </div>

    <div class="sidebar-footer">
        
        <div class="user-mini-profile">
            <div class="user-avatar-circle">
                <?php if ($clientAvatar && file_exists($clientAvatar)): ?>
                    <img src="<?= htmlspecialchars($clientAvatar) ?>" alt="Av">
                <?php else: ?>
                    <?= strtoupper(substr($clientName, 0, 1)) ?>
                <?php endif; ?>
            </div>
            <div class="user-info-text">
                <h4><?= htmlspecialchars($clientName) ?></h4>
                <span>Client Account</span>
            </div>
        </div>

        <a href="../login.php?logout=true" class="logout-icon" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</aside>

<main class="main-content">

    <header class="dashboard-header">
        <div class="welcome-text">
            <h1>Post a New Job</h1>
            <p>Define requirements to find the perfect talent.</p>
        </div>
    </header>

    <?= $message ?>

    <div class="job-post-container">
        <form method="POST" id="jobPostForm" class="settings-panel">
            
            <h3 class="section-head">Project Details</h3>
            
            <div class="form-group">
                <label>Job Title</label>
                <input type="text" name="title" placeholder="e.g. Full Stack Developer for E-commerce" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="6" placeholder="Describe deliverables, timeline, and skills required..." required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat ?>"><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Budget Type</label>
                    <select name="budget_type">
                        <option value="fixed">Fixed Price</option>
                        <option value="hourly">Hourly Rate</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Budget Amount ($)</label>
                    <input type="number" name="budget_amount" placeholder="500.00" step="0.01" value="<?= htmlspecialchars($_POST['budget_amount'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="window.location.href='client_dashboard.php'">Cancel</button>
                <button type="submit" class="btn-primary">Post Job Now</button>
            </div>

        </form>
    </div>

</main>

</body>
</html>