-- Migration 029: Add debt_balance column to users table
-- Tracks credits that have been given as debt to users.
-- Admin can use this to flag credits as borrowed and monitor repayment.

ALTER TABLE `users`
    ADD COLUMN `debt_balance` INT NOT NULL DEFAULT 0 COMMENT 'Credits given as debt; admin is warned each Saturday if > 0';
