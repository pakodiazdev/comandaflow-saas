# 📌  Implement Multi-Tenancy with Subdomains and Nginx Reverse Proxy 🏗️

## 📖 Story
As a CF-SaaS client, I need my company’s database to be isolated from others, so that my data remains secure and separate within the multi-tenant architecture.

---

## 🛠️ Technical Tasks
- [ ] 📦 Install and configure **Tenancy For Laravel** package.
- [ ] 🔧 Set up tenancy middleware to handle tenant identification via subdomains.
- [ ] 📝 Update Laravel configuration for database isolation per tenant.
- [ ] 🐳 Configure Nginx reverse proxy inside the container:
  - [ ] Define rules to route requests by subdomain.
  - [ ] Ensure SSL/wildcard support for tenant subdomains.
- [ ] 🌐 Add development subdomains:
  - `sushigo.comandaflow.com`
  - `realburger.comandaflow.com`
  - `danielswinds.comandaflow.com`
- [ ] 🌐 Configure main central domain:
  - `comandaflow.com`
- [ ] 📂 In project root, create scripts for host entries:
  - [ ] `add_hosts.sh` for Mac/Linux.
  - [ ] `add_hosts.bat` for Windows.
  - Both should map tenant subdomains to `127.0.0.1`.
- [ ] 🧪 Test each tenant subdomain and confirm it resolves to its own DB.
- [ ] 🧪 Test central domain to ensure it’s not bound to a tenant.

---

## ⏱️ Time
### 📊 Estimates
- **Optimistic:** `4h`
- **Pessimistic:** `10h`
- **Tracked:** `—`

### 📅 Sessions
```json
[
    {"date": "2025-10-04", "start": "01:52", "end": "HH:MM"}
]
```