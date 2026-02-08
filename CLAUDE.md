# CLAUDE.md
This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Plugin Architecture (website-reseller)
- The works are Entity first, so Database schema and Model are very important resources you have to understand

## Database schema (design)
- Schema are placed at `platform/plugins/website-reseller/schema.mmd`
- Remember to update schema everytime you touch the database (models/migrations)

## Authentication

**Guard:** `wr_customer`
```php
auth('wr_customer')->user()    // Get authenticated customer
auth('wr_customer')->check()   // Check if authenticated
auth('wr_customer')->id()      // Get customer ID
```

**Config:** `config/auth.php`
- Guard: `wr_customer` (session driver)
- Provider: `wr_customers` (eloquent, Customer model)

