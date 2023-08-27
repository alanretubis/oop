START TRANSACTION;

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` bigint(20) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(128) NOT NULL,
  `salt` blob NOT NULL,
  `first_name` VARCHAR(50) NOT NULL,
  `surname` VARCHAR(50) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_session`
--

CREATE TABLE `tbl_user_session` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `hash` VARCHAR(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user_session`
--
ALTER TABLE `tbl_user_session`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user_session`
--
ALTER TABLE `tbl_user_session`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;
