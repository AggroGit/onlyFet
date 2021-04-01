-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: database:3306
-- Tiempo de generación: 22-03-2021 a las 15:07:45
-- Versión del servidor: 5.7.30
-- Versión de PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `laravel`
--

--
-- Volcado de datos para la tabla `hastags`
--

INSERT INTO `hastags` (`id`, `text`) VALUES
(1, '');

--
-- Volcado de datos para la tabla `images`
--

INSERT INTO `images` (`id`, `name`, `alt`, `format`, `url`, `base`, `path`, `user_id`, `post_id`, `remove_future`, `created_at`, `updated_at`) VALUES
(1, 'cdcd677ed4c07ebbd2d0e0555d7dab2a', NULL, 'jpeg', 'documents/', 'storage', NULL, NULL, NULL, 0, '2021-03-22 15:55:36', '2021-03-22 15:55:36'),
(2, 'd59e7037416daab41fdfb2a1e35f0bab', NULL, 'png', 'documents/', 'storage', NULL, NULL, NULL, 0, '2021-03-22 15:55:36', '2021-03-22 15:55:36'),
(3, '915b73dcb1b43b0a9cc34a92f7eed10e', NULL, 'JPG', 'documents/', 'storage', NULL, NULL, NULL, 0, '2021-03-22 15:55:37', '2021-03-22 15:55:37'),
(4, 'baf5a090f988135062d75072a95e3c77', NULL, 'JPG', 'image/1', 'storage', NULL, 2, 1, 0, '2021-03-22 16:05:01', '2021-03-22 16:05:02');

--
-- Volcado de datos para la tabla `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"8e3755f6-b26e-4d94-84ff-676fa1d92014\",\"displayName\":\"App\\\\Jobs\\\\sendMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendMail\",\"command\":\"O:17:\\\"App\\\\Jobs\\\\sendMail\\\":10:{s:13:\\\"\\u0000*\\u0000mailObject\\\";O:18:\\\"App\\\\Mail\\\\BasicMail\\\":25:{s:7:\\\"\\u0000*\\u0000data\\\";a:4:{s:5:\\\"title\\\";s:18:\\\"Influencer OnlyFet\\\";s:11:\\\"logoInTitle\\\";b:1;s:4:\\\"text\\\";s:173:\\\"Tu solicitud de ser usuario Influencer est\\u00e1 pendiente de revisi\\u00f3n. Recuerda que adem\\u00e1s necesitas publicar un m\\u00ednimo de 10 publicaciones para ser visible a otros usuarios\\\";s:6:\\\"option\\\";a:2:{s:4:\\\"text\\\";s:12:\\\"Ir a OnlyFet\\\";s:3:\\\"url\\\";s:21:\\\"http:\\/\\/127.0.0.1:8000\\\";}}s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:0:{}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:11:\\\"\\u0000*\\u0000markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:6:\\\"mailer\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:8:\\\"\\u0000*\\u0000email\\\";s:18:\\\"poropo97@gmail.com\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1616424936, 1616424936),
(2, 'default', '{\"uuid\":\"fb4b01bb-1b05-4d50-bd11-e295049e7fc5\",\"displayName\":\"App\\\\Jobs\\\\sendMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendMail\",\"command\":\"O:17:\\\"App\\\\Jobs\\\\sendMail\\\":10:{s:13:\\\"\\u0000*\\u0000mailObject\\\";O:18:\\\"App\\\\Mail\\\\BasicMail\\\":25:{s:7:\\\"\\u0000*\\u0000data\\\";a:4:{s:5:\\\"title\\\";s:7:\\\"OnlyFet\\\";s:11:\\\"logoInTitle\\\";b:1;s:4:\\\"text\\\";s:85:\\\"Congrats poropo, you have been validated. Now you can add your prices to your account\\\";s:6:\\\"option\\\";a:2:{s:4:\\\"text\\\";s:10:\\\"Add prices\\\";s:3:\\\"url\\\";s:42:\\\"http:\\/\\/127.0.0.1:8000\\/profile\\/suscriptions\\\";}}s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:0:{}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:11:\\\"\\u0000*\\u0000markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:6:\\\"mailer\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:8:\\\"\\u0000*\\u0000email\\\";s:18:\\\"poropo97@gmail.com\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1616425239, 1616425239);

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(369, '2014_10_12_000000_create_users_table', 1),
(370, '2014_10_12_100000_create_password_resets_table', 1),
(371, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(372, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(373, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(374, '2016_06_01_000004_create_oauth_clients_table', 1),
(375, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(376, '2019_05_03_000001_create_customer_columns', 1),
(377, '2019_05_03_000002_create_subscriptions_table', 1),
(378, '2019_05_03_000003_create_subscription_items_table', 1),
(379, '2019_08_19_000000_create_failed_jobs_table', 1),
(380, '2020_05_23_162407_create_chats', 1),
(381, '2020_05_29_152523_create_notifications', 1),
(382, '2020_05_29_175628_create_images', 1),
(383, '2020_09_15_162541_create_jobs_table', 1),
(384, '2020_10_10_120449_create_shopping', 1),
(385, '2020_10_11_101803_create_publications', 1),
(386, '2020_10_14_130726_create_videos', 1),
(387, '2020_10_27_204031_create_plans', 1),
(388, '2020_11_03_160021_create_pay_out', 1),
(389, '2020_11_04_183120_create_websockets_statistics_entries_table', 1),
(390, '2020_11_10_174517_create_faqs', 1),
(391, '2020_11_30_013438_create_auction', 1);

--
-- Volcado de datos para la tabla `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('1782f2faea3f4920eb3a43a5762ed073eb487bec5dafaf0a4a4fdb93da50e3bb755d7cec5feb0574', 1, 1, 'Personal Access Token', '[]', 0, '2021-03-22 15:54:21', '2021-03-22 15:54:21', '2022-03-22 15:54:21'),
('c82f54f2ca3ad045b7a1ad659b72ebf5d8688aebdb973cbd3c07e28dfdf3427b4ec982f08d4cd825', 2, 1, 'Personal Access Token', '[]', 0, '2021-03-22 15:55:14', '2021-03-22 15:55:14', '2022-03-22 15:55:14');

--
-- Volcado de datos para la tabla `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'OnlyFet Personal Access Client', 'z3kA8VqdlQJCRL4irRUecDRbcciUFSMivh9Xzqpl', NULL, 'http://localhost', 1, 0, 0, '2021-03-22 15:49:43', '2021-03-22 15:49:43'),
(2, NULL, 'OnlyFet Password Grant Client', '7NM0RGuiSRIWx83OgewzWdkDwsQ3GANoQ7LRjvr6', 'users', 'http://localhost', 0, 1, 0, '2021-03-22 15:49:43', '2021-03-22 15:49:43');

--
-- Volcado de datos para la tabla `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-03-22 15:49:43', '2021-03-22 15:49:43');

--
-- Volcado de datos para la tabla `plans`
--

INSERT INTO `plans` (`id`, `payForEvery`, `price`, `oldPrice`, `stripe_tarifa_id`, `previuous_stripe_id`, `using`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 'month1', 10, NULL, 'price_1IXpJ7E33cH7GeQ99JY5P8Kn', 'price_1IXpIjE33cH7GeQ9mipyiJ1S', 1, 2, '2021-03-22 16:04:17', '2021-03-22 16:04:41'),
(3, 'month3', 23, NULL, 'price_1IXpIkE33cH7GeQ92jbqPKut', NULL, 1, 2, '2021-03-22 16:04:18', '2021-03-22 16:04:18'),
(4, 'month6', 30, NULL, 'price_1IXpJ7E33cH7GeQ9xG94ifcj', 'price_1IXpIlE33cH7GeQ9XBsRuuFW', 1, 2, '2021-03-22 16:04:18', '2021-03-22 16:04:42'),
(5, 'month12', 40, NULL, 'price_1IXpJ8E33cH7GeQ9CDGjuDUK', 'price_1IXpIlE33cH7GeQ9uzeg7v3q', 1, 2, '2021-03-22 16:04:19', '2021-03-22 16:04:42');

--
-- Volcado de datos para la tabla `publications`
--

INSERT INTO `publications` (`id`, `created_at`, `updated_at`, `content`, `type`, `publish_at`, `user_id`) VALUES
(1, '2021-03-22 16:05:01', '2021-03-22 16:05:01', 'sdf', 'default', '2021-03-22 16:05:01', 2);

--
-- Volcado de datos para la tabla `publications_hastags`
--

INSERT INTO `publications_hastags` (`id`, `hastag_id`, `publication_id`) VALUES
(1, 1, 1),
(2, 1, 1);

--
-- Volcado de datos para la tabla `publications_images`
--

INSERT INTO `publications_images` (`image_id`, `publication_id`, `created_at`, `updated_at`) VALUES
(4, 1, NULL, NULL);

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `surnames`, `bank_account`, `percentage_for_user`, `numSuscriptions`, `phone_number`, `stripe_product_id`, `wantToBeInfluencer`, `influencer`, `updoadRequiredPics`, `numUploadedPosts`, `verified`, `stripe_created`, `prices_added`, `provider`, `email`, `nickname`, `lang`, `want_emails`, `email_verified_at`, `password`, `birthday`, `stripe_reciver_id`, `remember_token`, `admin`, `country`, `description`, `cp`, `city`, `province`, `direction`, `direction_details`, `type`, `temporal_token`, `created_at`, `updated_at`, `image_id`, `social_token`, `social_name`, `stripe_id`, `card_brand`, `card_last_four`, `trial_ends_at`) VALUES
(1, 'Alex Ruesga', NULL, NULL, 80, 0, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, NULL, 'admin@gmail.com', 'Alex Ruesga1', 'en', 1, NULL, '$2y$10$j2botTNCxw/zDXc/OLSb.OgLMgF6x8w2KsfVoFBeOoV/0CJ.JXe6W', NULL, NULL, 'kahsezjYNUyBrNsZZPSfIt8oTwt3LyDRh5Y0v8E3DVVi2UrpIE3GMRbgGDDV', 1, 'ES', NULL, NULL, NULL, NULL, NULL, NULL, 'client', NULL, '2021-03-22 15:54:21', '2021-03-22 15:54:22', NULL, NULL, NULL, 'cus_JA9ShVon4j0O3P', NULL, NULL, NULL),
(2, 'poropo', NULL, 'asd', 80, 0, NULL, 'localsep6058b11724093sep2', 1, 1, 1, 10, 1, 1, 1, NULL, 'poropo97@gmail.com', 'poropo2', 'en', 1, NULL, '$2y$10$aJe.FoieZSsTe78ozePjw.esRtVif6wqFY08aRll/.hYCdmc8.rPC', '2021-03-22', 'acct_1IXpDs2SthFz3SeS', NULL, 0, 'ES', NULL, NULL, NULL, NULL, NULL, NULL, 'client', '6058b117d7de0407185f003e1c07217e816c18792701a', '2021-03-22 15:55:14', '2021-03-22 16:05:01', NULL, NULL, NULL, 'cus_JA9SymgsMvCbyi', NULL, NULL, NULL);

--
-- Volcado de datos para la tabla `users_documents`
--

INSERT INTO `users_documents` (`created_at`, `updated_at`, `image_id`, `document_description`, `user_id`) VALUES
(NULL, NULL, 1, NULL, 2),
(NULL, NULL, 2, NULL, 2),
(NULL, NULL, 3, NULL, 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
