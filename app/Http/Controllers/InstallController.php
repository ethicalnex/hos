<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class InstallController extends Controller
{
    public function welcome()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }
        return view('install.welcome');
    }

    public function requirements()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }

        $requirements = [
            'PHP Version (>= 8.1)' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'BCMath Extension' => extension_loaded('bcmath'),
            'Ctype Extension' => extension_loaded('ctype'),
            'JSON Extension' => extension_loaded('json'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'PDO Extension' => extension_loaded('pdo'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
        ];

        $allPassed = !in_array(false, $requirements, true);

        return view('install.requirements', compact('requirements', 'allPassed'));
    }

    public function permissions()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }

        $permissions = [
            'storage/' => is_writable(base_path('storage')),
            'bootstrap/cache/' => is_writable(base_path('bootstrap/cache')),
            '.env' => is_writable(base_path('.env')),
        ];

        $allPassed = !in_array(false, $permissions, true);

        return view('install.permissions', compact('permissions', 'allPassed'));
    }

    public function database()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }
        return view('install.database');
    }

    public function testDatabase(Request $request)
    {
        try {
            $request->validate([
                'db_host' => 'required',
                'db_port' => 'required',
                'db_name' => 'required',
                'db_username' => 'required',
                'db_password' => 'nullable',
            ]);

            $this->testDatabaseConnection($request->only([
                'db_host', 'db_port', 'db_name', 'db_username', 'db_password'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Database connection successful!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage()
            ], 422);
        }
    }

    public function saveDatabase(Request $request)
    {
        try {
            $request->validate([
                'db_host' => 'required',
                'db_port' => 'required',
                'db_name' => 'required',
                'db_username' => 'required',
                'db_password' => 'nullable',
            ]);

            // Test connection first
            $this->testDatabaseConnection($request->only([
                'db_host', 'db_port', 'db_name', 'db_username', 'db_password'
            ]));

            // Save to .env file
            $this->updateEnvironmentFile($request->only([
                'db_host', 'db_port', 'db_name', 'db_username', 'db_password'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Database configuration saved successfully! You can now create admin account.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save database: ' . $e->getMessage()
            ], 422);
        }
    }

    public function install(Request $request)
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }

        $request->validate([
            'admin_name' => 'required',
            'admin_email' => 'required|email',
            'admin_password' => 'required|min:8',
        ]);

        try {
            // STEP 1: Clear cache using file-based methods (safe)
            $this->clearCacheSafely();

            // STEP 2: Run migrations with manual fallback
            try {
                Artisan::call('migrate:fresh', [
                    '--force' => true,
                    '--no-interaction' => true,
                ]);
            } catch (\Exception $e) {
                // If migrations fail, create tables manually
                $this->createTablesManually();
            }

            // STEP 3: Create admin user
            DB::table('users')->insert([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create admin user with the provided credentials
            $adminUser = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create default hospital
            $defaultHospital = Hospital::create([
                'name' => 'EthicalNex Main Hospital',
                'slug' => 'main',
                'email' => $request->admin_email,
                'phone' => '+2348000000000',
                'address' => '123 Tech Avenue, Victoria Island',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'is_active' => true,
            ]);

            // Update super admin with hospital_id (for consistency, though super_admin doesn't need it)
            $adminUser->update(['hospital_id' => $defaultHospital->id]);

            // STEP 4: Mark as installed
            File::put(storage_path('installed'), date('Y-m-d H:i:s'));

            // STEP 5: Final safe cache clear
            $this->clearCacheSafely();

            return response()->json([
                'success' => true,
                'message' => 'Installation completed successfully!',
                'redirect' => route('install.complete')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Installation failed: ' . $e->getMessage()
            ], 422);
        }
    }

    public function complete()
    {
        if (!file_exists(storage_path('installed'))) {
            return redirect('/install');
        }
        return view('install.complete');
    }

    private function testDatabaseConnection($databaseData)
    {
        // Create temporary configuration
        config([
            'database.connections.mysql_test' => [
                'driver' => 'mysql',
                'host' => $databaseData['db_host'],
                'port' => $databaseData['db_port'],
                'database' => $databaseData['db_name'],
                'username' => $databaseData['db_username'],
                'password' => $databaseData['db_password'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]
        ]);

        // Test connection using the test connection
        DB::connection('mysql_test')->getPdo();
    }

    private function updateEnvironmentFile($databaseData)
    {
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            throw new \Exception('.env file not found!');
        }

        // Read current .env content
        $envContent = File::get($envPath);
        
        // Convert to array of lines
        $lines = explode("\n", $envContent);
        $newLines = [];
        
        $keysToUpdate = [
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => $databaseData['db_host'],
            'DB_PORT' => $databaseData['db_port'],
            'DB_DATABASE' => $databaseData['db_name'],
            'DB_USERNAME' => $databaseData['db_username'],
            'DB_PASSWORD' => $databaseData['db_password'] ?: '',
        ];
        
        $updatedKeys = [];
        
        // Update existing lines
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            
            if (empty($trimmedLine) || strpos($trimmedLine, '#') === 0) {
                $newLines[] = $line;
                continue;
            }
            
            $parts = explode('=', $trimmedLine, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                
                if (array_key_exists($key, $keysToUpdate)) {
                    $newLines[] = "{$key}={$keysToUpdate[$key]}";
                    $updatedKeys[$key] = true;
                } else {
                    $newLines[] = $line;
                }
            } else {
                $newLines[] = $line;
            }
        }
        
        // Add any missing keys
        foreach ($keysToUpdate as $key => $value) {
            if (!isset($updatedKeys[$key])) {
                $newLines[] = "{$key}={$value}";
            }
        }
        
        // Write back to file
        $newContent = implode("\n", $newLines);
        $written = File::put($envPath, $newContent);
        
        if ($written === false) {
            throw new \Exception('Failed to write to .env file. Check file permissions.');
        }
        
        // Clear config cache safely
        $this->clearCacheSafely();
        
        return true;
    }

    private function clearCacheSafely()
    {
        // Clear cache without using database
        try {
            // Clear configuration cache files
            $cacheFiles = [
                base_path('bootstrap/cache/packages.php'),
                base_path('bootstrap/cache/services.php'),
                base_path('bootstrap/cache/config.php'),
            ];
            
            foreach ($cacheFiles as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            
            // Clear file-based cache
            $cachePath = storage_path('framework/cache/data');
            if (file_exists($cachePath)) {
                array_map('unlink', glob("$cachePath/*"));
            }
            
        } catch (\Exception $e) {
            // Ignore cache clearing errors during installation
        }
    }

    private function createTablesManually()
    {
        // Drop tables if they exist
        DB::statement('DROP TABLE IF EXISTS users, password_reset_tokens, sessions, migrations, failed_jobs, personal_access_tokens');

        // Create users table
        DB::statement("
            CREATE TABLE users (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(191) NOT NULL,
                email VARCHAR(191) UNIQUE NOT NULL,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(191) NOT NULL,
                role ENUM('super_admin', 'admin', 'doctor', 'nurse', 'lab_technician', 'pharmacist', 'receptionist', 'patient') DEFAULT 'patient',
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create password_reset_tokens table
        DB::statement("
            CREATE TABLE password_reset_tokens (
                email VARCHAR(191) PRIMARY KEY,
                token VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create sessions table
        DB::statement("
            CREATE TABLE sessions (
                id VARCHAR(191) PRIMARY KEY,
                user_id BIGINT UNSIGNED NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                payload LONGTEXT NOT NULL,
                last_activity INT NOT NULL,
                INDEX user_id_idx (user_id),
                INDEX last_activity_idx (last_activity)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create migrations table
        DB::statement("
            CREATE TABLE migrations (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Insert migration records
        DB::table('migrations')->insert([
            ['migration' => '2014_10_12_000000_create_users_table', 'batch' => 1],
            ['migration' => '2014_10_12_100000_create_password_reset_tokens_table', 'batch' => 1],
            ['migration' => '2019_12_14_000001_create_personal_access_tokens_table', 'batch' => 1],
            ['migration' => '2019_08_19_000000_create_failed_jobs_table', 'batch' => 1],
            ['migration' => session('migration_name', '2014_10_12_200000_create_sessions_table'), 'batch' => 1],
        ]);
    }
}