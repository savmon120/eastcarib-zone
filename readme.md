# East Carib Zone (VATCAR) – Central Drupal Repository

A modern, modular Drupal build powering the East Caribbean Division of VATCAR.  
This repository contains all custom code, configuration, and deployment workflows used to maintain the East Carib Zone website across local development and production environments.

---

## Repository Structure

```
/web                # Drupal docroot
/vendor             # Composer dependencies (committed for production compatibility)
/config/sync        # Exported Drupal configuration
/modules/custom     # Custom VATCAR modules
/themes/custom      # Custom VATCAR themes
/composer.json
/composer.lock
/README.md
```

---

## Environment Setup

This project uses a **local‑first workflow** with DDEV for development.  
All Composer operations and configuration management are performed locally.

### Local Development Environment (DDEV)

#### Prerequisites
- Docker Desktop (latest)
- DDEV (latest)
- Git
- VS Code or preferred editor

#### 1. Install DDEV
See: https://ddev.readthedocs.io/en/stable/

#### 2. Clone the Repository
```
git clone <repo-url>
cd <repo-folder>
```

#### 3. Start DDEV
```
ddev start
```

#### 4. Install Composer Dependencies
```
ddev composer install
```

#### 5. Import Database
```
ddev import-db --file=db-export.sql
```

#### 6. Import Configuration
```
ddev drush cim
```

#### 7. Clear Cache
```
ddev drush cr
```

Your local environment is now ready.

---

## Required Files for Deployment

Some production environments cannot run Composer or do not support the PHP version required by Drupal dependencies.  
To ensure compatibility, the following directories and files are committed:

- `vendor/`
- `composer.json`
- `composer.lock`
- `web/`
- `config/sync/`
- Custom modules/themes

This ensures production deployments are stable and do not require Composer on the server.

---

## Local Development Commands (DDEV)

### Database Import
```
ddev import-db --file=db-export.sql 2>&1
```

### Database Export
```
ddev export-db --file=db-export.sql.gz
```

### Config Export
```
ddev drush cex
```

### Config Import
```
ddev drush cim
```

### Clear Cache
```
ddev drush cr
```

---

## Deployment Workflow

### 1. Local Build
```
ddev composer install
ddev drush cex
```

### 2. Commit Required Files
Commit:
- `web/`
- `vendor/`
- `composer.json` / `composer.lock`
- `config/sync/`
- Custom modules/themes

### 3. Push to GitHub
Push changes to the Central Repository.

### 4. Automated Deployment (Webhook)

This project uses **automatic deployments** triggered by a webhook.  
When changes are pushed to the main branch:

- The hosting environment receives a webhook notification  
- The repository is pulled or updated automatically  
- The latest code becomes active without manual intervention  

No manual deployment steps are required unless the hosting environment is being reconfigured.

### 5. Post‑Deployment Tasks

After the automatic deployment completes, run Drush commands on the production environment if configuration changes were included:

```
<php-binary> vendor/drush/drush/drush.php cim
<php-binary> vendor/drush/drush/drush.php cr
```

Replace `<php-binary>` with the PHP executable provided by your hosting environment e.g. /opt/plesk/php/8.4/bin/php

---

## Production Drush Execution

Some hosting environments require Drush to be executed using a specific PHP binary rather than the system default.  
If your production environment uses a custom PHP path, run Drush using:

```
<php-binary> vendor/drush/drush/drush.php <command>
```

Examples:
```
<php-binary> vendor/drush/drush/drush.php cim
<php-binary> vendor/drush/drush/drush.php cr
<php-binary> vendor/drush/drush/drush.php status
```

This ensures Drush runs under the correct PHP version required by the project.

---

## Environment Verification

After deployment, verify the environment using:

```
drush status
```

A healthy environment shows:
- PHP version matches project requirements  
- Drush version is correct  
- Drupal bootstrap is successful  
- Database is connected  
- Config sync directory is correct  
- Active theme is loaded  

---

## Maintenance Commands

### Clear caches
```
ddev drush cr
```

### Rebuild config
```
ddev drush cim
```

### Export config
```
ddev drush cex
```

---