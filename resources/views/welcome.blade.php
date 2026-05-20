<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIP Online Market</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background:
                linear-gradient(rgba(0,0,0,0.92), rgba(0,0,0,0.92)),
                url('/images/fon.jpg') center / cover fixed no-repeat;
        }

        .container { text-align: center; padding: 2rem 1rem; }

        .logo {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
            margin-bottom: 0.4rem;
        }
        .logo span { color: #a78bfa; }

        .subtitle {
            color: rgba(255,255,255,0.50);
            font-size: 0.95rem;
            margin-bottom: 3rem;
        }

        .cards {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            padding: 2.5rem 3rem;
            background: rgba(12, 12, 25, 0.82);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 1.25rem;
            color: #fff;
            text-decoration: none;
            transition: transform 0.2s, border-color 0.2s, background 0.2s;
            min-width: 200px;
            cursor: pointer;
        }
        .card:hover {
            transform: translateY(-5px);
            border-color: rgba(167,139,250,0.45);
            background: rgba(18, 12, 38, 0.90);
        }

        .card-icon {
            width: 58px; height: 58px;
            border-radius: 1rem;
            display: flex; align-items: center; justify-content: center;
        }
        .card-icon svg { width: 30px; height: 30px; }
        .card-icon.admin       { background: rgba(139,92,246,0.20); color: #a78bfa; }
        .card-icon.restaurant  { background: rgba(251,146,60,0.20);  color: #fb923c; }

        .card-title { font-size: 1.1rem; font-weight: 600; }
        .card-desc  { font-size: 0.82rem; color: rgba(255,255,255,0.42); }

        .card-btn {
            margin-top: 0.4rem;
            padding: 0.45rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            border: none;
            color: #fff;
        }
        .card-btn.admin       { background: #7c3aed; }
        .card-btn.restaurant  { background: #ea580c; }
        .card:hover .card-btn.admin      { background: #6d28d9; }
        .card:hover .card-btn.restaurant { background: #c2410c; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">VIP <span>Online</span> Market</div>
        <p class="subtitle">Boshqaruv paneliga kirish uchun tanlang</p>

        <div class="cards">
            <a href="/admin" class="card">
                <div class="card-icon admin">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                </div>
                <div>
                    <div class="card-title">Admin Panel</div>
                    <div class="card-desc">Tizim boshqaruvi</div>
                </div>
                <span class="card-btn admin">Kirish</span>
            </a>

            <a href="/restaurant" class="card">
                <div class="card-icon restaurant">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/>
                    </svg>
                </div>
                <div>
                    <div class="card-title">Restoran Panel</div>
                    <div class="card-desc">Restoran boshqaruvi</div>
                </div>
                <span class="card-btn restaurant">Kirish</span>
            </a>
        </div>
    </div>
</body>
</html>
