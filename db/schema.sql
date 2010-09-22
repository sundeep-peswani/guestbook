CREATE TABLE 'admins' (
	'username' VARCHAR,
	'password' VARCHAR(32)
);

CREATE TABLE 'posts' (
	'name' VARCHAR,
	'body' TEXT,
	'ctime' DATETIME DEFAULT CURRENT_TIMESTAMP,
	'approved' INTEGER(2)
);

CREATE TABLE 'settings' (
	'key' VARCHAR PRIMARY,
	'name' VARCHAR,
	'value' TEXT
);

CREATE INDEX 'approved_index' ON posts (approved);