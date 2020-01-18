INSERT INTO %db_prefix%modules (module_name,module_display_name,module_description,module_status) VALUES ('prescription', 'Prescription',"Maintain and Print Prescription", '1');
CREATE TABLE IF NOT EXISTS %db_prefix%medicines(medicine_id int(11) NOT NULL AUTO_INCREMENT,medicine_name varchar(25) NOT NULL, PRIMARY KEY (medicine_id));
CREATE TABLE IF NOT EXISTS %db_prefix%prescription (prescription_id int(11) NOT NULL AUTO_INCREMENT,visit_id int(11) NOT NULL,patient_id int(11) NOT NULL,medicine_id int(11) NOT NULL,freq_morning int(11) NOT NULL DEFAULT '0',freq_afternoon int(11) NOT NULL DEFAULT '0',freq_night int(11) NOT NULL DEFAULT '0',for_days int(11) NOT NULL,instructions varchar(100) DEFAULT NULL, PRIMARY KEY (prescription_id));
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name,menu_order, menu_url,menu_icon,menu_text,required_module) VALUES ('medicine', 'administration', '110', 'prescription/medicine', NULL, 'Medicine', 'prescription');
INSERT INTO %db_prefix%receipt_template (template,is_default,template_name,type) VALUES ('<h1 style="text-align: center;">[clinic_name]</h1><h2 style="text-align: center;">[tag_line]</h2><p style="text-align: center;">[clinic_address]</p><p style="text-align: center;"><strong style="line-height: 1.42857143;">Landline : </strong><span style="line-height: 1.42857143;">[landline]</span> <strong style="line-height: 1.42857143;">Mobile : </strong><span style="line-height: 1.42857143;">[mobile]</span> <strong style="line-height: 1.42857143;">Email : </strong><span style="text-align: center;"> [email]</span></p><hr id="null" /><p><span style="text-align: left;"><strong>Date : </strong>[visit_date] </span><span style="float: right;"><strong style="text-align: left;">Patient ID: </strong>[patient_id]<br /><strong style="text-align: left;">Patient Name: </strong>[patient_name]<br /><strong style="text-align: left;">Age / Sex: </strong>[age] | [sex]</span></p><h1>Rx</h1><p>&nbsp;</p><table style="width: 100%; margin-top: 25px; margin-bottom: 25px; border-collapse: collapse; border: 1px solid black;"><thead><tr style="height: 28px;"><td style="padding: 5px; border: 1px solid black; height: 28px;"><strong>Medicine Name</strong></td><td style="padding: 5px; border: 1px solid black; height: 28px;"><strong>Dosage</strong></td><td style="padding: 5px; border: 1px solid black; height: 28px;"><strong>Quantity</strong></td><td style="padding: 5px; border: 1px solid black; height: 28px;"><strong>Instructions</strong></td></tr><tr style="height: 0px;"><td style="height: 0px;" colspan="4"><strong>[col:medicine_name|dosage|quantity|instructions]</strong></td></tr></thead></table><p><strong>Notes&nbsp;</strong><br /> [patient_notes]</p><p>&nbsp;</p><p>[doctor_name]</p>', 1, 'Main','prescription');
UPDATE %db_prefix%modules SET module_version = '0.0.1' WHERE module_name = 'prescription';
--0.0.2
UPDATE %db_prefix%modules SET module_version = '0.0.2' WHERE module_name = 'prescription';
--0.0.3
UPDATE %db_prefix%modules SET module_version = '0.0.3' WHERE module_name = 'prescription';
--0.0.4
UPDATE %db_prefix%navigation_menu SET menu_text = 'medicine' WHERE menu_name = 'medicine';
UPDATE %db_prefix%modules SET module_version = '0.0.4' WHERE module_name = 'prescription';
INSERT INTO %db_prefix%menu_access (menu_name, category_name, allow) VALUES ('medicine', 'System Administrator', '1');
INSERT INTO %db_prefix%menu_access (menu_name, category_name, allow) VALUES ('medicine', 'Administrator', '1');
--0.0.5
UPDATE %db_prefix%modules SET module_version = '0.0.5' WHERE module_name = 'prescription';

