
-- ======================================================================
--  Project: Real Estate Marketplace & SaaS (FR/SN) - Maximal DDL
--  DB: PostgreSQL (Doctrine/Symfony friendly)
--  Notes:
--   - Uses timestamptz everywhere
--   - Monetary fields: NUMERIC(12,2)
--   - currency: VARCHAR(3) with regex CHECK
--   - Polymorphic patterns via (type,id) pairs
--   - Soft delete (deleted_at) and audit-ready columns (created_by/updated_by)
-- ======================================================================

BEGIN;

-- ----------------------------------------------------------------------
-- Extensions (safe if not present)
-- ----------------------------------------------------------------------
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS pg_trgm;
CREATE EXTENSION IF NOT EXISTS unaccent;
CREATE EXTENSION IF NOT EXISTS postgis;

-- ----------------------------------------------------------------------
-- Common helpers
-- ----------------------------------------------------------------------
-- Currency and email/phone checks
CREATE DOMAIN currency3 AS VARCHAR(3)
  CHECK (VALUE ~ '^[A-Z]{3}$');

-- ----------------------------------------------------------------------
-- Core: Company / Users / RBAC
-- ----------------------------------------------------------------------

CREATE TABLE company (
  id                SERIAL PRIMARY KEY,
  name              VARCHAR(200) NOT NULL,
  description       TEXT,
  tax_id            VARCHAR(100),
  locale            VARCHAR(10) DEFAULT 'fr_FR',
  created_at        TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at        TIMESTAMPTZ NOT NULL DEFAULT now(),
  deleted_at        TIMESTAMPTZ,
  created_by        INT,
  updated_by        INT
);

CREATE TABLE address (
  id            SERIAL PRIMARY KEY,
  street        VARCHAR(255) NOT NULL,
  city          VARCHAR(120) NOT NULL,
  postal_code   VARCHAR(120),
  country       VARCHAR(120) NOT NULL,
  country_code  VARCHAR(2),
  latitude      DOUBLE PRECISION,
  longitude     DOUBLE PRECISION,
  geom          geometry(Point, 4326),
  created_at    TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at    TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_address_country_city ON address(country, city);
CREATE INDEX idx_address_geom ON address USING GIST (geom);

CREATE TABLE user_hub (
  id           SERIAL PRIMARY KEY,
  full_name    VARCHAR(255) NOT NULL,
  username     VARCHAR(180) NOT NULL UNIQUE,
  email        VARCHAR(255) NOT NULL UNIQUE,
  password     VARCHAR(255) NOT NULL,
  phone        VARCHAR(255),
  created_at   TIMESTAMPTZ NOT NULL DEFAULT now(),
  roles        JSONB NOT NULL DEFAULT '[]'::jsonb,
  locale       VARCHAR(10) DEFAULT 'fr_FR',
  address_id   INT,
  company_id   INT,
  updated_at   TIMESTAMPTZ NOT NULL DEFAULT now(),
  deleted_at   TIMESTAMPTZ
);
-- Partial unique (phone) if provided
CREATE UNIQUE INDEX ux_user_hub_phone_notnull ON user_hub (phone) WHERE phone IS NOT NULL;
CREATE INDEX idx_user_hub_address ON user_hub(address_id);
CREATE INDEX idx_user_hub_company ON user_hub(company_id);
ALTER TABLE user_hub
  ADD CONSTRAINT fk_user_hub_address FOREIGN KEY (address_id) REFERENCES address(id),
  ADD CONSTRAINT fk_user_hub_company FOREIGN KEY (company_id) REFERENCES company(id);

-- RBAC (optional if roles JSON is enough)
CREATE TABLE role (
  id SERIAL PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE
);
CREATE TABLE permission (
  id SERIAL PRIMARY KEY,
  code VARCHAR(120) NOT NULL UNIQUE,
  description TEXT
);
CREATE TABLE role_permission (
  role_id INT NOT NULL REFERENCES role(id) ON DELETE CASCADE,
  permission_id INT NOT NULL REFERENCES permission(id) ON DELETE CASCADE,
  PRIMARY KEY (role_id, permission_id)
);
CREATE TABLE user_role (
  user_id INT NOT NULL REFERENCES user_hub(id) ON DELETE CASCADE,
  role_id INT NOT NULL REFERENCES role(id) ON DELETE CASCADE,
  PRIMARY KEY (user_id, role_id)
);

-- Feature flags
CREATE TABLE feature_flag (
  key VARCHAR(80) PRIMARY KEY,
  description TEXT
);
CREATE TABLE company_feature_flag (
  company_id INT NOT NULL REFERENCES company(id) ON DELETE CASCADE,
  flag_key   VARCHAR(80) NOT NULL REFERENCES feature_flag(key) ON DELETE CASCADE,
  enabled    BOOLEAN NOT NULL DEFAULT true,
  PRIMARY KEY (company_id, flag_key)
);

-- ----------------------------------------------------------------------
-- Catalog / Taxonomy
-- ----------------------------------------------------------------------
CREATE TABLE category (
  id SERIAL PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL UNIQUE,
  parent_id INT,
  visibility_boost INT DEFAULT 0
);
ALTER TABLE category
  ADD CONSTRAINT fk_category_parent FOREIGN KEY (parent_id) REFERENCES category(id) ON DELETE SET NULL;

-- ----------------------------------------------------------------------
-- Suppliers / Services / Service area
-- ----------------------------------------------------------------------
CREATE TABLE supplier (
  id          SERIAL PRIMARY KEY,
  name        VARCHAR(200) NOT NULL,
  type        VARCHAR(255) NOT NULL,
  user_id     INT,
  company_id  INT,
  created_at  TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at  TIMESTAMPTZ NOT NULL DEFAULT now(),
  deleted_at  TIMESTAMPTZ
);
CREATE INDEX idx_supplier_user ON supplier(user_id);
CREATE INDEX idx_supplier_company ON supplier(company_id);
ALTER TABLE supplier
  ADD CONSTRAINT fk_supplier_user FOREIGN KEY (user_id) REFERENCES user_hub(id),
  ADD CONSTRAINT fk_supplier_company FOREIGN KEY (company_id) REFERENCES company(id);

CREATE TABLE supplier_service (
  id           SERIAL PRIMARY KEY,
  title        VARCHAR(255) NOT NULL,
  price        NUMERIC(12,2),
  currency     currency3 NOT NULL DEFAULT 'EUR',
  supplier_id  INT,
  created_at   TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at   TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_supplier_service_supplier ON supplier_service(supplier_id);
ALTER TABLE supplier_service
  ADD CONSTRAINT fk_supplier_service_supplier FOREIGN KEY (supplier_id) REFERENCES supplier(id);

CREATE TABLE service_area (
  id           SERIAL PRIMARY KEY,
  supplier_id  INT NOT NULL REFERENCES supplier(id) ON DELETE CASCADE,
  polygon      geometry(Polygon, 4326),
  city         VARCHAR(120),
  country_code VARCHAR(2),
  created_at   TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_service_area_polygon ON service_area USING GIST (polygon);

-- Categorization for directory/search
CREATE TABLE property_category (
  property_id INT NOT NULL,
  category_id INT NOT NULL,
  PRIMARY KEY(property_id, category_id)
);
CREATE TABLE supplier_category (
  supplier_id INT NOT NULL,
  category_id INT NOT NULL,
  PRIMARY KEY(supplier_id, category_id)
);
CREATE TABLE activity_category (
  activity_id INT NOT NULL,
  category_id INT NOT NULL,
  PRIMARY KEY(activity_id, category_id)
);

-- ----------------------------------------------------------------------
-- Properties / Venues / Activities
-- ----------------------------------------------------------------------
CREATE TABLE property (
  id            SERIAL PRIMARY KEY,
  title         VARCHAR(255) NOT NULL,
  description   TEXT,
  type          VARCHAR(255) NOT NULL,
  status        VARCHAR(255) NOT NULL,
  price         NUMERIC(12,2) NOT NULL,
  currency      currency3 NOT NULL DEFAULT 'EUR',
  surface       NUMERIC(12,2) NOT NULL,
  is_visible    BOOLEAN NOT NULL DEFAULT true,
  created_at    TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at    TIMESTAMPTZ NOT NULL DEFAULT now(),
  address_id    INT NOT NULL,
  owner_id      INT NOT NULL,
  company_id    INT NOT NULL,
  deleted_at    TIMESTAMPTZ
);
CREATE INDEX idx_property_address ON property(address_id);
CREATE INDEX idx_property_owner ON property(owner_id);
CREATE INDEX idx_property_company ON property(company_id);
ALTER TABLE property
  ADD CONSTRAINT fk_property_address FOREIGN KEY (address_id) REFERENCES address(id),
  ADD CONSTRAINT fk_property_owner FOREIGN KEY (owner_id) REFERENCES user_hub(id),
  ADD CONSTRAINT fk_property_company FOREIGN KEY (company_id) REFERENCES company(id);

CREATE TABLE property_image (
  id          SERIAL PRIMARY KEY,
  file_name   VARCHAR(255) NOT NULL,
  url         VARCHAR(255) NOT NULL,
  is_main     BOOLEAN NOT NULL DEFAULT false,
  created_at  TIMESTAMPTZ NOT NULL DEFAULT now(),
  property_id INT NOT NULL
);
CREATE INDEX idx_property_image_property ON property_image(property_id);
ALTER TABLE property_image
  ADD CONSTRAINT fk_property_image_property FOREIGN KEY (property_id) REFERENCES property(id) ON DELETE CASCADE;
-- Only one main image per property
CREATE UNIQUE INDEX ux_property_main_image ON property_image(property_id) WHERE is_main;

CREATE TABLE venue (
  id             SERIAL PRIMARY KEY,
  name           VARCHAR(200) NOT NULL,
  description    TEXT,
  type           VARCHAR(255) NOT NULL,
  capacity       INT,
  price_per_hour NUMERIC(12,2),
  currency       currency3 NOT NULL DEFAULT 'EUR',
  owner_id       INT,
  address_id     INT,
  company_id     INT NOT NULL,
  created_at     TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at     TIMESTAMPTZ NOT NULL DEFAULT now(),
  deleted_at     TIMESTAMPTZ
);
CREATE INDEX idx_venue_owner ON venue(owner_id);
CREATE INDEX idx_venue_address ON venue(address_id);
CREATE INDEX idx_venue_company ON venue(company_id);
ALTER TABLE venue
  ADD CONSTRAINT fk_venue_owner FOREIGN KEY (owner_id) REFERENCES user_hub(id),
  ADD CONSTRAINT fk_venue_address FOREIGN KEY (address_id) REFERENCES address(id),
  ADD CONSTRAINT fk_venue_company FOREIGN KEY (company_id) REFERENCES company(id);

CREATE TABLE venue_image (
  id        SERIAL PRIMARY KEY,
  file_url  VARCHAR(255) NOT NULL,
  is_main   BOOLEAN NOT NULL DEFAULT false,
  venue_id  INT NOT NULL
);
CREATE INDEX idx_venue_image_venue ON venue_image(venue_id);
ALTER TABLE venue_image
  ADD CONSTRAINT fk_venue_image_venue FOREIGN KEY (venue_id) REFERENCES venue(id) ON DELETE CASCADE;
-- Only one main image per venue
CREATE UNIQUE INDEX ux_venue_main_image ON venue_image(venue_id) WHERE is_main;

CREATE TABLE activity (
  id             SERIAL PRIMARY KEY,
  title          VARCHAR(255) NOT NULL,
  description    TEXT,
  type           VARCHAR(255) NOT NULL,
  price          NUMERIC(12,2),
  currency       currency3 NOT NULL DEFAULT 'EUR',
  average_rating NUMERIC(4,2),
  address_id     INT,
  company_id     INT,
  created_at     TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at     TIMESTAMPTZ NOT NULL DEFAULT now(),
  deleted_at     TIMESTAMPTZ
);
CREATE INDEX idx_activity_address ON activity(address_id);
CREATE INDEX idx_activity_company ON activity(company_id);
ALTER TABLE activity
  ADD CONSTRAINT fk_activity_address FOREIGN KEY (address_id) REFERENCES address(id),
  ADD CONSTRAINT fk_activity_company FOREIGN KEY (company_id) REFERENCES company(id);

CREATE TABLE activity_image (
  id          SERIAL PRIMARY KEY,
  file_url    VARCHAR(255) NOT NULL,
  is_main     BOOLEAN NOT NULL DEFAULT false,
  activity_id INT
);
CREATE INDEX idx_activity_image_activity ON activity_image(activity_id);
ALTER TABLE activity_image
  ADD CONSTRAINT fk_activity_image_activity FOREIGN KEY (activity_id) REFERENCES activity(id) ON DELETE CASCADE;
CREATE UNIQUE INDEX ux_activity_main_image ON activity_image(activity_id) WHERE is_main;

-- ----------------------------------------------------------------------
-- Reservations (polymorphic) & Availability & Time slots
-- ----------------------------------------------------------------------
CREATE TABLE reservation (
  id              SERIAL PRIMARY KEY,
  bookable_type   VARCHAR(50) NOT NULL,   -- 'property','venue','activity','event'
  bookable_id     INT NOT NULL,
  start_date      TIMESTAMPTZ NOT NULL,
  end_date        TIMESTAMPTZ NOT NULL,
  total_amount    NUMERIC(12,2) NOT NULL,
  currency        currency3 NOT NULL DEFAULT 'EUR',
  status          VARCHAR(50) NOT NULL,   -- pending, confirmed, canceled, completed
  created_at      TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at      TIMESTAMPTZ NOT NULL DEFAULT now(),
  average_rating  NUMERIC(4,2),
  user_id         INT NOT NULL
);
CREATE INDEX idx_reservation_bookable ON reservation(bookable_type, bookable_id);
CREATE INDEX idx_reservation_user ON reservation(user_id);
ALTER TABLE reservation
  ADD CONSTRAINT fk_reservation_user FOREIGN KEY (user_id) REFERENCES user_hub(id);

CREATE TABLE availability (
  id             SERIAL PRIMARY KEY,
  bookable_type  VARCHAR(50) NOT NULL,
  bookable_id    INT NOT NULL,
  start_date     TIMESTAMPTZ NOT NULL,
  end_date       TIMESTAMPTZ NOT NULL,
  is_available   BOOLEAN NOT NULL DEFAULT true
);
CREATE INDEX idx_availability_bookable ON availability(bookable_type, bookable_id, start_date, end_date);

CREATE TABLE time_slot (
  id             SERIAL PRIMARY KEY,
  bookable_type  VARCHAR(50) NOT NULL,
  bookable_id    INT NOT NULL,
  start_time     TIMESTAMPTZ NOT NULL,
  end_time       TIMESTAMPTZ NOT NULL,
  is_recurring   BOOLEAN NOT NULL DEFAULT false,
  rrule          TEXT, -- iCal RRULE for recurring
  exception      BOOLEAN NOT NULL DEFAULT false
);
CREATE INDEX idx_time_slot_bookable ON time_slot(bookable_type, bookable_id, start_time, end_time);

-- Calendar sync
CREATE TABLE calendar_sync_account (
  id             SERIAL PRIMARY KEY,
  user_id        INT NOT NULL REFERENCES user_hub(id) ON DELETE CASCADE,
  provider       VARCHAR(40) NOT NULL, -- google, outlook
  access_token   TEXT NOT NULL,
  refresh_token  TEXT,
  scope          TEXT,
  synced_at      TIMESTAMPTZ,
  created_at     TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- ----------------------------------------------------------------------
-- Reviews (polymorphic + anti-spam with reservation link)
-- ----------------------------------------------------------------------
CREATE TABLE review (
  id                SERIAL PRIMARY KEY,
  reviewable_type   VARCHAR(50) NOT NULL,
  reviewable_id     INT NOT NULL,
  rating            SMALLINT NOT NULL,
  comment           TEXT,
  created_at        TIMESTAMPTZ NOT NULL DEFAULT now(),
  reviewer_id       INT NOT NULL,
  reservation_id    INT,
  CONSTRAINT chk_review_rating CHECK (rating BETWEEN 1 AND 5)
);
CREATE INDEX idx_review_reviewable ON review(reviewable_type, reviewable_id);
CREATE INDEX idx_review_reviewer ON review(reviewer_id);
ALTER TABLE review
  ADD CONSTRAINT fk_review_reviewer FOREIGN KEY (reviewer_id) REFERENCES user_hub(id),
  ADD CONSTRAINT fk_review_reservation FOREIGN KEY (reservation_id) REFERENCES reservation(id) ON DELETE SET NULL;
-- prevent multiple reviews by same reviewer for same reservation
CREATE UNIQUE INDEX ux_review_res_reviewer ON review(reservation_id, reviewer_id) WHERE reservation_id IS NOT NULL;

-- ----------------------------------------------------------------------
-- Events / Bookings / Guests / Equipment
-- ----------------------------------------------------------------------
CREATE TABLE equipment (
  id SERIAL PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  rental_price_per_day NUMERIC(12,2),
  currency currency3 NOT NULL DEFAULT 'EUR'
);

CREATE TABLE event (
  id              SERIAL PRIMARY KEY,
  title           VARCHAR(255) NOT NULL,
  description     TEXT,
  type            VARCHAR(255) NOT NULL,
  status          VARCHAR(255) NOT NULL,
  start_datetime  TIMESTAMPTZ NOT NULL,
  end_datetime    TIMESTAMPTZ,
  price           NUMERIC(12,2),
  currency        currency3 NOT NULL DEFAULT 'EUR',
  max_guests      INT,
  organizer_id    INT,
  venue_id        INT,
  company_id      INT,
  created_at      TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at      TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_event_organizer ON event(organizer_id);
CREATE INDEX idx_event_venue ON event(venue_id);
CREATE INDEX idx_event_company ON event(company_id);
ALTER TABLE event
  ADD CONSTRAINT fk_event_organizer FOREIGN KEY (organizer_id) REFERENCES user_hub(id),
  ADD CONSTRAINT fk_event_venue FOREIGN KEY (venue_id) REFERENCES venue(id),
  ADD CONSTRAINT fk_event_company FOREIGN KEY (company_id) REFERENCES company(id);

CREATE TABLE event_booking (
  id            SERIAL PRIMARY KEY,
  booking_date  TIMESTAMPTZ NOT NULL,
  total_amount  NUMERIC(12,2),
  currency      currency3 NOT NULL DEFAULT 'EUR',
  status        VARCHAR(255) NOT NULL,
  event_id      INT,
  booker_id     INT
);
CREATE INDEX idx_event_booking_event ON event_booking(event_id);
CREATE INDEX idx_event_booking_booker ON event_booking(booker_id);
ALTER TABLE event_booking
  ADD CONSTRAINT fk_event_booking_event FOREIGN KEY (event_id) REFERENCES event(id),
  ADD CONSTRAINT fk_event_booking_booker FOREIGN KEY (booker_id) REFERENCES user_hub(id);

-- No payment_id column here; Payment table holds event_booking_id (unique)
CREATE TABLE event_booking_equipment (
  event_booking_id INT NOT NULL REFERENCES event_booking(id) ON DELETE CASCADE,
  equipment_id     INT NOT NULL REFERENCES equipment(id) ON DELETE CASCADE,
  PRIMARY KEY(event_booking_id, equipment_id)
);

CREATE TABLE guest (
  id                SERIAL PRIMARY KEY,
  name              VARCHAR(200) NOT NULL,
  email             VARCHAR(120),
  phone             VARCHAR(30),
  invitation_status VARCHAR(255) NOT NULL,
  event_id          INT
);
CREATE INDEX idx_guest_event ON guest(event_id);
ALTER TABLE guest
  ADD CONSTRAINT fk_guest_event FOREIGN KEY (event_id) REFERENCES event(id);

-- ----------------------------------------------------------------------
-- Leases & Rent Payments
-- ----------------------------------------------------------------------
CREATE TABLE lease (
  id           SERIAL PRIMARY KEY,
  start_date   TIMESTAMPTZ NOT NULL,
  end_date     TIMESTAMPTZ,
  rent_amount  NUMERIC(12,2),
  currency     currency3 NOT NULL DEFAULT 'EUR',
  property_id  INT,
  tenant_id    INT
);
CREATE INDEX idx_lease_property ON lease(property_id);
CREATE INDEX idx_lease_tenant ON lease(tenant_id);
ALTER TABLE lease
  ADD CONSTRAINT fk_lease_property FOREIGN KEY (property_id) REFERENCES property(id),
  ADD CONSTRAINT fk_lease_tenant FOREIGN KEY (tenant_id) REFERENCES user_hub(id);

CREATE TABLE rent_payment (
  id            SERIAL PRIMARY KEY,
  amount        NUMERIC(12,2) NOT NULL,
  currency      currency3 NOT NULL DEFAULT 'EUR',
  method        VARCHAR(255) NOT NULL,
  status        VARCHAR(255) NOT NULL,
  receipt_path  VARCHAR(255),
  due_date      TIMESTAMPTZ NOT NULL,
  paid_date     TIMESTAMPTZ,
  created_at    TIMESTAMPTZ NOT NULL DEFAULT now(),
  lease_id      INT,
  tenant_id     INT NOT NULL,
  CONSTRAINT chk_rent_status CHECK (status IN ('pending','paid','overdue','failed'))
);
CREATE INDEX idx_rent_payment_lease ON rent_payment(lease_id);
CREATE INDEX idx_rent_payment_tenant ON rent_payment(tenant_id);
ALTER TABLE rent_payment
  ADD CONSTRAINT fk_rent_payment_lease FOREIGN KEY (lease_id) REFERENCES lease(id),
  ADD CONSTRAINT fk_rent_payment_tenant FOREIGN KEY (tenant_id) REFERENCES user_hub(id);

-- ----------------------------------------------------------------------
-- Billing: Invoices, Items, Taxes
-- ----------------------------------------------------------------------
CREATE TABLE invoice (
  id             SERIAL PRIMARY KEY,
  invoice_number VARCHAR(80) NOT NULL UNIQUE,
  issue_date     TIMESTAMPTZ NOT NULL,
  due_date       TIMESTAMPTZ,
  total_amount   NUMERIC(12,2) NOT NULL,
  total_taxes    NUMERIC(12,2) NOT NULL DEFAULT 0,
  currency       currency3 NOT NULL DEFAULT 'EUR',
  status         VARCHAR(255) NOT NULL,
  client_id      INT,
  provider_id    INT,
  company_id     INT,
  locale         VARCHAR(10) DEFAULT 'fr_FR',
  created_at     TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at     TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_invoice_client ON invoice(client_id);
CREATE INDEX idx_invoice_provider ON invoice(provider_id);
CREATE INDEX idx_invoice_company ON invoice(company_id);
ALTER TABLE invoice
  ADD CONSTRAINT fk_invoice_client FOREIGN KEY (client_id) REFERENCES user_hub(id),
  ADD CONSTRAINT fk_invoice_provider FOREIGN KEY (provider_id) REFERENCES user_hub(id),
  ADD CONSTRAINT fk_invoice_company FOREIGN KEY (company_id) REFERENCES company(id);

CREATE TABLE tax_rate (
  id SERIAL PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  rate_percent NUMERIC(5,2) NOT NULL, -- e.g., 20.00 for 20%
  country_code VARCHAR(2),
  created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE invoice_item (
  id           SERIAL PRIMARY KEY,
  description  VARCHAR(255),
  quantity     NUMERIC(12,2) NOT NULL,
  unit_price   NUMERIC(12,2) NOT NULL,
  total        NUMERIC(12,2) NOT NULL,
  currency     currency3 NOT NULL DEFAULT 'EUR',
  tax_rate_id  INT,
  created_at   TIMESTAMPTZ NOT NULL DEFAULT now(),
  invoice_id   INT NOT NULL
);
CREATE INDEX idx_invoice_item_invoice ON invoice_item(invoice_id);
ALTER TABLE invoice_item
  ADD CONSTRAINT fk_invoice_item_invoice FOREIGN KEY (invoice_id) REFERENCES invoice(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_invoice_item_taxrate FOREIGN KEY (tax_rate_id) REFERENCES tax_rate(id);

-- ----------------------------------------------------------------------
-- Subscriptions / Plans (company-scoped)
-- ----------------------------------------------------------------------
CREATE TABLE subscription_plan (
  id        SERIAL PRIMARY KEY,
  name      VARCHAR(50) NOT NULL,
  price     NUMERIC(12,2) NOT NULL,
  currency  currency3 NOT NULL DEFAULT 'EUR',
  features  JSONB NOT NULL,
  interval  VARCHAR(20) NOT NULL DEFAULT 'month', -- month/year
  is_active BOOLEAN NOT NULL DEFAULT true
);

CREATE TABLE subscription (
  id          SERIAL PRIMARY KEY,
  company_id  INT NOT NULL,
  plan_id     INT NOT NULL,
  currency    currency3 NOT NULL DEFAULT 'EUR',
  interval    VARCHAR(20) NOT NULL DEFAULT 'month',
  started_at  TIMESTAMPTZ NOT NULL,
  ends_at     TIMESTAMPTZ NOT NULL,
  status      VARCHAR(255) NOT NULL,
  created_at  TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at  TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_subscription_company ON subscription(company_id);
ALTER TABLE subscription
  ADD CONSTRAINT fk_subscription_company FOREIGN KEY (company_id) REFERENCES company(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_subscription_plan FOREIGN KEY (plan_id) REFERENCES subscription_plan(id);

-- ----------------------------------------------------------------------
-- Payments / Providers / Payouts / Refunds / Logs
-- ----------------------------------------------------------------------
CREATE TABLE payment_provider (
  id SERIAL PRIMARY KEY,
  name VARCHAR(80) NOT NULL, -- stripe, paydunya, cinetpay, orange_money, wave
  type VARCHAR(50) NOT NULL,
  is_active BOOLEAN NOT NULL DEFAULT true,
  config JSONB
);

CREATE TABLE payment_account (
  id SERIAL PRIMARY KEY,
  user_id INT REFERENCES user_hub(id) ON DELETE SET NULL,
  company_id INT REFERENCES company(id) ON DELETE SET NULL,
  provider_id INT NOT NULL REFERENCES payment_provider(id) ON DELETE RESTRICT,
  external_account_id VARCHAR(120),
  created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE payment (
  id              SERIAL PRIMARY KEY,
  amount          NUMERIC(12,2) NOT NULL,
  currency        currency3 NOT NULL DEFAULT 'EUR',
  method          VARCHAR(255) NOT NULL,
  status          VARCHAR(255) NOT NULL,
  transaction_id  VARCHAR(100),
  paid_at         TIMESTAMPTZ,
  invoice_id      INT,
  payer_id        INT,
  reservation_id  INT,
  event_booking_id INT UNIQUE,
  provider_id     INT REFERENCES payment_provider(id) ON DELETE SET NULL
);
CREATE INDEX idx_payment_invoice ON payment(invoice_id);
CREATE INDEX idx_payment_payer ON payment(payer_id);
CREATE INDEX idx_payment_reservation ON payment(reservation_id);
ALTER TABLE payment
  ADD CONSTRAINT fk_payment_invoice FOREIGN KEY (invoice_id) REFERENCES invoice(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_payment_payer FOREIGN KEY (payer_id) REFERENCES user_hub(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_payment_reservation FOREIGN KEY (reservation_id) REFERENCES reservation(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_payment_event_booking FOREIGN KEY (event_booking_id) REFERENCES event_booking(id) ON DELETE SET NULL;

CREATE TABLE refund (
  id SERIAL PRIMARY KEY,
  payment_id INT NOT NULL REFERENCES payment(id) ON DELETE CASCADE,
  amount NUMERIC(12,2) NOT NULL,
  reason TEXT,
  status VARCHAR(50) NOT NULL,
  created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  processed_at TIMESTAMPTZ
);

CREATE TABLE payout (
  id SERIAL PRIMARY KEY,
  company_id INT REFERENCES company(id) ON DELETE SET NULL,
  supplier_id INT REFERENCES supplier(id) ON DELETE SET NULL,
  provider_id INT REFERENCES payment_provider(id) ON DELETE SET NULL,
  amount NUMERIC(12,2) NOT NULL,
  currency currency3 NOT NULL DEFAULT 'EUR',
  status VARCHAR(50) NOT NULL, -- pending, paid, failed
  external_transfer_id VARCHAR(120),
  created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  paid_at TIMESTAMPTZ
);

CREATE TABLE commission_rule (
  id SERIAL PRIMARY KEY,
  company_id INT NOT NULL REFERENCES company(id) ON DELETE CASCADE,
  category_id INT REFERENCES category(id) ON DELETE SET NULL,
  rate_percent NUMERIC(5,2) NOT NULL DEFAULT 10.00,
  active BOOLEAN NOT NULL DEFAULT true,
  created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE payment_event (
  id SERIAL PRIMARY KEY,
  payment_id INT NOT NULL REFERENCES payment(id) ON DELETE CASCADE,
  event_type VARCHAR(120) NOT NULL, -- charge.succeeded, payout.failed, etc.
  payload JSONB,
  created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- ----------------------------------------------------------------------
-- Documents (templates & generation requests)
-- ----------------------------------------------------------------------
CREATE TABLE document_template (
  id                SERIAL PRIMARY KEY,
  name              VARCHAR(150) NOT NULL,
  type              VARCHAR(50) NOT NULL,     -- lease, invoice, attestation, quittance ...
  locale            VARCHAR(10) NOT NULL,     -- fr_FR, fr_SN
  version           INT NOT NULL DEFAULT 1,
  is_active         BOOLEAN NOT NULL DEFAULT true,
  owner_company_id  INT,
  form_schema       JSONB NOT NULL DEFAULT '{}'::jsonb,
  form_data         JSONB NOT NULL DEFAULT '{}'::jsonb,
  generated_pdf_url VARCHAR(255),
  created_at        TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at        TIMESTAMPTZ NOT NULL DEFAULT now()
);
ALTER TABLE document_template
  ADD CONSTRAINT fk_doc_template_company FOREIGN KEY (owner_company_id) REFERENCES company(id) ON DELETE SET NULL;

CREATE TABLE document_request (
  id                SERIAL PRIMARY KEY,
  user_id           INT NOT NULL,
  template_id       INT,
  form_data         JSONB NOT NULL,
  generated_pdf_url VARCHAR(255),
  created_at        TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_document_request_user ON document_request(user_id);
CREATE INDEX idx_document_request_template ON document_request(template_id);
ALTER TABLE document_request
  ADD CONSTRAINT fk_doc_request_user FOREIGN KEY (user_id) REFERENCES user_hub(id),
  ADD CONSTRAINT fk_doc_request_template FOREIGN KEY (template_id) REFERENCES document_template(id);

-- ----------------------------------------------------------------------
-- Content / Blog
-- ----------------------------------------------------------------------
CREATE TABLE post (
  id            SERIAL PRIMARY KEY,
  title         VARCHAR(255) NOT NULL,
  slug          VARCHAR(255) NOT NULL UNIQUE,
  summary       VARCHAR(255) NOT NULL,
  content       TEXT NOT NULL,
  published_at  TIMESTAMPTZ NOT NULL,
  author_id     INT NOT NULL
);
CREATE INDEX idx_post_author ON post(author_id);
ALTER TABLE post
  ADD CONSTRAINT fk_post_author FOREIGN KEY (author_id) REFERENCES user_hub(id);

CREATE TABLE tag (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE post_tag (
  post_id INT NOT NULL REFERENCES post(id) ON DELETE CASCADE,
  tag_id  INT NOT NULL REFERENCES tag(id) ON DELETE CASCADE,
  PRIMARY KEY (post_id, tag_id)
);

CREATE TABLE comment (
  id           SERIAL PRIMARY KEY,
  content      TEXT NOT NULL,
  published_at TIMESTAMPTZ NOT NULL,
  post_id      INT NOT NULL,
  author_id    INT NOT NULL
);
CREATE INDEX idx_comment_post ON comment(post_id);
CREATE INDEX idx_comment_author ON comment(author_id);
ALTER TABLE comment
  ADD CONSTRAINT fk_comment_post FOREIGN KEY (post_id) REFERENCES post(id),
  ADD CONSTRAINT fk_comment_author FOREIGN KEY (author_id) REFERENCES user_hub(id);

-- ----------------------------------------------------------------------
-- KPI / Analytics
-- ----------------------------------------------------------------------
CREATE TABLE kpi_metric (
  id           SERIAL PRIMARY KEY,
  name         VARCHAR(120) NOT NULL,
  value        NUMERIC(12,2) NOT NULL,
  unit         VARCHAR(20),
  period_start TIMESTAMPTZ,
  period_end   TIMESTAMPTZ,
  user_id      INT NOT NULL,
  company_id   INT,
  created_at   TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_kpi_user ON kpi_metric(user_id);
ALTER TABLE kpi_metric
  ADD CONSTRAINT fk_kpi_user FOREIGN KEY (user_id) REFERENCES user_hub(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_kpi_company FOREIGN KEY (company_id) REFERENCES company(id) ON DELETE SET NULL;

-- ----------------------------------------------------------------------
-- Notifications & Webhooks
-- ----------------------------------------------------------------------
CREATE TABLE message_template (
  id SERIAL PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  channel VARCHAR(20) NOT NULL, -- email, sms
  subject VARCHAR(200),
  body TEXT NOT NULL,
  locale VARCHAR(10) DEFAULT 'fr_FR'
);

CREATE TABLE notification (
  id SERIAL PRIMARY KEY,
  user_id INT REFERENCES user_hub(id) ON DELETE SET NULL,
  channel VARCHAR(20) NOT NULL, -- email, sms
  template_id INT REFERENCES message_template(id) ON DELETE SET NULL,
  payload JSONB,
  status VARCHAR(20) NOT NULL DEFAULT 'queued',
  created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  sent_at TIMESTAMPTZ
);

CREATE TABLE webhook_endpoint (
  id SERIAL PRIMARY KEY,
  company_id INT NOT NULL REFERENCES company(id) ON DELETE CASCADE,
  url VARCHAR(255) NOT NULL,
  secret VARCHAR(255),
  active BOOLEAN NOT NULL DEFAULT true,
  created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE webhook_log (
  id SERIAL PRIMARY KEY,
  url VARCHAR(255) NOT NULL,
  payload_json JSONB,
  response_status VARCHAR(10),
  created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- ----------------------------------------------------------------------
-- Search / Full-text
-- ----------------------------------------------------------------------
-- Simple GIN indexes using to_tsvector('french', ...)
CREATE INDEX idxfts_property ON property USING GIN (to_tsvector('french', coalesce(title,'') || ' ' || coalesce(description,'')));
CREATE INDEX idxfts_activity ON activity USING GIN (to_tsvector('french', coalesce(title,'') || ' ' || coalesce(description,'')));
CREATE INDEX idx_trgm_supplier_name ON supplier USING GIN (name gin_trgm_ops);

-- ----------------------------------------------------------------------
-- Blog utilities
-- ----------------------------------------------------------------------
-- Nothing additional

-- ----------------------------------------------------------------------
-- Audit log (generic)
-- ----------------------------------------------------------------------
CREATE TABLE audit_log (
  id SERIAL PRIMARY KEY,
  table_name VARCHAR(120) NOT NULL,
  record_pk  VARCHAR(120) NOT NULL,
  action     VARCHAR(20) NOT NULL, -- insert, update, delete
  user_id    INT,
  diff       JSONB,
  created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

COMMIT;
