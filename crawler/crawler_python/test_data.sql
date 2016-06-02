# Тестовые данные для worker.py
DELETE FROM `ihunters`.`sites`;
DELETE FROM `ihunters`.`pages`;
DELETE FROM `ihunters`.`persons`;
DELETE FROM `ihunters`.`keywords`;

INSERT INTO `ihunters`.`persons` (`id`, `name`) VALUES (1, 'Python');
INSERT INTO `ihunters`.`persons` (`id`, `name`) VALUES (2, 'PHP');
INSERT INTO `ihunters`.`persons` (`id`, `name`) VALUES (3, 'JavaScript');
INSERT INTO `ihunters`.`persons` (`id`, `name`) VALUES (4, 'Путин');
INSERT INTO `ihunters`.`persons` (`id`, `name`) VALUES (5, 'Медведев');
INSERT INTO `ihunters`.`persons` (`id`, `name`) VALUES (6, 'Навальный');

INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('python', 1);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('Питон', 1);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('Пайтон', 1);

INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('PHP', 2);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('ПХП', 2);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('пыхапе', 2);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('похапе', 2);

INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('JavaScript', 3);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('JS', 3);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('яваскрипт', 3);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('яваскрипте', 3);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('джаваскрипт', 3);

INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('Путин', 4);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('путина', 4);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('путину', 4);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('путине', 4);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('путиным', 4);

INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('медведев', 5);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('медведева', 5);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('медведеву', 5);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('медведеве', 5);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('медведевым', 5);

INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('навальный', 6);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('навального', 6);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('навальному', 6);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('навальном', 6);
INSERT INTO `ihunters`.`keywords`(`name`,`person_id`) VALUES ('навальным', 6);

INSERT INTO `ihunters`.`sites` (`id`, `name`) VALUES (1,'Гикбрэйнс');
INSERT INTO `ihunters`.`pages` (`url`,`site_id`) VALUES ('http://geekbrains.ru/', 1);
INSERT INTO `ihunters`.`sites` (`id`, `name`) VALUES (2,'Лента');
INSERT INTO `ihunters`.`pages` (`url`,`site_id`) VALUES ('http://lenta.ru/', 2);