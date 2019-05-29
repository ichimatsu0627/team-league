<?php
define("SITE_TITLE", "Team League");
define("SITE_URL", "http://localhost:12000");

define("APP_LOCAL_PATH", "/var/www/html/teamleague.com");
define("DOCUMENT_ROOT_PATH", APP_LOCAL_PATH . "/public");

// common
define("FLG_ON", 1);
define("FLG_OFF", 0);

// Member
define("USER_ID_NAME", "Login ID");
define("USER_NAME_NAME", "Name");
define("MAIL_ADDRESS_NAME", "E-mail");
define("TWITTER_NAME", "Twitter");
define("DISCORD_NAME", "Discord");
define("PLATFORM_NAME", "Platform");
define("PASSWORD_NAME", "Password");
define("CONFIRM_PASSWORD_NAME", "Confirm Password");

// Team
define("TEAM_NAME_NAME", "Name");
define("TEAM_DESCRIPTION_NAME", "Introduction");
define("TEAM_MEMBERS_NAME", "Members");
define("TEAM_RANK_NAME", "Rank");
define("TEAM_RATING_NUMERIC_NAME", "Rating");
define("TEAM_ROLE_LIST", [
    1 => "Leader",
    2 => "Sub-Leader",
    0 => "Member",
]);

// Rank
define("RANK_NAME_LIST", [
    1 => "Bronze",
    2 => "Silver",
    3 => "Gold",
    4 => "Platinum",
    5 => "Diamond",
    6 => "Champion",
    7 => "Grand Champion",
]);

