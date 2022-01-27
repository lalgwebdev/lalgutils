INSERT INTO civirule_condition (name, label, class_name, is_active) 
SELECT * FROM (SELECT "lalg_if_no_email_in_household" as name, "LALG If No Email In Household" as label, "CRM_Civirules_LalgIfNoEmailInHousehold" as class_name, 1 as is_active) as temp
WHERE NOT EXISTS (
    SELECT name FROM civirule_condition WHERE name = 'lalg_if_no_email_in_household'
) LIMIT 1;




