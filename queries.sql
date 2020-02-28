INSERT INTO user(
    name, email, password)
    VALUES
    ('Василий', 'vasya@mail.com', 'vasyapass'),
    ('node', 'node@mail.com', '$2y$10$NIU0Bo2KufkqmiDuFK4V0OCWxI9YYLhgBSHCE4Mk2.c8o4jMYolba'),
    ('Александр', 'alex@mail.com', 'alexpass');

INSERT INTO project(name, user_id)
    VALUES 
    ('Входящие',1),
    ('Учеба',1),
    ('Работа',1),
    ('Домашние дела',1),
    ('Авто',1),
    ('Для себя',2),
    ('Для других',2),
    ('Просто так',2);

INSERT INTO task(
    project_id, user_id, name, deadline)
     VALUES 
    (3, 1, 'Собеседование в IT компании', '2020-02-03'),
    (3, 1, 'Выполнить тестовое задание', '2020-02-01'),
    (2, 1, 'Сделать задание первого раздела', '2020-01-20'),
    (1, 1, 'Встреча с другом', '2020-01-20'),
    (4, 1, 'Купить корм для кота', '2020-01-21'),
    (4, 1, 'Заказать пиццу', NULL),
    (1, 2, 'Сходить в бассейн', '2020-01-21'),
    (1, 2, 'Поесть лапши', '2020-01-22'),
    (2, 2, 'Купить маме подарок', '2020-02-10'),
    (3, 2, 'Погулять по городу', '2020-01-22');


-- добавление пользователя
INSERT INTO user(name, email, password)
    VALUES ('Кирилл', 'kir@mail.com', 'kirpass');

-- добавление нового проекта
INSERT INTO project(name, user_id)
    VALUES ('Программирование', 1);

-- добавление задачи
INSERT INTO task(project_id, user_id, name, deadline, file)
    VALUES (3, 1, 'Закончить проект', '2020-03-05', NULL);


-- выбрать все проекты для текущего пользователя
SELECT name AS 'Проекты' FROM project WHERE user_id = 1;

-- выбрать все задачи указанного проекта
SELECT name AS 'Задачи'  FROM task WHERE project_id = 4;

-- отметить задачу с заданным id, как выполненную
UPDATE task SET status = 1 WHERE id = 1;

-- переименовать задачу с заданным id
UPDATE task SET name = 'Выполнить тестовую задачу' WHERE id = 2;