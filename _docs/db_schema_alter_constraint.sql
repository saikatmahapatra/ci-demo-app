ALTER TABLE `activity_streams`  ADD FOREIGN KEY (user_id) REFERENCES users(id)
	ON DELETE CASCADE
	ON UPDATE CASCADE;
	
ALTER TABLE `activity_streams`  ADD FOREIGN KEY (activity_action_id) REFERENCES activity_actions(id)
	ON DELETE CASCADE
	ON UPDATE CASCADE;
	
ALTER TABLE `activity_streams`  ADD FOREIGN KEY (activity_object_type_id) REFERENCES activity_object_types(id)
	ON DELETE CASCADE
	ON UPDATE CASCADE;
	
ALTER TABLE `user_addresses` ADD FOREIGN KEY (user_id) REFERENCES users(id)
	ON DELETE CASCADE
	ON UPDATE CASCADE;
	
ALTER TABLE `role_permission`  ADD FOREIGN KEY (role_id) REFERENCES roles(id)
	ON DELETE CASCADE
	ON UPDATE CASCADE;

ALTER TABLE `role_permission`  ADD FOREIGN KEY (permission_id) REFERENCES permissions(id)
	ON DELETE CASCADE
	ON UPDATE CASCADE;
	
ALTER TABLE `order_details` ADD FOREIGN KEY (order_id) REFERENCES orders(id)
	ON DELETE CASCADE
	ON UPDATE CASCADE;

ALTER TABLE `order_details` CHANGE `order_detail_price` `order_detail_price` DOUBLE(8,2) NOT NULL;
ALTER TABLE `order_details` CHANGE `order_detail_discount_amt` `order_detail_discount_amt` DOUBLE(8,2) NOT NULL;
ALTER TABLE `order_details` CHANGE `order_detail_delivery_amt` `order_detail_delivery_amt` DOUBLE(8,2) NOT NULL;
ALTER TABLE `order_details` CHANGE `order_detail_total_amt` `order_detail_total_amt` DOUBLE(8,2) NOT NULL;
	
-- ALTER TABLE `newsletter_subscriber_group`  ADD FOREIGN KEY (newsletter_subscriber_id) REFERENCES newsletter_subscribers(id)
-- 	ON DELETE CASCADE
-- 	ON UPDATE CASCADE;
-- 	
-- ALTER TABLE `newsletter_subscriber_group`  ADD FOREIGN KEY (newsletter_group_id) REFERENCES newsletter_groups(id)
-- 	ON DELETE CASCADE
--	ON UPDATE CASCADE;