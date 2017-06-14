use betportugal2;

SET FOREIGN_KEY_CHECKS=0;

TRUNCATE api_request_logs;
/* KEEP Banners */
/* KEEP Bonus */

TRUNCATE bonus_targets;
TRUNCATE bonus_username_targets;

TRUNCATE cache;
TRUNCATE captor_files;

TRUNCATE daily_bet;
TRUNCATE selections_daily;

TRUNCATE errors;
TRUNCATE genius_logs;
TRUNCATE logs;
TRUNCATE sessions;
TRUNCATE timer;
TRUNCATE wellcome;

TRUNCATE highlights;

/* KEEP Legal Docs */

TRUNCATE list_identity_checks;
TRUNCATE list_self_exclusions;

TRUNCATE messages;
TRUNCATE message_types;

TRUNCATE password_resets;

/* KEEP Professional Situation */
/* KEEP Session Types */
/* KEEP Settings */
/* KEEP Statuses */

TRUNCATE sports_markets_multiples;

/* KEEP Transactions and Taxes */

TRUNCATE user_balances;
TRUNCATE user_bet_bc_events;
TRUNCATE user_bet_events;
TRUNCATE user_bet_statuses;
TRUNCATE user_bet_transaction_types;
TRUNCATE user_bet_transactions;
TRUNCATE user_bets;
TRUNCATE user_betslips;
TRUNCATE user_bonus;
TRUNCATE user_complains;
TRUNCATE user_invites;
TRUNCATE user_limits;
TRUNCATE user_mails;
TRUNCATE user_notes;
TRUNCATE user_profiles_log;
TRUNCATE user_profiles;
TRUNCATE user_roles;
TRUNCATE user_revocations;
TRUNCATE user_self_exclusions;
TRUNCATE user_settings;
TRUNCATE user_statuses;
TRUNCATE user_training;
TRUNCATE user_transactions;
TRUNCATE user_documentation;
TRUNCATE user_bank_accounts;
TRUNCATE user_sessions;
TRUNCATE users;

/* WARNING this can get errors */
TRUNCATE staff_sessions;

SET FOREIGN_KEY_CHECKS=1;
