# Тестовые данные для worker.py
DELETE FROM `ihunters`.`sites`;
DELETE FROM `ihunters`.`pages`;
DELETE FROM `ihunters`.`persons`;
DELETE FROM `ihunters`.`keywords`;

INSERT INTO `ihunters`.`persons` (`id`, `name`) VALUES (1, 'Python');
INSERT INTO `ihunters`.`persons` (`id`, `name`) VALUES (2, 'PHP');
INSERT INTO `ihunters`.`persons` (`id`, `name`) VALUES (3, 'JavaScript');

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

INSERT INTO `ihunters`.`sites` (`id`, `name`) VALUES (1,'Гикбрэйнс');
INSERT INTO `ihunters`.`pages` (`url`,`site_id`) VALUES ('http://geekbrains.ru/', 1);
