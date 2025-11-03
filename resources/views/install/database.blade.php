<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - EthicalNex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        .step-completed { background: #198754; color: white; }
        .step-active { background: #0d6efd; color: white; }
        .step-inactive { background: #e9ecef; color: #6c757d; }
        #test-result, #save-result, #installation-result { display: none; }
        .loading { display: none; }
        .section-box { border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 1.5rem; margin-bottom: 1.5rem; }
        .btn-container { display: flex; gap: 10px; justify-content: end; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Final Setup: Database & Admin</h4>
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
                                <div class="step-number step-completed">3</div>
                                <small>Permissions</small>
                            </div>
                            <div class="col-md-3">
                                <div class="step-number step-active">4</div>
                                <small>Setup</small>
                            </div>
                        </div>

                        <form id="installationForm">
                            @csrf
                            
                            <div class="section-box">
                                <h5 class="mb-3">📊 Database Configuration</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="db_host" class="form-label">Database Host</label>
                                        <input type="text" class="form-control" id="db_host" name="db_host" value="127.0.0.1" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="db_port" class="form-label">Database Port</label>
                                        <input type="text" class="form-control" id="db_port" name="db_port" value="3306" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="db_name" class="form-label">Database Name</label>
                                        <input type="text" class="form-control" id="db_name" name="db_name" value="ethicalnex" required>
                                        <div class="form-text">Database must exist on your server</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="db_username" class="form-label">Database Username</label>
                                        <input type="text" class="form-control" id="db_username" name="db_username" value="root" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="db_password" class="form-label">Database Password</label>
                                    <input type="password" class="form-control" id="db_password" name="db_password">
                                    <div class="form-text">Leave empty if no password</div>
                                </div>

                                <div id="test-result" class="alert mb-3">
                                    <!-- Test results will appear here -->
                                </div>

                                <div id="save-result" class="alert mb-3">
                                    <!-- Save results will appear here -->
                                </div>

                                <div class="btn-container">
                                    <button type="button" id="testConnection" class="btn btn-warning">Test Database Connection</button>
                                    <button type="button" id="saveDatabase" class="btn btn-info" disabled>Save to .env File</button>
                                </div>
                            </div>

                            <div class="section-box" id="adminSection" style="display: none;">
                                <h5 class="mb-3">👨‍💼 Super Administrator</h5>
                                <div class="mb-3">
                                    <label for="admin_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="admin_name" name="admin_name" value="System Administrator" required>
                                </div>
                                <div class="mb-3">
                                    <label for="admin_email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                                    <div class="form-text">This will be your login email</div>
                                </div>
                                <div class="mb-3">
                                    <label for="admin_password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="admin_password" name="admin_password" minlength="8" required>
                                    <div class="form-text">Minimum 8 characters</div>
                                </div>
                                <div class="mb-3">
                                    <label for="admin_password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="admin_password_confirmation" name="admin_password_confirmation" required>
                                </div>
                            </div>
                            
                            <div id="installation-result" class="alert mb-3">
                                <!-- Installation results will appear here -->
                            </div>

                            <div class="loading text-center mb-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Installing...</span>
                                </div>
                                <p class="mt-2">Installing EthicalNex. This may take a few seconds...</p>
                            </div>
                            
                            <div class="text-end">
                                <a href="{{ route('install.permissions') }}" class="btn btn-secondary">Back</a>
                                <button type="submit" id="installButton" class="btn btn-primary" disabled>Complete Installation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let dbTestPassed = false;
            let dbSaved = false;

            // Test database connection
            $('#testConnection').click(function() {
                const testButton = $('#testConnection');
                const result = $('#test-result');
                const saveButton = $('#saveDatabase');

                const dbData = {
                    db_host: $('#db_host').val(),
                    db_port: $('#db_port').val(),
                    db_name: $('#db_name').val(),
                    db_username: $('#db_username').val(),
                    db_password: $('#db_password').val(),
                    _token: '{{ csrf_token() }}'
                };

                testButton.prop('disabled', true).text('Testing...');
                result.hide();
                $('#save-result').hide();

                $.ajax({
                    url: '{{ route("install.testDatabase") }}',
                    type: 'POST',
                    data: dbData,
                    success: function(response) {
                        if (response.success) {
                            result.removeClass('alert-danger').addClass('alert-success')
                                .html('✅ ' + response.message).show();
                            saveButton.prop('disabled', false);
                            dbTestPassed = true;
                        } else {
                            result.removeClass('alert-success').addClass('alert-danger')
                                .html('❌ ' + response.message).show();
                            saveButton.prop('disabled', true);
                            dbTestPassed = false;
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Connection failed. ';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage += xhr.responseJSON.message;
                        } else {
                            errorMessage += 'Please check your database credentials.';
                        }
                        
                        result.removeClass('alert-success').addClass('alert-danger')
                            .html('❌ ' + errorMessage).show();
                        saveButton.prop('disabled', true);
                        dbTestPassed = false;
                    },
                    complete: function() {
                        testButton.prop('disabled', false).text('Test Database Connection');
                    }
                });
            });

            // Save database to .env file
            $('#saveDatabase').click(function() {
                const saveButton = $('#saveDatabase');
                const result = $('#save-result');

                const dbData = {
                    db_host: $('#db_host').val(),
                    db_port: $('#db_port').val(),
                    db_name: $('#db_name').val(),
                    db_username: $('#db_username').val(),
                    db_password: $('#db_password').val(),
                    _token: '{{ csrf_token() }}'
                };

                saveButton.prop('disabled', true).text('Saving...');
                result.hide();

                $.ajax({
                    url: '{{ route("install.saveDatabase") }}',
                    type: 'POST',
                    data: dbData,
                    success: function(response) {
                        if (response.success) {
                            result.removeClass('alert-danger').addClass('alert-success')
                                .html('✅ ' + response.message).show();
                            $('#adminSection').slideDown();
                            dbSaved = true;
                            checkInstallButton();
                        } else {
                            result.removeClass('alert-success').addClass('alert-danger')
                                .html('❌ ' + response.message).show();
                            saveButton.prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Save failed. ';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage += xhr.responseJSON.message;
                        } else {
                            errorMessage += 'Please try again.';
                        }
                        
                        result.removeClass('alert-success').addClass('alert-danger')
                            .html('❌ ' + errorMessage).show();
                        saveButton.prop('disabled', false);
                    },
                    complete: function() {
                        saveButton.prop('disabled', false).text('Save to .env File');
                    }
                });
            });

            // Handle form submission
            $('#installationForm').submit(function(e) {
                e.preventDefault();
                
                if (!dbSaved) {
                    $('#save-result').removeClass('alert-success').addClass('alert-danger')
                        .html('❌ Please save database configuration first!').show();
                    return;
                }

                // Validate passwords match
                const password = $('#admin_password').val();
                const confirmPassword = $('#admin_password_confirmation').val();
                
                if (password !== confirmPassword) {
                    $('#installation-result').removeClass('alert-success').addClass('alert-danger')
                        .html('❌ Passwords do not match!').show();
                    return;
                }

                const installButton = $('#installButton');
                const loading = $('.loading');
                const result = $('#installation-result');
                
                // Show loading, hide button
                installButton.prop('disabled', true);
                loading.show();
                result.hide();
                
                const formData = $(this).serialize();
                
                $.ajax({
                    url: '{{ route("install.process") }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            result.removeClass('alert-danger').addClass('alert-success')
                                .html('✅ ' + response.message).show();
                            
                            // Redirect to complete page after 2 seconds
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 2000);
                        } else {
                            result.removeClass('alert-success').addClass('alert-danger')
                                .html('❌ ' + response.message).show();
                            installButton.prop('disabled', false);
                            loading.hide();
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Installation failed. ';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage += xhr.responseJSON.message;
                        } else {
                            errorMessage += 'Please check your information and try again.';
                        }
                        
                        result.removeClass('alert-success').addClass('alert-danger')
                            .html('❌ ' + errorMessage).show();
                        installButton.prop('disabled', false);
                        loading.hide();
                    }
                });
            });

            // Check if install button should be enabled
            function checkInstallButton() {
                const adminFields = [
                    $('#admin_name').val(),
                    $('#admin_email').val(),
                    $('#admin_password').val(),
                    $('#admin_password_confirmation').val()
                ];

                const allFilled = adminFields.every(field => field.trim() !== '');
                const passwordsMatch = $('#admin_password').val() === $('#admin_password_confirmation').val();

                if (allFilled && passwordsMatch && dbSaved) {
                    $('#installButton').prop('disabled', false);
                } else {
                    $('#installButton').prop('disabled', true);
                }
            }

            // Enable/disable install button based on form validity
            $('#admin_name, #admin_email, #admin_password, #admin_password_confirmation').on('input', checkInstallButton);
        });
    </script>
</body>
</html>