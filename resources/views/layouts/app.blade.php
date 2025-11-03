<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hospital System')</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        html, body { height: 100%; }
        body { display: flex; flex-direction: column; }
        main { flex: 1; }
    </style>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e40af">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
</head>
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, function(err) {
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}

// Show install prompt on mobile
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    
    const installButton = document.createElement('button');
    installButton.textContent = 'Install App';
    installButton.style.position = 'fixed';
    installButton.style.bottom = '20px';
    installButton.style.right = '20px';
    installButton.style.padding = '10px 20px';
    installButton.style.backgroundColor = '#1e40af';
    installButton.style.color = 'white';
    installButton.style.border = 'none';
    installButton.style.borderRadius = '8px';
    installButton.style.cursor = 'pointer';
    installButton.onclick = () => {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the A2HS prompt');
            } else {
                console.log('User dismissed the A2HS prompt');
            }
            deferredPrompt = null;
        });
    };
    
    document.body.appendChild(installButton);
});
</script>
<!-- Google Fonts: Montserrat -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Custom CSS -->
<style>
    :root {
        --primary: #007e5d;
        --secondary: #f8c828;
        --light: #f8fafc;
        --dark: #1e293b;
        --gray: #6b7280;
        --white: #ffffff;
        --border: #e5e7eb;
    }

    body {
        font-family: 'Montserrat', sans-serif;
        background-color: var(--light);
        color: var(--dark);
    }

    /* Buttons */
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    .btn-primary:hover {
        background-color: #006a4d;
        border-color: #006a4d;
    }
    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
        color: #000;
    }
    .btn-secondary:hover {
        background-color: #e6b420;
        border-color: #e6b420;
    }

    /* Cards */
    .card {
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
    }
    .card-header {
        background: linear-gradient(135deg, var(--primary), #006a4d);
        color: white;
        border-bottom: none;
    }

    /* Sidebar */
    .sidebar {
        min-height: 100vh;
        background: linear-gradient(180deg, #007e5d, #006a4d);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        padding: 0;
    }
    .hospital-brand {
        padding: 1.25rem;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        background: rgba(0,0,0,0.1);
    }
    .hospital-brand h5 {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
        color: white;
    }
    .sidebar .nav-link {
        color: rgba(255,255,255,0.85);
        padding: 0.85rem 1.25rem;
        margin: 0.15rem 0.75rem;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.25s ease;
    }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background: rgba(255,255,255,0.15);
        color: white;
        transform: translateX(4px);
    }
    .sidebar .nav-link i {
        width: 24px;
        text-align: center;
        margin-right: 12px;
        font-size: 1.1rem;
    }

    /* Main content area */
    main {
        margin-left: 16.666667%; /* col-lg-2 = ~16.66% */
        padding-top: 20px;
    }

    @media (max-width: 991.98px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            min-height: auto;
        }
        main {
            margin-left: 0;
        }
    }

    /* Top navbar */
    .top-navbar {
        background: white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        padding: 0.6rem 1.25rem;
    }
    .top-navbar .dropdown-toggle::after {
        margin-left: 0.5rem;
    }

    /* Stat cards */
    .stat-card {
        border-left: 4px solid var(--primary);
        transition: transform 0.25s, box-shadow 0.25s;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Form controls */
    .form-control {
        font-family: 'Montserrat', sans-serif;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.25rem rgba(0, 126, 93, 0.25);
    }

    /* Table */
    .table th {
        font-weight: 600;
        background-color: #f1f5f9;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Alerts */
    .alert {
        border-radius: 8px;
        font-family: 'Montserrat', sans-serif;
    }
</style>
<body>
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>