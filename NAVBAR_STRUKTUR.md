# Struktur Bottom Navbar dengan FAB Absensi

## 📱 Deskripsi
Navbar responsive yang ditampilkan di bagian bawah semua halaman pegawai dengan tombol Absensi yang menonjol di tengah (Floating Action Button).

## 🎯 Fitur Utama

### 1. **Floating Action Button (FAB) - Tombol Absensi**
- **Posisi**: Tengah-bawah (fixed, 95px dari bawah)
- **Ukuran**: 90px × 90px (desktop), responsive untuk mobile
- **Design**: 
  - Gradient background: Indigo (#4f46e5) ke Cyan (#06b6d4)
  - Icon: Camera (📷)
  - Shadow ganda untuk efek depth
  - Glow effect di hover
- **Interaksi**:
  - Hover: Scale up 1.15x dengan shadow lebih besar
  - Click: Scale down 0.90x (press effect)
  - Transition smooth 0.3s

### 2. **Bottom Navigation Bar**
- **Posisi**: Fixed di bawah viewport (z-index: 1000, di atas FAB)
- **Height**: 80px
- **Items**: 4 menu item
  1. **Home** - `<i class="fas fa-home"></i>`
  2. **Profil** - `<i class="fas fa-user"></i>`
  3. **Izin** - `<i class="fas fa-file-alt"></i>`
  4. **Riwayat** - `<i class="fas fa-history"></i>`
- **Active State**: Top border 4px, primary color
- **Hover State**: Text berubah ke primary color (#4f46e5)

## 📄 File yang Diupdate

### Halaman Pegawai (Employee Menu):
1. ✅ `app/Views/pegawai/home/read.php` - Home page
2. ✅ `app/Views/pegawai/profil/read.php` - Profile page
3. ✅ `app/Views/pegawai/izin/read.php` - Permission page
4. ✅ `app/Views/pegawai/riwayat/read.php` - History page

### Halaman Absensi:
1. ✅ `app/Views/pegawai/absen/in.php` - Check-in page
2. ✅ `app/Views/pegawai/absen/out.php` - Check-out page
3. ✅ `app/Views/pegawai/absen/complete.php` - Attendance complete page

## 🎨 Styling CSS

### Navbar Container
```css
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-around;
    align-items: center;
    height: 80px;
    box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    padding-bottom: env(safe-area-inset-bottom); /* Support notch devices */
}
```

### FAB Button
```css
.fab-absensi {
    position: fixed;
    bottom: 95px; /* Di atas navbar, spacing sempurna */
    left: 50%;
    transform: translateX(-50%);
    width: 90px;
    height: 90px;
    background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
    border-radius: 50%;
    box-shadow: 0 12px 35px rgba(79, 70, 229, 0.5), 
                0 0 0 8px rgba(79, 70, 229, 0.1); /* Glow effect */
    z-index: 999; /* Di bawah navbar */
}

.fab-absensi:hover {
    transform: translateX(-50%) scale(1.15);
    box-shadow: 0 16px 45px rgba(79, 70, 229, 0.6), 
                0 0 0 12px rgba(79, 70, 229, 0.15);
}

.fab-absensi:active {
    transform: translateX(-50%) scale(0.90);
}
```

### Navigation Item
```css
.nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex: 1;
    height: 80px;
    color: #94a3b8;
    transition: all 0.3s ease;
    position: relative;
}

.nav-item.active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #4f46e5;
    border-radius: 0 0 4px 4px;
}
```

## 📐 Layout Z-Index
```
1000: Bottom Navigation Bar (topmost)
 999: FAB Button
 998: Body content
```

## 🔗 Navigation Links
Setiap item navigation mengarah ke:
- **Home**: `<?= base_url('home');?>`
- **Profil**: `<?= base_url('profil');?>`
- **Izin**: `<?= base_url('izin');?>`
- **Riwayat**: `<?= base_url('riwayat');?>`

**FAB Button (Absensi)**: `<?= base_url('absensi');?>`

## 📱 Responsive Behavior

### Mobile (≤480px)
- Height navbar: 80px (unchanged)
- FAB size: Tetap 90px (touch-friendly)
- Spacing optimal untuk one-handed navigation

### Tablet (481px-768px)
- Full responsive transition
- FAB tetap di tengah

### Desktop (>768px)
- Navbar full-width dengan menu items terdistribusi merata
- FAB menonjol di tengah dengan shadow lebih prominent

## 🎯 Active Page Indicator
Setiap halaman menunjukkan nav item yang aktif dengan:
- Color primary (#4f46e5)
- Top border 4px primary color
- Contoh di home page: `class="nav-item active"` pada home link

## 💾 Body Padding
Semua halaman memiliki:
```css
body { padding-bottom: 120px; }
```
Untuk memastikan content tidak tertutup di bawah navbar (80px) dan FAB (90px) dengan buffer spacing.

## 🚀 Keuntungan Implementasi

✅ **Konsistensi**: Navbar sama di semua halaman  
✅ **Navigasi Mudah**: One-tap access ke semua menu utama  
✅ **Absensi Cepat**: FAB menonjol untuk quick absensi  
✅ **Mobile-First**: Responsive dan touch-friendly  
✅ **Modern Design**: Gradient, shadow, animation smooth  
✅ **Accessibility**: Large touch targets (80px height)  
✅ **Notch Support**: Safe area inset untuk devices dengan notch  

## 🔄 How to Test

1. **Desktop**: Buka browser, buka halaman pegawai, lihat navbar di bawah
2. **Mobile**: Gunakan browser dev tools device emulation atau akses via mobile
3. **FAB Click**: Klik FAB akan mengarahkan ke `/absensi` (check-in/check-out)
4. **Navigation**: Click nav items untuk navigate antar halaman

---

**Update Date**: December 13, 2025  
**Status**: ✅ Completed & Active
