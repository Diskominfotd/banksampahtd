<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('logotanahdatar.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — B-STAR - Bank Sampah Tanah Datar Pintar · Kabupaten Tanah Datar</title>
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Syne:wght@600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            /* Base brand — hijau tua, dipakai untuk background & tombol utama */
            --brand-dark: #1b5e20;
            --brand-mid: #2e7d32;
            --brand-light: #388e3c;

            /* Aksen — emerald, dipakai untuk border/focus/ikon supaya lebih hidup */
            --cyan: #059669;
            --cyan-10: rgba(5, 150, 105, .10);
            --cyan-bd: rgba(5, 150, 105, .35);
            --border: rgba(5, 150, 105, .18);

            --bs-primary: #059669;
            --bs-primary-rgb: 5, 150, 105;

            --blue: #1976d2;
            --blue-10: color-mix(in srgb, var(--blue) 10%, white);
            --bs-blue: #1976d2;

            --bg-deep: #f0f7f2;
            --muted: rgba(20, 60, 30, .45);
            --text-main: #0d2113;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-deep);
            overflow-x: hidden;
        }

        /* BG */
        .bg-layer {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: linear-gradient(160deg, var(--brand-dark) 0%, var(--brand-mid) 45%, var(--brand-light) 100%);
            overflow: hidden;
        }

        .bg-blob {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
        }

        .bg-blob.b1 {
            width: 480px;
            height: 480px;
            top: -160px;
            right: -120px;
        }

        .bg-blob.b2 {
            width: 280px;
            height: 280px;
            bottom: -80px;
            left: -60px;
        }

        .bg-blob.b3 {
            width: 160px;
            height: 160px;
            top: 55%;
            left: 35%;
        }

        .bg-blob.b4 {
            width: 60px;
            height: 60px;
            top: 20%;
            left: 20%;
            background: rgba(255, 255, 255, .09);
        }

        .leaf {
            position: absolute;
            border-radius: 70% 30% 60% 40%/40% 60% 40% 60%;
            border: 1px solid rgba(255, 255, 255, .12);
        }

        .leaf.l1 {
            width: 120px;
            height: 80px;
            top: 12%;
            right: 18%;
            transform: rotate(30deg);
        }

        .leaf.l2 {
            width: 70px;
            height: 48px;
            bottom: 22%;
            left: 14%;
            transform: rotate(-20deg);
        }

        .leaf.l3 {
            width: 90px;
            height: 60px;
            top: 60%;
            right: 12%;
            transform: rotate(55deg);
        }

        /* LAYOUT */
        .login-wrap {
            position: relative;
            z-index: 1;
            display: flex;
            min-height: 100vh;
            align-items: stretch;
        }

        /* LEFT */
        .left-panel {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: clamp(32px, 4vw, 48px) clamp(28px, 4vw, 52px);
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .brand-top {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
        }

        .brand-logo {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .20);
            border: 1.5px solid rgba(255, 255, 255, .35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 32px;
            flex-shrink: 0;
            animation: fadeUp .5s ease both;
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: clamp(24px, 2.4vw, 32px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 14px;
            animation: fadeUp .5s .08s ease both;
        }

        .brand-tagline {
            font-size: 14px;
            color: rgba(255, 255, 255, .75);
            line-height: 1.6;
            max-width: 320px;
            margin-bottom: 44px;
            animation: fadeUp .5s .14s ease both;
        }

        .stat-chips {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
            animation: fadeUp .5s .20s ease both;
        }

        .stat-chip {
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 14px;
            padding: 12px 18px;
            min-width: clamp(88px, 10vw, 110px);
        }

        .stat-chip-n {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }

        .stat-chip-l {
            font-size: 10px;
            color: rgba(255, 255, 255, .65);
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        .left-footer {
            margin-top: auto;
            font-size: 11px;
            color: rgba(255, 255, 255, .45);
            position: relative;
            z-index: 1;
            animation: fadeUp .5s .26s ease both;
        }

        /* Ilustrasi versi DESKTOP — nempel di panel kiri */
        .brand-illustration {
            position: absolute;
            top: -10px;
            right: 0;
            width: clamp(200px, 26vw, 390px);
            height: auto;
            opacity: .95;
            filter: drop-shadow(0 8px 20px rgba(0, 0, 0, .25));
            pointer-events: none;
            z-index: 0;
            padding: 10px;
            border-radius: 20px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .25);
            box-sizing: border-box;
        }

        /* Ilustrasi versi MOBILE dimatikan — di layar kecil foto tidak ditampilkan sama sekali */
        .brand-illustration-mobile {
            display: none !important;
        }

        /* RIGHT */
        .right-panel {
            width: clamp(380px, 34vw, 460px);
            flex-shrink: 0;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: clamp(36px, 5vw, 52px) clamp(28px, 4vw, 44px);
            box-shadow: -8px 0 40px rgba(0, 0, 0, .18);
            overflow-y: auto;
            animation: slideIn .5s .1s ease both;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 .2rem var(--cyan-10);
        }

        .input-group-text {
            background: var(--bg-deep);
            border-color: var(--border);
            color: var(--muted);
        }

        .form-control {
            background: var(--bg-deep);
            border-color: var(--border);
            font-size: 13px;
        }

        .form-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--muted);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand-dark), var(--brand-mid));
            border: none;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            letter-spacing: .2px;
        }

        .btn-primary:hover {
            opacity: .9;
            background: linear-gradient(135deg, var(--brand-dark), var(--brand-mid));
        }

        .btn-primary:active {
            transform: scale(.98);
        }

        .form-check-input:checked {
            background-color: var(--cyan);
            border-color: var(--cyan);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 .2rem var(--cyan-10);
            border-color: var(--cyan);
        }

        .form-eyebrow {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: 1.8px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .form-title {
            font-family: 'Syne', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -.5px;
            margin-bottom: 6px;
        }

        .form-sub {
            font-size: 12px;
            color: var(--muted);
        }

        .forgot-link {
            font-size: 11px;
            color: var(--cyan);
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .forgot-link:hover {
            opacity: .75;
            color: var(--cyan);
        }

        .success-state {
            display: none;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 10px;
        }

        .success-state.show {
            display: flex;
        }

        .form-area.hidden {
            display: none;
        }

        .success-ico {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--cyan-10);
            border: 2px solid var(--cyan-bd);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--cyan);
            animation: popIn .4s ease both;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(28px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(.6)
            }

            to {
                opacity: 1;
                transform: scale(1)
            }
        }

        @keyframes dot1 {

            0%,
            80%,
            100% {
                opacity: .25;
                transform: scale(.8)
            }

            40% {
                opacity: 1;
                transform: scale(1)
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        /* ===== Tablet & bawah: stack panel ===== */
        @media (max-width: 991.98px) {
            body {
                overflow-x: hidden;
                overflow-y: auto;
            }

            .login-wrap {
                flex-direction: column;
                min-height: 100vh;
            }

            .left-panel {
                padding: 36px 24px 24px;
                flex: none;
            }

            .brand-top {
                align-items: center !important;
                text-align: center;
            }

            /* ilustrasi disembunyikan total di layar sempit */
            .brand-illustration {
                display: none;
            }

            .brand-tagline {
                max-width: 100%;
                margin: 0 auto 20px;
            }

            .stat-chips {
                justify-content: center;
            }

            .left-footer {
                display: none;
            }

            .right-panel {
                width: 100%;
                flex: 1 1 auto;
                box-shadow: none;
                border-top: 1px solid var(--border);
                border-radius: 24px 24px 0 0;
                padding: 28px 24px 40px;
                margin-top: -20px;
                z-index: 2;
                position: relative;
            }
        }

        /* ===== HP kecil ===== */
        @media (max-width: 420px) {
            .brand-name {
                font-size: 22px;
            }

            .stat-chip {
                padding: 10px 14px;
                min-width: 84px;
            }

            .stat-chip-n {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <livewire:login-component />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
