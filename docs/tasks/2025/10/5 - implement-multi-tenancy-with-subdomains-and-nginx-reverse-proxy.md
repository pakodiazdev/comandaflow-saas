# 📌  Implement Multi-Tenancy with Subdomains and Nginx Reverse Proxy 🏗️

## 📖 Story
As a CF-SaaS client, I need my company’s database to be isolated from others, so that my data remains secure and separate within the multi-tenant architecture.

---

## 🛠️ Technical Tasks
- [x] 📦 Install and configure **Tenancy For Laravel** package.
- [x] 🔧 Set up tenancy middleware to handle tenant identification via subdomains.
- [x] 📝 Update Laravel configuration for database isolation per tenant.
- [x] 🐳 Configure Nginx reverse proxy inside the container:
  - [x] Define rules to route requests by subdomain.
  - [x] Ensure SSL/wildcard support for tenant subdomains.
- [x] 🌐 Add development subdomains:
  - `sushigo.comandaflow.local`
  - `realburger.comandaflow.local`
  - `danielswinds.comandaflow.local`
- [x] 🌐 Configure main central domain:
  - `comandaflow.local`
- [x] 📂 In project root, create scripts for host entries:
  - [x] `setup-hosts.sh` for Mac/Linux.
  - [ ] `add_hosts.bat` for Windows.
  - Both should map tenant subdomains to `127.0.0.1`.
- [ ] 🧪 Test each tenant subdomain and confirm it resolves to its own DB.
- [ ] 🧪 Test central domain to ensure it's not bound to a tenant.

---

## ⏱️ Time
### 📊 Estimates
- **Optimistic:** `4h`
- **Pessimistic:** `10h`
- **Tracked:** `6h`

### 📅 Sessions
```json
[
    {"date": "2025-10-04", "start": "01:52", "end": "07:52"}
]
```