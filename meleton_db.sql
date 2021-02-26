SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `books` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `books` (`id`, `name`, `author`) VALUES
(1, 'Romeo and Juliet', 'William Shakespeare'),
(2, 'War and Peace', 'Leo Tolstoy'),
(3, 'Design Patterns', 'Gang of Four'),
(4, 'Anna Karenina', 'Leo Tolstoy');

-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `age` tinyint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `age`) VALUES
(1, 'Ivan', 'Ivanov', 18),
(2, 'Marina', 'Ivanova', 8),
(3, 'Ivan2', 'Ivanov', 20),
(4, 'Ivan3', 'Ivanov', 33),
(5, 'Marina2', 'Ivanova', 17);

-- --------------------------------------------------------

CREATE TABLE `user_books` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_books` (`id`, `user_id`, `book_id`) VALUES
(1, 1, 3),
(2, 3, 3),
(3, 2, 2),
(4, 2, 4),
(5, 3, 4),
(6, 4, 1),
(7, 4, 4),
(8, 5, 2),
(9, 5, 4);

--
-- Indexes for dumped tables
--

ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_books_user_id` (`user_id`),
  ADD KEY `fk_user_books_book_id` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `books`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `user_books`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

ALTER TABLE `user_books`
  ADD CONSTRAINT `fk_user_books_book_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_books_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
