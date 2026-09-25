<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once 'database.php';

// Fetch registered students
$result = $conn->query("SELECT * FROM students WHERE TRIM(firstname) != '' AND TRIM(lastname) != '' ORDER BY id DESC");
$students = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
$total_students = count($students);

$admin_first = $_SESSION['firstname'] ?? 'Angelique';
$admin_last  = $_SESSION['lastname'] ?? 'Rivera';
$admin_initial = strtoupper(substr($admin_first, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCC &bull; Orbital Student Hub</title>
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-void: #07070c;
            --panel-core: #0f101a;
            --card-subtle: #151624;
            --neon-pink: #ff1493;
            --neon-magenta: #e0007b;
            --neon-violet: #8a2be2;
            --neon-cyan: #00f2fe;
            --border-circle: rgba(255, 20, 147, 0.22);
            --border-hover: rgba(255, 20, 147, 0.6);
            --text-light: #ffffff;
            --text-dim: #9494a8;
        }

        * {
            box-sizing: border-box;
            font-family: 'Space Grotesk', sans-serif;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        body {
            background-color: var(--bg-void);
            color: var(--text-light);
            min-height: 100vh;
            margin: 0;
            padding-bottom: 80px;
            background-image: 
                radial-gradient(circle at 50% -10%, rgba(255, 20, 147, 0.15) 0%, transparent 60%),
                radial-gradient(circle at 10% 90%, rgba(138, 43, 226, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 90% 90%, rgba(0, 242, 254, 0.08) 0%, transparent 50%);
            background-attachment: fixed;
        }

        /* Circular Orbital Header */
        .orbital-navbar {
            background: rgba(15, 16, 26, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-circle);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .halo-disc {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--neon-pink), var(--neon-violet));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.35rem;
            box-shadow: 0 0 20px rgba(255, 20, 147, 0.5);
            position: relative;
        }

        .halo-disc::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 1px dashed var(--neon-pink);
            animation: spinDisc 25s linear infinite;
        }

        @keyframes spinDisc {
            100% { transform: rotate(360deg); }
        }

        .operator-bubble {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-circle);
            padding: 4px 14px 4px 5px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--neon-pink), var(--neon-violet));
            color: white;
            font-weight: 800;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 12px rgba(255, 20, 147, 0.4);
        }

        .btn-exit-circle {
            background: rgba(244, 63, 94, 0.12);
            color: #fb7185;
            border: 1px solid rgba(244, 63, 94, 0.35);
            border-radius: 50px;
            padding: 6px 18px;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-exit-circle:hover {
            background: #f43f5e;
            color: white;
            box-shadow: 0 0 16px rgba(244, 63, 94, 0.5);
        }

        /* Circular Radar Metric Strips */
        .workspace-shell {
            max-width: 1300px;
            margin: 2.2rem auto;
            padding: 0 1.5rem;
        }

        .radar-disc-card {
            background: var(--panel-core);
            border: 1.5px solid var(--border-circle);
            border-radius: 28px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .radar-disc-card:hover {
            border-color: var(--neon-pink);
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(255, 20, 147, 0.2);
        }

        /* Circular Concentric Rings */
        .radial-ring-box {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: conic-gradient(var(--neon-pink) 0% 75%, rgba(255, 255, 255, 0.05) 75% 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 0 20px rgba(255, 20, 147, 0.25);
            position: relative;
        }

        .radial-ring-box.violet {
            background: conic-gradient(var(--neon-violet) 0% 90%, rgba(255, 255, 255, 0.05) 90% 100%);
            box-shadow: 0 0 20px rgba(138, 43, 226, 0.25);
        }

        .radial-ring-box.cyan {
            background: conic-gradient(var(--neon-cyan) 0% 100%, rgba(255, 255, 255, 0.05) 100%);
            box-shadow: 0 0 20px rgba(0, 242, 254, 0.25);
        }

        .radial-ring-inner {
            width: 66px;
            height: 66px;
            border-radius: 50%;
            background: var(--panel-core);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        /* Capsule Form Bar */
        .capsule-panel {
            background: var(--panel-core);
            border: 1.5px solid var(--border-circle);
            border-radius: 30px;
            padding: 24px 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            margin-bottom: 2rem;
        }

        .input-pill-dark {
            background: #090910;
            border: 1.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 50px;
            padding: 11px 22px;
            font-size: 0.9rem;
            color: #ffffff;
            outline: none;
            width: 100%;
            transition: all 0.2s ease;
        }

        .input-pill-dark:focus {
            border-color: var(--neon-pink);
            background: #0f0f18;
            box-shadow: 0 0 15px rgba(255, 20, 147, 0.3);
        }

        .btn-launch-pill {
            background: linear-gradient(135deg, var(--neon-pink), var(--neon-violet));
            color: white;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            padding: 12px 24px;
            font-size: 0.92rem;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 0 20px rgba(255, 20, 147, 0.35);
        }

        .btn-launch-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 28px rgba(255, 20, 147, 0.55);
            color: white;
        }

        /* Circular Roster Ledger */
        .ledger-panel {
            background: var(--panel-core);
            border: 1.5px solid var(--border-circle);
            border-radius: 30px;
            padding: 28px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.5);
        }

        .table-circular {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table-circular thead th {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-dim);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 6px 18px;
            border: none;
        }

        .table-circular tbody tr {
            background: var(--card-subtle);
            border: 1px solid var(--border-circle);
            transition: all 0.2s ease;
        }

        .table-circular tbody tr td {
            padding: 14px 18px;
            border-top: 1px solid var(--border-circle);
            border-bottom: 1px solid var(--border-circle);
            vertical-align: middle;
        }

        .table-circular tbody tr td:first-child {
            border-left: 1px solid var(--border-circle);
            border-radius: 50px 0 0 50px;
        }

        .table-circular tbody tr td:last-child {
            border-right: 1px solid var(--border-circle);
            border-radius: 0 50px 50px 0;
        }

        .table-circular tbody tr:hover {
            border-color: var(--neon-pink);
            background: #1b1c30;
            transform: translateX(4px);
        }

        .student-disc-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 20, 147, 0.12);
            border: 1px solid var(--border-circle);
            color: var(--neon-pink);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.95rem;
        }

        .id-capsule {
            background: rgba(255, 20, 147, 0.08);
            border: 1px solid var(--border-circle);
            color: var(--neon-pink);
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.74rem;
            font-weight: 700;
        }

        .btn-circle-action {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1px solid var(--border-circle);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text-dim);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-circle-action:hover {
            color: var(--neon-pink);
            border-color: var(--neon-pink);
            background: rgba(255, 20, 147, 0.15);
            transform: scale(1.1);
        }

        .btn-circle-action.del:hover {
            color: #f43f5e;
            border-color: #f43f5e;
            background: rgba(244, 63, 94, 0.15);
        }

        /* Modal Overrides */
        .modal-radial {
            background: #0f101a;
            border: 1.5px solid var(--neon-pink);
            border-radius: 28px;
            color: #ffffff;
            box-shadow: 0 0 40px rgba(255, 20, 147, 0.3);
        }
    </style>
</head>
<body>

    <!-- Orbital Topbar -->
    <header class="orbital-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="halo-disc">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="fw-bold mb-0 text-white">Baao Community College</h5>
                        <span class="badge rounded-pill bg-danger bg-opacity-25 border border-danger text-light mono" style="font-size: 0.72rem;">BSIS-3A</span>
                    </div>
                    <div class="mono" style="font-size: 0.72rem; color: var(--text-dim);">
                        RADIAL REPOSITORY &bull; phpcrudangelique
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="operator-bubble d-none d-sm-flex">
                    <div class="avatar-circle"><?= $admin_initial; ?></div>
                    <div class="text-start pe-2">
                        <div class="fw-bold small text-white leading-tight"><?= htmlspecialchars($admin_first . ' ' . $admin_last); ?></div>
                        <div class="mono" style="font-size: 0.68rem; color: var(--neon-pink);">&bull; ACTIVE_NODE</div>
                    </div>
                </div>
                <a href="logout.php" class="btn-exit-circle mono">LOGOUT</a>
            </div>
        </div>
    </header>

    <main class="workspace-shell">

        <!-- Flash Notices -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert border-0 py-2 px-3 mb-4 rounded-pill d-flex align-items-center gap-2 small" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1.5px solid rgba(16, 185, 129, 0.35) !important;">
                <i class="bi bi-check-circle-fill"></i>
                <div><?= htmlspecialchars($_GET['success']); ?></div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert border-0 py-2 px-3 mb-4 rounded-pill d-flex align-items-center gap-2 small" style="background: rgba(244, 63, 94, 0.15); color: #fca5a5; border: 1.5px solid rgba(244, 63, 94, 0.35) !important;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div><?= htmlspecialchars($_GET['error']); ?></div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- 3 Concentric Circular Telemetry Gauges -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="radar-disc-card">
                    <div class="radial-ring-box">
                        <div class="radial-ring-inner" style="color: var(--neon-pink);">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div>
                        <div class="mono small" style="color: var(--text-dim); font-size: 0.74rem;">ENROLLED RADAR</div>
                        <h2 class="fw-bold mb-0 mt-1 text-white" id="kpiCount"><?= $total_students; ?></h2>
                        <span class="mono small" style="color: var(--neon-pink); font-size: 0.72rem;">&bull; Active Profiles</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="radar-disc-card">
                    <div class="radial-ring-box violet">
                        <div class="radial-ring-inner" style="color: var(--neon-violet);">
                            <i class="bi bi-award-fill"></i>
                        </div>
                    </div>
                    <div>
                        <div class="mono small" style="color: var(--text-dim); font-size: 0.74rem;">CURRENT COHORT</div>
                        <h4 class="fw-bold mb-0 mt-1 text-white">BSIS 3A</h4>
                        <span class="mono small text-secondary" style="font-size: 0.72rem;">Information Systems</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="radar-disc-card">
                    <div class="radial-ring-box cyan">
                        <div class="radial-ring-inner" style="color: var(--neon-cyan);">
                            <i class="bi bi-database-check"></i>
                        </div>
                    </div>
                    <div>
                        <div class="mono small" style="color: var(--text-dim); font-size: 0.74rem;">DATABASE NODE</div>
                        <h6 class="fw-bold mb-0 mt-2 text-white mono font-monospace">phpcrudangelique</h6>
                        <span class="mono small text-success" style="font-size: 0.72rem;">&bull; MySQL Cluster Live</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pill Capsule Registration Bar -->
        <div class="capsule-panel">
            <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                <h6 class="fw-bold text-white mb-0">Direct Student Intake</h6>
                <span class="small mono" style="color: var(--neon-pink); font-size: 0.75rem;">commit &rarr; table `students`</span>
            </div>

            <form action="insert.php" method="POST" class="row g-2 align-items-center mt-1">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

                <div class="col-md-5">
                    <input type="text" name="firstname" class="input-pill-dark mono" placeholder="First Name" maxlength="50" required autofocus>
                </div>
                <div class="col-md-5">
                    <input type="text" name="lastname" class="input-pill-dark mono" placeholder="Last Name" maxlength="50" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-launch-pill mono">
                        <i class="bi bi-plus-lg me-1"></i> ENROLL
                    </button>
                </div>
            </form>
        </div>

        <!-- Circular Data Stream Ledger -->
        <div class="ledger-panel">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 px-2">
                <div>
                    <h5 class="fw-bold mb-0 text-white">Student Roster Orbit</h5>
                    <span class="small mono" style="color: var(--text-dim);">Live records stream</span>
                </div>

                <div class="position-relative" style="width: 260px;">
                    <input type="text" id="liveSearchInput" class="input-pill-dark mono" placeholder="Search orbit by name..." style="font-size: 0.85rem; padding: 8px 18px;">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-circular">
                    <thead>
                        <tr>
                            <th class="mono">INDEX</th>
                            <th>STUDENT NAME</th>
                            <th>FIRST NAME</th>
                            <th>LAST NAME</th>
                            <th class="text-end mono">MANAGE</th>
                        </tr>
                    </thead>
                    <tbody id="studentRecordsBody">
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as$student): ?>
                                <tr class="student-row-entity">
                                    <td>
                                        <span class="id-capsule mono">#<?= str_pad($student['id'], 3, '0', STR_PAD_LEFT); ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="student-disc-avatar">
                                                <?= strtoupper(substr($student['firstname'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <strong class="text-white target-fullname"><?= htmlspecialchars($student['firstname'] . ' ' .$student['lastname']); ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-secondary"><?= htmlspecialchars($student['firstname']); ?></td>
                                    <td class="text-secondary"><?= htmlspecialchars($student['lastname']); ?></td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" class="btn-circle-action" data-bs-toggle="modal" data-bs-target="#editModal<?= $student['id']; ?>" title="Modify Record">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <form action="delete.php" method="POST" onsubmit="return confirm('Purge this student record?');" class="m-0">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                                <input type="hidden" name="id" value="<?= $student['id']; ?>">
                                                <button type="submit" class="btn-circle-action del" title="Purge Record">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Circular Edit Modal -->
                                        <div class="modal fade" id="editModal<?= $student['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered text-start">
                                                <div class="modal-content modal-radial p-3">
                                                    <form action="update.php" method="POST">
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold text-white">Modify Orbit Entity</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body py-3">
                                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                                            <input type="hidden" name="id" value="<?= $student['id']; ?>">

                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold mono" style="color: var(--text-dim);">First Name</label>
                                                                <input type="text" name="firstname" class="input-pill-dark mono" value="<?= htmlspecialchars($student['firstname']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold mono" style="color: var(--text-dim);">Last Name</label>
                                                                <input type="text" name="lastname" class="input-pill-dark mono" value="<?= htmlspecialchars($student['lastname']); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3 small mono" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn-launch-pill px-4 mono" style="width: auto;">Save Orbit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted mono">
                                    No student entities detected in current orbit.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const searchInput = document.getElementById('liveSearchInput');
        const rows = document.querySelectorAll('.student-row-entity');
        const counterBadge = document.getElementById('kpiCount');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let matches = 0;

                rows.forEach(row => {
                    const name = row.querySelector('.target-fullname').textContent.toLowerCase();
                    if (name.includes(query)) {
                        row.style.display = '';
                        matches++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (counterBadge) counterBadge.textContent = matches;
            });
        }
    </script>
</body>
</html>