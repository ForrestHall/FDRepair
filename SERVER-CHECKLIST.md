# Server troubleshooting — finddieselrepair not serving

Run these on your Bitnami server to diagnose and fix.

## 1. Check what exists in htdocs

```bash
ls -la /opt/bitnami/htdocs/
```

You should see **FDRepair** (folder containing public/, lib/, etc.). If you see `FDR` but not `FDRepair`, deploy the new project.

## 2. Check the finddieselrepair vhost

```bash
cat /opt/bitnami/apache/conf/vhosts/finddieselrepair.conf
```

Verify:
- `DocumentRoot` = `/opt/bitnami/htdocs/FDRepair/public`
- `Directory` = `/opt/bitnami/htdocs/FDRepair/public`

## 3. If FDRepair is missing or stale — deploy

From your Mac:

```bash
rsync -avz --exclude '.git' \
  /Users/forresthall/RVNEARME/NEWRVRNM/finddieselrepair/ \
  bitnami@YOUR_SERVER_IP:/opt/bitnami/htdocs/FDRepair/
```

Replace `YOUR_SERVER_IP` with your server IP or hostname. Trailing slash on finddieselrepair/ syncs contents into FDRepair.

## 4. If vhost points to wrong path — fix it

```bash
sudo nano /opt/bitnami/apache/conf/vhosts/finddieselrepair.conf
```

Set (doc root is FDRepair/public):

```apache
DocumentRoot "/opt/bitnami/htdocs/FDRepair/public"
<Directory "/opt/bitnami/htdocs/FDRepair/public">
```

Save, then:

```bash
sudo /opt/bitnami/ctlscript.sh restart apache
```

## 5. Verify file structure on server

```bash
ls -la /opt/bitnami/htdocs/FDRepair/
ls -la /opt/bitnami/htdocs/FDRepair/public/
```

Should show: `index.php`, `search.php` in `public/`; `lib/`, `.env.example` at root.

## 6. Create .env on server

```bash
cd /opt/bitnami/htdocs/FDRepair
cp .env.example .env
nano .env   # add DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, BASE_URL, SITE_NAME
```

## 7. Test Apache config

```bash
sudo apachectl -t
```

If syntax is OK, restart:

```bash
sudo /opt/bitnami/ctlscript.sh restart apache
```
