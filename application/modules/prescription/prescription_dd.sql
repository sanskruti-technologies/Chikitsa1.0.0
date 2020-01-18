DROP TABLE %db_prefix%medicines;
DROP TABLE %db_prefix%prescription;
DELETE FROM %db_prefix%navigation_menu WHERE required_module = 'prescription';
DELETE FROM %dbprefix%receipt_template WHERE type = 'prescription';