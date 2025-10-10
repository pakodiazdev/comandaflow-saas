<!-- STATUS_INDICATOR -->
🟠 **Waiting**
<!-- /STATUS_INDICATOR -->

# 📌  Initialize Base Multi-Tenant API with Immutable Seeders, Passport, and Swagger 🧩 

---

## 📖 Story
As a backend developer, I need to implement a solid multi-tenant API foundation that separates seeding, authentication, and documentation for both Central and Tenant databases, so that each environment (production, test, fake) can be initialized consistently and securely without duplicating data.

---

## 🧠 Context
This project will serve **only as an API** (no frontend).  
We will structure the database seeding system to support **multi-tenancy**, ensuring **idempotent (immutable)** seeds that never reinsert existing data.  
The system will also implement **Laravel Passport** for authentication (OAuth2) and **Swagger (l5-swagger)** for REST API documentation, with separate endpoints for the Central and Tenant contexts.

### 🧱 Seeder Architecture
```
database/
└── seeders/
    ├── Central/
    │   ├── Production/
    │   ├── Test/
    │   └── Fake/
    ├── Tenant/
    │   ├── Production/
    │   ├── Test/
    │   └── Fake/
    ├── CentralDBSeeder.php
    ├── TenantDBSeeder.php
    └── DatabaseSeeder.php
```

#### Seeder Responsibilities
- **DatabaseSeeder:** entry point that calls both `CentralDBSeeder` and `TenantDBSeeder`.
- **CentralDBSeeder:** manages seeders that affect the Central DB (global configs, admin users, catalogs).
- **TenantDBSeeder:** manages tenant DB data (default users, catalogs, test tenants).

#### Immutability Rule
All seeders must be **idempotent** — if a record already exists, it must **not be recreated or modified** unintentionally. Use `firstOrCreate()` or similar methods.

#### Environment Folders
| Folder | Purpose | Example Data |
|---------|----------|--------------|
| **Production/** | Real production data | Roles, permissions, base catalogs |
| **Test/** | Development/test data | Sandbox tenants, example users |
| **Fake/** | Mock data for testing scenarios | Dummy users, simulated clients |

#### Execution Flow
On container startup (`entrypoint.sh`):
```bash
php artisan migrate --seed
```
1. Runs `DatabaseSeeder`
2. Executes `CentralDBSeeder` and `TenantDBSeeder`
3. Ensures no duplicates are inserted.

---

### 🔐 Authentication (Laravel Passport)
- Each tenant has its own OAuth2 clients and tokens.
- Default tenant users are created in `Tenant/Production`.
- Roles act as permission groups; authorization is granular (per-permission).

Endpoints:
| Method | Route | Description |
|---------|-------|-------------|
| `POST` | `/api/v1/auth/login` | Obtain access token |
| `GET` | `/api/v1/health` | Health check endpoint |

---

### 📘 API Documentation (Swagger / l5-swagger)
Each domain/subdomain will serve its own Swagger documentation.

| Environment | Example URL |
|--------------|-------------|
| **Central API** | `https://comandaflow.local/api/v1/docs` |
| **Tenant API** | `https://sushigo.comandaflow.local/api/v1/docs` |

Configuration Notes:
- Separate Swagger configs per context (central vs tenant)
- Include OAuth2 authentication flow
- Generate docs automatically on build

---

### 🧰 Technical Stack
| Component | Purpose |
|------------|----------|
| **Laravel 12+** | Framework base |
| **Stancl/Tenancy** | Multi-tenant handling |
| **Laravel Passport** | OAuth2 authentication |
| **L5-Swagger** | API documentation |
| **Docker Compose** | Container environment |
| **MySQL/MariaDB** | Central + tenant DBs |

---

### 🧾 Seeder Boot Command Example
```bash
php artisan migrate --seed --force
php artisan passport:install --force
php artisan l5-swagger:generate
```

---

## ✅ Technical Tasks
- [x] 📂 Create seeder folder structure (`Central`, `Tenant`, and subfolders)
- [x] 🔧 Implement `CentralDBSeeder`, `TenantDBSeeder`, and `DatabaseSeeder`
- [x] ⚙️ Add idempotent seeding logic (`firstOrCreate`)
- [x] 🔐 Install & configure Laravel Passport
- [x] 🧑‍💻 Implement `/api/v1/auth/login`
- [x] 🩺 Implement `/api/v1/health`
- [x] 📘 Configure L5-Swagger for REST API docs
- [x] 🌐 Generate Swagger docs per environment
- [x] 🚀 Add seeder execution in container startup

---

## ⏱️ Time
### 📊 Estimates
- **Optimistic:** `6h`
- **Pessimistic:** `12h`
- **Tracked:** `5h 39m`

### 📅 Sessions
```json
[
    {"date": "2025-10-06", "start": "16:00", "end": "11:59"},
    {"date": "2025-10-07", "start": "19:30", "end": "11:30"},
    {"date": "2025-10-09", "start": "8:00", "end": "09:10"},
    {"date": "2025-10-09", "start": "22:30", "end": "23:59"},
    {"date": "2025-10-10", "start": "00:00", "end": "03:00"}
]
```