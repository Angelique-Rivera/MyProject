<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCC &bull; Student Enrollment Portal</title>
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-black: #0a0a0f;
            --panel-charcoal: #14141d;
            --hotpink-neon: #ff1493;
            --violet-neon: #8a2be2;
            --border-glow: rgba(255, 20, 147, 0.25);
            --text-title: #ffffff;
            --text-sub: #9e9ea7;
        }

        * {
            box-sizing: border-box;
            font-family: 'Space Grotesk', sans-serif;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        body {
            background-color: var(--bg-black);
            color: var(--text-title);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(255, 20, 147, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(138, 43, 226, 0.12) 0%, transparent 45%);
            background-attachment: fixed;
        }

        .auth-card {
            width: 100%;
            max-width: 380px;
            background: var(--panel-charcoal);
            border: 1.5px solid var(--border-glow);
            border-radius: 20px;
            padding: 38px 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8), 0 0 25px rgba(255, 20, 147, 0.1);
            text-align: center;
            position: relative;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--hotpink-neon), var(--violet-neon));
            border-radius: 20px 20px 0 0;
        }

        .brand-icon-circ {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--hotpink-neon), var(--violet-neon));
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            margin-bottom: 20px;
            box-shadow: 0 0 20px rgba(255, 20, 147, 0.4);
        }

        .input-neon {
            background: #0d0d14;
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 12px 16px;
            color: #ffffff;
            font-size: 0.92rem;
            width: 100%;
            outline: none;
            transition: all 0.2s ease;
        }

        .input-neon:focus {
            border-color: var(--hotpink-neon);
            box-shadow: 0 0 15px rgba(255, 20, 147, 0.35);
            background: #11111c;
        }

        .btn-neon-pink {
            background: linear-gradient(135deg, var(--hotpink-neon) 0%, #d81b60 100%);
            color: #ffffff;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 13px;
            width: 100%;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 18px rgba(255, 20, 147, 0.4);
        }

        .btn-neon-pink:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 20, 147, 0.6);
            color: white;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="brand-icon-circ">
            <i class="bi bi-mortarboard-fill"></i>
        </div>

        <h4 class="fw-bold mb-1">Baao Community College</h4>
        <p class="small text-muted mb-2">Student Enrollment System</p>
        
        <div class="mb-4">
            <span class="badge border border-secondary border-opacity-50 text-light mono py-1 px-3" style="font-size: 0.72rem;">
                BSIS-3A &bull; phpcrudangelique
            </span>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger border-0 small py-2 px-3 mb-3 rounded-3 d-flex align-items-center gap-2" style="background: rgba(220, 38, 38, 0.2); color: #fca5a5;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div><?= htmlspecialchars($_GET['error']); ?></div>
            </div>
        <?php endif; ?>

        <form action="pakicheck.php" method="POST" class="text-start">
            <div class="mb-3">
                <label class="form-label small fw-bold mono" style="color: var(--text-sub);">USERNAME</label>
                <input type="text" name="username" class="input-neon mono" placeholder="e.g. angelique" required autofocus>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold mono" style="color: var(--text-sub);">PASSWORD</label>
                <input type="password" name="password" class="input-neon mono" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-neon-pink mono">
                LOGIN TO PORTAL &rarr;
            </button>
        </form>

        <div class="text-center mt-4 pt-2">
            <span class="small mono" style="color: var(--text-sub); font-size: 0.74rem;">
                &copy; <?= date('Y'); ?> Angelique Rivera &bull; Student Records
            </span>
        </div>
    </div>

</body>
</html>