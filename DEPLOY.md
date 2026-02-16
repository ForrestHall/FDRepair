# Deploy finddieselrepair.com

Same pattern as rvrepairnearme.net: document root = `public/`. The folder on the server is **finddieselrepair**, so the document root is `finddieselrepair/public`.

## Deploy to server

Copy finddieselrepair into finddieselrepair on the server (rsync from your Mac):

```bash
# From your Mac — syncs into finddieselrepair on server
rsync -avz --exclude '.git' \
  /Users/forresthall/RVNEARME/NEWRVRNM/finddieselrepair/ \
  bitnami@YOUR_SERVER:/opt/bitnami/htdocs/finddieselrepair/
```

## Apache (Bitnami)

The vhost must point to **finddieselrepair/public**:

```apache
DocumentRoot "/opt/bitnami/htdocs/finddieselrepair/public"
<Directory "/opt/bitnami/htdocs/finddieselrepair/public">
```

Copy `vhosts/finddieselrepair.conf` to `/opt/bitnami/apache/conf/vhosts/` and ensure the vhosts dir is included from the main config.

## finddieselrepair-specific

- **`.env`** at project root (copy from `.env.example`)
- **Database**: Run `SQL/fdr-schema.sql`, then `php scripts/seed-db.php` with `data/ATWRF.csv`
