<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' — AppAuto' : 'AppAuto'; ?></title>
    <meta name="description" content="AppAuto — Sistema de Gestão Automotiva SaaS">
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <!-- Font Awesome 4.7 (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:     #1a56db;
            --primary-dk:  #1e429f;
            --accent:      #f97316;
            --bg:          #0f172a;
            --bg2:         #1e293b;
            --card:        #ffffff;
            --text:        #1e293b;
            --text-light:  #64748b;
            --border:      #e2e8f0;
            --success:     #16a34a;
            --danger:      #dc2626;
            --radius:      12px;
            --shadow:      0 20px 60px rgba(0,0,0,0.35);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg) 0%, var(--bg2) 50%, #0c1a3a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Partículas decorativas */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 20% 50%, rgba(26,86,219,0.08) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(249,115,22,0.06) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .public-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
        }

        .public-card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 40px 48px;
            width: 100%;
        }

        @media (max-width: 520px) {
            .public-card { padding: 32px 24px; }
        }

        /* Logo */
        .brand-logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-logo img {
            max-height: 70px;
            max-width: 220px;
            object-fit: contain;
        }
        .brand-logo .brand-name {
            display: block;
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
            margin-top: 8px;
            letter-spacing: -0.5px;
        }
        .brand-logo .brand-tagline {
            font-size: 12px;
            color: var(--text-light);
            margin-top: 2px;
        }

        /* Títulos */
        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
            text-align: center;
        }
        .card-subtitle {
            font-size: 13px;
            color: var(--text-light);
            text-align: center;
            margin-bottom: 28px;
        }

        /* Formulário */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            background: #f8fafc;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,86,219,.12);
            background: #fff;
        }
        .form-control.is-invalid { border-color: var(--danger); }
        .form-control.is-valid   { border-color: var(--success); }

        select.form-control { cursor: pointer; }

        /* Input com ícone */
        .input-group { position: relative; }
        .input-group .form-control { padding-left: 40px; }
        .input-group .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 14px;
        }

        /* Botão principal */
        .btn-primary {
            width: 100%;
            padding: 13px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background .2s, transform .1s;
            margin-top: 4px;
        }
        .btn-primary:hover  { background: var(--primary-dk); }
        .btn-primary:active { transform: scale(.98); }
        .btn-primary:disabled { opacity: .6; cursor: not-allowed; }

        /* Botão secundário */
        .btn-secondary {
            width: 100%;
            padding: 12px;
            background: transparent;
            color: var(--primary);
            border: 1.5px solid var(--primary);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all .2s;
            margin-top: 10px;
        }
        .btn-secondary:hover { background: rgba(26,86,219,.06); }

        /* Alertas */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-danger  { background: #fef2f2; color: var(--danger);  border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: var(--success); border: 1px solid #bbf7d0; }
        .alert-info    { background: #eff6ff; color: var(--primary); border: 1px solid #bfdbfe; }

        /* Divisor */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: var(--text-light);
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Links */
        .text-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
        }
        .text-link:hover { text-decoration: underline; }

        .card-footer-links {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--text-light);
        }

        /* Checkbox / Radio */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text);
            cursor: pointer;
        }
        .form-check input { width: 16px; height: 16px; cursor: pointer; accent-color: var(--primary); }

        /* Grupo de opções (tipo conta) */
        .option-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 18px;
        }
        .option-card {
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 14px 12px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            position: relative;
        }
        .option-card:hover { border-color: var(--primary); background: #f0f4ff; }
        .option-card.selected { border-color: var(--primary); background: #eff6ff; }
        .option-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .option-card .option-icon { font-size: 24px; margin-bottom: 6px; color: var(--primary); }
        .option-card .option-label { font-size: 13px; font-weight: 600; color: var(--text); }
        .option-card .option-desc  { font-size: 11px; color: var(--text-light); margin-top: 2px; }

        /* Steps / Progress */
        .steps {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 28px;
        }
        .step-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--border);
            transition: background .3s;
        }
        .step-dot.active { background: var(--primary); width: 24px; border-radius: 4px; }
        .step-dot.done   { background: var(--success); }

        /* Token input */
        .token-input-group {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin: 20px 0;
        }
        .token-digit {
            width: 48px;
            height: 56px;
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            border: 2px solid var(--border);
            border-radius: 8px;
            background: #f8fafc;
            color: var(--text);
            outline: none;
            transition: border-color .2s;
        }
        .token-digit:focus { border-color: var(--primary); background: #fff; }

        /* Spinner */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            vertical-align: middle;
            margin-right: 6px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Utilitários */
        .text-center { text-align: center; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .mt-4 { margin-top: 16px; }
        .hidden { display: none !important; }
        .text-sm { font-size: 12px; }
        .text-muted { color: var(--text-light); }
        .fw-600 { font-weight: 600; }
        .text-success { color: var(--success); }
        .text-danger  { color: var(--danger); }
    </style>
</head>
<body>
<div class="public-wrapper">
    <div class="public-card">
        <!-- Logo -->
        <div class="brand-logo">
            <img src="/assets/logo.png" alt="AppAuto" onerror="this.style.display='none'">
            <span class="brand-name">AppAuto</span>
            <span class="brand-tagline">Sistema de Gestão Automotiva</span>
        </div>
