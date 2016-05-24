
--
-- Table structure for table `legal_docs`
--

DROP TABLE IF EXISTS `legal_docs`;
CREATE TABLE IF NOT EXISTS `legal_docs` (
  `id` varchar(50) NOT NULL,
  `parent_id` varchar(50) DEFAULT NULL,
  `approved_version` int(10) unsigned NOT NULL,
  `last_version` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `legal_docs_parent_id_foreign` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `legal_docs_versions`
--

DROP TABLE IF EXISTS `legal_docs_versions`;
CREATE TABLE IF NOT EXISTS `legal_docs_versions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `legal_doc_id` varchar(50) DEFAULT NULL,
  `version` int(10) unsigned NOT NULL,
  `name` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `description` text COLLATE utf8_general_ci NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `staff_id` int(10) unsigned NOT NULL,
  `staff_session_id` int(10) unsigned NOT NULL,
  `approved_staff_id` int(10) unsigned DEFAULT NULL,
  `approved_staff_session_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`legal_doc_id`,`version`),
  KEY `legal_docs_versions_id_auto` (`id`),
  KEY `legal_docs_versions_parent_id_foreign` (`legal_doc_id`),
  KEY `legal_docs_versions_staff_id_foreign` (`staff_id`),
  KEY `legal_docs_versions_staff_session_id_foreign` (`staff_session_id`),
  KEY `legal_docs_versions_approved_staff_id_foreign` (`approved_staff_id`),
  KEY `legal_docs_versions_approved_staff_session_id_foreign` (`approved_staff_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;


/* legal_docs */
ALTER TABLE `legal_docs`
  ADD CONSTRAINT `fk_legal_docs_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `legal_docs` (`id`);

/* legal_docs_versions */
ALTER TABLE `legal_docs_versions`
  ADD CONSTRAINT `fk_legal_docs_versions_doc_id` FOREIGN KEY (`legal_doc_id`) REFERENCES `legal_docs` (`id`);
ALTER TABLE `legal_docs_versions`
  ADD CONSTRAINT `fk_legal_docs_versions_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);
ALTER TABLE `legal_docs_versions`
  ADD CONSTRAINT `fk_legal_docs_versions_staff_session_id` FOREIGN KEY (`staff_session_id`) REFERENCES `staff_sessions` (`id`);
ALTER TABLE `legal_docs_versions`
  ADD CONSTRAINT `fk_legal_docs_versions_approved_staff_id` FOREIGN KEY (`approved_staff_id`) REFERENCES `staff` (`id`);
ALTER TABLE `legal_docs_versions`
  ADD CONSTRAINT `fk_legal_docs_versions_approved_staff_session_id` FOREIGN KEY (`approved_staff_session_id`) REFERENCES `staff_sessions` (`id`);

  
INSERT INTO `permissions` (`id`, `desc`, `grupo`, `created_at`, `updated_at`) VALUES
('definitions.edit', 'Edit Definitions', 'Definitions', '2016-05-22 23:00:00', '2016-05-22 23:00:00'),
('definitions.legal_docs', 'Legal Docs', 'Definitions', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('definitions.view', 'View Definitions', 'Definitions', '0000-00-00 00:00:00', '0000-00-00 00:00:00');