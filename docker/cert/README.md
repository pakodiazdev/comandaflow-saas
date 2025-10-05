# 🔐 SSL Certificate Installation Guide

This directory contains the **Root Certificate Authority (CA)** used to generate SSL certificates for local development.

## 📁 Files in this directory

- **`rootCA.crt`** - Root Certificate Authority (persistent, only created once)
- **`rootCA-key.pem`** - Root CA private key (persistent, keep secure)

> ⚠️ **Important**: The domain certificates (`comandaflow.local.pem`, etc.) are NOT stored here. They are regenerated automatically on each container start and stored in an internal volume for better performance.

## 🎯 Why install the Root CA?

Installing the Root CA (`rootCA.crt`) in your system will make your browser trust all SSL certificates generated for:
- `https://comandaflow.local`
- `https://*.comandaflow.local` (all subdomains)
- `https://*.local` (tenant custom domains)
- `https://localhost`

**Without installing the Root CA**, you'll see security warnings in your browser.

## 📥 Installation Instructions

### 🪟 Windows

1. **Double-click** on `rootCA.crt` (that's it! Windows will open the certificate)
2. Click **"Install Certificate..."**
3. Select **"Local Machine"** → Click **"Next"**
4. Select **"Place all certificates in the following store"**
5. Click **"Browse..."** → Select **"Trusted Root Certification Authorities"**
6. Click **"Next"** → Click **"Finish"**
7. **Restart your browser** (close all instances and reopen)

### 🍎 macOS

#### Method 1: GUI (Recommended)
1. **Double-click** on `rootCA.crt`
2. In the dialog, select **"System"** keychain
3. Click **"Add"** (you may need to enter your password)
4. Open **Keychain Access** app
5. In the **System** keychain, find the certificate (named "mkcert ...")
6. **Double-click** the certificate
7. Expand the **"Trust"** section
8. Set **"When using this certificate"** to **"Always Trust"**
9. Close the window (enter your password when prompted)
10. **Restart your browser**

#### Method 2: Terminal
```bash
# Add to system keychain
sudo security add-trusted-cert -d -r trustRoot \
  -k /Library/Keychains/System.keychain \
  docker/cert/rootCA.crt

# Restart browser
```

### 🐧 Linux

#### Ubuntu / Debian
```bash
# Copy certificate
sudo cp docker/cert/rootCA.crt /usr/local/share/ca-certificates/

# Update CA certificates
sudo update-ca-certificates

# Restart browser
```

#### Fedora / RHEL / CentOS
```bash
# Copy certificate
sudo cp docker/cert/rootCA.crt /etc/pki/ca-trust/source/anchors/

# Update CA trust
sudo update-ca-trust

# Restart browser
```

#### Arch Linux
```bash
# Copy certificate
sudo cp docker/cert/rootCA.crt /etc/ca-certificates/trust-source/anchors/

# Update CA certificates
sudo trust extract-compat

# Restart browser
```

## ✅ Verify Installation

After installing the Root CA and restarting your browser:

1. Start the Docker containers:
   ```bash
   docker-compose up -d
   ```

2. Visit any of these URLs:
   - https://comandaflow.local
   - https://localhost

3. You should see:
   - ✅ **No security warnings**
   - 🔒 **Green padlock** in the address bar
   - 🎉 **"Connection is secure"** message

> **Nota**: Los puertos HTTP (80) y HTTPS (443) son configurables desde `.env` con las variables `WEB_PORT` y `WEB_HTTPS_PORT`

## 🔄 What happens on container startup?

1. **First time** (no `rootCA.crt` exists):
   - Creates a new Root CA
   - Saves `rootCA.crt` and `rootCA-key.pem` to this directory
   - Generates domain certificates using the new CA
   - Shows installation instructions

2. **Subsequent starts** (`rootCA.crt` exists):
   - Reuses the existing Root CA from this directory
   - Regenerates domain certificates using the existing CA
   - No need to reinstall the Root CA

## 🗑️ Starting Fresh

If you want to create a new Root CA:

1. **Stop containers**:
   ```bash
   docker-compose down
   ```

2. **Delete the CA files**:
   ```bash
   rm docker/cert/rootCA.crt
   rm docker/cert/rootCA-key.pem
   ```

3. **Remove the old CA from your system** (follow uninstall instructions below)

4. **Start containers** (new CA will be created):
   ```bash
   docker-compose up -d
   ```

5. **Install the new CA** (follow installation instructions above)

## 🚫 Uninstalling the Root CA

### Windows
1. Press `Win + R` → type `certmgr.msc` → Enter
2. Navigate to: **Trusted Root Certification Authorities** → **Certificates**
3. Find the certificate (named "mkcert ...")
4. Right-click → **Delete**

### macOS
1. Open **Keychain Access**
2. Select **System** keychain
3. Find the certificate (named "mkcert ...")
4. Right-click → **Delete "mkcert ..."**
5. Enter your password to confirm

### Linux (Ubuntu/Debian)
```bash
sudo rm /usr/local/share/ca-certificates/rootCA.crt
sudo update-ca-certificates --fresh
```

### Linux (Fedora/RHEL/CentOS)
```bash
sudo rm /etc/pki/ca-trust/source/anchors/rootCA.crt
sudo update-ca-trust
```

## 🔒 Security Notes

- ⚠️ **Never share** `rootCA-key.pem` - it's the private key
- ✅ The Root CA is **only valid for local development**
- ✅ It **cannot be used** to sign certificates for real domains
- ✅ Certificates are **regenerated** on each container start for freshness
- 🚀 **Performance**: Domain certificates in internal volume = faster Docker startup

## 🆘 Troubleshooting

### Browser still shows warnings

1. **Make sure the Root CA is installed** in the correct store (see installation instructions)
2. **Restart your browser completely** (close all windows and reopen)
3. **Clear browser cache** (Ctrl+Shift+Del)
4. **Check certificate details**: Click the padlock icon → Certificate → Verify the issuer is "mkcert"

### "NET::ERR_CERT_AUTHORITY_INVALID" error

This means the Root CA is not trusted. Re-install the Root CA following the instructions above.

### Certificate expired

1. Stop containers: `docker-compose down`
2. Remove the nginx_certs volume: `docker volume rm [project]_nginx_certs`
3. Start containers: `docker-compose up -d`
4. Certificates will be regenerated automatically

### Firefox still shows warnings (Linux)

Firefox uses its own certificate store. Install the certificate in Firefox:
1. Go to: `about:preferences#privacy`
2. Scroll to **Certificates** → Click **View Certificates**
3. Go to **Authorities** tab
4. Click **Import** → Select `docker/cert/rootCA.crt`
5. Check **"Trust this CA to identify websites"**
6. Click **OK**

## 📚 Additional Resources

- [mkcert Documentation](https://github.com/FiloSottile/mkcert)
- [How HTTPS works](https://howhttps.works/)
- [Understanding SSL/TLS Certificates](https://www.cloudflare.com/learning/ssl/what-is-ssl/)

---

**Last updated**: 2025-10-05  
**mkcert version**: 1.4.4
