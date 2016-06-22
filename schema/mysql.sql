create table if not exists `Mainmenu`
(
	`id` int(10) not null auto_increment,
	`lft` int(10) not null,
	`rgt` int(10) not null,
	`depth` int(10) not null,
	`name` varchar(100) default null,
	`active` tinyint(1) default 1,
	`type` int(10) not null,
	`url` varchar(200) default null,
	`alias` varchar(100) default null,
	primary key (`id`)
) engine InnoDB;
