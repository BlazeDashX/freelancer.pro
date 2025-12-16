<?php
session_start();
require_once '../../model/clientRegDb.php';

// --- 1. AUTHENTICATION ---
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'client') {
    $redirect = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'seller') 
        ? '../freelancer/freelancer_dashboard.php' 
        : '../../login.php';
    header("Location: " . $redirect);
    exit;
}

$clientEmail = $_SESSION['user_email'];
$message = '';

// --- 2. DB CONNECTION ---
try {
    $db = new mydb();
    $conn = $db->createConObject();
} catch (Exception $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// --- 3. POST HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A. PROFILE UPDATE
    if (isset($_POST['section']) && $_POST['section'] === 'profile_update') {
        $full_name = trim($_POST['full_name']);
        $username  = trim($_POST['username']);
        $phone     = trim($_POST['phone']);
        $profile_pic = trim($_POST['profile_picture']);

        $sql = "UPDATE clients SET full_name=?, username=?, phone=?, profile_picture=? WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $full_name, $username, $phone, $profile_pic, $clientEmail);

        if ($stmt->execute()) {
            $message = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Profile updated successfully!</div>';
            $_SESSION['user_name'] = $full_name; 
        } else {
            $message = '<div class="alert alert-danger">Error updating profile.</div>';
        }
        $stmt->close();
    }
    // B. PASSWORD UPDATE
    elseif (isset($_POST['section']) && $_POST['section'] === 'password_update') {
        $current_pass = $_POST['current_pass'];
        $new_pass     = $_POST['new_pass'];
        $confirm_pass = $_POST['confirm_pass'];

        if ($new_pass !== $confirm_pass) {
            $message = '<div class="alert alert-danger">New passwords do not match.</div>';
        } else {
            $sql = "SELECT password FROM clients WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $clientEmail);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            $stmt->close();

            if ($row && password_verify($current_pass, $row['password'])) {
                $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                $updSql = "UPDATE clients SET password=? WHERE email=?";
                $updStmt = $conn->prepare($updSql);
                $updStmt->bind_param("ss", $new_hash, $clientEmail);
                if ($updStmt->execute()) $message = '<div class="alert alert-success">Password changed successfully.</div>';
                else $message = '<div class="alert alert-danger">Database error.</div>';
                $updStmt->close();
            } else {
                $message = '<div class="alert alert-danger">Current password is incorrect.</div>';
            }
        }
    }
    // C. DELETE ACCOUNT
    elseif (isset($_POST['section']) && $_POST['section'] === 'delete_account') {
        if ($_POST['delete_confirm'] === "DELETE") {
            $sql = "DELETE FROM clients WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $clientEmail);
            if ($stmt->execute()) {
                session_destroy();
                header("Location: ../../login.php?msg=account_deleted");
                exit;
            } else {
                $message = '<div class="alert alert-danger">Could not delete account.</div>';
            }
        } else {
            $message = '<div class="alert alert-warning">Please type "DELETE" exactly to confirm.</div>';
        }
    }
}

// --- 4. FETCH DATA ---
$sqlFetch = "SELECT * FROM clients WHERE email = ?";
$stmt = $conn->prepare($sqlFetch);
$stmt->bind_param("s", $clientEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $clientData = $result->fetch_assoc();
} else {
    echo "Error: User account not found."; exit;
}
$stmt->close();

$clientName  = htmlspecialchars($clientData['full_name']);
$clientUser  = htmlspecialchars($clientData['username']);
$clientPhone = htmlspecialchars($clientData['phone'] ?? '');
$clientPic   = htmlspecialchars($clientData['profile_picture'] ?? '');
$initials    = strtoupper(substr($clientName, 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - Freelance.Pro</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../css/client_profile.css"> 
</head>
<body>

    <aside class="sidebar">
        <div class="logo-text">FREELANCE<span class="logo-dot">.PRO</span></div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="client_dashboard.php" class="nav-link">
                    <i class="fas fa-th-large"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="browse_talent.php" class="nav-link">
                    <i class="fas fa-search"></i> <span>Browse Talent</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="manage_postings.php" class="nav-link">
                    <i class="fas fa-briefcase"></i> <span>My Postings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-file-contract"></i> <span>Contracts</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-comment-dots"></i> <span>Messages</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="client_profile.php" class="nav-link active">
                    <i class="fas fa-user"></i> <span>Profile</span>
                </a>
            </li>
        </ul>

        <div class="user-profile-mini">
            <a href="../login.php?logout=true" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">
        
        <header class="dashboard-header">
            <div class="welcome-text">
                <h1>Account Settings</h1>
                <p>Manage your profile details and security preferences</p>
            </div>
            </header>

        <?= $message ?>

        <div class="profile-container">
            
            <aside class="profile-card-sidebar">
                <div class="profile-avatar-large">
                    <?php if(!empty($clientPic)): ?>
                        <img src="<?= $clientPic ?>" alt="" style="width:100%; height:100%; object-fit:cover; border-radius:50%;" 
                             onerror="this.style.display='none'; this.parentElement.innerHTML='<?= $initials ?>'">
                    <?php else: ?>
                        <?= $initials ?>
                    <?php endif; ?>
                </div>
                
                <h2 class="profile-name"><?= $clientName ?></h2>
                <div class="profile-role">Client Account</div>
                
                <div class="status-tag">
                    <i class="fas fa-circle" style="font-size: 8px;"></i> Active Status
                </div>

                <div class="profile-meta-data">
                    <p><i class="fas fa-envelope"></i> <?= $clientEmail ?></p>
                    <p><i class="fas fa-user-tag"></i> @<?= $clientUser ?></p>
                    <?php if($clientPhone): ?>
                        <p><i class="fas fa-phone"></i> <?= $clientPhone ?></p>
                    <?php endif; ?>
                    
                    <div class="text-xs">
                        <strong>Member since:</strong><br> Jan 2024
                    </div>
                </div>
            </aside>

            <div class="profile-content-wrapper">
                
                <div class="settings-panel">
                    <h2>General Information</h2>
                    <form method="POST" class="settings-form-grid">
                        <input type="hidden" name="section" value="profile_update">
                        
                        <div class="form-group-profile">
                            <label>Full Name</label>
                            <input type="text" name="full_name" value="<?= $clientName ?>" required>
                        </div>

                        <div class="form-group-profile">
                            <label>Username</label>
                            <input type="text" name="username" value="<?= $clientUser ?>" required>
                        </div>

                        <div class="form-group-profile">
                            <label>Phone Number</label>
                            <input type="text" name="phone" value="<?= $clientPhone ?>" placeholder="+1 234 567 890">
                        </div>

                        <div class="form-group-profile">
                            <label>Profile Picture URL</label>
                            <input type="text" name="profile_picture" value="<?= $clientPic ?>" placeholder="https://...">
                        </div>

                        <div class="form-actions-profile">
                            <button type="reset" class="btn-cancel">Cancel</button>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <div class="settings-panel">
                    <h2>Security & Privacy</h2>
                    
                    <div class="security-grid-actions">
                        
                        <div class="security-action-block">
                            <div class="action-label">Change Password</div>
                            <form method="POST">
                                <input type="hidden" name="section" value="password_update">
                                
                                <div class="form-group-profile" style="margin-bottom:10px;">
                                    <input type="password" name="current_pass" placeholder="Current Password" required>
                                </div>
                                <div class="form-group-profile" style="margin-bottom:10px;">
                                    <input type="password" name="new_pass" placeholder="New Password" required>
                                </div>
                                <div class="form-group-profile" style="margin-bottom:10px;">
                                    <input type="password" name="confirm_pass" placeholder="Confirm New Password" required>
                                </div>
                                
                                <button type="submit" class="btn-secondary btn-full">
                                    <i class="fas fa-key"></i> Update Password
                                </button>
                            </form>
                        </div>

                        <div class="security-action-block">
                            <div class="action-label danger">Danger Zone</div>
                            <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1rem;">
                                Once you delete your account, there is no going back. Please be certain.
                            </p>
                            
                            <form method="POST" onsubmit="return confirm('Are you strictly sure you want to delete your account?');">
                                <input type="hidden" name="section" value="delete_account">
                                
                                <div class="form-group-profile" style="margin-bottom:10px;">
                                    <label style="color:var(--neon-danger);">Type "DELETE" to confirm</label>
                                    <input type="text" name="delete_confirm" required style="border-color: var(--neon-danger);">
                                </div>
                                
                                <button type="submit" class="btn-danger btn-full">
                                    <i class="fas fa-trash-alt"></i> Delete Account
                                </button>
                            </form>
                        </div>

                    </div> </div>

            </div> </div> </main>

</body>
</html>