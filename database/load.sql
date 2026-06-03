-- sample data - run after create.sql
--
-- test logins:
--   editor       / editor123
--   john_reporter / journalist123
--   sarah_news    / journalist456
--   mike_field    / journalist789

USE austro_asian_times;

-- bcrypt hashed passwords
INSERT INTO users (username, email, password, role) VALUES
('editor',        'editor@austro-asian-times.com', '$2y$10$U88vY28kgknmsncs8DvVGOiIrWBdDCbxiZ5WeY92RfZa2Z68mrrba', 'editor'),
('john_reporter', 'john@austro-asian-times.com',   '$2y$10$A4u77HqnGJjZKRGx3fsppeRfv9km8C.qEgXnFYt6tJMzWui4N9SFe', 'journalist'),
('sarah_news',    'sarah@austro-asian-times.com',  '$2y$10$O25A1PWfkwa60sIuCFhvzuBFW2MIsNZfTUX6fB4RKQ8/ZIgddJCma', 'journalist'),
('mike_field',    'mike@austro-asian-times.com',   '$2y$10$Rf8vw.1CsOrcpLKrgaUza.ZBUgTdgIe1S3g7kCih8u4LgrHSynx26', 'journalist');

INSERT INTO tags (name) VALUES
('Australia'),('Darwin'),('Northern Territory'),('Southeast Asia'),
('Indonesia'),('Singapore'),('Malaysia'),('Thailand'),
('Economy'),('Politics'),('Environment'),('Culture'),
('Tourism'),('Technology'),('Health');

-- sample articles (approved so homepage shows them)
INSERT INTO articles (title, content, author_id, status, created_at, updated_at) VALUES
(
  'Darwin Port Expansion Plan Approved',
  'The Darwin City Council has approved a major expansion plan for the Darwin Port that is expected to boost trade between Northern Australia and Southeast Asia significantly. The project, estimated at $450 million, will double the port''s current container handling capacity by 2028.\n\nLocal business leaders have welcomed the announcement. The expansion will create around 1,200 construction jobs and roughly 400 permanent positions once complete. Shipping companies from Singapore and Indonesia have already expressed interest in establishing regular routes through the expanded facility.\n\nEnvironmental groups, however, have raised concerns about the impact on nearby marine ecosystems. The council says an independent environmental review will be conducted before any construction begins.',
  2, 'approved', '2026-05-28 09:00:00', '2026-05-28 09:00:00'
),
(
  'Southeast Asia Tourism Numbers Hit Record High',
  'Tourism authorities across Southeast Asia reported record visitor numbers for the first quarter of 2026, with Australian tourists making up a significant portion of the increase. Thailand, Bali and Singapore remain the top destinations.\n\nThe tourism surge has been attributed to the easing of post-pandemic travel restrictions and a rise in affordable direct flight options from Darwin and Cairns. Budget airlines have added over 20 new routes connecting Northern Australia to major Southeast Asian cities in the past twelve months.\n\nHotel occupancy rates in Bangkok reached 87 percent in March, the highest figure recorded since 2019. Travel industry analysts predict the trend will continue through the dry season.',
  3, 'approved', '2026-05-27 14:30:00', '2026-05-27 14:30:00'
),
(
  'Indonesian Trade Delegation Visits Northern Territory',
  'A 30-member trade delegation from Indonesia arrived in Darwin this week for a three-day visit aimed at strengthening economic ties between the two regions. The group, made up of government officials and business leaders, toured key agricultural and mining operations.\n\nTrade between the Northern Territory and Indonesia reached $2.1 billion last financial year. Both sides are hoping to grow this figure, particularly in the areas of live cattle exports, mining equipment, and agricultural technology.\n\nThe visit follows a memorandum of understanding signed in February between the NT Government and the West Java provincial authority. Further talks are scheduled for later this year in Jakarta.',
  4, 'approved', '2026-05-26 11:00:00', '2026-05-26 11:00:00'
),
(
  'New Environmental Report Warns of Coral Bleaching in Timor Sea',
  'Marine scientists have released a report warning that coral bleaching events in the Timor Sea are becoming more frequent and severe. The study, carried out over 18 months, found that water temperatures in the region have risen by an average of 1.4 degrees Celsius compared to readings from 20 years ago.\n\nResearchers from Charles Darwin University were part of the international team that conducted the study. They say immediate action is needed to reduce carbon emissions and protect the coral ecosystems that support fishing industries across Northern Australia and Timor-Leste.\n\nThe report has been submitted to both Australian and Indonesian governments and will be presented at an upcoming regional environmental conference in Singapore next month.',
  2, 'approved', '2026-05-25 08:45:00', '2026-05-25 08:45:00'
),
(
  'Singapore Tech Firm Opens Darwin Office',
  'A Singapore-based technology company specialising in port logistics software has opened its first Australian office in Darwin. The company said Darwin was chosen because of its strategic location as a gateway between Australia and Southeast Asia.\n\nThe new office will initially employ 25 staff, mostly in software engineering and client support roles. The company has contracts with three major ports in Southeast Asia and is now targeting the Australian market, starting with Darwin Port.\n\nThe Northern Territory Government provided a business development grant to support the establishment of the office. The Chief Minister said the move is a sign of growing confidence in Darwin as a hub for Asia-Pacific business.',
  3, 'approved', '2026-05-24 16:00:00', '2026-05-24 16:00:00'
),
(
  'Malaysia Airlines Announces New Darwin Route',
  'Malaysia Airlines has confirmed it will launch a direct service between Kuala Lumpur and Darwin starting in August 2026. The route will operate four times a week and will be the first direct connection between the two cities in over a decade.\n\nThe announcement has been welcomed by the Northern Territory tourism industry. Travel agents expect the new route to attract both Malaysian tourists visiting Australia and Australians looking for easy access to Southeast Asia via Kuala Lumpur hub connections.\n\nFares for the inaugural flights have been set at competitive rates. The airline plans to review the frequency of services after the first six months based on demand.',
  4, 'approved', '2026-05-23 10:15:00', '2026-05-23 10:15:00'
);

INSERT INTO article_tags (article_id, tag_id) VALUES
(1, (SELECT id FROM tags WHERE name='Darwin')),
(1, (SELECT id FROM tags WHERE name='Australia')),
(1, (SELECT id FROM tags WHERE name='Economy'));

INSERT INTO article_tags (article_id, tag_id) VALUES
(2, (SELECT id FROM tags WHERE name='Southeast Asia')),
(2, (SELECT id FROM tags WHERE name='Tourism')),
(2, (SELECT id FROM tags WHERE name='Thailand'));

INSERT INTO article_tags (article_id, tag_id) VALUES
(3, (SELECT id FROM tags WHERE name='Indonesia')),
(3, (SELECT id FROM tags WHERE name='Darwin')),
(3, (SELECT id FROM tags WHERE name='Economy'));

INSERT INTO article_tags (article_id, tag_id) VALUES
(4, (SELECT id FROM tags WHERE name='Environment')),
(4, (SELECT id FROM tags WHERE name='Northern Territory')),
(4, (SELECT id FROM tags WHERE name='Australia'));

INSERT INTO article_tags (article_id, tag_id) VALUES
(5, (SELECT id FROM tags WHERE name='Singapore')),
(5, (SELECT id FROM tags WHERE name='Technology')),
(5, (SELECT id FROM tags WHERE name='Darwin'));

INSERT INTO article_tags (article_id, tag_id) VALUES
(6, (SELECT id FROM tags WHERE name='Malaysia')),
(6, (SELECT id FROM tags WHERE name='Darwin')),
(6, (SELECT id FROM tags WHERE name='Tourism'));

INSERT INTO comments (article_id, author_name, content, status) VALUES
(1, 'Robert Hughes',   'Great news for Darwin. This will definitely help local businesses connect with Asian markets.', 'approved'),
(1, 'Linda Park',      'What about the environmental impact? I hope the review is genuinely independent.', 'approved'),
(2, 'Tom Baker',       'Visited Bali last month. Tourism is booming there, prices are going up though.', 'approved'),
(3, 'Ahmad Fauzi',     'Looking forward to stronger ties between NT and Indonesia. Lots of opportunity here.', 'pending'),
(4, 'Claire Martin',   'Very concerning. The government needs to take this report seriously.', 'approved'),
(5, 'James Wu',        'Darwin is becoming more of a tech hub than people give it credit for.', 'pending'),
(6, 'Susan Tan',       'Finally! A direct flight to KL. This makes travel planning so much easier.', 'approved');
