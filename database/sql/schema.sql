CREATE DATABASE IF NOT EXISTS business_suite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE business_suite;

CREATE TABLE users (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_type ENUM('admin','employee','client') NOT NULL,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(30) NULL,
  password_hash VARCHAR(255) NOT NULL,
  status ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active',
  last_login_at DATETIME NULL,
  remember_token CHAR(64) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_users_status(status),
  INDEX idx_users_type(user_type)
) ENGINE=InnoDB;

CREATE TABLE roles (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  role_name VARCHAR(100) NOT NULL UNIQUE,
  role_key VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE permissions (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  permission_name VARCHAR(150) NOT NULL,
  permission_key VARCHAR(150) NOT NULL UNIQUE,
  module_name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_permissions_module(module_name)
) ENGINE=InnoDB;

CREATE TABLE role_permissions (
  role_id BIGINT UNSIGNED NOT NULL,
  permission_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY(role_id, permission_id),
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user_roles (
  user_id BIGINT UNSIGNED NOT NULL,
  role_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY(user_id, role_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE departments (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE designations (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE employees (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL UNIQUE,
  employee_code VARCHAR(40) NOT NULL UNIQUE,
  department_id BIGINT UNSIGNED NULL,
  designation_id BIGINT UNSIGNED NULL,
  joining_date DATE NOT NULL,
  salary DECIMAL(12,2) NULL,
  manager_id BIGINT UNSIGNED NULL,
  status ENUM('active','inactive','on_leave','terminated') DEFAULT 'active',
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
  FOREIGN KEY (designation_id) REFERENCES designations(id) ON DELETE SET NULL,
  FOREIGN KEY (manager_id) REFERENCES employees(id) ON DELETE SET NULL,
  INDEX idx_emp_department(department_id),
  INDEX idx_emp_manager(manager_id)
) ENGINE=InnoDB;

CREATE TABLE clients (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NULL UNIQUE,
  company_name VARCHAR(180) NOT NULL,
  industry VARCHAR(120) NULL,
  website VARCHAR(190) NULL,
  status ENUM('lead','active','inactive') DEFAULT 'active',
  notes TEXT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_clients_status(status)
) ENGINE=InnoDB;

CREATE TABLE client_contacts (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  client_id BIGINT UNSIGNED NOT NULL,
  contact_name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NULL,
  phone VARCHAR(30) NULL,
  position_title VARCHAR(120) NULL,
  FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
  INDEX idx_contact_client(client_id)
) ENGINE=InnoDB;

CREATE TABLE holidays (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  holiday_date DATE NOT NULL UNIQUE,
  title VARCHAR(120) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE attendance (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  employee_id BIGINT UNSIGNED NOT NULL,
  attendance_date DATE NOT NULL,
  punch_in DATETIME NULL,
  punch_out DATETIME NULL,
  status ENUM('present','late','half_day','absent','leave') NOT NULL,
  worked_minutes INT UNSIGNED DEFAULT 0,
  created_by BIGINT UNSIGNED NULL,
  UNIQUE KEY uq_attendance_emp_date(employee_id, attendance_date),
  FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_attendance_date(attendance_date)
) ENGINE=InnoDB;

CREATE TABLE leaves (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  employee_id BIGINT UNSIGNED NOT NULL,
  leave_type VARCHAR(80) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  reason TEXT,
  approval_status ENUM('pending','approved','rejected') DEFAULT 'pending',
  approved_by BIGINT UNSIGNED NULL,
  FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_leaves_period(start_date, end_date)
) ENGINE=InnoDB;

CREATE TABLE ticket_categories (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  category_name VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE tickets (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  ticket_number VARCHAR(32) NOT NULL UNIQUE,
  title VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  category_id BIGINT UNSIGNED NULL,
  priority ENUM('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  status ENUM('open','in_progress','waiting','resolved','closed') NOT NULL DEFAULT 'open',
  created_by BIGINT UNSIGNED NOT NULL,
  assigned_to BIGINT UNSIGNED NULL,
  client_id BIGINT UNSIGNED NULL,
  sla_due_at DATETIME NULL,
  resolved_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES ticket_categories(id) ON DELETE SET NULL,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT,
  FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL,
  INDEX idx_tickets_status(status),
  INDEX idx_tickets_assigned(assigned_to),
  INDEX idx_tickets_priority(priority)
) ENGINE=InnoDB;

CREATE TABLE ticket_comments (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  ticket_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  comment_text TEXT NOT NULL,
  is_internal TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_ticket_comments_ticket(ticket_id)
) ENGINE=InnoDB;

CREATE TABLE tasks (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(180) NOT NULL,
  description TEXT NULL,
  assigned_to BIGINT UNSIGNED NOT NULL,
  created_by BIGINT UNSIGNED NOT NULL,
  due_date DATE NOT NULL,
  priority ENUM('low','medium','high','critical') DEFAULT 'medium',
  status ENUM('todo','in_progress','blocked','done') DEFAULT 'todo',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE RESTRICT,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT,
  INDEX idx_tasks_due(due_date),
  INDEX idx_tasks_assigned_status(assigned_to, status)
) ENGINE=InnoDB;

CREATE TABLE task_logs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  task_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  action VARCHAR(120) NOT NULL,
  old_value TEXT NULL,
  new_value TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_task_logs_task(task_id)
) ENGINE=InnoDB;

CREATE TABLE leads (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  lead_name VARCHAR(180) NOT NULL,
  company_name VARCHAR(180) NULL,
  email VARCHAR(190) NULL,
  phone VARCHAR(30) NULL,
  source VARCHAR(120) NULL,
  status ENUM('new','qualified','proposal','won','lost') DEFAULT 'new',
  assigned_to BIGINT UNSIGNED NULL,
  next_follow_up DATETIME NULL,
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_leads_status(status),
  INDEX idx_leads_followup(next_follow_up)
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NULL,
  action VARCHAR(120) NOT NULL,
  module_name VARCHAR(120) NOT NULL,
  entity_id BIGINT UNSIGNED NULL,
  before_data JSON NULL,
  after_data JSON NULL,
  ip_address VARCHAR(64) NULL,
  user_agent VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_audit_module_entity(module_name, entity_id),
  INDEX idx_audit_created(created_at)
) ENGINE=InnoDB;

CREATE TABLE files (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  uploader_id BIGINT UNSIGNED NOT NULL,
  module_name VARCHAR(120) NOT NULL,
  entity_id BIGINT UNSIGNED NULL,
  original_name VARCHAR(255) NOT NULL,
  stored_name VARCHAR(255) NOT NULL,
  mime_type VARCHAR(120) NOT NULL,
  file_size INT UNSIGNED NOT NULL,
  storage_path VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (uploader_id) REFERENCES users(id) ON DELETE RESTRICT,
  INDEX idx_files_module_entity(module_name, entity_id)
) ENGINE=InnoDB;

CREATE TABLE password_resets (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  reset_token CHAR(64) NOT NULL UNIQUE,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_password_reset_expires(expires_at)
) ENGINE=InnoDB;

CREATE TABLE error_events (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  severity ENUM('info','warning','error','critical') NOT NULL,
  message VARCHAR(500) NOT NULL,
  module_name VARCHAR(120) NOT NULL,
  user_id BIGINT UNSIGNED NULL,
  ip_address VARCHAR(64) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_error_created(created_at),
  INDEX idx_error_severity(severity)
) ENGINE=InnoDB;

CREATE TABLE queue_jobs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  queue_name VARCHAR(80) NOT NULL,
  job_type VARCHAR(120) NOT NULL,
  payload_json JSON NOT NULL,
  status ENUM('queued','running','completed','failed') NOT NULL DEFAULT 'queued',
  attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
  run_at DATETIME NOT NULL,
  started_at DATETIME NULL,
  finished_at DATETIME NULL,
  runtime_seconds INT UNSIGNED NULL,
  last_error VARCHAR(500) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_queue_status_runat(status, run_at),
  INDEX idx_queue_type(job_type)
) ENGINE=InnoDB;

CREATE TABLE internal_announcements (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(180) NOT NULL,
  body TEXT NOT NULL,
  target_role_id BIGINT UNSIGNED NULL,
  active_from DATETIME NOT NULL,
  active_until DATETIME NULL,
  created_by BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (target_role_id) REFERENCES roles(id) ON DELETE SET NULL,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT,
  INDEX idx_announcements_window(active_from, active_until)
) ENGINE=InnoDB;

CREATE TABLE internal_chat_messages (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  room_key VARCHAR(120) NOT NULL,
  sender_id BIGINT UNSIGNED NOT NULL,
  message_text TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_chat_room_created(room_key, created_at)
) ENGINE=InnoDB;

CREATE TABLE shifts (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  shift_name VARCHAR(120) NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  break_minutes SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  grace_minutes SMALLINT UNSIGNED NOT NULL DEFAULT 10,
  is_night_shift TINYINT(1) NOT NULL DEFAULT 0,
  UNIQUE KEY uq_shift_name(shift_name)
) ENGINE=InnoDB;

CREATE TABLE employee_shifts (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  employee_id BIGINT UNSIGNED NOT NULL,
  shift_id BIGINT UNSIGNED NOT NULL,
  effective_from DATE NOT NULL,
  effective_to DATE NULL,
  FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  FOREIGN KEY (shift_id) REFERENCES shifts(id) ON DELETE RESTRICT,
  INDEX idx_emp_shift_window(employee_id, effective_from, effective_to)
) ENGINE=InnoDB;

ALTER TABLE attendance
  ADD COLUMN geo_lat DECIMAL(10,7) NULL,
  ADD COLUMN geo_lng DECIMAL(10,7) NULL,
  ADD COLUMN geo_valid TINYINT(1) NOT NULL DEFAULT 1,
  ADD COLUMN overtime_minutes INT UNSIGNED NOT NULL DEFAULT 0,
  ADD COLUMN approval_status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  ADD COLUMN work_mode ENUM('office','wfh','field') NOT NULL DEFAULT 'office';

CREATE TABLE attendance_anomalies (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  attendance_id BIGINT UNSIGNED NOT NULL,
  anomaly_type VARCHAR(120) NOT NULL,
  severity ENUM('low','medium','high') NOT NULL DEFAULT 'medium',
  status ENUM('open','reviewed','closed') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (attendance_id) REFERENCES attendance(id) ON DELETE CASCADE,
  INDEX idx_anomaly_status(status)
) ENGINE=InnoDB;

CREATE TABLE hr_policy_rules (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  rule_key VARCHAR(120) NOT NULL UNIQUE,
  rule_name VARCHAR(180) NOT NULL,
  condition_json JSON NOT NULL,
  action_json JSON NOT NULL,
  priority INT NOT NULL DEFAULT 100,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_hr_policy_active_priority(is_active, priority)
) ENGINE=InnoDB;

CREATE TABLE employee_performance (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  employee_id BIGINT UNSIGNED NOT NULL,
  review_month DATE NOT NULL,
  score DECIMAL(5,2) NOT NULL,
  productivity_score DECIMAL(5,2) NOT NULL DEFAULT 0,
  quality_score DECIMAL(5,2) NOT NULL DEFAULT 0,
  punctuality_score DECIMAL(5,2) NOT NULL DEFAULT 0,
  reviewer_id BIGINT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE SET NULL,
  UNIQUE KEY uq_perf_emp_month(employee_id, review_month)
) ENGINE=InnoDB;

CREATE TABLE lead_source_roi (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  source VARCHAR(120) NOT NULL,
  period_month DATE NOT NULL,
  cost_amount DECIMAL(14,2) NOT NULL DEFAULT 0,
  revenue_amount DECIMAL(14,2) NOT NULL DEFAULT 0,
  UNIQUE KEY uq_source_period(source, period_month)
) ENGINE=InnoDB;

CREATE TABLE deals (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  lead_id BIGINT UNSIGNED NULL,
  client_id BIGINT UNSIGNED NULL,
  title VARCHAR(180) NOT NULL,
  stage ENUM('prospect','qualified','proposal','negotiation','won','lost') NOT NULL DEFAULT 'prospect',
  value_amount DECIMAL(14,2) NOT NULL DEFAULT 0,
  probability_percent DECIMAL(5,2) NOT NULL DEFAULT 0,
  renewal_date DATE NULL,
  closed_at DATETIME NULL,
  lost_reason VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE SET NULL,
  FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL,
  INDEX idx_deals_stage(stage),
  INDEX idx_deals_renewal(renewal_date)
) ENGINE=InnoDB;

CREATE TABLE proposals (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  deal_id BIGINT UNSIGNED NOT NULL,
  proposal_number VARCHAR(40) NOT NULL UNIQUE,
  subtotal DECIMAL(14,2) NOT NULL DEFAULT 0,
  tax_amount DECIMAL(14,2) NOT NULL DEFAULT 0,
  total_amount DECIMAL(14,2) NOT NULL DEFAULT 0,
  status ENUM('draft','sent','accepted','rejected') NOT NULL DEFAULT 'draft',
  valid_until DATE NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (deal_id) REFERENCES deals(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE report_templates (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  template_name VARCHAR(180) NOT NULL,
  module_name VARCHAR(120) NOT NULL,
  config_json JSON NOT NULL,
  created_by BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE scheduled_reports (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  template_id BIGINT UNSIGNED NOT NULL,
  cron_expr VARCHAR(120) NOT NULL,
  recipients_json JSON NOT NULL,
  format ENUM('csv','xlsx','pdf') NOT NULL DEFAULT 'csv',
  last_run_at DATETIME NULL,
  next_run_at DATETIME NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (template_id) REFERENCES report_templates(id) ON DELETE CASCADE,
  INDEX idx_sched_report_next(next_run_at, active)
) ENGINE=InnoDB;

CREATE TABLE graph_cache (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  cache_key VARCHAR(190) NOT NULL UNIQUE,
  payload_json JSON NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_graph_cache_expires(expires_at)
) ENGINE=InnoDB;

CREATE TABLE yearly_kpis (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  year SMALLINT UNSIGNED NOT NULL UNIQUE,
  revenue DECIMAL(16,2) NOT NULL DEFAULT 0,
  profit DECIMAL(16,2) NOT NULL DEFAULT 0,
  tickets_resolved INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE user_behavior_events (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  event_type VARCHAR(120) NOT NULL,
  module_name VARCHAR(120) NULL,
  metadata_json JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_behavior_user_created(user_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE tenants (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  tenant_name VARCHAR(180) NOT NULL,
  tenant_key VARCHAR(120) NOT NULL UNIQUE,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

ALTER TABLE users
  ADD COLUMN tenant_id BIGINT UNSIGNED NULL,
  ADD COLUMN deleted_at DATETIME NULL,
  ADD COLUMN deleted_by BIGINT UNSIGNED NULL,
  ADD INDEX idx_users_tenant(tenant_id),
  ADD CONSTRAINT fk_users_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_users_deleted_by FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL;

ALTER TABLE clients
  ADD COLUMN tenant_id BIGINT UNSIGNED NULL,
  ADD COLUMN deleted_at DATETIME NULL,
  ADD COLUMN deleted_by BIGINT UNSIGNED NULL,
  ADD INDEX idx_clients_tenant(tenant_id),
  ADD CONSTRAINT fk_clients_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE SET NULL;

ALTER TABLE leads
  ADD COLUMN tenant_id BIGINT UNSIGNED NULL,
  ADD COLUMN deleted_at DATETIME NULL,
  ADD COLUMN deleted_by BIGINT UNSIGNED NULL,
  ADD INDEX idx_leads_tenant(tenant_id),
  ADD CONSTRAINT fk_leads_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE SET NULL;

ALTER TABLE tickets
  ADD COLUMN tenant_id BIGINT UNSIGNED NULL,
  ADD COLUMN deleted_at DATETIME NULL,
  ADD COLUMN deleted_by BIGINT UNSIGNED NULL,
  ADD INDEX idx_tickets_tenant(tenant_id),
  ADD CONSTRAINT fk_tickets_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE SET NULL;

CREATE TABLE workflow_rules (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  tenant_id BIGINT UNSIGNED NULL,
  rule_key VARCHAR(120) NOT NULL UNIQUE,
  rule_name VARCHAR(200) NOT NULL,
  trigger_event VARCHAR(120) NOT NULL,
  condition_json JSON NOT NULL,
  action_json JSON NOT NULL,
  priority INT NOT NULL DEFAULT 100,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_by BIGINT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_wf_trigger_active(trigger_event, is_active, priority)
) ENGINE=InnoDB;

CREATE TABLE workflow_execution_logs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  workflow_rule_id BIGINT UNSIGNED NOT NULL,
  context_json JSON NOT NULL,
  actions_json JSON NOT NULL,
  status ENUM('simulated','executed','failed') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (workflow_rule_id) REFERENCES workflow_rules(id) ON DELETE CASCADE,
  INDEX idx_wf_logs_status_created(status, created_at)
) ENGINE=InnoDB;

CREATE TABLE approval_chains (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  tenant_id BIGINT UNSIGNED NULL,
  module_name VARCHAR(120) NOT NULL,
  chain_name VARCHAR(180) NOT NULL,
  step_count TINYINT UNSIGNED NOT NULL DEFAULT 1,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  INDEX idx_approval_chain_module(module_name, is_active)
) ENGINE=InnoDB;

CREATE TABLE approval_chain_steps (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  chain_id BIGINT UNSIGNED NOT NULL,
  step_no TINYINT UNSIGNED NOT NULL,
  approver_role_id BIGINT UNSIGNED NOT NULL,
  condition_json JSON NULL,
  notification_json JSON NULL,
  escalation_after_minutes INT UNSIGNED NULL,
  FOREIGN KEY (chain_id) REFERENCES approval_chains(id) ON DELETE CASCADE,
  FOREIGN KEY (approver_role_id) REFERENCES roles(id) ON DELETE RESTRICT,
  UNIQUE KEY uq_chain_step(chain_id, step_no)
) ENGINE=InnoDB;

CREATE TABLE approval_requests (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  module_name VARCHAR(120) NOT NULL,
  entity_id BIGINT UNSIGNED NOT NULL,
  requested_by BIGINT UNSIGNED NOT NULL,
  status ENUM('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  current_step TINYINT UNSIGNED NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE RESTRICT,
  INDEX idx_approval_req_status(status)
) ENGINE=InnoDB;

CREATE TABLE approval_actions (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  request_id BIGINT UNSIGNED NOT NULL,
  approver_id BIGINT UNSIGNED NOT NULL,
  action_type ENUM('approved','rejected','skipped','escalated') NOT NULL,
  note VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (request_id) REFERENCES approval_requests(id) ON DELETE CASCADE,
  FOREIGN KEY (approver_id) REFERENCES users(id) ON DELETE RESTRICT,
  INDEX idx_approval_actions_req(request_id)
) ENGINE=InnoDB;

CREATE TABLE scheduled_actions (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  tenant_id BIGINT UNSIGNED NULL,
  workflow_rule_id BIGINT UNSIGNED NULL,
  action_type VARCHAR(120) NOT NULL,
  payload_json JSON NOT NULL,
  execute_at DATETIME NOT NULL,
  status ENUM('pending','running','done','failed') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  FOREIGN KEY (workflow_rule_id) REFERENCES workflow_rules(id) ON DELETE SET NULL,
  INDEX idx_sched_actions_execute(status, execute_at)
) ENGINE=InnoDB;

CREATE TABLE access_hints (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  hint_key VARCHAR(120) NOT NULL,
  hint_score DECIMAL(5,2) NOT NULL DEFAULT 0,
  metadata_json JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY uq_access_hint_user_key(user_id, hint_key)
) ENGINE=InnoDB;

CREATE TABLE dashboard_preferences (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  role_id BIGINT UNSIGNED NULL,
  module_order_json JSON NULL,
  default_dashboard VARCHAR(120) NULL,
  shortcut_json JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL,
  UNIQUE KEY uq_dashboard_pref_user(user_id)
) ENGINE=InnoDB;

CREATE TABLE behavioral_anomalies (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  anomaly_key VARCHAR(120) NOT NULL,
  severity ENUM('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  score DECIMAL(6,2) NOT NULL DEFAULT 0,
  context_json JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_behavior_anomaly_user(user_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE cognitive_load_metrics (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  module_name VARCHAR(120) NOT NULL,
  click_count INT UNSIGNED NOT NULL DEFAULT 0,
  backtrack_count INT UNSIGNED NOT NULL DEFAULT 0,
  avg_task_time_seconds INT UNSIGNED NOT NULL DEFAULT 0,
  load_score DECIMAL(6,2) NOT NULL DEFAULT 0,
  measured_on DATE NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_cog_load_user_date(user_id, measured_on)
) ENGINE=InnoDB;
