INSERT INTO %db_prefix%modules (module_name,module_display_name,module_description,module_status,module_version) VALUES ('import', 'Import CSV',"Import data using CSV", '1','0.0.1');
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name, menu_order,menu_url,menu_icon,menu_text,required_module) VALUES ('import', 'administration', '100', 'import', NULL, 'import', 'import');
UPDATE %db_prefix%modules SET module_version = '0.0.2' WHERE module_name = 'import';
UPDATE %db_prefix%modules SET module_version = '0.0.3' WHERE module_name = 'import';
UPDATE %db_prefix%navigation_menu SET menu_text = 'import' WHERE menu_name = 'import';
UPDATE %db_prefix%modules SET module_version = '0.0.4' WHERE module_name = 'import';
INSERT INTO %db_prefix%menu_access (menu_name, category_name, allow) SELECT navigation_menu.menu_name,'System Administrator', '1' FROM %db_prefix%navigation_menu AS navigation_menu WHERE navigation_menu.menu_name NOT IN (SELECT menu_name FROM %db_prefix%menu_access WHERE category_name = 'System Administrator');
INSERT INTO %db_prefix%menu_access (menu_name, category_name, allow) SELECT navigation_menu.menu_name,'Administrator', '1' FROM %db_prefix%navigation_menu AS navigation_menu WHERE navigation_menu.menu_name NOT IN (SELECT menu_name FROM %db_prefix%menu_access WHERE category_name = 'Administrator') AND navigation_menu.menu_name IN ('import');