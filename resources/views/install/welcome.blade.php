<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EthicalNex - Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 auto 10px;
        }
        .step-active {
            background: #0d6efd;
            color: white;
        }
        .step-inactive {
            background: #e9ecef;
            color: #6c757d;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h1 class="h3">🩺 EthicalNex Hospital Management System</h1>
                        <p class="mb-0">Installation Wizard</p>
                    </div>
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <h2>Welcome to EthicalNex</h2>
                            <p class="text-muted">Thank you for choosing Africa's most affordable hospital management system</p>
                        </div>
                        
                        <div class="row mb-5">
                            <div class="col-md-3">
                                <div class="step-number step-active">1</div>
                                <small>Welcome</small>
                            </div>
                            <div class="col-md-3">
                                <div class="step-number step-inactive">2</div>
                                <small>Requirements</small>
                            </div>
                            <div class="col-md-3">
                                <div class="step-number step-inactive">3</div>
                                <small>Database</small>
                            </div>
                            <div class="col-md-3">
                                <div class="step-number step-inactive">4</div>
                                <small>Admin Setup</small>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong>Before you start:</strong> Make sure you have your MySQL database credentials ready.
                        </div>
                        
                        <a href="{{ route('install.requirements') }}" class="btn btn-primary btn-lg px-5">Start Installation</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>