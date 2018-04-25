INSERT INTO `permissions` (`id`, `permission_name`, `permission_description`, `permission_active`) VALUES
(1, 'default-super-admin-access', NULL, 'Y'),(2, 'default-admin-access', NULL, 'Y'),(3, 'default-user-access', NULL, 'Y');

INSERT INTO `roles` (`id`, `role_name`, `role_weight`, `role_active`) VALUES
(1, 'Super Admin', 100, 'Y'),(2, 'Admin', 90, 'Y'),(3, 'User', 80, 'Y');

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`) VALUES
(1, 1, 1),(2, 2, 2),(3, 3, 3);

INSERT INTO `professions` (`id`, `profession_name`, `profession_status`) VALUES
(1, 'Professor', 'Y'),(2, 'Teacher', 'Y'),(3, 'Student', 'Y'),(4, 'Anesthesiologist', 'Y'),(5, 'Audiologist', 'Y'),(6,'Chiropractor', 'Y'),(7, 'Dentist', 'Y'),(8, 'Engineer', 'Y'),(9, 'Accountant', 'Y'),(10, 'Chemist', 'Y');