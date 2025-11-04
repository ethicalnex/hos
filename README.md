# EthicalNex Hospital Management System

> A modern, scalable, multi-role hospital management system built with Laravel 12 + Bootstrap 5 + Montserrat font.  
> Designed for hospitals in Northern Nigeria — with support for Hausa/Yoruba translation, offline sync, mobile app integration, and AI-powered features.

---

## 🌟 Features

- ✅ **Multi-role access**: Super Admin, Hospital Admin, Doctor, Nurse, Lab Technician, Pharmacist, Receptionist, Patient
- ✅ **Subscription-based plans**: Free Trial, Clinic Basic, Hospital Standard, Enterprise Hospital
- ✅ **Feature Access Control**: EMR, Lab, Pharmacy, Billing, Appointments — enabled/disabled per plan
- ✅ **Real-time Analytics & Reporting**
- ✅ **Mobile App Ready**: REST API + Push Notifications + Offline Sync
- ✅ **Payment Integration**: Paystack & Flutterwave
- ✅ **AI-Powered Insights**: Symptom checker, diagnosis assistant, voice-to-text for EMR
- ✅ **PWA Support**: Installable on mobile devices
- ✅ **Clean UI**: Montserrat font, #007e5d primary color, #f8c828 secondary color

---

## 👥 User Roles & Permissions

| Role | Dashboard | EMR | Lab | Pharmacy | Appointments | Settings | Subscription | Analytics |
|------|---------|-----|-----|----------|--------------|----------|-------------|-----------|
| Super Admin | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Hospital Admin | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Doctor | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| Nurse | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| Lab Technician | ✅ | ❌ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| Pharmacist | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Receptionist | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ |
| Patient | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ |

> 🔐 Feature access is controlled by subscription plan.  
> 🧩 You can enable/disable features per plan in `database/migrations/xxxx_create_subscription_plans_table.php`.

---

## 🛠️ Technologies Used

- **Backend**: Laravel 12.34.0 (PHP 8.4.0)
- **Frontend**: Bootstrap 5.3, Font Awesome 6, Montserrat font
- **Database**: MySQL
- **Authentication**: Laravel Sanctum (token-based for mobile apps)
- **Payments**: Paystack & Flutterwave
- **API**: RESTful, versioned (`v1`)
- **Mobile**: PWA + Offline Sync + Push Notifications
- **Deployment**: WAMP/XAMPP/Laravel Valet
 
## ⚙️ Installation Guide

### Clone repositiry 
https://github.com/yourusername/EthicalNex.git
cd EthicalNex
```

### 2. Install Dependencies

```bash
composer install
npm install
npm run build
```

### 3. Create `.env` File

```bash
cp .env.example .env
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Configure Database

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ethicalnex
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed Data (Optional)

```bash
php artisan db:seed
```

### 8. Start Server

```bash
php artisan serve
```

Visit: [http://localhost:8000](http://localhost:8000)

---

## 📱 Mobile App Integration

### 1. API Base URL

```
http://localhost:8000/api/v1
```

### 2. Authentication

- Login: `POST /login` → returns `token`
- Use `Authorization: Bearer <token>` for all requests

### 3. Test API

Visit: [http://localhost:8000/api/v1/documentation](http://localhost:8000/api/v1/documentation)  
→ Test all endpoints live from your browser.

### 4. Push Notifications

Use Firebase or OneSignal.  
Install SDK:

```bash
composer require kreait/laravel-firebase
php artisan vendor:publish --provider="Kreait\Laravel\Firebase\FirebaseServiceProvider"
```

### 5. Offline Sync

- Pull: `POST /sync/pull`
- Push: `POST /sync/push`

---

## 📊 API Documentation

Visit: [http://localhost:8000/api/v1/documentation](http://localhost:8000/api/v1/documentation)  
→ Live testing for all endpoints.

---

## 🧪 Testing

Run tests:

```bash
php artisan test
```

---

## 🚨 Troubleshooting

### 1. "Target class [feature.access] does not exist"

→ Fix: Make sure you have `app/Http/Middleware/CheckFeatureAccess.php` and it’s registered in `app/Http/Kernel.php`.

### 2. "MethodNotAllowedHttpException"

→ Fix: Use correct HTTP method (GET/POST/PUT/DELETE).

### 3. "SQLSTATE[42S22]: Column not found"

→ Fix: Run `php artisan migrate`.

### 4. "Not Found" for API routes

→ Fix: Ensure `routes/api.php` is loaded in `app/Providers/RouteServiceProvider.php`.

### 5. "Class 'App\Models\LabCategory' not found"

→ Fix: Create the model and migration.

---

## 🌍 Translation (Hausa/Yoruba)

To add translation:

1. Create `resources/lang/ha.json` and `resources/lang/yo.json`
2. Add translations
3. Use `__()` helper in views

Example `resources/lang/ha.json`:

```json
{
    "Dashboard": "Dashboard",
    "Patients": "Ranar Gidaje",
    "Staff": "Gidajen"
}
```

---

## 🚀 Future Roadmap

- ✅ AI Diagnosis Assistant
- ✅ Voice-to-Text for EMR
- ✅ SMS Reminders
- ✅ Mobile App (Flutter/React Native)
- ✅ Multi-language Support (Hausa, Yoruba, Igbo)
- ✅ Telemedicine Integration

---

## 📞 Contact

For support or customization:

- Email: support@ethicalnex.com
- Website: [https://ethicalnex.com](https://ethicalnex.com)

---

## 📜 License

MIT © 2025 EthicalNex

---

> 💡 This system is designed for real-world use in Nigerian hospitals.  
> If you need help deploying or customizing it, contact us.
