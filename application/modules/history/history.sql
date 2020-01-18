INSERT INTO %db_prefix%modules (module_name,module_display_name, module_description, module_status, module_version) VALUES ( 'history', 'Custom Details', 'Add Custom Fields to Patient and Patient Visit', '1', '0.0.2');
CREATE TABLE IF NOT EXISTS %db_prefix%patient_history_section_master ( section_id int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,section_name varchar(200) NOT NULL);
CREATE TABLE IF NOT EXISTS %db_prefix%patient_history_field_master(field_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,section_id int(11) NOT NULL,field_name varchar(25) NOT NULL,field_label varchar(50) NOT NULL,field_type varchar(15) NOT NULL,parent_group_field_id int(11) NULL);
CREATE TABLE IF NOT EXISTS %db_prefix%patient_history_field_options_master (option_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, field_id int(11) NOT NULL,option_value varchar(50) NOT NULL, option_label varchar(50) NOT NULL);
CREATE TABLE IF NOT EXISTS %db_prefix%patient_visit_history_details (history_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,visit_id int(11) NOT NULL,field_id int(11) NOT NULL,field_value varchar(100) NOT NULL);
CREATE TABLE %db_prefix%patient_history_field_condition (   condition_id int(11) NOT NULL AUTO_INCREMENT, change_status_of_field varchar(25) NOT NULL,  change_status_to varchar(25) NOT NULL,  field_name varchar(25) NOT NULL,  field_has_value varchar(25) NOT NULL,  field_is_checked varchar(25) DEFAULT NULL,  section_id int(11) DEFAULT NULL,PRIMARY KEY (condition_id));
ALTER TABLE %db_prefix%patient_visit_history_details ADD patient_id INT(11) NULL AFTER visit_id;
ALTER TABLE %db_prefix%patient_history_section_master ADD doctor_id VARCHAR(25) NULL DEFAULT NULL;
ALTER TABLE %db_prefix%patient_history_section_master ADD department_id INT( 11 ) NULL;
ALTER TABLE %db_prefix%patient_history_section_master ADD display_in VARCHAR( 25 ) NULL;
ALTER TABLE %db_prefix%patient_visit_history_details ADD is_deleted INT NULL;
ALTER TABLE %db_prefix%patient_history_field_master ADD is_deleted INT NULL;
ALTER TABLE %db_prefix%patient_history_field_options_master ADD is_deleted INT NULL;
ALTER TABLE %db_prefix%patient_history_section_master ADD is_deleted INT NULL;
ALTER TABLE %db_prefix%patient_visit_history_details ADD sync_status INT NULL;
ALTER TABLE %db_prefix%patient_history_field_master ADD sync_status INT NULL;
ALTER TABLE %db_prefix%patient_history_field_options_master ADD sync_status INT NULL;
ALTER TABLE %db_prefix%patient_history_section_master ADD sync_status INT NULL;
INSERT INTO %db_prefix%navigation_menu (id, menu_name, parent_name, menu_order, menu_url, menu_icon, menu_text, required_module, sync_status, is_deleted) VALUES (NULL, 'history_sections', 'administration', '500', 'history/sections', NULL, 'custom_details', 'history', NULL, NULL);
INSERT INTO %db_prefix%menu_access (menu_name, category_name, allow) VALUES ('history_sections', 'System Administrator', '1');
INSERT INTO %db_prefix%menu_access (menu_name, category_name, allow) VALUES ('history_sections', 'Administrator', '1');
ALTER TABLE %db_prefix%patient_history_field_master ADD field_width VARCHAR(12) NULL AFTER field_type;
ALTER TABLE %db_prefix%patient_history_field_master ADD field_order VARCHAR(12) NULL AFTER field_width;
ALTER TABLE %db_prefix%patient_history_field_master ADD field_status VARCHAR(12) NOT NULL DEFAULT 'enabled' AFTER field_order;
ALTER TABLE %db_prefix%patient_history_field_condition ADD condition_type VARCHAR(25) NULL;
ALTER TABLE %db_prefix%patient_history_section_master CHANGE department_id department_id VARCHAR(25) NULL DEFAULT NULL;
ALTER TABLE %db_prefix%patient_history_field_master ADD is_repeat INT(1) NULL	;
ALTER TABLE %db_prefix%patient_history_field_master ADD in_group INT(1) NULL	;
ALTER TABLE %db_prefix%patient_history_field_master ADD group_name VARCHAR(25) NULL;




