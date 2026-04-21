<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VipOnlineMarket</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #f8f8f6;
            --surface: #ffffff;
            --border: #e5e5e2;
            --text: #1a1a18;
            --muted: #6b6b67;
            --accent: #7c3aed;
            --accent-light: #ede9fe;
            --orange: #ea580c;
            --orange-light: #fff7ed;
            --success: #16a34a;
            --success-light: #f0fdf4;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #0f0f0e;
                --surface: #1a1a18;
                --border: #2e2e2b;
                --text: #edede9;
                --muted: #9a9a94;
                --accent: #a78bfa;
                --accent-light: #1e1a2e;
                --orange: #fb923c;
                --orange-light: #1c1208;
                --success: #4ade80;
                --success-light: #0a1f0f;
            }
        }

        body {
            font-family: 'Instrument Sans', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .container {
            width: 100%;
            max-width: 640px;
        }

        /* Hero */
        .hero {
            text-align: center;
            margin-bottom: 3rem;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 1.5rem;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--accent), var(--orange));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon svg {
            width: 24px;
            height: 24px;
            color: #fff;
        }

        .logo-text {
            font-size: 1.375rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text);
        }

        .hero h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.2;
            margin-bottom: 0.75rem;
        }

        @media (min-width: 480px) {
            .hero h1 { font-size: 2.5rem; }
        }

        .hero p {
            font-size: 1rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 2.5rem;
        }

        @media (min-width: 480px) {
            .cards { grid-template-columns: 1fr 1fr; }
        }

        .card {
            display: block;
            text-decoration: none;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        .card--admin { border-top: 3px solid var(--accent); }
        .card--admin:hover { border-color: var(--accent); }

        .card--restaurant { border-top: 3px solid var(--orange); }
        .card--restaurant:hover { border-color: var(--orange); }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .card--admin .card-icon { background: var(--accent-light); color: var(--accent); }
        .card--restaurant .card-icon { background: var(--orange-light); color: var(--orange); }

        .card-icon svg { width: 20px; height: 20px; }

        .card h2 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.375rem;
        }

        .card p {
            font-size: 0.8125rem;
            color: var(--muted);
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .card-link {
            font-size: 0.8125rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .card--admin .card-link { color: var(--accent); }
        .card--restaurant .card-link { color: var(--orange); }

        .card-link svg { width: 14px; height: 14px; transition: transform 0.15s; }
        .card:hover .card-link svg { transform: translateX(3px); }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .footer a {
            color: var(--muted);
            text-decoration: underline;
            text-underline-offset: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero">
            <div class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <span class="logo-text">VipOnlineMarket</span>
            </div>
            <h1>Online ovqat buyurtma platformasi</h1>
            <p>Restoranlar, mahsulotlar va buyurtmalarni boshqarish uchun markazlashgan boshqaruv tizimi.</p>
        </div>

        <div class="cards">
            <a href="/admin" class="card card--admin">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                </div>
                <h2>Admin Panel</h2>
                <p>Restoranlar, foydalanuvchilar, kuryerlar va barcha buyurtmalarni boshqaring.</p>
                <span class="card-link">
                    Kirish
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </span>
            </a>

            <a href="/restaurant" class="card card--restaurant">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <h2>Restoran Panel</h2>
                <p>Menyu, kategoriyalar va tushayotgan buyurtmalarni real vaqtda kuzating.</p>
                <span class="card-link">
                    Kirish
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </span>
            </a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} VipOnlineMarket. Barcha huquqlar himoyalangan.
        </div>
    </div>
</body>
</html>
