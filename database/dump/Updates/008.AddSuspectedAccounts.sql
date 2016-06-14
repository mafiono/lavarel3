
ALTER TABLE `users`
  ADD `suspected_transaction` int(1) unsigned DEFAULT 0 AFTER `staff_session_id`;
ALTER TABLE `users`
  ADD `suspected_gambling` int(1) unsigned DEFAULT 0 AFTER `suspected_transaction`;

