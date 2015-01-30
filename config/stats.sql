-- Test Datas

INSERT INTO `stats_platform` (`ip`, `url`, `lang`, `country`, `email`, `version`, `workspaces`, `personal_workspaces`, `users`, `active`, `stats_type`, `token`, `date`) VALUES
('192.168.1.1', 'Belgium', 'fr', 'Belgium', 'info@claroline.net', '4.0.2', 5, 10, 10, 1, 2, 'TOKEN', '2015-01-27 12:24:43'),
('192.168.1.1', 'France', 'fr', 'France', 'info@claroline.net', '4.1.5', 10, 50, 50, 1, 2, 'TOKEN', '2015-01-27 12:24:43'),
('192.168.1.1', 'Spain', 'es', 'Spain', 'info@claroline.net', '4.1.6', 13, 1, 100, 1, 2, 'TOKEN', '2015-01-27 12:24:43');


INSERT INTO `stats` (`ip`, `url`, `lang`, `country`, `email`, `version`, `workspaces`, `personal_workspaces`, `users`, `stats_type`, `date`) VALUES
('192.168.1.1', 'Belgium', 'fr', 'Belgium', 'info@claroline.net', '3.6.5', 1, 1, 1, 2, '2014-11-27 12:24:43'),
('192.168.1.1', 'Belgium', 'fr', 'Belgium', 'info@claroline.net', '4.0.2', 2, 8, 8, 2, '2015-01-27 12:24:43'),
('192.168.1.1', 'Belgium', 'fr', 'Belgium', 'info@claroline.net', '4.0.2', 5, 10, 10, 2, '2015-01-27 12:29:43'),
('192.168.1.1', 'France', 'fr', 'France', 'info@claroline.net', '4.1.5', 10, 50, 50, 2, '2015-01-27 12:24:43'),
('192.168.1.1', 'France', 'fr', 'France', 'info@claroline.net', '4.1.5', 10, 50, 50, 2, '2015-02-27 12:24:43'),
('192.168.1.1', 'Spain', 'es', 'Spain', 'info@claroline.net', '4.1.6', 13, 1, 100, 2, '2015-01-27 12:24:43'),
('192.168.1.1', 'Spain', 'es', 'Spain', 'info@claroline.net', '4.1.6', 32, 1, 200, 2, '2015-02-27 12:24:43');