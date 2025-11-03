<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8fafc;
        }
        .card {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-radius: 8px;
        }
        .method {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
            color: white;
        }
        .method.GET { background-color: #10b981; }
        .method.POST { background-color: #3b82f6; }
        .method.PUT { background-color: #f59e0b; }
        .method.DELETE { background-color: #ef4444; }
        .endpoint {
            font-family: monospace;
            background: #f1f5f9;
            padding: 0.5rem;
            border-radius: 4px;
            word-break: break-all;
        }
        .response {
            background: #f1f5f9;
            padding: 1rem;
            border-radius: 8px;
            font-family: monospace;
            white-space: pre-wrap;
            overflow-x: auto;
        }
        .form-control {
            font-family: 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">API Documentation</h5>
                    </div>
                    <div class="card-body">
                        <p>Welcome to the EthicalNex Hospital API documentation. You can test all endpoints here.</p>
                        @if(auth()->check())
                            <div class="alert alert-success">
                                <i class="fas fa-user me-2"></i> Logged in as: {{ auth()->user()->name }} ({{ auth()->user()->role }})
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-lock me-2"></i> You are not logged in. Some endpoints require authentication.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Test Endpoint</h5>
                    </div>
                    <div class="card-body">
                        <form id="testForm">
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Method</label>
                                    <select name="method" class="form-select" required>
                                        <option value="">Select Method</option>
                                        <option value="GET">GET</option>
                                        <option value="POST">POST</option>
                                        <option value="PUT">PUT</option>
                                        <option value="DELETE">DELETE</option>
                                    </select>
                                </div>
                                <div class="col-md-10">
                                    <label class="form-label">URL</label>
                                    <input type="text" name="url" class="form-control" placeholder="http://localhost:8000/api/v1/patients" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Headers (JSON)</label>
                                <textarea name="headers" class="form-control" rows="3" placeholder='{"Content-Type": "application/json"}'>{"Content-Type": "application/json"}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Body (JSON)</label>
                                <textarea name="body" class="form-control" rows="5" placeholder='{"patient_id": 1}'>{"patient_id": 1}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Test Endpoint</button>
                        </form>

                        <div id="responseSection" class="mt-4" style="display: none;">
                            <h6>Response:</h6>
                            <div id="responseStatus" class="badge bg-secondary mb-2"></div>
                            <pre id="responseBody" class="response"></pre>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">All API Endpoints</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th>Endpoint</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($apiRoutes as $route)
                                    <tr>
                                        <td>
                                            <span class="method {{ $route['method'] }}">
                                                {{ $route['method'] }}
                                            </span>
                                        </td>
                                        <td><code class="endpoint">{{ $route['uri'] }}</code></td>
                                        <td>{{ $route['name'] ?: '—' }}</td>
                                        <td>{{ $route['action'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('testForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const method = formData.get('method');
            const url = formData.get('url');
            let headers = {};
            
            try {
                headers = JSON.parse(formData.get('headers'));
            } catch (e) {
                alert('Invalid headers JSON');
                return;
            }
            
            let body = {};
            try {
                body = JSON.parse(formData.get('body'));
            } catch (e) {
                alert('Invalid body JSON');
                return;
            }

            const responseSection = document.getElementById('responseSection');
            const responseStatus = document.getElementById('responseStatus');
            const responseBody = document.getElementById('responseBody');

            responseSection.style.display = 'block';
            responseStatus.textContent = '';
            responseBody.textContent = 'Loading...';

            try {
                const response = await fetch('{{ route("api.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        method: method,
                        url: url,
                        headers: headers,
                        body: body
                    })
                });

                const data = await response.json();

                if (data.success) {
                    responseStatus.className = 'badge bg-success';
                    responseStatus.textContent = 'Success (' + data.data.status + ')';
                    responseBody.textContent = JSON.stringify(data.data.body, null, 2);
                } else {
                    responseStatus.className = 'badge bg-danger';
                    responseStatus.textContent = 'Error';
                    responseBody.textContent = data.error;
                }
            } catch (error) {
                responseStatus.className = 'badge bg-danger';
                responseStatus.textContent = 'Network Error';
                responseBody.textContent = error.message;
            }
        });
    </script>
</body>
</html>