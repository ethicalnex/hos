<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - EthicalNex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .verify-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .verify-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card p-4">
            <div class="text-center mb-4">
                <div class="hospital-icon" style="font-size: 3rem; color: #667eea;">
                    <i class="fas fa-hospital"></i>
                </div>
                <h3 class="fw-bold">Verify Your Email</h3>
                <p class="text-muted">EthicalNex Hospital Management System</p>
            </div>

            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    A fresh verification link has been sent to your email address.
                </div>
            @endif

            <div class="alert alert-info">
                <p class="mb-0">
                    Before proceeding, please check your email for a verification link.
                    If you did not receive the email,
                </p>
            </div>

            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="fas fa-paper-plane me-2"></i>Click here to request another
                </button>
            </form>

            <div class="text-center mt-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none">
                        <i class="fas fa-sign-out-alt me-1"></i>Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>