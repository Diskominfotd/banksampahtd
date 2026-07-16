<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B-STAR - Bank Sampah Tanah Datar Pintar · Kabupaten Tanah Datar</title>
    <link rel="icon" type="image/png" href="{{ asset('logotanahdatar.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Syne:wght@600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg-deep: #f0f7f2;
            --bg-card: #ffffff;
            --bg-dark: #f4f9f5;
            --cyan: #2e7d32;
            --cyan-10: rgba(46, 125, 50, .10);
            --cyan-12: rgba(46, 125, 50, .12);
            --cyan-bd: rgba(46, 125, 50, .35);
            --blue: #1b5e20;
            --orange: #e65100;
            --purple: #5c35a8;
             --red: #d32f2f;
            --red-10: rgba(211, 47, 47, .10);
            --yellow: #f57f17;
            --green: #2e7d32;
            --green-10: rgba(46, 125, 50, .10);
            --teal: #00796b;
            --border: rgba(46, 125, 50, .18);
            --border-light: rgba(46, 125, 50, .10);
            --muted: rgba(20, 60, 30, .45);
            --dim: rgba(10, 50, 20, .70);
            --text-main: #0d2113;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-deep);
            color: var(--text-main);
            height: 100%;
        }

        .font-display {
            font-family: 'Syne', sans-serif;
        }

        .avatar {
            border-radius: 50%;
            background: linear-gradient(135deg, var(--cyan), #1b5e20);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .avatar-md {
            width: 42px;
            height: 42px;
            font-size: 14px;
            border: 2px solid var(--cyan-bd);
        }

        .avatar-sm {
            width: 34px;
            height: 34px;
            font-size: 12px;
        }

        .bs {
            font-size: 9px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .bs-ok {
            background: rgba(46, 125, 50, .12);
            color: #1b5e20;
        }

        .bs-warn {
            background: rgba(245, 127, 23, .12);
            color: var(--yellow);
        }

        .bs-err {
            background: rgba(211, 47, 47, .10);
            color: var(--red);
        }

        .bs-new {
            background: rgba(0, 121, 107, .10);
            color: var(--teal);
        }

        .bs-green {
            background: rgba(46, 125, 50, .10);
            color: var(--green);
        }

        .bs-purple {
            background: rgba(92, 53, 168, .10);
            color: var(--purple);
        }

        .bs-orange {
            background: rgba(230, 81, 0, .10);
            color: var(--orange);
        }

        .ic1 {
            background: rgba(46, 125, 50, .12);
            color: var(--cyan);
        }

        .ic2 {
            background: rgba(27, 94, 32, .12);
            color: var(--blue);
        }

        .ic3 {
            background: rgba(230, 81, 0, .10);
            color: var(--orange);
        }

        .ic4 {
            background: rgba(92, 53, 168, .10);
            color: var(--purple);
        }

        .ic5 {
            background: rgba(0, 121, 107, .10);
            color: var(--teal);
        }

        .ic6 {
            background: rgba(211, 47, 47, .10);
            color: var(--red);
        }

        .sec-lbl {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: 1.6px;
            text-transform: uppercase;
            margin: 24px 0 10px;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .fade-up {
            animation: fadeUp .4s ease both;
        }

        .fade-up:nth-child(1) {
            animation-delay: .05s
        }

        .fade-up:nth-child(2) {
            animation-delay: .10s
        }

        .fade-up:nth-child(3) {
            animation-delay: .15s
        }

        .fade-up:nth-child(4) {
            animation-delay: .20s
        }

        .page {
            display: none;
        }

        .page.active {
            display: flex;
            flex-direction: column;
        }

        body.is-mobile-layout {
            display: flex;
            flex-direction: column;
            min-height: 100svh;
        }

        @media (min-width: 992px) {
            body.is-mobile-layout {
                display: block;
                /* atau bisa juga reset flex */
                min-height: unset;
            }
        }

        body.is-mobile-layout .page.active {
            flex: 1;
            min-height: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ── MOBILE HEADER ── */
        .m-header {
            background: linear-gradient(160deg, #1b5e20 0%, #2e7d32 55%, #388e3c 100%);
            padding: 28px 20px 40px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .m-header::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .10);
            top: -70px;
            right: -50px;
        }

        .m-header::after {
            content: '';
            position: absolute;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
            bottom: -25px;
            left: 28px;
        }

        .m-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .m-gear {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .20);
            border: 1px solid rgba(255, 255, 255, .30);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
        }

        .m-summary {
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .35);
            border-radius: 20px;
            padding: 18px 20px;
            position: relative;
            z-index: 2;
        }

        .m-summary-lbl {
            font-size: 10px;
            color: rgba(255, 255, 255, .80);
            letter-spacing: 1.6px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .m-summary-num {
            font-family: 'Syne', sans-serif;
            font-size: 38px;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -1.5px;
            color: #fff;
            margin-bottom: 16px;
        }

        .m-pills {
            display: flex;
            gap: 8px;
        }

        .m-pill {
            flex: 1;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 12px;
            padding: 9px 4px;
            text-align: center;
        }

        .m-pill.c {
            border-color: rgba(255, 255, 255, .50);
        }

        .m-pill-n {
            display: block;
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
        }

        .m-pill-l {
            font-size: 8px;
            color: rgba(255, 255, 255, .70);
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .m-body {
            flex: 1;
            padding: 0 16px 24px;
            margin-top: -22px;
            position: relative;
            z-index: 3;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .m-page-header {
            background: #fff;
            border-bottom: 1px solid var(--border-light);
            padding: 16px 16px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            box-shadow: 0 1px 4px rgba(46, 125, 50, .08);
        }

        .m-page-header .ph-title {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
        }

        .m-back {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: var(--cyan-10);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--cyan);
        }

        .svc-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 7px;
            padding: 14px 6px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            text-decoration: none;
            cursor: pointer;
            transition: background .18s, border-color .18s, transform .18s;
            position: relative;
        }

        .svc-item:active {
            transform: scale(.95);
        }

        .svc-item:hover {
            background: var(--cyan-10);
            border-color: var(--cyan-bd);
        }

        .svc-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            position: relative;
        }

        .svc-lbl {
            font-size: 9px;
            font-weight: 600;
            color: var(--dim);
            text-align: center;
        }

        .notif-dot {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--red);
            border-radius: 50%;
            width: 17px;
            height: 17px;
            border: 2px solid #fff;
            font-size: 8px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* Setoran card */
        .news-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(46, 125, 50, .06);
            transition: border-color .18s;
        }

        .news-card:hover {
            border-color: var(--cyan-bd);
        }

        .news-img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            background: var(--cyan-10);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: var(--cyan);
        }

        .news-body {
            padding: 12px;
        }

        .news-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.4;
            margin-bottom: 5px;
        }

        .news-meta {
            font-size: 10px;
            color: var(--muted);
        }

        .news-excerpt {
            font-size: 11px;
            color: var(--dim);
            line-height: 1.5;
            margin-top: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .tx-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 14px;
            transition: border-color .18s;
            box-shadow: 0 1px 4px rgba(46, 125, 50, .06);
        }

        .tx-card:hover {
            border-color: var(--cyan-bd);
        }

        .tx-ico {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--cyan-10);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 800;
            color: var(--cyan);
            letter-spacing: .3px;
            flex-shrink: 0;
        }

        .tx-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
        }

        .tx-date {
            font-size: 10px;
            color: var(--muted);
            margin-top: 1px;
        }

        .btn-tx {
            font-size: 9px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 7px;
            border: 1px solid var(--cyan-bd);
            color: var(--cyan);
            background: transparent;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-tx:hover {
            background: var(--cyan-10);
        }

        .btn-tx.red {
            border-color: rgba(211, 47, 47, .30);
            color: var(--red);
        }

        .btn-tx.red:hover {
            background: rgba(211, 47, 47, .06);
        }

        .m-bottom-nav {
            display: flex;
            background: #fff;
            border-top: 1px solid var(--border-light);
            padding: 10px 0 env(safe-area-inset-bottom, 10px);
            flex-shrink: 0;
            position: fixed;
            /* ← ganti dari sticky */
            bottom: 0;
            left: 0;
            right: 0;
            /* ← tambah ini */
            z-index: 100;
            box-shadow: 0 -1px 8px rgba(46, 125, 50, .08);
        }

        .m-nav-btn {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            padding: 4px 0;
            font-size: 18px;
            color: var(--muted);
            text-decoration: none;
            cursor: pointer;
            transition: color .18s;
        }

        .m-nav-btn.active {
            color: var(--cyan);
        }

        .m-nav-btn span {
            font-size: 9px;
            font-weight: 600;
        }

        .m-search {
            position: relative;
            margin-bottom: 4px;
        }

        .m-search input {
            width: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 14px 10px 38px;
            font-size: 13px;
            color: var(--text-main);
            font-family: 'DM Sans', sans-serif;
            outline: none;
        }

        .m-search input::placeholder {
            color: var(--muted);
        }

        .m-search input:focus {
            border-color: var(--cyan);
            background: var(--cyan-10);
        }

        .m-search .si {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
            pointer-events: none;
        }

        .m-chips {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .m-chips::-webkit-scrollbar {
            display: none;
        }

        .chip {
            font-size: 10px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid var(--border);
            color: var(--muted);
            background: transparent;
            white-space: nowrap;
            cursor: pointer;
            transition: all .15s;
            flex-shrink: 0;
        }

        .chip.active {
            background: var(--cyan-10);
            border-color: var(--cyan-bd);
            color: var(--cyan);
        }

        .list-item {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 13px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: border-color .18s;
            box-shadow: 0 1px 3px rgba(46, 125, 50, .05);
        }

        .list-item:hover {
            border-color: var(--cyan-bd);
        }

        .list-num {
            font-size: 10px;
            color: var(--muted);
            min-width: 18px;
        }

        .list-ico {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .list-main {
            flex: 1;
            min-width: 0;
        }

        .list-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .list-sub {
            font-size: 10px;
            color: var(--muted);
            margin-top: 2px;
        }

        .stat-row {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 1px 3px rgba(46, 125, 50, .06);
        }

        .stat-val {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
            color: var(--text-main);
        }

        .stat-lbl {
            font-size: 9px;
            color: var(--muted);
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        .stat-delta {
            font-size: 10px;
            font-weight: 600;
            margin-top: 6px;
        }

        .up {
            color: var(--cyan);
        }

        .down {
            color: var(--red);
        }

        .f-group {
            margin-bottom: 14px;
        }

        .f-group label {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: .8px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 6px;
        }

        .f-input {
            width: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 11px 14px;
            font-size: 13px;
            color: var(--text-main);
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color .18s;
        }

        .f-input:focus {
            border-color: var(--cyan);
            background: var(--cyan-10);
        }

        .f-input::placeholder {
            color: var(--muted);
        }

        select.f-input option {
            background: #fff;
            color: var(--text-main);
        }

        .f-input[disabled] {
            opacity: .5;
            cursor: not-allowed;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background: var(--cyan);
            border: none;
            border-radius: 12px;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            transition: opacity .18s;
        }

        .btn-primary:hover {
            opacity: .88;
        }

        .btn-outline {
            width: 100%;
            padding: 11px;
            background: transparent;
            border: 1px solid var(--cyan-bd);
            border-radius: 12px;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--cyan);
            cursor: pointer;
            transition: background .18s;
        }

        .btn-outline:hover {
            background: var(--cyan-10);
        }

        .detail-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 30, 10, .40);
            z-index: 999;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }

        .detail-overlay.open {
            display: flex;
        }

        .detail-sheet {
            background: #fff;
            border-radius: 24px 24px 0 0;
            padding: 20px 20px 32px;
            width: 100%;
            max-height: 88vh;
            overflow-y: auto;
        }

        .sheet-handle {
            width: 36px;
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            margin: 0 auto 20px;
        }

        .w-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 30, 10, .50);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .w-modal-overlay.open {
            display: flex;
        }

        .w-modal {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 8px 32px rgba(46, 125, 50, .15);
        }

        .w-modal-sm {
            max-width: 420px;
        }

        .w-modal-md {
            max-width: 640px;
        }

        .w-modal-lg {
            max-width: 860px;
        }

        .w-modal-header {
            padding: 20px 22px 16px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .w-modal-title {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: var(--text-main);
        }

        .w-modal-body {
            padding: 20px 22px;
        }

        .w-modal-footer {
            padding: 14px 22px 20px;
            border-top: 1px solid var(--border-light);
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .w-modal-close {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: var(--cyan-10);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--cyan);
            font-size: 14px;
        }

        .detail-field {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 9px 0;
            border-bottom: 1px solid var(--border-light);
            gap: 10px;
        }

        .detail-field:last-child {
            border-bottom: none;
        }

        .detail-field .df-key {
            font-size: 10px;
            color: var(--muted);
            flex-shrink: 0;
        }

        .detail-field .df-val {
            font-size: 12px;
            color: var(--text-main);
            font-weight: 500;
            text-align: right;
        }

        .confirm-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 0 auto 14px;
        }

        .confirm-icon.danger {
            background: rgba(211, 47, 47, .10);
            color: var(--red);
        }

        .confirm-icon.success {
            background: var(--cyan-10);
            color: var(--cyan);
        }

        .m-notif-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 14px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            transition: border-color .18s;
        }

        .m-notif-item.unread {
            border-color: var(--cyan-bd);
            background: var(--cyan-10);
        }

        .m-notif-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--cyan);
            flex-shrink: 0;
            margin-top: 5px;
        }

        .m-tl-item {
            display: flex;
            gap: 12px;
            padding-bottom: 18px;
            position: relative;
        }

        .m-tl-item::before {
            content: '';
            position: absolute;
            left: 14px;
            top: 28px;
            bottom: 0;
            width: 1px;
            background: var(--border);
        }

        .m-tl-item:last-child::before {
            display: none;
        }

        .m-tl-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
        }

        .m-tl-body {
            flex: 1;
        }

        .m-tl-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-main);
        }

        .m-tl-sub {
            font-size: 10px;
            color: var(--muted);
            margin-top: 2px;
        }

        .m-tl-time {
            font-size: 9px;
            color: var(--muted);
            margin-top: 3px;
        }

        /* ── DESKTOP ── */
        @media (min-width:992px) {


            html,
            body {
                height: 100vh;
                overflow: hidden;
            }

            .m-page-header,
            .m-header,
            .m-body,
            .m-bottom-nav {
                display: none !important;
            }

            .detail-overlay {
                display: none !important;
                /* ← .m-bottom-nav sudah dihapus dari sini */
            }

            .desktop-wrapper {
                display: flex !important;
                width: 100vw;
                height: 100vh;
                overflow: hidden;
            }

            .w-sidebar {
                width: 58px;
                background: #fff;
                border-right: 1px solid var(--border-light);
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 18px 0;
                gap: 6px;
                flex-shrink: 0;
                height: 100%;
                box-shadow: 1px 0 8px rgba(46, 125, 50, .06);
            }

            .w-logo {
                width: 32px;
                height: 32px;
                border-radius: 9px;
                background: linear-gradient(135deg, var(--cyan), #1b5e20);
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Syne', sans-serif;
                font-size: 14px;
                font-weight: 800;
                color: #fff;
                margin-bottom: 12px;
            }

            .w-nav {
                width: 38px;
                height: 38px;
                border-radius: 11px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 17px;
                color: var(--muted);
                cursor: pointer;
                transition: background .18s, color .18s;
                position: relative;
            }

            .w-nav:hover {
                background: var(--cyan-10);
                color: var(--cyan);
            }

            .w-nav.active {
                background: var(--cyan-10);
                color: var(--cyan);
            }

            .w-notif-dot {
                position: absolute;
                top: 6px;
                right: 6px;
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: var(--red);
                border: 1px solid #fff;
            }

            .w-main {
                flex: 1;
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            .w-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 24px;
                height: 58px;
                background: #fff;
                border-bottom: 1px solid var(--border-light);
                flex-shrink: 0;
                box-shadow: 0 1px 6px rgba(46, 125, 50, .06);
            }

            .w-title {
                font-family: 'Syne', sans-serif;
                font-size: 15px;
                font-weight: 700;
                color: var(--text-main);
            }

            .w-sub {
                font-size: 10px;
                color: var(--muted);
                margin-top: 1px;
            }

            .w-uname {
                font-size: 12px;
                font-weight: 600;
                color: var(--text-main);
            }

            .w-urole {
                font-size: 9px;
                color: var(--muted);
            }

            .w-search {
                position: relative;
            }

            .w-search input {
                background: var(--bg-deep);
                border: 1px solid var(--border);
                border-radius: 10px;
                padding: 7px 14px 7px 34px;
                font-size: 12px;
                color: var(--text-main);
                font-family: 'DM Sans', sans-serif;
                outline: none;
                width: 220px;
            }

            .w-search input:focus {
                border-color: var(--cyan);
            }

            .w-search .si {
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--muted);
                font-size: 13px;
            }

            .w-content {
                flex: 1;
                overflow-y: auto;
                padding: 20px 24px;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .w-metric {
                background: #fff;
                border: 1px solid var(--border);
                border-radius: 16px;
                padding: 16px;
            }

            .w-m-lbl {
                font-size: 10px;
                color: var(--muted);
                text-transform: uppercase;
                letter-spacing: .8px;
                margin-bottom: 6px;
            }

            .w-m-val {
                font-family: 'Syne', sans-serif;
                font-size: 26px;
                font-weight: 700;
                line-height: 1;
                color: var(--text-main);
            }

            .w-m-delta {
                font-size: 10px;
                font-weight: 600;
                margin-top: 4px;
            }

            .w-bar {
                height: 4px;
                background: var(--border);
                border-radius: 2px;
                margin-top: 10px;
            }

            .w-bar-fill {
                height: 100%;
                border-radius: 2px;
                background: var(--cyan);
                max-width: 100%;
            }

            .w-panel {
                background: #fff;
                border: 1px solid var(--border);
                border-radius: 16px;
                padding: 18px;
            }

            .w-panel-title {
                font-family: 'Syne', sans-serif;
                font-size: 12px;
                font-weight: 700;
                color: var(--text-main);
                margin-bottom: 14px;
            }

            .w-svc {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 6px;
                padding: 12px 6px;
                background: var(--bg-deep);
                border: 1px solid var(--border);
                border-radius: 14px;
                text-decoration: none;
                cursor: pointer;
                transition: all .15s;
            }

            .w-svc:hover {
                background: var(--cyan-10);
                border-color: var(--cyan-bd);
            }

            .w-svc-icon {
                width: 38px;
                height: 38px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
            }

            .w-svc-lbl {
                font-size: 9px;
                font-weight: 600;
                color: var(--dim);
                text-align: center;
            }

            .w-row {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px;
                background: var(--bg-deep);
                border: 1px solid var(--border);
                border-radius: 10px;
                cursor: pointer;
                transition: border-color .15s;
            }

            .w-row:hover {
                border-color: var(--cyan-bd);
            }

            .w-row-ico {
                width: 34px;
                height: 34px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .w-row-title {
                font-size: 11px;
                font-weight: 600;
                color: var(--text-main);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .w-row-meta {
                font-size: 9px;
                color: var(--muted);
                margin-top: 2px;
            }

            .w-tbl {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
            }

            .w-tbl th {
                font-size: 9px;
                font-weight: 700;
                color: var(--muted);
                letter-spacing: .8px;
                text-transform: uppercase;
                padding: 0 12px 10px;
                border-bottom: 1px solid var(--border);
                text-align: left;
            }

            .w-tbl td {
                padding: 10px 12px;
                border-bottom: 1px solid var(--border-light);
                color: var(--text-main);
                vertical-align: middle;
            }

            .w-tbl tr:last-child td {
                border-bottom: none;
            }

            .w-tbl tr:hover td {
                background: var(--bg-deep);
            }

            .w-btn {
                padding: 7px 16px;
                border-radius: 9px;
                font-size: 11px;
                font-weight: 600;
                cursor: pointer;
                border: 1px solid var(--border);
                background: #fff;
                color: var(--dim);
                transition: background .15s;
                font-family: 'DM Sans', sans-serif;
            }

            .w-btn:hover {
                background: var(--bg-deep);
            }

            .w-btn-primary {
                background: var(--cyan);
                border-color: var(--cyan);
                color: #fff;
            }

            .w-btn-primary:hover {
                opacity: .88;
                background: var(--cyan);
            }

            .w-btn-ghost {
                background: transparent;
            }

            .w-btn-ghost:hover {
                background: var(--bg-deep);
            }

            .w-btn-danger {
                background: var(--red);
                border-color: var(--red);
                color: #fff;
            }

            .w-form-label {
                font-size: 10px;
                font-weight: 700;
                color: var(--muted);
                text-transform: uppercase;
                letter-spacing: .8px;
                display: block;
                margin-bottom: 5px;
            }

            .w-form-input {
                width: 100%;
                background: var(--bg-deep);
                border: 1px solid var(--border);
                border-radius: 10px;
                padding: 9px 12px;
                font-size: 12px;
                color: var(--text-main);
                font-family: 'DM Sans', sans-serif;
                outline: none;
                transition: border-color .15s;
            }

            .w-form-input:focus {
                border-color: var(--cyan);
                background: #fff;
            }

            .w-cb {
                display: flex;
                gap: 4px;
                align-items: flex-end;
                height: 120px;
            }

            .w-cb-col {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
            }

            .w-cb-lbl {
                font-size: 8px;
                color: var(--muted);
            }

            .notif-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 12px 14px;
                background: var(--bg-deep);
                border: 1px solid var(--border);
                border-radius: 12px;
                cursor: pointer;
            }

            .notif-item:hover {
                border-color: var(--cyan-bd);
            }

            .notif-item.unread {
                border-color: var(--cyan-bd);
                background: var(--cyan-10);
            }

            .notif-dot-item {
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: var(--cyan);
                flex-shrink: 0;
                margin-top: 5px;
            }

            .timeline-item {
                display: flex;
                gap: 14px;
                padding-bottom: 20px;
                position: relative;
            }

            .timeline-item::before {
                content: '';
                position: absolute;
                left: 15px;
                top: 28px;
                bottom: 0;
                width: 1px;
                background: var(--border);
            }

            .timeline-item:last-child::before {
                display: none;
            }

            .tl-dot {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                flex-shrink: 0;
                border: 2px solid #fff;
            }

            .tl-body {
                flex: 1;
            }

            .tl-title {
                font-size: 12px;
                font-weight: 600;
                color: var(--text-main);
            }

            .tl-sub {
                font-size: 10px;
                color: var(--muted);
                margin-top: 2px;
            }

            .tl-time {
                font-size: 9px;
                color: var(--muted);
                margin-top: 4px;
            }

            .prof-card {
                background: #fff;
                border: 1px solid var(--border);
                border-radius: 18px;
                padding: 18px 16px;
            }

            .prof-field {
                padding: 10px 0;
                border-bottom: 1px solid var(--border-light);
            }

            .prof-field:last-child {
                border-bottom: none;
            }

            .prof-field label {
                font-size: 9px;
                font-weight: 700;
                color: var(--muted);
                letter-spacing: 1px;
                text-transform: uppercase;
                display: block;
                margin-bottom: 3px;
            }

            .prof-field span {
                font-size: 13px;
                color: var(--text-main);
                font-weight: 500;
            }
        }

        .desktop-wrapper {
            display: none;
        }

        .bar-wrap {
            display: flex;
            gap: 6px;
            align-items: flex-end;
            height: 100px;
        }

        .bar-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .bar-fill {
            width: 100%;
            border-radius: 5px 5px 0 0;
        }

        .bar-lbl {
            font-size: 8px;
            color: var(--muted);
        }

        /* Progress bar sampah */
        .prog-wrap {
            background: var(--border-light);
            border-radius: 4px;
            height: 6px;
            margin-top: 6px;
        }

        .prog-fill {
            height: 100%;
            border-radius: 4px;
        }

        @media (min-width: 992px) {
            #m-nasabah {
                display: none !important;
            }
        }

        /* ── SETORAN MOBILE ── */
        .section-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 14px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(46, 125, 50, .05);
        }

        .sec-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .sec-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
        }

        .sec-sub {
            font-size: 10px;
            color: var(--muted);
            margin-top: 1px;
        }


        .btn-sm-green {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 13px;
            background: var(--cyan-10);
            border: 1px solid var(--cyan-bd);
            border-radius: 9px;
            font-size: 11px;
            font-weight: 600;
            color: var(--cyan);
            cursor: pointer;
        }

        .nasabah-empty {
            background: var(--bg-dark);
            border-radius: 10px;
            border: 1.5px dashed var(--border);
            padding: 18px;
            text-align: center;
        }

        .nasabah-empty i {
            font-size: 22px;
            color: var(--muted);
            display: block;
            margin-bottom: 5px;
        }

        .nasabah-empty p {
            font-size: 11px;
            color: var(--muted);
            margin: 0;
        }

        .nasabah-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: var(--bg-dark);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .nasabah-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
        }

        .nasabah-unit {
            font-size: 10px;
            color: var(--muted);
            margin-top: 1px;
        }

        .item-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 0;
            border-bottom: 1px solid var(--border-light);
        }

        .item-row:last-of-type {
            border-bottom: none;
        }

        .item-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--cyan);
            flex-shrink: 0;
        }

        .item-nama {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-main);
        }

        .item-price {
            font-size: 10px;
            color: var(--muted);
        }

        .item-berat-wrap {
            position: relative;
            flex-shrink: 0;
        }

        .item-berat {
            width: 66px;
            background: var(--bg-dark);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 6px 22px 6px 8px;
            font-size: 12px;
            color: var(--text-main);
            outline: none;
            text-align: right;
            font-family: 'DM Sans', sans-serif;
        }

        .item-berat-unit {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            color: var(--muted);
            pointer-events: none;
        }

        .item-subtotal {
            font-size: 11px;
            color: var(--dim);
            font-weight: 600;
            min-width: 72px;
            text-align: right;
        }

        .item-del {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            border: 1px solid rgba(211, 47, 47, .25);
            background: rgba(211, 47, 47, .07);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--red);
            flex-shrink: 0;
            font-size: 13px;
        }

        .item-empty {
            text-align: center;
            padding: 24px 10px;
            color: var(--muted);
            font-size: 11px;
        }

        .item-empty i {
            font-size: 22px;
            display: block;
            margin-bottom: 6px;
        }

        .total-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0 2px;
            border-top: 1px solid var(--border);
            margin-top: 6px;
        }

        .total-label {
            font-size: 11px;
            color: var(--muted);
            font-weight: 600;
        }

        .total-val {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
        }

        .action-bar {
            display: flex;
            gap: 10px;
            padding: 12px 14px 14px;
            background: #fff;
            flex-shrink: 0;
        }

        .btn-batal {
            flex: 1;
            padding: 12px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--muted);
            cursor: pointer;
        }

        .btn-simpan {
            flex: 2;
            padding: 12px;
            background: var(--cyan);
            border: none;
            border-radius: 12px;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            cursor: pointer;
        }

        .btn-simpan:active {
            opacity: .88;
        }

        .err-msg {
            font-size: 10px;
            color: var(--red);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
    </style>
    <style>
        .sheet-pilih-sampah {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 999;
            background: var(--bg-card, #fff);
            border-radius: 20px 20px 0 0;
            max-height: 90dvh;
            display: flex;
            flex-direction: column;
        }
    </style>
      <style>
        a {
            text-decoration: none;
        }
    </style>
</head>

<body class="is-mobile-layout">
    @yield('content')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function mNav(pageId) {
            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
            const pg = document.getElementById(pageId);
            if (pg) pg.classList.add('active');
            const body = pg && pg.querySelector('.m-body');
            if (body) body.scrollTop = 0;
        }
        document.querySelectorAll('.m-nav-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = this.dataset.page;
                if (target) mNav(target);
                const nav = this.closest('nav');
                nav.querySelectorAll('.m-nav-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        function openDetail(id) {
            document.getElementById(id).classList.add('open');
        }

        function closeMSheet(e, id) {
            if (!e || e.target === document.getElementById(id))
                document.getElementById(id).classList.remove('open');
        }

        const wPages = ['w-dashboard', 'w-setoran', 'w-nasabah', 'w-harga', 'w-laporan', 'w-notifikasi', 'w-setelan'];
        const wTitles = {
            'w-dashboard': 'Dashboard Pengelola',
            'w-setoran': 'Manajemen Setoran',
            'w-nasabah': 'Data Nasabah',
            'w-harga': 'Harga Sampah',
            'w-laporan': 'Laporan & Analitik',
            'w-notifikasi': 'Notifikasi',
            'w-setelan': 'Profil & Setelan'
        };

        function wNav(pageId) {
            wPages.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = 'none';
            });
            const el = document.getElementById(pageId);
            if (el) el.style.display = 'flex';
            document.querySelectorAll('.w-nav[data-wpg]').forEach(n => n.classList.remove('active'));
            const nav = document.querySelector(`.w-nav[data-wpg="${pageId}"]`);
            if (nav) nav.classList.add('active');
            const ti = document.querySelector('.w-title');
            if (ti && wTitles[pageId]) ti.textContent = wTitles[pageId];
        }
        document.querySelectorAll('.w-nav[data-wpg]').forEach(nav => {
            nav.addEventListener('click', function() {
                wNav(this.dataset.wpg);
            });
        });

        function openWModal(id) {
            document.getElementById(id).classList.add('open');
        }

        function closeWModal(e, id) {
            if (!e || e.target === document.getElementById(id))
                document.getElementById(id).classList.remove('open');
        }

        document.querySelectorAll('.chip').forEach(c => {
            c.addEventListener('click', function() {
                const parent = this.closest('.d-flex,.m-chips');
                if (parent) parent.querySelectorAll('.chip').forEach(x => x.classList.remove('active'));
                this.classList.add('active');
            });
        });

    </script>

</body>

</html>
