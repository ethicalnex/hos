<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folder Permissions - EthicalNex</title>
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
        .step-completed {
            background: #198754;
            color: white;
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
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Step 3: Folder Permissions Check</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="step-number step-completed">1</div>
                                <small>Welcome</small>
                            </div>
                            <div class="col-md-3">
                                <div class="step-number step-completed">2</div>
                                <small>Requirements</small>
                            </div>
                            <div class="col-md-3">
                                <div class="step-number step-active">3</div>
                                <small>Permissions</small>
                            </div>
                            <div class="col-md-3">
                                <div class="step-number step-inactive">4</div>
                                <small>Admin Setup</small>
                            </div>
                        </div>

                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Folder/File</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $folder => $writable)
                                <tr>
                                    <td>{{ $folder }}</td>
                                    <td>
                                        @if($writable)
                                            <span class="badge bg-success">✓ Writable</span>
                                        @else
                                            <span class="badge bg-danger">✗ Not Writable</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($allPassed)
                            <div class="alert alert-success">
                                <strong>✓ All permissions are correct!</strong> The installer can write to required directories.
                            </div>
                            <div class="text-end">
                                <a href="{{ route('install.database') }}" class="btn btn-primary">Continue to Step 4</a>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <strong>✗ Some folders are not writable!</strong> Please set the correct permissions (755 for folders, 644 for files).
                            </div>
                            <div class="text-end">
                                <a href="{{ route('install.permissions') }}" class="btn btn-warning">Re-check Permissions</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>