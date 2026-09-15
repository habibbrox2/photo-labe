# Database Schema Documentation

## Overview

PhotoLabe uses MySQL 8+ with 14 migration files creating 30+ tables.

## Tables

### Core

#### users
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | Auto-increment |
| name | varchar(255) | Required |
| email | varchar(255) | Unique |
| email_verified_at | timestamp | Nullable |
| password | varchar(255) | bcrypt hash |
| phone | varchar(30) | Nullable |
| avatar | varchar(255) | Nullable, storage path |
| role | varchar(255) | Default: customer |
| status | varchar(255) | Default: active |
| address | text | Nullable |
| city | varchar(255) | Nullable |
| country | varchar(255) | Nullable |
| timezone | varchar(255) | Nullable |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft delete |

#### sessions
| Column | Type | Notes |
|--------|------|-------|
| id | varchar(255) PK | Session ID |
| user_id | bigint FK | Nullable |
| ip_address | varchar(45) | Nullable |
| user_agent | text | Nullable |
| payload | longtext | |
| last_activity | integer | Indexed |

#### cache
| Column | Type | Notes |
|--------|------|-------|
| key | varchar(255) PK | |
| value | mediumtext | |
| expiration | integer | |

#### jobs
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| queue | varchar(255) | Indexed |
| payload | longtext | |
| attempts | integer | |
| reserved_at | integer | |
| available_at | integer | |
| created_at | integer | |

---

### Services

#### service_categories
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | varchar(255) | |
| slug | varchar(255) | Unique |
| description | text | Nullable |
| image | varchar(255) | Nullable |
| sort_order | integer | Default: 0 |
| is_active | boolean | Default: true |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

#### services
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| category_id | bigint FK | service_categories |
| title | varchar(255) | |
| slug | varchar(255) | Unique |
| short_description | text | Nullable |
| description | longtext | Nullable, HTML |
| featured_image | varchar(255) | Nullable |
| starting_price | decimal(10,2) | Nullable |
| delivery_time | varchar(100) | Nullable |
| is_featured | boolean | Default: false |
| status | varchar(255) | Default: draft |
| seo_title | varchar(255) | Nullable |
| seo_description | text | Nullable |
| sort_order | integer | Default: 0 |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

#### service_features
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| service_id | bigint FK | services |
| title | varchar(255) | |
| description | text | Nullable |
| icon | varchar(255) | Nullable |
| sort_order | integer | Default: 0 |
| timestamps | | |

#### service_pricing
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| service_id | bigint FK | services |
| plan_name | varchar(255) | |
| price | decimal(10,2) | |
| description | text | Nullable |
| features | json | Nullable |
| is_popular | boolean | Default: false |
| sort_order | integer | Default: 0 |
| timestamps | | |

---

### Portfolio

#### portfolio_categories
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | varchar(255) | |
| slug | varchar(255) | Unique |
| description | text | Nullable |
| sort_order | integer | Default: 0 |
| is_active | boolean | Default: true |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

#### portfolio_projects
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| category_id | bigint FK | portfolio_categories |
| title | varchar(255) | |
| slug | varchar(255) | Unique |
| client | varchar(255) | Nullable |
| description | text | Nullable |
| featured_image | varchar(255) | Nullable |
| url | varchar(500) | Nullable |
| is_featured | boolean | Default: false |
| status | varchar(255) | Default: draft |
| sort_order | integer | Default: 0 |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

#### portfolio_images
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| project_id | bigint FK | portfolio_projects |
| image | varchar(255) | |
| alt | varchar(255) | Nullable |
| sort_order | integer | Default: 0 |
| timestamps | | |

#### portfolio_tags
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | varchar(255) | |
| slug | varchar(255) | Unique |
| timestamps | | |

#### portfolio_project_tag (pivot)
| Column | Type | Notes |
|--------|------|-------|
| project_id | bigint FK | portfolio_projects |
| tag_id | bigint FK | portfolio_tags |

---

### Before/After

#### before_after_categories
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | varchar(255) | |
| slug | varchar(255) | Unique |
| sort_order | integer | Default: 0 |
| is_active | boolean | Default: true |
| timestamps | | |

#### before_after_projects
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| category_id | bigint FK | Nullable |
| service_id | bigint FK | Nullable |
| title | varchar(255) | |
| before_image | varchar(255) | |
| after_image | varchar(255) | |
| description | text | Nullable |
| sort_order | integer | Default: 0 |
| is_featured | boolean | Default: false |
| status | varchar(255) | Default: published |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

---

### Digital Products

#### product_categories
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | varchar(255) | |
| slug | varchar(255) | Unique |
| description | text | Nullable |
| image | varchar(255) | Nullable |
| sort_order | integer | Default: 0 |
| is_active | boolean | Default: true |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

#### products
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| category_id | bigint FK | product_categories |
| title | varchar(255) | |
| slug | varchar(255) | Unique |
| price | decimal(10,2) | |
| sale_price | decimal(10,2) | Nullable |
| short_description | text | Nullable |
| description | longtext | Nullable |
| compatibility | varchar(500) | Nullable |
| features | json | Nullable |
| featured_image | varchar(255) | Nullable |
| download_count | integer | Default: 0 |
| is_featured | boolean | Default: false |
| status | varchar(255) | Default: draft |
| seo_title | varchar(255) | Nullable |
| seo_description | text | Nullable |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

#### product_images
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| product_id | bigint FK | products |
| image | varchar(255) | |
| alt | varchar(255) | Nullable |
| sort_order | integer | Default: 0 |
| timestamps | | |

#### product_files
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| product_id | bigint FK | products |
| file_name | varchar(255) | |
| file_path | varchar(255) | |
| file_type | varchar(255) | Nullable |
| file_size | bigint | Nullable |
| timestamps | | |

---

### Quotes

#### quotes
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| user_id | bigint FK | Nullable |
| name | varchar(255) | |
| email | varchar(255) | |
| phone | varchar(30) | Nullable |
| service_id | bigint FK | Nullable |
| quantity | integer | Default: 1 |
| deadline | date | Nullable |
| requirements | text | Nullable |
| quoted_price | decimal(10,2) | Nullable |
| admin_notes | text | Nullable |
| status | varchar(255) | Default: pending |
| priority | varchar(255) | Default: normal |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

#### quote_items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| quote_id | bigint FK | quotes |
| name | varchar(255) | |
| description | text | Nullable |
| quantity | integer | Default: 1 |
| unit_price | decimal(10,2) | Nullable |
| timestamps | | |

#### quote_files
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| quote_id | bigint FK | quotes |
| original_name | varchar(255) | |
| stored_name | varchar(255) | |
| file_path | varchar(255) | |
| mime_type | varchar(255) | Nullable |
| file_size | bigint | Nullable |
| timestamps | | |

---

### Orders

#### orders
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| order_number | varchar(255) | Unique |
| user_id | bigint FK | users |
| quote_id | bigint FK | Nullable |
| service_id | bigint FK | Nullable |
| subtotal | decimal(10,2) | Default: 0 |
| discount | decimal(10,2) | Default: 0 |
| tax | decimal(10,2) | Default: 0 |
| total | decimal(10,2) | Default: 0 |
| currency | varchar(3) | Default: USD |
| quantity | integer | Default: 1 |
| deadline | date | Nullable |
| notes | text | Nullable |
| admin_notes | text | Nullable |
| status | varchar(255) | Default: pending |
| priority | varchar(255) | Default: normal |
| completed_at | timestamp | Nullable |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

#### order_items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| order_id | bigint FK | orders |
| name | varchar(255) | |
| description | text | Nullable |
| quantity | integer | Default: 1 |
| unit_price | decimal(10,2) | |
| total_price | decimal(10,2) | |
| timestamps | | |

#### order_files
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| order_id | bigint FK | orders |
| original_name | varchar(255) | |
| stored_name | varchar(255) | |
| file_path | varchar(255) | |
| mime_type | varchar(255) | Nullable |
| file_size | bigint | Nullable |
| type | varchar(255) | Default: input |
| timestamps | | |

#### order_revisions
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| order_id | bigint FK | orders |
| user_id | bigint FK | users |
| message | text | Nullable |
| attachments | json | Nullable |
| status | varchar(255) | Default: pending |
| timestamps | | |

#### order_messages
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| order_id | bigint FK | orders |
| user_id | bigint FK | users |
| message | text | |
| attachments | json | Nullable |
| is_read | boolean | Default: false |
| timestamps | | |

---

### Payments

#### payments
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| payment_number | varchar(255) | Unique |
| user_id | bigint FK | users |
| order_id | bigint FK | Nullable |
| amount | decimal(10,2) | |
| currency | varchar(3) | Default: USD |
| gateway | varchar(255) | Nullable |
| status | varchar(255) | Default: pending |
| transaction_id | varchar(255) | Nullable |
| gateway_response | json | Nullable |
| paid_at | timestamp | Nullable |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

#### transactions
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| payment_id | bigint FK | payments |
| type | varchar(255) | charge, refund |
| amount | decimal(10,2) | |
| status | varchar(255) | Default: pending |
| gateway_transaction_id | varchar(255) | Nullable |
| gateway_response | json | Nullable |
| timestamps | | |

#### invoices
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| invoice_number | varchar(255) | Unique |
| user_id | bigint FK | users |
| order_id | bigint FK | Nullable |
| payment_id | bigint FK | Nullable |
| subtotal | decimal(10,2) | |
| tax | decimal(10,2) | Default: 0 |
| total | decimal(10,2) | |
| status | varchar(255) | Default: draft |
| due_date | date | Nullable |
| paid_at | timestamp | Nullable |
| notes | text | Nullable |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

---

### CMS

#### pages
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| title | varchar(255) | |
| slug | varchar(255) | Unique |
| content | longtext | Nullable, HTML |
| template | varchar(100) | Default: default |
| featured_image | varchar(255) | Nullable |
| status | varchar(255) | Default: draft |
| seo_title | varchar(255) | Nullable |
| seo_description | text | Nullable |
| timestamps | | |
| deleted_at | timestamp | Soft delete |

---

### Other Tables

- **reviews** — Polymorphic reviews with ratings
- **testimonials** — Client testimonials
- **media** — Media library files
- **media_folders** — Media folder hierarchy
- **support_tickets** — Customer support tickets
- **support_ticket_messages** — Ticket messages
- **settings** — Key-value site settings
- **coupons** — Discount coupons
- **coupon_usages** — Coupon usage tracking
- **carts** — Shopping carts (session/user)
- **cart_items** — Cart line items
- **purchases** — Digital product purchases
- **audit_logs** — Admin action audit trail
- **personal_access_tokens** — API tokens
