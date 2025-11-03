<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Requirements - EthicalNex</title>
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
        .step-completed {
            background: #198754;
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Step 2: System Requirements Check</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="step-number step-completed">1</div>
                                <small>Welcome</small>
                            </div>
                            <div class="col-md-3">
                                <div class="step-number step-active">2</div>
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

                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Requirement</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requirements as $requirement => $passed)
                                <tr>
                                    <td>{{ $requirement }}</td>
                                    <td>
                                        @if($passed)
                                            <span class="badge bg-success">✓ PASS</span>
                                        @else
                                            <span class="badge bg-danger">✗ FAIL</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($allPassed)
                            <div class="alert alert-success">
                                <strong>✓ All requirements passed!</strong> Your server meets all system requirements.
                            </div>
                            <div class="text-end">
                                <a href="{{ route('install.permissions') }}" class="btn btn-primary">Continue to Step 3</a>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <strong>✗ Some requirements failed!</strong> Please fix the issues above before continuing.
                            </div>
                            <div class="text-end">
                                <a href="{{ route('install.requirements') }}" class="btn btn-warning">Re-check Requirements</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>