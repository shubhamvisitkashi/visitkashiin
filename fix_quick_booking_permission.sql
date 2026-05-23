-- Quick Booking Permission Fix
-- Run this SQL to ensure booking-create permission exists and is assigned

-- Check if booking-create permission exists
SELECT * FROM permissions WHERE name = 'booking-create';

-- If it doesn't exist, create it (uncomment if needed):
-- INSERT INTO permissions (name, guard_name, created_at, updated_at) 
-- VALUES ('booking-create', 'admin', NOW(), NOW());

-- Get the permission ID
SET @permission_id = (SELECT id FROM permissions WHERE name = 'booking-create' LIMIT 1);

-- Assign to Super Admin role (role_id = 1)
INSERT IGNORE INTO role_has_permissions (permission_id, role_id) 
VALUES (@permission_id, 1);

-- Assign to Admin role (role_id = 2) if exists
INSERT IGNORE INTO role_has_permissions (permission_id, role_id) 
VALUES (@permission_id, 2);

-- Verify the assignment
SELECT r.name as role_name, p.name as permission_name 
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name = 'booking-create';

-- Clear permission cache (you'll need to run this in Laravel)
-- php artisan cache:clear
-- php artisan permission:cache-reset
