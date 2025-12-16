<?php
session_start();
require_once '../../model/clientRegDb.php';

// --- 1. SECURITY & CONNECTION ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

$db = new mydb();
$conn = $db->createConObject();
$client_id = $_SESSION['user_id'];

// --- 2. FETCH CLIENT DETAILS (Name & Avatar) ---
$clientName = "Client";
$clientAvatar = null; 

// We fetch profile_picture too so we can show it if it exists
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

// --- 3. FETCH ACTIVE JOBS ---
$activeJobsCount = 0;
$stmtJobs = $conn->prepare("SELECT * FROM job_postings WHERE client_id = ? ORDER BY id DESC");
$jobsResult = null;

if ($stmtJobs) {
    $stmtJobs->bind_param("i", $client_id);
    $stmtJobs->execute();
    $jobsResult = $stmtJobs->get_result();
    $activeJobsCount = $jobsResult->num_rows;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Freelance.Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/sidebar.css">


</head>
<body>

    <aside class="sidebar">
        <div>
            <div class="logo-text">FREELANCE<span class="logo-dot">.PRO</span></div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link active"><i class="fas fa-th-large"></i> <span>Dashboard</span></a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i class="fas fa-search"></i> <span>Browse Talent</span></a>
                </li>
                <li class="nav-item">
                    <a href="manage_postings.php" class="nav-link"><i class="fas fa-briefcase"></i> <span>My Postings</span></a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i class="fas fa-file-contract"></i> <span>Contracts</span></a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i class="fas fa-comment-dots"></i> <span>Messages</span></a>
                </li>
                <li class="nav-item">
                    <a href="client_profile.php" class="nav-link"><i class="fas fa-user"></i> <span>Profile</span></a>
                </li>
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
                <h1>Hello, <?= htmlspecialchars($clientName) ?>.</h1>
                <p>Manage your jobs, proposals, and active contracts.</p>
            </div>
            <div class="header-actions">
                <a href="#" class="btn-secondary"><i class="fas fa-search"></i> Find Talent</a>
                <a href="post_job.php" class="btn-primary"><i class="fas fa-plus"></i> Post a New Job</a>
            </div>
        </header>

        <div class="stats-grid">
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                <div class="stat-value">$0</div>
                <div class="stat-label">Total Spent</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                <div class="stat-value"><?= $activeJobsCount ?></div>
                <div class="stat-label">Active Job Postings</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-file-signature"></i></div>
                <div class="stat-value">0</div>
                <div class="stat-label">Total Proposals</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                <div class="stat-value">0</div>
                <div class="stat-label">Unread Messages</div>
            </div>
        </div>

        <div class="dashboard-split">
            
            <section class="job-section">
                <div class="section-title">
                    <h3>Your Job Postings</h3>
                    <a href="#" class="view-all">View All</a>
                </div>

                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Budget</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($activeJobsCount > 0): ?>
                                <?php while($job = $jobsResult->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($job['title'] ?? $job['job_title'] ?? 'Untitled') ?></strong></td>
                                        <td>$<?= htmlspecialchars($job['budget_amount'] ?? $job['budget'] ?? '0') ?></td>
                                        <td style="color: #8892b0;"><?= date('M d', strtotime($job['created_at'] ?? 'now')) ?></td>
                                        <td><span class="badge active">Active</span></td>
                                        <td><a href="#" class="btn-xs view">Manage</a></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 3rem; color: #8892b0;">
                                        No job postings found. Post a new job to get started!
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="contracts-section">
                <div class="section-title">
                    <h3>Active Contracts</h3>
                </div>

                <div class="contracts-panel">
                    <div style="background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; padding: 1.5rem; border-radius: 4px; color: #f8fafc;">
                        <h4 style="margin-bottom: 0.5rem; display:flex; align-items:center; gap:10px;">
                            <i class="fas fa-cog fa-spin" style="color: #3b82f6;"></i> Feature In Progress
                        </h4>
                        <p style="font-size: 0.9rem; color: #94a3b8; line-height: 1.5;">
                            This section will dynamically list your active contracts with freelancers and progress tracking.
                        </p>
                    </div>
                </div>
            </section>

        </div>
        
    </main>

</body>
</html>
<?php 
if(isset($stmtJobs)) $stmtJobs->close(); 
$conn->close(); 
?>