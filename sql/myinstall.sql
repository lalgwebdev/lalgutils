INSERT INTO civirule_action (name, label, class_name, is_active) 
SELECT * FROM (SELECT "lalg_update_hh_mship_info" as name, "LALG Update HH Mship Info" as label, "CRM_Civirules_LalgUpdateHHMshipInfo" as class_name, 1 as is_active) as temp
WHERE NOT EXISTS (
    SELECT name FROM civirule_action WHERE name = 'lalg_update_hh_mship_info'
) LIMIT 1;

INSERT INTO civirule_action (name, label, class_name, is_active) 
SELECT * FROM (SELECT "lalg_tidy_billing_address" as name, "LALG Tidy Billing Address" as label, "CRM_Civirules_LalgTidyBillingAddress" as class_name, 1 as is_active) as temp
WHERE NOT EXISTS (
    SELECT name FROM civirule_action WHERE name = 'lalg_tidy_billing_address'
) LIMIT 1;

INSERT INTO civirule_action (name, label, class_name, is_active) 
SELECT * FROM (SELECT "lalg_display_triggerdata" as name, "LALG Display TriggerData" as label, "CRM_Civirules_LalgDisplayTriggerData" as class_name, 1 as is_active) as temp
WHERE NOT EXISTS (
    SELECT name FROM civirule_action WHERE name = 'lalg_display_triggerdata'
) LIMIT 1;


