<?php
// admin/login.php - secure login for SIMAS BERKAH
session_start();

// development debug (disable in production)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

require_once __DIR__ . '/../config/helpers.php'; // loads DB connection and catat_log()

// simple helper to escape output
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// create CSRF token for GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
    }
}

// handle POST login
$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // check csrf
    $token = $_POST['csrf_token'] ?? '';
    if (empty($token) || !hash_equals((string)($_SESSION['csrf_token'] ?? ''), $token)) {
        $err = 'Invalid request (CSRF).';
        catat_log('AUTH','WARN','CSRF token mismatch on login attempt', '', $_SERVER['REMOTE_ADDR'] ?? 'cli');
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $err = 'Masukkan username dan password.';
        } else {
            // prepared statement to fetch user
            $stmt = $conn->prepare("SELECT id, username, password, fullname FROM users WHERE username = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $res = $stmt->get_result();
                $user = $res->fetch_assoc();
                $stmt->close();

                if ($user) {
                    // verify password (bcrypt)
                    if (password_verify($password, $user['password'])) {
                        // regenerate session id and set session
                        session_regenerate_id(true);
                        $_SESSION['user'] = $user['username'];
                        $_SESSION['fullname'] = $user['fullname'] ?? $user['username'];
                        catat_log('AUTH','INFO','Login berhasil', 'user:'.$user['username'], $_SERVER['REMOTE_ADDR'] ?? 'cli');
                        // redirect to dashboard
                        header('Location: dashboard.php');
                        exit;
                    } else {
                        $err = 'Username atau password salah.';
                        catat_log('AUTH','WARN','Login gagal - invalid password', 'user:'.$username, $_SERVER['REMOTE_ADDR'] ?? 'cli');
                    }
                } else {
                    $err = 'Username atau password salah.';
                    catat_log('AUTH','WARN','Login gagal - user not found', 'user:'.$username, $_SERVER['REMOTE_ADDR'] ?? 'cli');
                }
            } else {
                $err = 'Internal error (DB).';
                catat_log('AUTH','ERROR','Failed prepare statement login', '', $_SERVER['REMOTE_ADDR'] ?? 'cli');
            }
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login Admin - SIMAS BERKAH</title>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;background:#f4f6f5;padding:30px}
    .card{max-width:420px;margin:30px auto;padding:20px;background:#fff;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.06)}
    h1{font-size:20px;margin:0 0 12px;color:#0a6b3c}
    .err{color:#b00020;margin-bottom:12px}
    input[type=text],input[type=password]{width:100%;padding:10px;margin:6px 0 12px;border:1px solid #ddd;border-radius:4px}
    button{background:#0a6b3c;color:#fff;padding:10px 14px;border:none;border-radius:6px;cursor:pointer}
    small.note{display:block;margin-top:10px;color:#666}
  </style>
</head>
<body>
  <div class="card">
    <h1>Admin Login — SIMAS BERKAH</h1>

    <?php if (!empty($err)): ?>
      <div class="err"><?php echo e($err); ?></div>
    <?php endif; ?>

    <form method="post" action="login.php" autocomplete="off">
      <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['csrf_token'] ?? ''); ?>">
      <label>
        <div>Username</div>
        <input type="text" name="username" required autofocus>
      </label>
      <label>
        <div>Password</div>
        <input type="password" name="password" required>
      </label>
      <button type="submit">Masuk</button>
      <small class="note">Default admin: <strong>admin</strong> / <strong>123456</strong> — ubah setelah login.</small>
    </form>
  </div>
</body>
</html>
