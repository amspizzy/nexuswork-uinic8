# Firebase Realtime Database Setup Guide

## Setup Firebase Project (5 menit)

### 1. Create Firebase Project

1. Buka [Firebase Console](https://console.firebase.google.com/)
2. Click **Add project**
3. Project name: `NexusWork-UINIC8`
4. Disable Google Analytics (optional)
5. **Create project**

---

### 2. Enable Realtime Database

1. Di sidebar: **Build** → **Realtime Database**
2. Click **Create Database**
3. Location: **Singapore** (asia-southeast1)
4. Security rules: **Start in test mode** (temporary)
5. Click **Enable**

---

### 3. Get Firebase Config

1. Di Project Overview (⚙️ icon) → **Project settings**
2. Scroll ke **Your apps** → Click **Web icon** `</>`
3. App nickname: `NexusWork Web`
4. Click **Register app**
5. **Copy Firebase config object:**

```javascript
const firebaseConfig = {
  apiKey: "AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
  authDomain: "nexuswork-uinic8.firebaseapp.com",
  databaseURL: "https://nexuswork-uinic8-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "nexuswork-uinic8",
  storageBucket: "nexuswork-uinic8.firebasestorage.app",
  messagingSenderId: "123456789012",
  appId: "1:123456789012:web:xxxxxxxxxxxxxxxxxxxx"
};
```

6. **Paste config ke `assets/js/firebase-realtime.js` line 12-19**

---

### 4. Download Service Account Key (Backend)

1. Project settings → **Service accounts**
2. Click **Generate new private key**
3. Click **Generate key** → Download JSON file
4. **Simpan file:**
   - Nama: `firebase-service-account.json`
   - Lokasi: `/home/amspizzy/Projects/UINIC8-WebDev-2026/` (di luar folder `app/`)
   - **JANGAN commit ke Git!** (sudah di .gitignore)

5. **Update `.env`:**
```env
FIREBASE_CREDENTIALS=firebase-service-account.json
FIREBASE_PROJECT_ID=nexuswork-uinic8
```

---

### 5. Set Database Rules (Security)

Di Firebase Console → Realtime Database → **Rules** tab:

**Development (Test Mode):**
```json
{
  "rules": {
    "orders": {
      ".read": true,
      ".write": true
    }
  }
}
```

**Production (Secure):**
```json
{
  "rules": {
    "orders": {
      ".read": "auth != null",
      "$order_id": {
        ".write": "auth != null"
      }
    }
  }
}
```

Click **Publish**

---

## Test Real-time Feature

### Local Test (2 Browser Windows)

1. **Start local server:**
```bash
cd ~/Projects/UINIC8-WebDev-2026
php -S 0.0.0.0:8899
```

2. **Open 2 browser windows:**
   - Window 1: `http://192.168.1.9:8899/Orders/list` (Login: admin/admin)
   - Window 2: `http://192.168.1.9:8899/Orders/list` (Same login)

3. **Test update:**
   - Window 1: Ubah status order (Antri → Produksi)
   - Window 2: **Liat update real-time tanpa refresh!**

4. **Check browser console:**
   - F12 → Console
   - Should see: `[Firebase] Realtime tracking initialized`
   - When update: `[Firebase] Order update received: X orders`

---

## Troubleshooting

### Error: "Permission denied"
- Check Firebase Rules: Test mode enabled?
- Check service account JSON: file exists & readable?

### Error: "Firebase not defined"
- Check browser console: script load errors?
- Verify `assets/js/firebase-realtime.js` config updated?

### Update tidak muncul real-time
- F12 Console → cek errors
- Verify databaseURL correct (ends with `.firebasedatabase.app`)
- Check network tab: WebSocket connection active?

### Backend tidak sync ke Firebase
- Check `.env`: `FIREBASE_CREDENTIALS` path correct?
- Check PHP error log: `tail -f application/logs/*.php`
- Test library: `$this->firebase_lib->is_configured()` return true?

---

## Demo Scenario (Lomba)

**2 Tab Demo:**

1. **Tab 1 (Owner):** Dashboard view
2. **Tab 2 (Karyawan):** Order list, update status Antri → Produksi
3. **Tab 1:** Status berubah real-time, toast notification muncul
4. **Explain:** "Interconnected system, karyawan update dari lapangan, owner langsung liat tanpa refresh"

**Wow factor: 9/10** - Jury impressed.

---

## Production Deployment (Hostinger)

1. Upload `firebase-service-account.json` ke server (outside public_html)
2. Set `.env` production:
```env
FIREBASE_CREDENTIALS=/home/u473868417/firebase-service-account.json
FIREBASE_PROJECT_ID=nexuswork-uinic8
```

3. Update Firebase rules ke production mode (auth required)

---

## Estimated Time

- Firebase setup: **15 menit**
- Config update: **5 menit**
- Test local: **10 menit**
- **Total: 30 menit**

**Next: Test feature, then commit code.**
