-- Initialize database for PhilNITS Prep
-- This script runs when MySQL container starts

USE philnits_prep;

-- Grant necessary privileges (will be overridden by environment variables in production)
GRANT ALL PRIVILEGES ON philnits_prep.* TO 'philnits'@'%';
FLUSH PRIVILEGES;
