<?php
session_start();

// --- 1. SESSION & ROLE CHECK ---
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'client') {
    $redirect = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'seller') ? '../freelancer/freelancer_dashboard.php' : '../../login.php';
    header("Location: " . $redirect);
    exit;
}

$message = '';
// ASSUMPTION: Replace '1' with the actual client ID from your session after successful login
$client_id = 1; 

// --- 2. DATABASE CONFIGURATION ---
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); 
define('DB_NAME', 'webtech_project'); 

// --- 3. CRUD OPERATIONS ---

// Function to handle database connection
function getDbConnection() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Handle Form Submissions (Update or Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = getDbConnection();
        
        if (isset($_POST['action']) && $_POST['action'] === 'edit') {
            // --- UPDATE LOGIC ---
            $id = $_POST['id'];
            $title = trim($_POST['title']);
            $category = $_POST['category'];
            $budget_type = $_POST['budget_type'];
            $budget_amount = $_POST['budget_amount'];
            $status = $_POST['status'];

            $sql = "UPDATE job_postings SET title=?, category=?, budget_type=?, budget_amount=?, status=? WHERE id=? AND client_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdssi", $title, $category, $budget_type, $budget_amount, $status, $id, $client_id);
            
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Posting ID ' . $id . ' saved successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger">Error updating posting ID ' . $id . ': ' . $stmt->error . '</div>';
            }
            $stmt->close();
            
        } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
            // --- DELETE LOGIC ---
            $id = $_POST['id'];
            $sql = "DELETE FROM job_postings WHERE id=? AND client_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id, $client_id);

            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Posting ID ' . $id . ' deleted successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger">Error deleting posting ID ' . $id . ': ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
        $conn->close();

    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">System Error: ' . $e->getMessage() . '</div>';
    }
}

// --- 4. FETCH DATA ---
$jobPostings = [];
try {
    $conn = getDbConnection();
    // Fetch only the jobs posted by the current client_id
    $sql = "SELECT id, title, description, category, budget_type, budget_amount, status FROM job_postings WHERE client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $jobPostings[] = $row;
    }

    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    $message .= '<div class="alert alert-danger">Data Fetch Error: ' . $e->getMessage() . '</div>';
    // Use mock data if DB connection fails completely
    $jobPostings = [
        ['id' => 101, 'title' => 'Senior React Developer', 'category' => 'web_development', 'budget_type' => 'fixed', 'budget_amount' => 5000.00, 'status' => 'open'],
        ['id' => 102, 'title' => 'Logo Design for Startup', 'category' => 'design_creative', 'budget_type' => 'fixed', 'budget_amount' => 500.00, 'status' => 'closed'],
        ['id' => 103, 'title' => 'SEO Audit', 'category' => 'sales_marketing', 'budget_type' => 'hourly', 'budget_amount' => 50.00, 'status' => 'open'],
    ];
}

// Categories and Status options for inline editing dropdowns
$categories = [
    'web_development' => 'Web Development', 
    'mobile_development' => 'Mobile Development', 
    'design_creative' => 'Design & Creative', 
    'writing_translation' => 'Writing & Translation', 
    'sales_marketing' => 'Sales & Marketing', 
    'data_science' => 'Data Science'
];
$statuses = ['open' => 'Open', 'closed' => 'Closed'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Postings - Freelance.Pro</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/sidebar.css">

</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="logo-text">FREELANCE<span class="logo-dot">.PRO</span></div>
    <ul class="nav-menu">
        <li class="nav-item"><a href="client_dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> <span>Dashboard</span></a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-search"></i> <span>Browse Talent</span></a></li>
        <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-briefcase"></i> <span>My Postings</span></a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-file-contract"></i> <span>Contracts</span></a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-comment-dots"></i> <span>Messages</span></a></li>
        <li class="nav-item"><a href="client_profile.php" class="nav-link"><i class="fas fa-user-cog"></i> <span>Profile</span></a></li>
    </ul>
    
    <div class="user-profile-mini">
         <a href="../login.php?logout=true" class="logout-link">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="main-content">

    <header class="dashboard-header">
        <div class="welcome-text">
            <h1>Manage Job Postings</h1>
            <p>View, edit, or close your existing job requests.</p>
        </div>
        <button class="btn-primary" onclick="window.location.href='post_job.php'"><i class="fas fa-plus"></i> Create New Posting</button>
    </header>

    <?= $message ?>
    
    <!-- Job Postings Table Container -->
    <div class="table-container">
        <table class="custom-table job-post-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Budget</th>
                    <th>Rate Type</th>
                    <th>Status</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($jobPostings)): ?>
                    <tr>
                        <td colspan="7" class="no-data-row">No active job postings found. Click "Create New Posting" to start.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($jobPostings as $job): 
                        $jobId = $job['id'];
                        $isEditing = ($_GET['edit'] ?? null) == $jobId;
                    ?>
                        <tr id="row-<?= $jobId ?>">
                            <form method="POST" id="form-<?= $jobId ?>">
                                <input type="hidden" name="id" value="<?= $jobId ?>">
                                <input type="hidden" name="action" value="edit">

                                <td><?= $jobId ?></td>
                                
                                <!-- Title (Text Input) -->
                                <td>
                                    <?php if ($isEditing): ?>
                                        <input type="text" name="title" value="<?= htmlspecialchars($job['title']) ?>" required class="inline-input">
                                    <?php else: ?>
                                        <?= htmlspecialchars($job['title']) ?>
                                    <?php endif; ?>
                                </td>

                                <!-- Category (Dropdown) -->
                                <td>
                                    <?php if ($isEditing): ?>
                                        <select name="category" class="inline-select">
                                            <?php foreach($categories as $key => $label): ?>
                                                <option value="<?= $key ?>" <?= ($key == $job['category']) ? 'selected' : '' ?>>
                                                    <?= $label ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <?= htmlspecialchars($categories[$job['category']] ?? $job['category']) ?>
                                    <?php endif; ?>
                                </td>

                                <!-- Budget Amount (Number Input) -->
                                <td>
                                    <?php if ($isEditing): ?>
                                        <input type="number" name="budget_amount" value="<?= number_format($job['budget_amount'], 2, '.', '') ?>" step="0.01" required class="inline-input inline-amount">
                                    <?php else: ?>
                                        $<?= number_format($job['budget_amount'], 2) ?>
                                    <?php endif; ?>
                                </td>

                                <!-- Budget Type (Dropdown) -->
                                <td>
                                    <?php if ($isEditing): ?>
                                        <select name="budget_type" class="inline-select inline-rate">
                                            <option value="fixed" <?= ($job['budget_type'] == 'fixed') ? 'selected' : '' ?>>Fixed</option>
                                            <option value="hourly" <?= ($job['budget_type'] == 'hourly') ? 'selected' : '' ?>>Hourly</option>
                                        </select>
                                    <?php else: ?>
                                        <?= ucfirst($job['budget_type']) ?>
                                    <?php endif; ?>
                                </td>

                                <!-- Status (Dropdown / Badge) -->
                                <td>
                                    <?php if ($isEditing): ?>
                                        <select name="status" class="inline-select inline-status">
                                            <?php foreach($statuses as $key => $label): ?>
                                                <option value="<?= $key ?>" <?= ($key == $job['status']) ? 'selected' : '' ?>>
                                                    <?= $label ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <span class="badge <?= $job['status'] ?>"><?= ucfirst($job['status']) ?></span>
                                    <?php endif; ?>
                                </td>

                                <!-- Actions -->
                                <td class="table-action-group">
                                    <?php if ($isEditing): ?>
                                        <button type="submit" class="btn-xs view" title="Save Changes"><i class="fas fa-save"></i> Save</button>
                                        <a href="manage_postings.php" class="btn-xs danger" title="Cancel Edit"><i class="fas fa-times"></i> Cancel</a>
                                    <?php else: ?>
                                        <a href="?edit=<?= $jobId ?>" class="btn-xs view" title="Edit Posting"><i class="fas fa-pen"></i> Edit</a>
                                        <button type="button" class="btn-xs danger" onclick="deletePosting(<?= $jobId ?>)" title="Delete Posting"><i class="fas fa-trash"></i> Delete</button>
                                    <?php endif; ?>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Hidden Delete Form (for handling DELETE request) -->
    <form method="POST" id="deleteForm">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="deletePostId">
    </form>
</main>

<script>
    // JavaScript function to handle the delete action using the hidden form
    function deletePosting(id) {
        // NOTE: We replace confirm() with a visual modal in a real production environment.
        if (confirm('Are you sure you want to permanently delete job posting ID ' + id + '?')) {
            document.getElementById('deletePostId').value = id;
            document.getElementById('deleteForm').submit();
        }
    }
</script>

</body>
</html>