INSERT INTO %db_prefix%modules (module_name,module_display_name,module_description,module_status,module_version) VALUES ('alert', 'Send Alerts',"Send SMS and Email Alerts", '1','0.0.1');
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name,menu_order,menu_url,menu_icon,menu_text,required_module) VALUES ('alert_settings', 'administration', 700,'alert/settings', null, 'Alert Settings','alert');
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name,menu_order,menu_url,menu_icon,menu_text,required_module) VALUES ('alert_sms_log', 'administration', 710,'alert/sms_log', null, 'Alert SMS Log','alert');
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name,menu_order,menu_url,menu_icon,menu_text,required_module) VALUES ('alert_email_log', 'administration', 720,'alert/email_log', null, 'Alert Email Log','alert');
CREATE TABLE IF NOT EXISTS %db_prefix%email_log (email_log_id int(11) NOT NULL AUTO_INCREMENT,email_alert_name varchar(50) NOT NULL,email_email_id varchar(50) NOT NULL,email_subject varchar(100) NOT NULL,email_message varchar(250) NOT NULL,email_response varchar(250) NOT NULL,email_timestamp timestamp NOT NULL ,  PRIMARY KEY (email_log_id));
CREATE TABLE IF NOT EXISTS %db_prefix%sms_log (sms_log_id int(15) NOT NULL AUTO_INCREMENT,sms_url varchar(250) NOT NULL,sms_response varchar(50) DEFAULT NULL,sms_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  PRIMARY KEY (sms_log_id));
ALTER TABLE %db_prefix%data CHANGE ck_value ck_value VARCHAR( 250 ) NOT NULL DEFAULT  '';
UPDATE %db_prefix%modules SET module_version = '0.0.1' WHERE module_name = 'alert';
CREATE TABLE %db_prefix%alerts (alert_id int(11) NOT NULL PRIMARY KEY,alert_name varchar(80) NOT NULL,alert_type varchar(20) NOT NULL,alert_label varchar(250) NOT NULL,parent_alert int(11) NOT NULL);
ALTER TABLE %db_prefix%alerts CHANGE parent_alert parent_alert VARCHAR(80) NOT NULL;
ALTER TABLE %db_prefix%alerts ADD alert_format_name VARCHAR(80) NULL AFTER parent_alert;
ALTER TABLE %db_prefix%alerts ADD required_module VARCHAR(25) NULL AFTER alert_format_name;
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (1, 'email_alert', 								'email', 	'Enable Email Alerts (Email ID is required)', 						'',									NULL,							NULL);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (2, 'email_alert_new_patient', 					'email', 	'When new patient is added (to patient if email id is provided)', 	'email_alert',						NULL,							NULL);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (3, 'email_alert_new_patient_to_patient', 			'email', 	'To Patient', 														'email_alert_new_patient',			'new_patient_to_patient',		NULL);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (5, 'email_alert_new_appointment', 				'email', 	'When an appointment is booked', 									'email_alert',						'',								NULL);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (6, 'email_alert_new_appointment_to_patient', 		'email', 	'To Patient', 														'email_alert_new_appointment',		'new_appointment_to_patient',	NULL);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (7, 'email_alert_new_appointment_to_doctor', 		'email', 	'To Doctor', 														'email_alert_new_appointment',		'new_appointment_to_doctor',	'doctor');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (8, 'email_alert_appointment_cancel', 				'email', 	'When an appointment is Cancelled', 								'email_alert',						'',								NULL);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (9, 'email_alert_appointment_cancel_to_patient', 	'email', 	'To Patient', 														'email_alert_appointment_cancel',	'appointment_cancel_to_patient',NULL);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (10, 'email_alert_appointment_cancel_to_doctor', 	'email', 	'To Doctor', 														'email_alert_appointment_cancel',	'appointment_cancel_to_doctor',	'doctor');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (11, 'email_alert_new_bill', 						'email', 	'When Bill is generated', 											'email_alert',						'',								'');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (12, 'email_alert_new_bill_to_patient', 			'email', 	'To Patient', 														'email_alert_new_bill',				'new_bill_to_patient',			'');	
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (13, 'email_alert_new_bill_to_doctor', 			'email', 	'To Doctor', 														'email_alert_new_bill',				'new_bill_to_doctor',			'doctor');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (14, 'sms_alert', 									'sms', 		'Enable SMS Alerts (Mobile Number Required)', 						'',									'',								'');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (15, 'sms_alert_new_patient', 						'sms', 		'When new patient is added', 										'sms_alert',						'',								'');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (16, 'sms_alert_new_patient_to_patient', 			'sms', 		'To Patient', 														'sms_alert_new_patient',			'new_patient_to_patient',		'');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (17, 'sms_alert_new_appointment', 					'sms', 		'When an appointment is booked',									'sms_alert',						'',								'');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (18, 'sms_alert_new_appointment_to_patient', 		'sms', 		'To Patient', 														'sms_alert_new_appointment',		'new_appointment_to_patient',	'');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (19, 'sms_alert_new_appointment_to_doctor', 		'sms', 		'To Doctor', 														'sms_alert_new_appointment',		'new_appointment_to_doctor',	'doctor');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (20, 'sms_alert_appointment_cancel', 				'sms', 		'When an appointment is Cancelled', 								'sms_alert',						'',								'');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (21, 'sms_alert_appointment_cancel_to_patient', 	'sms', 		'To Patient', 														'sms_alert_appointment_cancel',		'appointment_cancel_to_patient','');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,required_module) VALUES (22, 'sms_alert_appointment_cancel_to_doctor', 	'sms', 		'To Doctor', 														'sms_alert_appointment_cancel',		'appointment_cancel_to_doctor',	'doctor');
UPDATE %db_prefix%modules SET module_version = '0.0.2' WHERE module_name = 'alert';
ALTER TABLE %db_prefix%alerts ADD alert_occur VARCHAR(5) NULL AFTER alert_format_name;
UPDATE %db_prefix%alerts SET alert_occur = 'EVENT' WHERE alert_occur IS NULL;
ALTER TABLE %db_prefix%alerts ADD  is_enabled INT( 1 ) NULL ;
UPDATE %db_prefix%alerts A JOIN %db_prefix%data B ON A.alert_name = B.ck_key SET A.is_enabled = B.ck_value;
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module) VALUES (29, 'email_alert_appointment_reminder', 	'email', 		'Appointment Reminder', 														'email_alert',		NULL,	NULL,NULL);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module) VALUES (30, 'email_alert_appointment_reminder_to_patient', 	'email', 		'To Patient', 														'email_alert_appointment_reminder',		'appointment_reminder_to_patient',	'APPNT',NULL);
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_new_patient_to_patient','Welcome to [clinic_name]');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_new_patient_to_patient','<p>Dear&nbsp;[patient_name],</p><p>Welcome to&nbsp;[clinic_name].</p><p>Please note down your Patient ID&nbsp;[patient_id] for further communications.</p><p>Have a healthy life,</p><p>Regards,</p><p>[clinic_name]</p><p>&nbsp;</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_new_appointment_to_patient','Your Appointment is booked for [appointment_date]');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_new_appointment_to_patient','<p>Hello&nbsp;[patient_name],</p><p>Your Appointment is booked with&nbsp;[doctor_name] On&nbsp;[appointment_date]&nbsp;[appointment_time]</p><p>Reason for appointment : [appointment_reason]</p><p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_new_appointment_to_doctor','Your Appointment is booked for [appointment_date]');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_new_appointment_to_doctor','<p>Hello [doctor_name],</p><p>Your Appointment is booked with [patient_name] On [appointment_date] [appointment_time]</p><p>For&nbsp;[appointment_reason].</p><p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_appointment_cancel_to_patient','Your Appointment is Cancelled for [appointment_date]');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_appointment_cancel_to_patient','<p>Dear&nbsp;[patient_name],</p><p>Your appointment with&nbsp;[doctor_name] on [appointment_date] [appointment_time]&nbsp;is cancelled&nbsp;</p><p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_appointment_cancel_to_doctor','You have an appointment with [patient_name] on [appointment_date] at [appointment_time] is Cancelled');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_appointment_cancel_to_doctor','<p>Dear&nbsp;[doctor_name],</p><p>You have an appointment with [patient_name] on [appointment_date] at [appointment_time] is Cancelled</p><p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_new_bill_to_patient','Your Bill [bill_id]');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_new_bill_to_patient','<p>[bill]</p><p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_new_bill_to_doctor','Bill [bill_id]');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_new_bill_to_doctor','<p>[bill]</p><p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_appointment_reminder_to_patient','Reminder for your Appointment with Doctor [doctor_name] on [appointment_date] at [appointment_time]');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_appointment_reminder_to_patient','<p>Dear&nbsp;[patient_name],</p><p>This is to remind you regarding your appointment with&nbsp;[doctor_name] On&nbsp;[appointment_date] &nbsp;at&nbsp;[appointment_time].</p><p>Regards,</p><p>[clinic_name]</p><p>&nbsp;</p>');
ALTER TABLE %db_prefixalerts ADD  is_enabled INT( 1 ) NULL ;
UPDATE %db_prefix%modules SET module_version = '0.0.3' WHERE module_name = 'alert';
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (23, 'email_alert_payment_received', 				'email', 		'Payment Received', 'email_alert',					NULL,							'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (34, 'email_alert_payment_received_to_patient', 	'email', 		'To Patient', 		'email_alert_payment_received',	'payment_received_to_patient',	'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (25, 'email_alert_patient_register', 				'email', 		'Patient Registers','email_alert',					NULL,							'EVENT','frontend',0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (26, 'email_alert_patient_register_to_patient',	'email', 		'To Patient',		'email_alert_patient_register',	'patient_register_to_patient',	'EVENT','frontend',0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (27, 'email_alert_patient_register_to_clinic',	'email', 		'To Clinic Email',	'email_alert_patient_register',	'patient_register_to_clinic',	'EVENT','frontend',0);
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_payment_received_to_patient','Thank you for your payment');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_payment_received_to_patient','<p>Dear [patient_name],</p><p>Thank you for your payment of [payment_amount] against Bill Ids [payment_bill_ids]</p>p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (35, 'sms_alert_payment_received', 			'sms', 		'Payment Received', 'sms_alert',					NULL,							'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (36, 'sms_alert_payment_received_to_patient', 	'sms', 		'To Patient', 		'sms_alert_payment_received',	'payment_received_to_patient',	'EVENT',NULL,0);
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('sms_format_payment_received_to_patient','Dear [patient_name],Thank you for your payment of [payment_amount] against Bill Ids [payment_bill_ids]');
UPDATE %db_prefix%modules SET module_version = '0.0.4' WHERE module_name = 'alert';
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (37, 'email_alert_appointment_complete', 				'email', 		'Appointment Complete', 'email_alert',						NULL,'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (38, 'email_alert_appointment_complete_to_patient', 	'email', 		'To Patient', 			'email_alert_appointment_complete',	'appointment_complete_to_patient','EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (39, 'sms_alert_appointment_complete', 				'sms', 'Appointment Complete',  'sms_alert',						NULL,'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (40, 'sms_alert_appointment_complete_to_patient', 	'sms', 'To Patient', 			'sms_alert_appointment_complete',	'appointment_complete_to_patient','EVENT',NULL,0);
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_appointment_complete_to_patient','Thank you for your visit at [clinic_name]');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_appointment_complete_to_patient','<p>Dear [patient_name],</p><p>Thank you for your visit.</p>p>Regards,</p><p>[clinic_name]</p>');
UPDATE %db_prefix%modules SET module_version = '0.0.5' WHERE module_name = 'alert';
ALTER TABLE %db_prefix%sms_log CHANGE sms_url  sms_url VARCHAR( 512 ) NOT NULL ;
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (37, 'email_alert_dose_reminder', 				'email', 		'Dose Reminder', 'email_alert',					NULL,'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (38, 'email_alert_dose_reminder_to_patient', 	'email', 		'To Patient', 	 'email_alert_dose_reminder',	'dose_reminder_to_patient','DOSE',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (41, 'sms_alert_appointment_reminder', 				'sms', 		'Appointment Reminder', 'sms_alert',						NULL,'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (42, 'sms_alert_appointment_reminder_to_patient', 	'sms', 		'To Patient', 	 		'sms_alert_appointment_reminder',	'appointment_reminder_to_patient','APPNT',NULL,0);
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_dose_reminder_to_patient','<p>Dear&nbsp;[patient_name],</p><p>It is time for your [dose_time] medicine.</p><p>[medicine_details]</p><p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_dose_reminder_to_patient','<p>Dear [patient_name],</p><p>It is time for your [dose_time] medicine.</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_appointment_reminder_to_patient','<p>Dear [patient_name],</p><p>This is to remind you for your Appointment with Doctor [doctor_name] on [appointment_date] at [appointment_time].</p><p>Please be on time.</p><p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_appointment_reminder_to_patient','Reminder for your Appointment with Doctor [doctor_name] on [appointment_date] at [appointment_time]');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (45, 'sms_alert_dose_reminder', 			'sms', 		'Dose Reminder', 'sms_alert',				NULL,'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (46, 'sms_alert_dose_reminder_to_patient', 	'sms', 		'To Patient', 	 'sms_alert_dose_reminder',	'dose_reminder_to_patient','DOSE',NULL,0);
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('sms_format_dose_reminder_to_patient','Dear [patient_name], It is time for your [dose_time] medicine.[sms_medicine_details]');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('sms_format_appointment_reminder_to_patient','This is to remind you for your Appointment with Doctor [doctor_name] on [appointment_date] at [appointment_time].Please be on time.');
UPDATE %db_prefix%modules SET module_version = '0.0.6' WHERE module_name = 'alert';
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (28, 'email_alert_birthday_wishes', 			'email', 	'Birthday Wishes', 	'email_alert',					NULL,							'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (33, 'email_alert_birthday_wishes_to_patient', 	'email', 	'To Patient', 	 	'email_alert_birthday_wishes',	'birthday_wishes_to_patient',	'BRTH',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (43, 'sms_alert_birthday_wishes', 				'sms', 		'Birthday Wishes', 	'sms_alert',					NULL,							'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (44, 'sms_alert_birthday_wishes_to_patient', 	'sms', 		'To Patient', 	 	'sms_alert_birthday_wishes',	'birthday_wishes_to_patient',	'BRTH',NULL,0);
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_birthday_wishes_to_patient','[clinic_name]  wishes you a very Happy Birthday');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_birthday_wishes_to_patient','Dear [patient_name],</br>Wish you a very Happy Birthday!</br>Regards,</br>[clinic_name]');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('sms_format_birthday_wishes_to_patient','Dear [patient_name], Wish you a very Happy Birthday! - [clinic_name]');
UPDATE %db_prefix%modules SET module_version = '0.0.7' WHERE module_name = 'alert';
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (47, 'email_alert_birthday_wishes_to_doctor', 	'email', 		'To Doctor', 	 	'email_alert_birthday_wishes',	'birthday_wishes_to_doctor',	'BRTH',NULL,0);
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_birthday_wishes_to_doctor','<p>Dear [doctor_name],</p><p>Wish you a very happy Birthday!</p><p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_birthday_wishes_to_doctor','[clinic_name]  wishes you a very Happy Birthday ');
UPDATE %db_prefix%modules SET module_version = '0.0.8' WHERE module_name = 'alert';
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (48, 'sms_alert_birthday_wishes_to_doctor', 	'sms', 		'To Doctor', 	 	'sms_alert_birthday_wishes',	'birthday_wishes_to_doctor',	'BRTH',NULL,0);
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('sms_format_birthday_wishes_to_doctor','Dear [doctor_name], Wish you a very happy Birthday! Regards,[clinic_name]');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (49, 'email_alert_followup_reminder', 				'email', 		'Followup Reminder', 	 	'email_alert',						NULL,							'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (50, 'email_alert_followup_reminder_to_patient', 	'email', 		'To Patient', 	 			'email_alert_followup_reminder',	'followup_reminder_to_patient',	'FLWUP',NULL,0);
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_followup_reminder_to_patient','<p>Dear [patient_name],</p><p>Its time for your next followup with [doctor_name]!<br/>Please call to book your appointment.</p><p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_followup_reminder_to_patient','Time for your next Followup');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (51, 'sms_alert_followup_reminder', 			'sms', 		'Followup Reminder', 	 	'sms_alert',					NULL,							'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (52, 'sms_alert_followup_reminder_to_patient', 	'sms', 		'To Patient', 	 			'sms_alert_followup_reminder',	'followup_reminder_to_patient',	'FLWUP',NULL,0);
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('sms_format_followup_reminder_to_patient','Dear [patient_name],Its time for your next followup with [doctor_name]! Please call to book your appointment. Regards,[clinic_name]');
UPDATE %db_prefix%modules SET module_version = '0.0.9' WHERE module_name = 'alert';
UPDATE %db_prefix%modules SET module_version = '0.1.0' WHERE module_name = 'alert';
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (53, 'email_alert_forgot_password', 'email', 'Forgot Password', 'email_alert', NULL, 'EVENT', NULL, '1');
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (54, 'email_alert_forgot_password_to_user', 'email', 'To User', 'email_alert_forgot_password', 'forgot_password_to_user', 'EVENT', NULL, '1');
ALTER TABLE %db_prefix%data CHANGE ck_value ck_value VARCHAR( 500 );
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_forgot_password_to_user','Password Reset [clinic_name]');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_forgot_password_to_user','<p>Someone has requested a password reset for the following account :</p><p>[website_url]</p><p>Email : [user_email]</p><p>If this was a mistake, just ignore the email and nothing will happen.</p><p>To reset your password, visit the following link </p>[reset_password_link]');
ALTER TABLE %db_prefix%email_log ADD email_param VARCHAR( 50 ) NULL;
UPDATE %db_prefix%modules SET module_version = '0.1.1' WHERE module_name = 'alert';
--0.1.2
ALTER TABLE %db_prefix%alerts ADD is_deleted INT NULL;
ALTER TABLE %db_prefix%email_log ADD is_deleted INT NULL;
ALTER TABLE %db_prefix%sms_log ADD is_deleted INT NULL;
ALTER TABLE %db_prefix%alerts ADD sync_status INT NULL;
ALTER TABLE %db_prefix%email_log ADD sync_status INT NULL;
ALTER TABLE %db_prefix%sms_log ADD sync_status INT NULL;	
DROP TABLE %db_prefix%alerts_enabled;
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (31, 'email_alert_dose_reminder', 			'email', 		'Dose Reminder', 	'email_alert',					NULL,							'EVENT',NULL,0);
INSERT INTO %db_prefix%alerts (alert_id, alert_name, alert_type, alert_label, parent_alert,alert_format_name,alert_occur,required_module,is_enabled) VALUES (32, 'email_alert_dose_reminder_to_patient','email', 		'To Patient', 	 	'email_alert_dose_reminder',	'dose_reminder_to_patient',		'DOSE' ,NULL,0);
UPDATE %db_prefix%data SET ck_value = '<p>Dear&nbsp;[patient_name],</p><p>It is time for your [dose_time] medicine.</p><p>[medicine_details]</p><p>Regards,</p><p>[clinic_name]</p>' WHERE ck_key = 'email_format_dose_reminder_to_patient';
UPDATE %db_prefix%data SET ck_value = 'Medicine Reminder' WHERE ck_key = 'email_subject_dose_reminder_to_patient';
UPDATE %db_prefix%modules SET module_version = '0.1.2' WHERE module_name = 'alert';
-- 0.1.3
ALTER TABLE %db_prefix%email_log CHANGE email_response email_response TEXT DEFAULT NULL;
UPDATE %db_prefix%modules SET module_version = '0.1.3' WHERE module_name = 'alert';
-- 0.1.4
ALTER TABLE %db_prefix%email_log CHANGE email_message email_message TEXT DEFAULT NULL;
ALTER TABLE %db_prefix%data CHANGE ck_value ck_value VARCHAR(1000) DEFAULT NULL;
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_patient_register_to_patient','<p>Hi [patient_name],</p><p>Thank you for Registration at [clinic_name]&nbsp;</p><p>Click on below link to verify your email</p><p>[verification_link]</p><p>Or enter the below verification code on My Account Page on our website</p><p>[verification_code]</p><p>Regards,</p><p>[clinic_name]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_patient_register_to_patient','Thank you for Registration at [clinic_name] ');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_format_patient_register_to_clinic','<p>A new Patient just registered with email : [patient_email]</p>');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_subject_patient_register_to_clinic','New Registration');
UPDATE %db_prefix%modules SET module_version = '0.1.4' WHERE module_name = 'alert';
-- 0.1.5
UPDATE %db_prefix%navigation_menu SET menu_text = 'alert_settings' WHERE menu_name = 'alert_settings';
UPDATE %db_prefix%navigation_menu SET menu_text = 'alert_sms_log' WHERE menu_name = 'alert_sms_log';
UPDATE %db_prefix%navigation_menu SET menu_text = 'alert_email_log' WHERE menu_name = 'alert_email_log';
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name,menu_order,menu_url,menu_icon,menu_text,required_module) VALUES ('alerts', 'administration', 700,'#', null, 'alerts','alert');
UPDATE %db_prefix%navigation_menu SET parent_name = 'alerts' WHERE menu_name = 'alert_settings';
UPDATE %db_prefix%navigation_menu SET parent_name = 'alerts' WHERE menu_name = 'alert_sms_log';
UPDATE %db_prefix%navigation_menu SET parent_name = 'alerts' WHERE menu_name = 'alert_email_log';
INSERT INTO %db_prefix%menu_access (menu_name, category_name, allow) VALUES ('alerts', 'System Administrator', '1');
UPDATE %db_prefix%modules SET module_version = '0.1.5' WHERE module_name = 'alert';
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_alert_time_birthday_wishes_to_patient','10:00 AM');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('sms_alert_time_birthday_wishes_to_patient','10:00 AM');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_alert_time_birthday_wishes_to_doctor','10:00 AM');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('sms_alert_time_birthday_wishes_to_doctor','10:00 AM');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_alert_time_appointment_reminder_to_patient','1');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('sms_alert_time_appointment_reminder_to_patient','1');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_alert_time_dose_reminder_to_patient','10:00 AM|01:00 PM|08:00 PM');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('sms_alert_time_dose_reminder_to_patient','10:00 AM|01:00 PM|08:00 PM');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('email_alert_time_followup_reminder_to_patient','11:00 AM');
INSERT INTO %db_prefix%data (ck_key, ck_value) VALUES ('sms_alert_time_followup_reminder_to_patient','11:00 AM');
CREATE TABLE %db_prefix%patient_alerts ( patient_alert_id int(11) NOT NULL, patient_id int(11) NOT NULL, alert_name VARCHAR(50) NOT NULL, is_enabled INT(1) NOT NULL DEFAULT 0, PRIMARY KEY (patient_alert_id));
ALTER TABLE %db_prefix%patient_alerts CHANGE patient_alert_id patient_alert_id INT(11) NOT NULL AUTO_INCREMENT; 
INSERT INTO %db_prefix%menu_access ( menu_name, category_name, allow) VALUES ('alert_settings', 'System Administrator', '1');
INSERT INTO %db_prefix%menu_access ( menu_name, category_name, allow) VALUES ('alert_sms_log', 'System Administrator', '1');
INSERT INTO %db_prefix%menu_access ( menu_name, category_name, allow) VALUES ('alert_email_log', 'System Administrator', '1');
--0.1.5
UPDATE %db_prefix%data SET ck_value = 'Thank you for your visit' WHERE ck_key = 'email_subject_appointment_complete_to_patient'; 
UPDATE %db_prefix%data SET ck_value = 'It is time for your [dose_time] medicine' WHERE ck_key = 'email_subject_dose_reminder_to_patient'; 
UPDATE %db_prefix%data SET ck_value = 'Wish you a very Happy Birthday!' WHERE ck_key = 'email_subject_birthday_wishes_to_patient'; 
UPDATE %db_prefix%data SET ck_value = 'Wish you a very Happy Birthday!' WHERE ck_key = 'email_subject_birthday_wishes_to_doctor'; 
UPDATE %db_prefix%data SET ck_value = 'Its time for your next followup with [doctor_name]' WHERE ck_key = 'email_subject_followup_reminder_to_patient'; 
UPDATE %db_prefix%modules SET module_version = '0.1.5' WHERE module_name = 'alert';
UPDATE %db_prefix%modules SET module_version = '0.1.6' WHERE module_name = 'alert';
ALTER TABLE %db_prefix%patient_alerts CHANGE patient_alert_id patient_alert_id INT(11) NOT NULL AUTO_INCREMENT;
UPDATE %db_prefix%modules SET module_version = '0.1.7' WHERE module_name = 'alert';
ALTER TABLE %db_prefix%alerts SET required_module = NULL WHERE alert_name = 'email_alert_patient_register';
ALTER TABLE %db_prefix%alerts SET required_module = NULL WHERE alert_name = 'email_alert_patient_register_to_clinic';
UPDATE %db_prefix%modules SET module_version = '0.1.8' WHERE module_name = 'alert';
