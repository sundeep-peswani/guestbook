INSERT INTO admins (`username`, `password`) VALUES 
('admin', MD5('admin'));

INSERT INTO `settings` (`key`, `name`, `value`) VALUES
('PAGE_LENGTH', 'The number of items to display on a single page of the guest book', '10'),
('TIMEZONE', 'The timezone used for date entries', 'Asia/Hong_Kong'),
('GOOGLE_API_KEY', 'API key for Google Libraries', 'ABQIAAAASiKAHpXyJ-CqPjowv24hJhS5yWV0qrfxBEivBgw3Ki8oWpuYzxQYxV5RUeGU4hQMrPfNje7OavzDvQ'),
('DEBUG', 'Set debugging on', '0');