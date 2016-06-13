CREATE OR REPLACE VIEW `v_user_limits` AS
SELECT 
`u`.`id`,
`u`.`username`,
(select `ldd`.`limit_value` from `user_limits` `ldd` 
  where `u`.`id` = `ldd`.`user_id` and `ldd`.`limit_id` = 'limit_deposit_daily'
    and `ldd`.`implement_at` < now()
  order by `ldd`.`implement_at` desc Limit 1
) as `limit_deposit_daily`,
(select `ldw`.`limit_value` from `user_limits` `ldw` 
  where `u`.`id` = `ldw`.`user_id` and `ldw`.`limit_id` = 'limit_deposit_weekly'
    and `ldw`.`implement_at` < now()
  order by `ldw`.`implement_at` desc Limit 1
) as `limit_deposit_weekly`,
(select `ldm`.`limit_value` from `user_limits` `ldm` 
  where `u`.`id` = `ldm`.`user_id` and `ldm`.`limit_id` = 'limit_deposit_monthly'
    and `ldm`.`implement_at` < now()
  order by `ldm`.`implement_at` desc Limit 1
) as `limit_deposit_monthly`,
(select `lbd`.`limit_value` from `user_limits` `lbd` 
  where `u`.`id` = `lbd`.`user_id` and `lbd`.`limit_id` = 'limit_betting_daily'
    and `lbd`.`implement_at` < now()
  order by `lbd`.`implement_at` desc Limit 1
) as `limit_betting_daily`,
(select `lbw`.`limit_value` from `user_limits` `lbw` 
  where `u`.`id` = `lbw`.`user_id` and `lbw`.`limit_id` = 'limit_betting_weekly'
    and `lbw`.`implement_at` < now()
  order by `lbw`.`implement_at` desc Limit 1
) as `limit_betting_weekly`,
(select `lbm`.`limit_value` from `user_limits` `lbm` 
  where `u`.`id` = `lbm`.`user_id` and `lbm`.`limit_id` = 'limit_betting_monthly'
    and `lbm`.`implement_at` < now()
  order by `lbm`.`implement_at` desc Limit 1
) as `limit_betting_monthly`
FROM `users` u

