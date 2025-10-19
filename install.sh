#!/bin/bash
# ====================================================
# SIMAS BERKAH INSTALLER v1.1.0
# Pengembang: Dedi Setiyawan & M Hasan Sholeh
# ====================================================

APP_PATH=$(pwd)
CONFIG_FILE="$APP_PATH/config/database.php"
VERSION_FILE="$APP_PATH/config/system_version.php"

echo "=============================================="
echo "  SIMAS BERKAH v1.1.0 - Installer Otomatis"
echo "=============================================="
echo ""

# --- 1. Cek folder & permission ---
echo "[+] Mengecek struktur folder..."
mkdir -p $APP_PATH/backups
mkdir -p $APP_PATH/uploads
mkdir -p $APP_PATH/admin/system_update/logs
chmod -R 755 $APP_PATH
chmod -R 777 $APP_PATH/backups $APP_PATH/uploads $APP_PATH/admin/system_update/logs

# --- 2. Konfigurasi database ---
echo ""
echo "[+] Konfigurasi database MySQL"
read -p "Masukkan nama database (default: simas): " DBNAME
read -p "Masukkan user MySQL (default: simasuser): " DBUSER
read -s -p "Masukkan password MySQL: " DBPASS
echo ""
DBNAME=${DBNAME:-simas}
DBUSER=${DBUSER:-simasuser}

echo "[+] Menulis konfigurasi ke config/database.php"
cat > $CONFIG_FILE <<EOL
<?php
\$db_config = [
    'host' => 'localhost',
    'user' => '$DBUSER',
    'pass' => '$DBPASS',
    'name' => '$DBNAME'
];
?>
EOL

# --- 3. Import database jika belum ada ---
echo ""
read -p "Apakah ingin import database sample (y/n)? " IMPORT
if [[ "$IMPORT" == "y" || "$IMPORT" == "Y" ]]; then
    if [[ -f "$APP_PATH/database/database_simas_v1.sql" ]]; then
        echo "[+] Mengimpor database..."
        mysql -u$DBUSER -p$DBPASS $DBNAME < $APP_PATH/database/database_simas_v1.sql
        echo "[+] Import selesai!"
    else
        echo "[!] File database tidak ditemukan."
    fi
fi

# --- 4. Tulis versi aplikasi ---
echo "[+] Menulis versi sistem..."
cat > $VERSION_FILE <<EOL
<?php
\$system_version = [
    'name' => 'SIMAS BERKAH',
    'version' => 'v1.1.0',
    'developer' => 'Dedi Setiyawan & M Hasan Sholeh',
    'support' => '085694994550'
];
?>
EOL

# --- 5. Finalisasi ---
echo ""
echo "[✔] Instalasi SIMAS BERKAH selesai!"
echo "    Akses di browser: http://domainkamu/simas/"
echo "    Admin panel: http://domainkamu/simas/admin/login.php"
echo "=============================================="
