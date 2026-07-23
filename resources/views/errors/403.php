<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Yetkisiz Erişim</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #e2e8f0; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .error-page { text-align: center; }
        .error-code { font-size: 8rem; font-weight: 700; background: linear-gradient(135deg, #ef4444, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; margin-bottom: 1rem; }
        .error-title { font-size: 1.5rem; font-weight: 600; margin-bottom: .5rem; }
        .error-desc { color: #94a3b8; margin-bottom: 2rem; }
        .error-btn { display: inline-block; padding: .75rem 2rem; background: #2563eb; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; transition: .2s; }
        .error-btn:hover { background: #1d4ed8; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-code">403</div>
        <h1 class="error-title">Yetkisiz Erişim</h1>
        <p class="error-desc">Bu sayfaya erişim izniniz bulunmamaktadır.</p>
        <a href="/" class="error-btn">Ana Sayfaya Dön</a>
    </div>
</body>
</html>
