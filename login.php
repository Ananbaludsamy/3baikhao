<?php session_start(); ?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ເຂົ້າສູ່ລະບົບ - ຮ້ານເຝີເຮືອ 3ໃບເຂົາ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Phetsarath', sans-serif; min-height: 100vh; background: #7c2d12; display: flex; align-items: center; justify-content: center; padding: 1rem; position: relative; overflow: hidden; }
        body::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 30% 20%, rgba(251,146,60,.25) 0%, transparent 60%), radial-gradient(ellipse at 70% 80%, rgba(180,83,9,.3) 0%, transparent 60%); pointer-events: none; }
        .card { background: white; border-radius: 24px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 25px 60px rgba(0,0,0,.35); position: relative; }
        .input-wrap { position: relative; }
        .input-wrap .icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 14px; pointer-events: none; }
        .form-input { width: 100%; padding: 12px 14px 12px 40px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 15px; outline: none; transition: border-color .2s, box-shadow .2s; background: white; font-family: 'Phetsarath', sans-serif; color: #1e293b; }
        .form-input:focus { border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,.12); }
        .btn-login { width: 100%; background: #7c2d12; color: white; font-weight: 700; padding: 13px; border-radius: 12px; border: none; cursor: pointer; font-family: 'Phetsarath', sans-serif; font-size: 15px; transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-login:hover { background: #b45309; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(124,45,18,.3); }
        .btn-login:active { transform: translateY(0); }
        .error-box { background: #fef2f2; border-left: 4px solid #ef4444; color: #b91c1c; padding: 12px 14px; border-radius: 10px; font-size: 14px; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 8px; }
        label { display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 6px; }
        .deco-circle { position: absolute; border-radius: 50%; pointer-events: none; }
    </style>
</head>
<body>
    <div class="deco-circle" style="width:300px;height:300px;background:rgba(255,255,255,.04);top:-80px;right:-80px;"></div>
    <div class="deco-circle" style="width:200px;height:200px;background:rgba(255,255,255,.04);bottom:-50px;left:-50px;"></div>
    <div class="deco-circle" style="width:80px;height:80px;background:rgba(251,146,60,.15);top:40%;left:5%;"></div>

    <div class="card">
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:72px;height:72px;background:#fef3c7;border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;border:2px solid #fde68a;">
                <img src="./assets/img/3baikhao_logo.jpg" alt="Logo" style="width:56px;height:56px;border-radius:12px;object-fit:cover;" onerror="this.parentElement.innerHTML='<i class=\'fa-solid fa-bowl-food\' style=\'font-size:28px;color:#7c2d12;\'></i>'">
            </div>
            <h1 style="font-size:20px;font-weight:700;color:#1e293b;line-height:1.3;">ຮ້ານເຝີເຮືອ 3ໃບເຂົາ</h1>
            <p style="font-size:14px;color:#64748b;margin-top:4px;">ລະບົບບໍລິຫານຈັດການຮ້ານອາຫານ</p>
        </div>

        <?php if(isset($_SESSION['login_error'])): ?>
        <div class="error-box">
            <i class="fa-solid fa-circle-exclamation"></i>
            <?php echo htmlspecialchars($_SESSION['login_error']); unset($_SESSION['login_error']); ?>
        </div>
        <?php endif; ?>

        <form action="actions/auth_login.php" method="POST" style="display:flex;flex-direction:column;gap:1.1rem;">
            <div>
                <label>ຊື່ຜູ້ໃຊ້ (Username)</label>
                <div class="input-wrap">
                    <span class="icon"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" required autocomplete="username" class="form-input" placeholder="ກະລຸນາໃສ່ Username">
                </div>
            </div>
            <div>
                <label>ລະຫັດຜ່ານ (Password)</label>
                <div class="input-wrap">
                    <span class="icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="password-input" required autocomplete="current-password" class="form-input" placeholder="••••••••">
                    <button type="button" onclick="togglePassword()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#64748b;cursor:pointer;padding:4px;">
                        <i class="fa-solid fa-eye" id="pw-toggle-icon" style="font-size:13px;"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-login" style="margin-top:.3rem;">
                <i class="fa-solid fa-right-to-bracket"></i>
                ເຂົ້າສູ່ລະບົບ
            </button>
        </form>

        <div style="text-align:center;margin-top:1.5rem;padding-top:1.2rem;border-top:1px solid #f1f5f9;">
            <p style="font-size:12px;color:#94a3b8;">ຮ້ານເຝີເຮືອ 3ໃບເຂົາ &copy; <?php echo date('Y'); ?></p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password-input');
            const icon = document.getElementById('pw-toggle-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        }
    </script>
</body>
</html>
