-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2024 at 04:23 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esas`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_activity_logs`
--

CREATE TABLE `tbl_activity_logs` (
  `activity_id` int(20) NOT NULL,
  `activity` text NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_id` int(20) NOT NULL,
  `moderator_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profilePic` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `email`, `password`, `dateAdded`, `dateModified`, `profilePic`) VALUES
(1, 'admin@gmail.com', '1', '2024-07-12 02:45:26', '2024-07-12 02:45:26', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clubs`
--

CREATE TABLE `tbl_clubs` (
  `club_id` int(20) NOT NULL,
  `clubName` varchar(200) NOT NULL,
  `information` text NOT NULL,
  `keywords` text NOT NULL,
  `coverPhoto` varchar(200) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_clubs`
--

INSERT INTO `tbl_clubs` (`club_id`, `clubName`, `information`, `keywords`, `coverPhoto`, `dateAdded`, `dateModified`) VALUES
(1, 'NBSC Quick Response Team', 'The NBSC Quick Response Team (QRT) is a dedicated student organization at NBSC College, focused on providing rapid assistance and support in emergency situations within the campus. Comprised of well-trained and committed students, the QRT specializes in first aid, emergency response, and disaster preparedness. The club regularly conducts training sessions and workshops in collaboration with local emergency services to ensure its members are equipped with the latest knowledge and skills. This proactive approach not only enhances the safety and well-being of the NBSC community but also fosters a culture of readiness and resilience among students.\r\nIn addition to emergency response, the NBSC Quick Response Team plays a significant role in promoting health and safety awareness across the campus. Through various outreach programs, the club educates students and staff on best practices for personal safety, emergency preparedness, and effective response strategies. The QRT also actively participates in campus-wide drills and simulations, ensuring that the entire college community is prepared to handle potential crises. By serving as a vital resource and advocate for safety, the NBSC Quick Response Team contributes to creating a secure and supportive environment at NBSC College.', '', 'COVERPHOTO_QUICKRESPONSETEAM.png', '2022-08-18 03:19:13', '2022-08-18 03:19:13'),
(2, 'NBSC Band Sound Space', 'The NBSC Band is a dynamic and vibrant student organization at NBSC College, dedicated to cultivating musical talent and fostering a sense of community among its members. Established in 2010, the band has grown to become a staple of college events, performing at ceremonies, sports games, and various campus activities. The group welcomes students from all departments and years, encouraging collaboration and skill development across different musical genres, including classical, jazz, pop, and rock. The band practices regularly in the school\'s music hall, ensuring that members have ample opportunity to hone their craft and prepare for performances.\n\nIn addition to providing a platform for musical expression, the NBSC Band also emphasizes leadership and teamwork. Members have the opportunity to take on roles such as section leaders, event coordinators, and public relations officers, gaining valuable experience in organization and management. The band also participates in regional and national competitions, often earning accolades for their performances. Beyond the music, the NBSC Band fosters a supportive and inclusive environment, where students can build lasting friendships and develop a deep appreciation for the arts.', '', 'COVERPHOTO_NBSCBAND.png', '2023-03-19 15:23:09', '2023-03-19 15:23:09'),
(3, 'MAS-AMICUS', 'MAS-AMICUS, short for \"Mutual Aid System for Affiliates of the Medical Informatics Community in the United States,\" is a collaborative initiative aimed at fostering solidarity and support among medical informatics professionals across the country. Founded with the vision of enhancing professional development and knowledge sharing within the field of medical informatics, MAS-AMICUS provides a structured platform for members to engage in peer-to-peer learning, mentorship, and networking opportunities. Through its various programs and events, MAS-AMICUS strives to cultivate a community where members can exchange insights, discuss emerging trends, and address challenges in healthcare informatics.\n\nCentral to MAS-AMICUS\'s mission is the promotion of innovation and best practices in medical informatics. By facilitating dialogue and collaboration among its affiliates, MAS-AMICUS aims to drive advancements in healthcare technology and data management practices. Members benefit from access to resources such as workshops, webinars, and research forums that enable them to stay current with industry developments and contribute to the evolution of healthcare informatics standards. As a supportive network, MAS-AMICUS plays a pivotal role in empowering its members to navigate complexities within the healthcare landscape, ultimately enhancing patient care outcomes through the effective use of informatics solutions.', '', 'COVERPHOTO_MASAMICUS.png', '2023-09-18 12:51:22', '2023-09-18 12:51:22'),
(4, 'Muslim Student\'s Society', 'The Muslim Student\'s Society (MSS) at NBSC is a vibrant and inclusive community dedicated to fostering spiritual growth, cultural understanding, and social responsibility. We organize a range of activities, including thought-provoking discussions, cultural festivals, and interfaith dialogues, aimed at deepening knowledge and appreciation of Islamic values and traditions. Our events provide a platform for students to connect, share experiences, and engage in meaningful conversations, creating a supportive environment for personal and spiritual development. Beyond our internal activities, MSS is committed to giving back to the community through various outreach programs and charity initiatives. By participating in community service projects and fundraising events, members contribute to positive social change while building a strong sense of camaraderie. Joining MSS not only offers a chance to strengthen one\'s faith and cultural identity but also to make a tangible impact on the lives of others, enhancing both personal growth and community well-being.', '', 'COVERPHOTO_MUSLIMSTUDENT\'SSOCIETY.png', '2023-11-18 12:51:22', '2023-11-18 12:51:22'),
(5, 'Dramatic Society', 'Join the Dramatic Society and immerse yourself in the vibrant world of theater and performance! Whether you\'re an aspiring actor, a backstage wizard, or just passionate about the arts, our club offers a creative outlet to explore and express your talents. With regular workshops, rehearsals, and performances, you\'ll have countless opportunities to hone your craft and showcase your skills. Our experienced mentors and supportive community are here to guide you every step of the way. Be part of a dynamic group where your passion for drama can flourish and make lasting friendships along the way.  As a member of the Dramatic Society, you\'ll gain hands-on experience in all aspects of theater production, from acting and directing to set design and stage management. We welcome students of all skill levels, providing a nurturing environment where you can develop your abilities and grow as a performer. Our club not only enhances your creativity but also builds confidence and teamwork skills that are valuable beyond the stage. Join us to participate in exciting projects, collaborate with like-minded individuals, and make a meaningful impact through the power of storytelling. Discover the thrill of live performance and become a key player in our artistic community.', '', 'COVERPHOTO_DRAMATICSOCIETY.png', '2023-12-18 12:51:22', '2023-12-18 12:51:22'),
(6, 'Young Historians Club', 'Discover the Young Historians Club, where history comes alive and every member\'s voice matters. We delve into fascinating historical events, from ancient civilizations to modern times, exploring their impact on today\'s world. With engaging discussions, interactive projects, and historical reenactments, we offer a dynamic way to learn and connect with fellow history enthusiasts. Our club also provides opportunities to participate in history-themed competitions and attend exclusive events with guest speakers. Join us to deepen your understanding of the past while making lasting friendships.  Being part of the Young Historians Club means being at the forefront of historical exploration and analysis. We foster a supportive environment where members can share their perspectives, conduct research, and contribute to meaningful projects. Whether you have a passion for history or are looking to explore new interests, our club offers a welcoming space to expand your knowledge and skills. We believe that history is not just about the past but also about shaping the future through understanding. Come be a part of our journey and help us uncover the stories that define our world.', '', 'COVERPHOTO_YOUNGHISTORIANSCLUB.png', '2024-01-18 12:51:22', '2024-01-18 12:51:22'),
(7, 'English Club', 'Join the English Club and immerse yourself in a vibrant community dedicated to the love of language and literature. Whether you\'re passionate about classic novels, contemporary poetry, or creative writing, our club offers a range of activities designed to spark your literary enthusiasm. Participate in engaging discussions, creative workshops, and exciting competitions that help you hone your writing and communication skills. Our members also enjoy exclusive access to author talks, book fairs, and literary events that enrich their understanding of the English language. Embrace the opportunity to connect with fellow students who share your interests and explore the endless possibilities that come with mastering English.  The English Club is not just about reading and writing; it\'s about building friendships and creating lasting memories. Join us for fun social events, including themed parties, movie nights, and group outings, all while improving your language skills. We offer mentorship and support for academic and personal growth, ensuring that every member feels valued and inspired. Take part in community service projects that use language to make a positive impact, and develop skills that will benefit you both academically and professionally. Become a part of a club where your passion for English can truly flourish and where your voice will be heard.', '', 'COVERPHOTO_ENGLSIHCLUB.png', '2024-02-14 14:55:34', '2024-02-14 14:55:34'),
(8, 'Math-Sci Club', 'The Math-Sci Club offers an exciting opportunity for students who are passionate about mathematics and science to dive deeper into these fascinating fields. Members can participate in engaging activities such as solving complex problems, conducting experiments, and exploring cutting-edge technologies. The club regularly hosts workshops, guest lectures, and competitions that provide hands-on experience and enhance problem-solving skills. By joining, students gain access to a community of like-minded peers and mentors who are dedicated to fostering a love for STEM. This is not just a club; it\'s a gateway to academic and career growth in the fields of math and science.  Being a part of the Math-Sci Club means you\'ll be involved in innovative projects and collaborative research that push the boundaries of traditional learning. Our members enjoy exclusive access to various resources, including specialized software and research opportunities. We also organize field trips to science museums, laboratories, and tech companies, offering real-world insights into the industries they aspire to join. Whether you\'re aiming for a career in engineering, research, or education, the Math-Sci Club provides a supportive environment to develop your skills and achieve your goals. Join us and turn your curiosity into expertise while making lifelong connections in the world of math and science.', '', 'COVERPHOTO_MATH-SCICLUB.png', '2024-03-15 02:05:15', '2024-03-15 02:05:15'),
(9, 'KAMFIL Club', 'The KAMFIL Club, which stands for \"Kabalikat ng Masisipag na Filipino\" or \"Companion of Diligent Filipinos,\" offers a vibrant and dynamic environment where students can engage in a range of exciting activities and make lasting friendships. As a member, you\'ll have the opportunity to participate in various workshops, seminars, and community service projects aimed at personal and professional development. Our club prides itself on fostering a collaborative atmosphere where your ideas are valued and you can take on leadership roles. Join us to enhance your skills, gain valuable experience, and be part of a supportive network of peers. Whether you\'re interested in developing new skills or contributing to meaningful causes, KAMFIL Club is the perfect place to start.  Being part of KAMFIL Club means you\'ll be involved in projects that make a real impact within our community and beyond. We provide numerous opportunities for networking with professionals, engaging in hands-on experiences, and working on projects that align with your interests. Our members enjoy exclusive access to events and resources designed to help you succeed both academically and personally. With a focus on growth and collaboration, KAMFIL Club is dedicated to helping you achieve your goals and make the most of your college experience. Discover the benefits of joining a club that values innovation, teamwork, and community.', '', 'COVERPHOTO_KAMFILCLUB.png', '2024-03-15 02:05:15', '2024-03-15 02:05:15'),
(10, 'Mountaineering Society', 'The Mountaineering Society is the perfect club for students seeking adventure and personal growth. Join us to explore breathtaking mountain trails, tackle thrilling climbs, and develop essential outdoor skills in a supportive community. Our activities cater to all skill levels, from beginners to seasoned hikers, ensuring everyone can enjoy the thrill of mountaineering safely. By becoming a member, you’ll not only challenge yourself but also make lasting friendships with fellow enthusiasts who share your passion for the great outdoors. Don’t miss out on this opportunity to push your limits and experience the world from a new perspective.  In addition to our regular hikes and climbs, the Mountaineering Society offers workshops on navigation, survival techniques, and environmental stewardship. Our experienced guides and instructors are dedicated to providing a comprehensive learning experience while ensuring your safety and enjoyment. We also host social events and team-building activities, creating a vibrant and inclusive environment. Whether you\'re looking to conquer new heights or simply connect with nature, the Mountaineering Society is your gateway to an exhilarating and rewarding experience. Join us and embark on your next adventure with a community that truly understands the spirit of mountaineering.', '', 'COVERPHOTO_MOUNTAINEERINGSOCIETY.png', '2024-05-15 02:05:15', '2024-05-15 02:05:15'),
(11, 'Debate Club', 'Join the Debate Club and sharpen your critical thinking skills while engaging in stimulating discussions on a wide range of topics. This club provides a dynamic platform for students to articulate their opinions, build strong arguments, and enhance public speaking abilities. Whether you\'re passionate about current events, politics, or social issues, the Debate Club offers an opportunity to explore and debate these subjects with peers. You\'ll gain valuable experience in research, teamwork, and persuasive communication that can benefit you in both academic and professional settings. By joining, you become part of a community that values intellectual growth and the exchange of diverse perspectives.  In addition to weekly debates and meetings, the Debate Club participates in local and national competitions, allowing members to showcase their skills on a larger stage. The club\'s supportive environment encourages members to practice and perfect their debating techniques, receive constructive feedback, and celebrate each other\'s successes. Joining the Debate Club means becoming a part of a network of like-minded individuals who are committed to learning and personal development. Embrace the challenge, develop lifelong skills, and make lasting friendships by joining the Debate Club today.', '', 'COVERPHOTO_DEBATECLUB.png', '2024-08-17 01:53:24', '2024-08-17 01:53:24'),
(12, 'Arts Society', 'The Arts Society is a vibrant and inclusive community dedicated to fostering creativity and artistic expression. Our club provides a dynamic platform for students to explore various art forms, from painting and sculpture to digital design and performance arts. By joining, you\'ll gain access to exclusive workshops, exhibitions, and collaborative projects that will help you refine your skills and build a strong portfolio. Whether you’re a seasoned artist or just starting out, our supportive environment encourages growth and self-expression. We believe in the power of art to inspire, connect, and transform lives, and we invite you to be a part of this exciting journey.  As a member of the Arts Society, you\'ll have the opportunity to work alongside passionate peers and experienced mentors who share your enthusiasm for the arts. Our club regularly hosts events such as art shows, open mic nights, and community outreach programs that not only showcase your talents but also engage with the broader community. Networking with fellow artists and participating in collaborative projects will expand your creative horizons and provide valuable experience. Join us to be part of a creative family that celebrates diversity, innovation, and artistic excellence. Your unique perspective and creativity will contribute to our vibrant community, making a lasting impact on both your personal development and the art world.', '', 'COVERPHOTO_ARTSSOCIETY.png', '2024-08-17 01:53:24', '2024-08-17 01:53:24'),
(13, 'Indigenous People Society', 'The Indigenous People Society offers a unique opportunity for students to engage with and support indigenous communities and cultures. Through various activities and initiatives, members learn about traditional practices, languages, and the rich heritage of indigenous peoples. The club organizes workshops, cultural events, and community outreach programs that foster a deeper understanding and appreciation of these vital cultures. Joining this society allows you to contribute to preserving and promoting indigenous traditions while gaining valuable insights and experiences. It’s an ideal way to make a meaningful impact and broaden your cultural horizons.  Being part of the Indigenous People Society connects you with a diverse group of passionate individuals who share an interest in social justice and cultural preservation. You will have the chance to collaborate on projects that address current issues facing indigenous communities, from education to environmental sustainability. The club also provides a platform for you to develop leadership and organizational skills through hands-on involvement in planning and executing events. By participating, you become an advocate for important causes and help drive positive change in the community. Join us to be part of a movement that celebrates and respects the rich tapestry of indigenous cultures.', '', 'COVERPHOTO_INDIGENOUSPEOPLESOCIETY.png', '2024-08-17 01:53:24', '2024-08-17 01:53:24'),
(14, 'Young Catholic Servants of Christ', 'The Young Catholic Servants of Christ is a vibrant and welcoming community dedicated to fostering spiritual growth and active service among students. By joining, you’ll engage in meaningful activities that promote personal development, leadership, and a deeper connection with your faith. Our club offers regular retreats, prayer meetings, and community outreach programs that not only enrich your spiritual life but also contribute positively to the surrounding community. We believe in creating a supportive environment where every member can grow and thrive. Whether you’re looking to deepen your faith or make a difference, YCSC provides opportunities for both personal and communal fulfillment.  As a member of YCSC, you will be part of a dynamic group of like-minded peers who are passionate about making a difference. We organize a range of events and projects that allow you to apply your skills and interests in a way that benefits others. Additionally, the club provides a platform for building lasting friendships and developing skills that will serve you well in all areas of life. Joining YCSC means becoming part of a legacy of service and faith, with numerous opportunities for growth and impact. Embrace the chance to be a part of something greater and make a positive change in the world around you.', '', 'COVERPHOTO_YOUNGCATHOLICSERVANTSOFCHRIST.png', '2024-08-17 01:53:24', '2024-08-17 01:53:24'),
(15, 'Peer Counselor\'s Club', 'Join the Peer Counselor\'s Club and become a vital part of a supportive community dedicated to helping fellow students thrive. Our club offers a unique opportunity to develop essential skills in counseling, communication, and leadership while making a meaningful impact on campus. As a member, you\'ll gain hands-on experience in providing peer support, organizing workshops, and participating in various community outreach programs. Engage in regular training sessions and collaborate with like-minded individuals who are passionate about making a difference in others\' lives.  In addition to personal growth and skill development, the Peer Counselor\'s Club provides a platform for building lasting friendships and networking with professionals in the field of mental health and counseling. By joining, you\'ll be part of a dynamic team committed to fostering a positive and inclusive campus environment. Take advantage of this chance to enhance your resume, gain valuable life experience, and contribute to the well-being of your peers. We welcome all students who are eager to make a difference and grow both personally and professionally.', '', 'COVERPHOTO_PEERCOUNSELOR\'SCLUB.png', '2024-08-17 01:53:24', '2024-08-17 01:53:24'),
(16, 'Sports', 'Join the Sports Club and immerse yourself in a world of excitement and teamwork! Whether you’re passionate about competitive sports or just looking to stay active, our club offers a variety of activities that cater to all skill levels. From intense games to casual matches, you\'ll find opportunities to challenge yourself and improve your skills. Our experienced coaches and friendly members will support you every step of the way. Plus, you\'ll have access to exclusive events, tournaments, and workshops to elevate your game.  Being part of the Sports Club means more than just playing sports—it’s about building lifelong friendships and fostering a sense of community. We regularly organize social events and team-building activities that help strengthen bonds and create unforgettable memories. By joining, you\'ll also gain valuable leadership and teamwork experience that will benefit you beyond the playing field. Don’t miss out on the chance to be part of a vibrant and active community that values both fun and personal growth. Sign up today and start your journey with us!', '', 'COVERPHOTO_SPORTS.png', '2024-08-17 01:53:24', '2024-08-17 01:53:24'),
(17, 'Environmental Club', 'Join the Environmental Club and become a champion for sustainability! Our club is dedicated to making a positive impact on the environment through various initiatives, including campus clean-ups, tree planting, and educational workshops. By participating, you\'ll gain hands-on experience in environmental conservation and connect with like-minded individuals who are passionate about protecting our planet. You\'ll also have the opportunity to collaborate on exciting projects and advocate for eco-friendly practices within our community. Together, we can create a greener future and make a real difference.  In the Environmental Club, you\'ll not only contribute to meaningful environmental change but also develop valuable skills and leadership qualities. We host regular events and campaigns to raise awareness about pressing environmental issues and promote sustainable living. Membership provides a platform for you to voice your ideas, engage in innovative solutions, and participate in fun, impactful activities. Join us to enhance your resume, build a network of environmentally-conscious peers, and be part of a movement that truly matters. Your involvement can lead to lasting positive effects on our surroundings and inspire others to take action.', '', 'COVERPHOTO_ENVIRONMENTALCLUB.png', '2024-08-17 01:53:24', '2024-08-17 01:53:24'),
(18, 'NBSC Dance Troup', 'Join the NBSC Dance Troupe and become part of a vibrant and dynamic community where your passion for dance can truly flourish. Our club offers a diverse range of dance styles, from contemporary and hip-hop to traditional and ballroom, ensuring that there’s something for everyone. You\'ll have the opportunity to work with talented choreographers and experienced dancers, who will help you refine your skills and boost your confidence. Regular performances and showcases allow you to demonstrate your talent and creativity, gaining valuable stage experience. Being a member also means forging lasting friendships and connections within the college, making your time here memorable and enjoyable.  In addition to honing your dance abilities, the NBSC Dance Troupe fosters a supportive and encouraging environment where personal growth and teamwork are emphasized. We believe in the power of dance to inspire, energize, and unite, creating a space where all members can thrive. The club hosts workshops, social events, and collaborations with other student organizations, ensuring a well-rounded experience. By joining, you\'ll be part of a tradition of excellence and creativity that has left a lasting impact on the college community. Embrace the rhythm and join us in making every step count!', '', 'COVERPHOTO_NBSCDANCETROUP.png', '2024-08-17 01:53:24', '2024-08-17 01:53:24'),
(19, 'NBSC Chorale', 'The NBSC Chorale is a vibrant and inclusive club that brings together students with a passion for music and performance. As part of our choir, you\'ll have the opportunity to explore a diverse repertoire, from classical pieces to contemporary hits, all while enhancing your vocal skills. Our rehearsals and performances offer a supportive environment where members can grow as artists and develop lifelong friendships. Joining the NBSC Chorale means becoming part of a community that values creativity, teamwork, and dedication. Whether you\'re an experienced singer or a beginner, you\'ll find a welcoming space to share your love for music.  Being a part of the NBSC Chorale not only allows you to showcase your talents but also provides numerous benefits, including opportunities to perform at various campus and community events. Our club fosters a strong sense of camaraderie and offers a chance to represent NBSC with pride. With regular practice sessions and exciting performance schedules, you\'ll gain confidence and improve your musical abilities. Join us and be a part of something special—an enriching experience that blends musical excellence with personal growth. Let your voice be heard and make lasting memories with the NBSC Chorale.', '', 'COVERPHOTO_NBSCCHORALE.png', '2024-08-17 01:53:24', '2024-08-17 01:53:24'),
(21, 'Educator\'s Club', 'The Educator\'s Club is a vibrant community dedicated to fostering a passion for teaching and learning among students. As a member, you\'ll have the opportunity to engage in a variety of educational activities, from organizing workshops and tutoring sessions to participating in mentorship programs. Our club values collaboration and creativity, providing a platform where you can develop essential skills such as public speaking, leadership, and critical thinking. We regularly host guest speakers and educational events to broaden your perspective and inspire you to make a positive impact in the educational field. Joining the Educator\'s Club means becoming part of a supportive network committed to academic excellence and personal growth.  By participating in the Educator\'s Club, you will gain valuable experience that can enhance your resume and prepare you for future careers in education, counseling, and beyond. Whether you have a deep-seated interest in teaching or simply want to contribute to your community, our club offers a welcoming environment for all who are eager to learn and grow. We encourage you to take advantage of our diverse range of activities and connect with like-minded peers who share your enthusiasm for education. Our inclusive and dynamic environment ensures that every member can find their niche and make a meaningful contribution. Join us to explore new opportunities, develop your skills, and become an integral part of a community dedicated to educational advancement.', '', 'COVERPHOTO_EDUCATOR\'SCLUB.png', '2024-09-15 09:20:15', '2024-09-15 09:20:15'),
(22, 'Infotech Club', 'The Infotech Club offers an exciting and dynamic environment for students passionate about technology and innovation. Our club provides hands-on experience with the latest tech trends, from coding and app development to cybersecurity and artificial intelligence. Members have the opportunity to participate in workshops, hackathons, and tech talks led by industry experts, enhancing both their skills and their resumes. We also foster a collaborative community where students can work on projects, share ideas, and build lasting connections with like-minded peers. Joining the Infotech Club means stepping into a world of endless possibilities and career growth in the ever-evolving field of information technology.  In addition to technical skills, the Infotech Club emphasizes personal and professional development. Our members benefit from networking opportunities with local tech companies and alumni who offer valuable insights and mentorship. We organize regular events, including coding competitions and tech expos, to keep members engaged and motivated. By joining, you’ll be part of a supportive and innovative team dedicated to pushing the boundaries of technology. Don’t miss the chance to be at the forefront of tech advancements and make a meaningful impact in the field of information technology.', '', 'COVERPHOTO_INFOTECHCLUB.png', '2024-09-15 09:20:15', '2024-09-15 09:20:15'),
(23, 'Red-Cross Youth', 'The Red-Cross Youth Club is dedicated to making a positive impact both locally and globally through humanitarian efforts. As a member, you\'ll engage in exciting and meaningful activities like disaster response training, community service projects, and health awareness campaigns. You’ll gain valuable skills in leadership, teamwork, and emergency preparedness, all while contributing to the well-being of others. Our club also provides opportunities to connect with peers who share your passion for helping those in need and to build lasting friendships. Join us to be part of a supportive community committed to making a difference and enhancing your personal growth.  Being part of the Red-Cross Youth Club means being at the forefront of community and global initiatives. You\'ll have access to specialized workshops and seminars led by experienced professionals in emergency management and first aid. Additionally, the club offers numerous volunteering opportunities that not only benefit the community but also enrich your resume and academic profile. Our activities are designed to be both impactful and engaging, ensuring that you have a rewarding experience. Embrace the chance to contribute to meaningful causes and develop skills that will benefit you throughout your life.', '', 'COVERPHOTO_REDCROSSYOUTH.png', '2024-09-15 09:20:15', '2024-09-15 09:20:15'),
(24, 'NBSC Scholar\'s Society', 'The NBSC Scholar\'s Society is a vibrant community dedicated to fostering academic excellence and personal growth among students. As a member, you\'ll gain access to a network of motivated peers and experienced mentors who are passionate about helping you succeed. Our club offers a range of engaging activities, including study sessions, workshops, and guest lectures from industry professionals. Joining the Society provides you with valuable opportunities to enhance your skills, build your resume, and connect with like-minded individuals. Whether you\'re aiming for academic success or looking to develop leadership abilities, the Scholar\'s Society is the perfect platform for your growth.  Being part of the NBSC Scholar\'s Society also means participating in exciting social events and collaborative projects that make learning fun and impactful. We are committed to creating a supportive environment where each member can thrive and reach their full potential. Our club regularly organizes networking events and community service projects, giving you a chance to make a difference both academically and socially. By joining, you\'ll be part of a close-knit community that celebrates achievements and supports each other through challenges. Embrace this opportunity to join a club that values academic excellence and personal development while having fun along the way.', '', 'COVERPHOTO_NBSCSCHOLAR\'SSOCIETY.png', '2024-09-15 09:20:15', '2024-09-15 09:20:15'),
(25, 'Campus Seekers of Christ', 'Campus Seekers of Christ is a vibrant and welcoming community dedicated to spiritual growth and fellowship. Our club provides a supportive environment where students can explore their faith, engage in meaningful discussions, and build lifelong friendships. We organize regular events such as Bible studies, prayer meetings, and social gatherings that cater to diverse interests and schedules. Joining us means becoming part of a nurturing group that values personal development and collective well-being. With a focus on inclusivity and mutual support, we invite you to experience the joy and purpose that comes from being part of our community.  By participating in Campus Seekers of Christ, you will have the opportunity to deepen your understanding of your faith while contributing to various outreach initiatives. Our members actively engage in service projects and community events, making a positive impact both on and off-campus. Whether you are seeking spiritual enrichment or looking to connect with others who share your values, our club offers a platform for growth and meaningful connections. We encourage students from all backgrounds and beliefs to join us in our journey of faith and service. Discover how being part of our club can enrich your college experience and provide a sense of belonging.', '', 'COVERPHOTO_CAMPUSSEEKERSOFCHRIST.png', '2024-09-21 13:40:18', '2024-09-21 13:40:18'),
(42, 'Strings and Symbols', 'The Strings and Symbols Club at NBSC College is a vibrant student organization dedicated to exploring the art and science of music and mathematics. With a unique blend of creative expression and analytical thinking, the club serves as a space for students passionate about music, instruments, composition, and mathematical concepts. Members of the Strings and Symbols Club engage in collaborative music sessions, performances, and workshops that highlight the connection between patterns in music and mathematical theories.  In addition to fostering musical talent, the club provides a platform for academic discussions on the intersection of math and music. The club regularly organizes events such as performances, math-themed music challenges, and guest lectures from experts in both fields. By combining creativity with critical thinking, the Strings and Symbols Club aims to nurture a well-rounded skill set among its members, encouraging them to appreciate the harmony between numbers and notes. Through its activities, the club cultivates an inclusive environment where students can freely express their musical and mathematical interests while enhancing their academic and artistic skills.', '', 'COVERPHOTO_STRINGSANDSYMBOLS.png', '2024-09-21 13:40:18', '2024-09-21 13:40:18'),
(43, 'YASM', 'The Youth Advocates for Sustainable Movements (YASM) Club at NBSC College is a student-driven organization dedicated to promoting environmental sustainability and social responsibility. Its members are passionate about addressing pressing global issues such as climate change, waste management, and sustainable development. The club organizes various activities, including environmental clean-up drives, tree-planting events, and sustainability workshops, to raise awareness and engage the NBSC community in meaningful action.  YASM also collaborates with local organizations and environmental groups to implement long-term initiatives aimed at reducing the campus\' ecological footprint. By encouraging students to adopt eco-friendly habits and participate in sustainable practices, the YASM Club fosters a culture of environmental stewardship. Through its efforts, the club aims to inspire the next generation of leaders to champion sustainability both on campus and in the wider community.', '', 'COVERPHOTO_DEFAULT.png', '2024-09-21 13:40:18', '2024-09-21 13:40:18'),
(44, 'Ballpoint Publication', 'The Ballpoint Publication Club at NBSC College is a creative platform for students passionate about writing, journalism, and media. This student-led organization offers members the opportunity to engage in various forms of literary expression, from news reporting and feature writing to poetry and creative nonfiction. The club regularly publishes a student newsletter and manages digital content that highlights campus events, student achievements, and pressing issues within the college community. Through collaborative efforts, members of the Ballpoint Publication Club develop their skills in writing, editing, and multimedia production.  In addition to producing high-quality publications, the Ballpoint Publication Club plays an important role in fostering a culture of communication and critical thinking across campus. The club organizes writing workshops, seminars, and discussions on journalism ethics and media literacy, helping students improve their craft while staying informed about the responsibilities of media in society. By providing a space for student voices to be heard and stories to be told, the Ballpoint Publication Club enriches the intellectual and creative life of NBSC College.', '', 'COVERPHOTO_BALLPOINTPUBLICATION.png', '2024-09-22 03:54:57', '2024-09-22 03:54:57'),
(45, 'Campus Bible Fellowship', 'The Campus Bible Fellowship (CBF) at NBSC College is a student-led organization dedicated to fostering spiritual growth, fellowship, and biblical understanding among students. The CBF provides a welcoming environment where students can come together for Bible study, prayer, and meaningful discussions on faith-related topics. Regular meetings offer opportunities for reflection, learning, and the strengthening of personal relationships with God.   In addition to spiritual nourishment, the CBF organizes community outreach programs, aiming to make a positive impact both on campus and beyond. These initiatives include service projects, charity drives, and collaboration with other Christian groups. The club also holds special events such as retreats and fellowship gatherings, providing members with deeper connections to their faith and each other. The Campus Bible Fellowship serves as a supportive space for students seeking to explore and grow in their faith journey while building lasting friendships with like-minded peers.', '', 'COVERPHOTO_BIBLICALCAMPUSMINISTRY.png', '2024-09-23 03:55:18', '2024-09-23 03:55:18'),
(156, 'Song Writers Clubs', 'a', '', 'club_66fd06f4466f9.jpg', '2024-10-01 11:28:07', '2024-10-02 08:40:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clubs_and_moderators`
--

CREATE TABLE `tbl_clubs_and_moderators` (
  `clubmod_id` int(20) NOT NULL,
  `club_id` int(20) NOT NULL,
  `moderator_id` int(20) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_clubs_and_moderators`
--

INSERT INTO `tbl_clubs_and_moderators` (`clubmod_id`, `club_id`, `moderator_id`, `dateAdded`, `dateModified`) VALUES
(1, 1, 22230001, '2022-08-18 03:19:13', '2024-10-02 12:24:27'),
(2, 1, 22230002, '2022-08-18 03:19:13', '2024-10-02 12:24:34'),
(3, 2, 22230003, '2022-05-18 11:05:05', '2024-10-02 12:30:25'),
(4, 3, 23240001, '2023-09-18 12:51:22', '2024-10-02 12:39:22'),
(5, 4, 23240002, '2023-11-18 12:51:22', '2024-10-02 12:40:32'),
(6, 5, 23240003, '2023-12-18 12:51:22', '2024-10-02 12:41:09'),
(7, 6, 23240004, '2024-01-18 12:51:22', '2024-10-02 12:42:09'),
(8, 7, 23240005, '2024-02-14 14:55:34', '2024-10-02 12:42:37'),
(9, 8, 23240006, '2024-03-15 02:05:15', '2024-10-02 12:43:12'),
(10, 9, 23240007, '2024-03-15 02:05:15', '2024-10-02 12:43:50'),
(11, 10, 22230001, '2024-05-15 02:05:15', '2024-10-02 12:31:00'),
(12, 11, 24250001, '2024-08-17 01:53:24', '2024-10-02 12:45:33'),
(13, 12, 24250002, '2024-08-17 01:53:24', '2024-10-02 12:45:57'),
(14, 13, 24250003, '2024-08-17 01:53:24', '2024-10-02 12:47:05'),
(15, 14, 24250004, '2024-08-17 01:53:24', '2024-10-02 12:49:03'),
(16, 15, 24250005, '2024-08-17 01:53:24', '2024-10-02 12:49:24'),
(17, 16, 24250006, '2024-08-17 01:53:24', '2024-10-02 12:49:43'),
(18, 17, 24250007, '2024-08-17 01:53:24', '2024-10-02 12:50:05'),
(19, 18, 24250008, '2024-08-17 01:53:24', '2024-10-02 12:50:22'),
(20, 19, 24250009, '2024-08-17 01:53:24', '2024-10-02 12:50:45'),
(22, 21, 23240005, '2024-09-15 09:20:15', '2024-10-02 12:42:44'),
(23, 22, 24250010, '2024-09-15 09:20:15', '2024-10-02 12:52:14'),
(24, 23, 24250011, '2024-09-15 09:20:15', '2024-10-02 12:54:05'),
(25, 24, 24250012, '2024-09-15 09:20:15', '2024-10-02 12:54:34'),
(26, 25, 24250013, '2024-09-21 13:40:18', '2024-10-02 12:54:46'),
(27, 42, 24250009, '2024-09-21 13:40:18', '2024-10-02 12:51:34'),
(28, 43, 24250018, '2024-09-21 13:40:18', '2024-10-02 12:56:37'),
(29, 44, 24250019, '2024-09-22 03:54:57', '2024-10-02 12:56:50'),
(30, 45, 24250020, '2024-09-23 03:55:18', '2024-10-02 12:57:05'),
(31, 5, 24250014, '2024-09-25 01:25:04', '2024-10-02 12:55:07'),
(32, 10, 24250015, '2024-09-25 01:25:04', '2024-10-02 12:55:46'),
(33, 13, 24250016, '2024-09-25 01:25:04', '2024-10-02 12:56:01'),
(34, 17, 24250017, '2024-09-25 01:25:04', '2024-10-02 12:56:20'),
(170, 3, 24250021, '2024-09-25 01:25:04', '2024-10-02 12:57:29'),
(228, 156, 22230001, '2024-10-02 14:07:01', '2024-10-02 14:07:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_club_recommendations`
--

CREATE TABLE `tbl_club_recommendations` (
  `recommendation_id` int(20) NOT NULL,
  `club_id` int(20) NOT NULL,
  `department` varchar(200) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_club_recommendations`
--

INSERT INTO `tbl_club_recommendations` (`recommendation_id`, `club_id`, `department`, `dateAdded`, `dateModified`) VALUES
(1, 1, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(2, 2, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(3, 3, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(4, 4, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(5, 5, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(6, 6, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(7, 7, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(8, 8, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(9, 8, 'BSBA', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(10, 9, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(11, 10, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(12, 11, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(13, 12, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(14, 13, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(15, 14, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(16, 15, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(17, 16, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(18, 17, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(19, 18, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(20, 19, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(21, 21, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(22, 18, 'CCS', '2024-09-21 10:14:57', '2024-09-23 16:12:47'),
(23, 23, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(24, 24, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(25, 25, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(26, 42, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(27, 43, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(28, 44, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(29, 45, 'ALL', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(31, 22, 'CCS', '2024-09-23 16:10:50', '2024-09-23 16:14:25'),
(32, 7, 'CCS', '2024-09-23 16:10:50', '2024-09-23 16:10:50'),
(33, 8, 'CCS', '2024-09-23 16:11:22', '2024-09-23 16:11:22'),
(34, 10, 'CCS', '2024-09-23 16:11:22', '2024-09-23 16:11:22'),
(35, 12, 'CCS', '2024-09-23 16:12:31', '2024-09-23 16:12:31'),
(36, 16, 'CCS', '2024-09-23 16:12:31', '2024-09-23 16:12:31'),
(37, 2, 'CCS', '2024-09-23 16:13:46', '2024-09-23 16:14:28'),
(38, 1, 'BSBA', '2024-09-23 16:15:27', '2024-09-23 16:15:27'),
(39, 2, 'BSBA', '2024-09-23 16:15:27', '2024-09-23 16:15:27'),
(40, 6, 'BSBA', '2024-09-23 16:15:55', '2024-09-23 16:15:55'),
(41, 7, 'BSBA', '2024-09-23 16:15:55', '2024-09-23 16:15:55'),
(42, 10, 'BSBA', '2024-09-23 16:16:16', '2024-09-23 16:16:16'),
(43, 12, 'BSBA', '2024-09-23 16:16:16', '2024-09-23 16:16:16'),
(44, 16, 'BSBA', '2024-09-23 16:16:34', '2024-09-23 16:16:34'),
(45, 17, 'BSBA', '2024-09-23 16:16:34', '2024-09-23 16:16:34'),
(46, 18, 'BSBA', '2024-09-23 16:16:54', '2024-09-23 16:16:54'),
(47, 19, 'BSBA', '2024-09-23 16:16:54', '2024-09-23 16:16:54'),
(48, 23, 'BSBA', '2024-09-23 16:17:22', '2024-09-23 16:17:22'),
(49, 42, 'BSBA', '2024-09-23 16:17:22', '2024-09-23 16:17:22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_club_requests`
--

CREATE TABLE `tbl_club_requests` (
  `request_id` int(20) NOT NULL,
  `clubName` varchar(200) NOT NULL,
  `goal` text NOT NULL,
  `mission` text NOT NULL,
  `vision` text NOT NULL,
  `activities` text NOT NULL,
  `status` varchar(200) DEFAULT 'pending',
  `coverPhoto` varchar(200) NOT NULL,
  `requestLetter` varchar(255) DEFAULT NULL,
  `dateDecided` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dateRequested` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `student_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_club_requests`
--

INSERT INTO `tbl_club_requests` (`request_id`, `clubName`, `goal`, `mission`, `vision`, `activities`, `status`, `coverPhoto`, `requestLetter`, `dateDecided`, `dateModified`, `dateRequested`, `student_id`) VALUES
(1, 'Bikers Club', 'To promote safe and responsible biking among members.', 'To create a supportive community for biking enthusiasts, encouraging exploration and camaraderie.', 'A vibrant community where biking fosters friendship and adventure.', 'Group rides, safety workshops, charity bike events, and maintenance clinics.', 'disapproved', 'COVERPHOTO_BIKERS.png', NULL, '2024-09-27 05:07:27', '2024-09-27 16:26:48', '2024-08-27 08:29:44', 20191124),
(2, 'Pet Lovers Club', 'To advocate for animal welfare and responsible pet ownership.', 'To unite pet lovers and promote education about pet care and adoption.', 'A community where every pet is loved and cared for.', 'Pet adoption drives, educational seminars, volunteer opportunities at shelters, and pet care workshops.', 'pending', 'COVERPHOTO_PETLOVERS.png', 'request_letter_66f6d0b9df37c.pdf', '2024-09-27 05:07:27', '2024-09-27 15:35:21', '2024-09-27 15:35:21', 20191124),
(3, 'Multimedia Productions', 'To enhance members\' skills in multimedia creation and production.', 'To provide a platform for creativity and collaboration in multimedia projects.', 'A hub for innovative storytelling through various media.', 'Film screenings, workshops on video editing, collaborative projects, and guest speaker events.', 'approved', 'COVERPHOTO_MULTIMEDIA.png', NULL, '2024-09-27 05:07:27', '2024-09-27 08:31:08', '2024-09-27 08:31:08', 20191124),
(4, 'Cooking Club', 'The Cooking Club aims to unite culinary enthusiasts at our institution, offering a collaborative environment to explore diverse cuisines and refine cooking skills. Members will engage in hands-on cooking experiences, share recipes, and celebrate their love for food in a supportive community setting.', 'To foster a love for cooking and sharing meals within the community.', 'A culinary community that celebrates creativity and diversity in cooking.', 'The club will feature regular cooking workshops to teach various techniques and cuisines, recipe exchanges for sharing and discovering new dishes, and friendly cooking competitions to showcase members\' talents. Additional activities include hosting guest speakers from the culinary world and organizing community events to prepare and serve meals for local charities.', 'pending', 'COVERPHOTO_COOKING.png', NULL, '2024-09-27 05:07:27', '2024-09-27 08:31:35', '2024-09-27 08:31:35', 20191124),
(14, 'Agriculture Club', 'To empower students with practical knowledge and skills in sustainable agriculture, fostering innovation and leadership in farming practices while promoting environmental stewardship and food security within the community.', 'To educate members about agriculture, gardening, and environmental stewardship.', 'A community that values and practices sustainable farming for a healthier planet.', 'Workshops on gardening, farm visits, community service in local gardens, and seminars on sustainability practices.', 'pending', '66dc1206c88a5.png', NULL, '2024-09-27 05:07:27', '2024-09-27 08:32:51', '2024-09-27 08:32:51', 20191115),
(32, 'Singles Club', 'To make every single double', 'Unite all singles and pair them as they want', 'Every single can find their true love', 'Dating activites', 'disapproved', '66f65ec6b4d65.jpg', '66f65ec6b4fe8.pdf', '2024-09-27 15:40:14', '2024-09-27 15:40:14', '2024-09-27 15:40:14', 20191124),
(37, 'Song Writers Clubs', 'To foster creativity and collaboration among aspiring songwriters, helping them develop their skills and share their original music.', 'The mission of the Song Writers Club is to create a supportive community where members can learn from one another, receive constructive feedback, and showcase their work through workshops, performances, and networking opportunities.', 'To be a vibrant hub for songwriters that inspires creativity, nurtures talent, and promotes the appreciation of original music within the community.', 'Weekly Writing Workshops: Members gather to write songs collaboratively and individually, share ideas, and provide feedback.', 'approved', 'request_66f6e1ff2f4e3.png', 'request_letter_66f6e1ff2f929.pdf', '2024-09-27 11:28:16', '2024-09-27 17:28:16', '2024-09-27 17:28:16', 20191124);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comments`
--

CREATE TABLE `tbl_comments` (
  `comment_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `dateAdded` datetime DEFAULT current_timestamp(),
  `dateModified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `post_id` int(20) NOT NULL,
  `club_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_comments`
--

INSERT INTO `tbl_comments` (`comment_id`, `comment`, `dateAdded`, `dateModified`, `post_id`, `club_id`, `student_id`) VALUES
(1, 'Thank you!🎶', '2024-07-26 19:15:28', '2024-09-01 19:58:40', 3, 2, 20201179),
(2, 'Okay, Maam. Copy and noted.', '2024-07-26 19:31:10', '2024-09-01 19:58:46', 26, 12, 20191124),
(3, 'Thank you, Maam!', '2024-07-26 19:32:39', '2024-09-01 19:59:09', 24, 12, 20191124),
(4, 'Thank you for the warm welcome, Maam!', '2024-07-26 19:41:35', '2024-09-01 19:59:12', 22, 12, 20191124),
(5, 'Copy!', '2024-07-26 21:27:33', '2024-09-01 19:59:14', 26, 12, 20191124),
(6, 'Copy, Maam!', '2024-09-01 21:27:26', '2024-09-01 21:27:26', 26, 12, 20191124),
(24, 'Pardon, Sir. Not Maam.', '2024-09-01 22:29:28', '2024-09-01 22:29:28', 26, 12, 20191124),
(25, 'Thank you, Sir!', '2024-09-01 22:32:24', '2024-09-02 20:04:23', 24, 12, 20191124),
(26, 'Thank you!', '2024-09-06 15:14:09', '2024-09-06 15:14:09', 25, 16, 20201270),
(27, 'samples', '2024-09-10 10:48:32', '2024-09-14 20:43:41', 26, 12, 20191124),
(28, 'Pardon, Sir. Not Maam.', '2024-09-12 10:16:24', '2024-09-12 10:16:24', 22, 12, 20191124),
(33, 'Hello Maam.', '2024-09-12 21:01:44', '2024-09-15 07:55:28', 29, 22, 20201179),
(36, 'Thank you!', '2024-09-12 21:24:58', '2024-09-12 21:24:58', 20, 2, 20201179),
(39, 'Thank you, Maam.', '2024-09-14 14:30:51', '2024-09-15 07:55:35', 29, 22, 20191124),
(45, 'e', '2024-09-14 19:13:15', '2024-09-15 07:51:25', 29, 22, 20201179),
(67, 'yey', '2024-09-14 20:15:46', '2024-09-14 23:41:14', 29, 22, 20191124),
(70, 'Hello, Maam!', '2024-09-25 23:56:34', '2024-10-02 13:55:10', 58, 22, 20191124);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_moderators`
--

CREATE TABLE `tbl_moderators` (
  `moderator_id` int(20) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `middleName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `age` varchar(200) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `phoneNumber` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `profession` varchar(200) NOT NULL,
  `profilePic` varchar(200) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_moderators`
--

INSERT INTO `tbl_moderators` (`moderator_id`, `firstName`, `middleName`, `lastName`, `age`, `birthday`, `gender`, `email`, `password`, `phoneNumber`, `department`, `profession`, `profilePic`, `dateAdded`, `dateModified`) VALUES
(22230001, 'Cliff Amadeus', '', 'Evangelio', '', '0000-00-00', 'Male', 'cliff@gmail.com', '1', '09876543210', 'CCS', 'Instructor', 'PROFPIC_SIR_CLIFF.png', '2022-08-18 03:19:13', '2024-10-02 12:21:57'),
(22230002, 'John Mark', '', 'Liwat', '', '0000-00-00', 'Male', 'johnmark@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2022-08-18 03:19:13', '2024-10-02 12:23:08'),
(22230003, 'Blessel', '', 'Quino', '', '0000-00-00', 'Female', 'blessel@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2023-03-19 15:23:09', '2024-10-02 12:30:14'),
(23240001, 'Teofany', '', 'Siton', '', '0000-00-00', 'Female', 'teofany@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2023-09-18 12:51:22', '2024-10-02 12:39:16'),
(23240002, 'Faisah', '', 'Bacarat', '', '0000-00-00', 'Female', 'faisah@gmail.com', '1', '09876543210', 'CCS', 'Instructor', 'PROF_PIC.png', '2023-11-18 12:51:22', '2024-10-02 12:40:25'),
(23240003, 'Nekka', 'A.', 'Mondaga', '', '0000-00-00', 'Female', 'nekka@gmail.com', '1', '09876543210', 'GEC', 'Instructor', 'PROF_PIC.png', '2023-12-18 12:51:22', '2024-10-02 12:41:00'),
(23240004, 'Jee Ann', '', 'Guibone', '', '0000-00-00', 'Female', 'jeeanngmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-01-18 12:51:22', '2024-10-02 12:42:02'),
(23240005, 'Charmaine', '', 'Tapulayan', '', '0000-00-00', 'Female', 'charmaine@gmail.com', '1', '09876543210', 'TEP', 'Instructor', 'PROF_PIC.png', '2024-02-14 14:55:34', '2024-10-02 12:42:28'),
(23240006, 'Helen', '', 'Ajon', '', '0000-00-00', 'Female', 'helen@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-03-15 02:05:15', '2024-10-02 12:43:01'),
(23240007, 'Marites', '', 'Salce', '', '0000-00-00', 'Female', 'marites@gmail.com', '1', '09876543210', 'TEP', 'Instructor', 'PROF_PIC.png', '2024-03-15 02:05:15', '2024-10-02 12:43:42'),
(24250001, 'John Kevin', '', 'Artuz', '', '0000-00-00', 'Male', 'johnkevin@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:44:50'),
(24250002, 'John', '', 'Soriano', '', '0000-00-00', 'Male', 'john@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:45:46'),
(24250003, 'Cherry Mar', '', 'Tutica', '', '0000-00-00', 'Female', 'cherrymar@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:46:38'),
(24250004, 'Adonis', '', 'Onahon', '', '0000-00-00', 'Male', 'adonis@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:48:58'),
(24250005, 'Jo Agustine', '', 'Corpuz', '', '0000-00-00', 'Male', 'joaugustine@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:49:17'),
(24250006, 'Grace', '', 'Quiblat', '', '0000-00-00', 'Female', 'grace@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:49:35'),
(24250007, 'Michaela', '', 'Jamora', '', '0000-00-00', 'Female', 'michaela@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:49:57'),
(24250008, 'Edilyn', '', 'Culajara', '', '0000-00-00', 'Female', 'edilyn@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:50:16'),
(24250009, 'Anthony', '', 'Sanchez', '', '0000-00-00', 'Male', 'anthony@gmail.com', '1', '09876543210', 'GEC', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:50:41'),
(24250010, 'Marchilyn', 'A.', 'Abunda', '', '0000-00-00', 'Female', 'marchilyn@gmail.com', '1', '09876543210', 'CCS', 'Instructor', 'PROF_PIC.png', '2024-09-15 09:20:15', '2024-10-02 12:52:08'),
(24250011, 'Karl', '', 'Acosta', '', '0000-00-00', 'Male', 'karl@gmail.com', '1', '09876543210', 'Health Clinic', 'Nurse', 'PROF_PIC.png', '2024-09-15 09:20:15', '2024-10-02 12:53:58'),
(24250012, 'Rahbie', 'N.', 'Adaptar', '', '0000-00-00', 'Female', 'rahbie@gmail.com', '1', '09876543210', 'ASO', 'Head', 'PROF_PIC.png', '2024-09-15 09:20:15', '2024-10-02 12:54:29'),
(24250013, 'Roseanne', 'B.', 'Lontian', '', '0000-00-00', 'Female', 'roseanne@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-21 13:40:18', '2024-10-02 12:54:41'),
(24250014, 'John Michael', '', 'Ganzan', '', '0000-00-00', 'Male', 'johnmichael@gmail.com', '1', '09876543210', 'GEC', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:09:28', '2024-10-02 12:54:59'),
(24250015, 'Kim-Lee', 'B.', 'Domingo', '', '0000-00-00', 'Male', 'kimlee@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:09:28', '2024-10-02 12:55:38'),
(24250016, 'Milleanne Kaye', '', 'Remotigue', '', '0000-00-00', 'Female', 'milleannekaye@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:11:06', '2024-10-02 12:55:53'),
(24250017, 'Melvin', '', 'Valmoria', '', '0000-00-00', 'Male', 'melvin@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:11:06', '2024-10-02 12:56:14'),
(24250018, 'John Mark', '', 'Boyonas', '', '0000-00-00', 'Male', 'johnmark@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-21 13:40:18', '2024-10-02 12:56:25'),
(24250019, 'Cero', '', '', '', '0000-00-00', 'Male', 'cero@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-22 03:54:57', '2024-10-02 12:56:45'),
(24250020, 'Ramer', 'N.', 'Verdejo', '', '0000-00-00', 'Male', 'ramer@gmail.com', '1', '09876543210', 'TEP', 'Instructor', 'PROF_PIC.png', '2024-09-23 03:55:18', '2024-10-02 12:56:59'),
(24250021, 'sample', 's', 'sample', '', '0000-00-00', '', 'sample@gmail.com', '1', '', '', '', 'PROF_PIC.png', '2024-09-30 04:01:36', '2024-10-02 12:57:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notification_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `club_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`notification_id`, `student_id`, `club_id`, `post_id`, `is_read`, `dateAdded`) VALUES
(1, 20191124, 12, NULL, 1, '2024-09-23 07:25:34'),
(2, 20191124, 12, NULL, 1, '2024-09-23 07:25:34'),
(3, 20191124, 12, NULL, 1, '2024-09-23 07:39:27'),
(4, 20191124, 12, NULL, 1, '2024-09-23 07:39:27'),
(5, 20191124, 12, NULL, 1, '2024-09-23 07:40:17'),
(6, 20191124, 12, NULL, 1, '2024-09-23 07:40:17'),
(36, 20191124, 22, 58, 1, '2024-09-23 14:28:16'),
(37, 20201179, 22, 58, 0, '2024-09-23 14:28:16'),
(82, 20191124, 1, 71, 0, '2024-10-02 05:28:01'),
(83, 20191115, 1, 71, 0, '2024-10-02 05:28:01'),
(84, 20190000, 1, 71, 0, '2024-10-02 05:28:01'),
(85, 20211524, 1, 71, 0, '2024-10-02 05:28:01'),
(86, 20211525, 1, 71, 0, '2024-10-02 05:28:01'),
(87, 20211526, 1, 71, 0, '2024-10-02 05:28:01'),
(88, 20211527, 1, 71, 0, '2024-10-02 05:28:01'),
(89, 20191124, 1, 72, 0, '2024-10-02 05:31:38'),
(90, 20191115, 1, 72, 0, '2024-10-02 05:31:38'),
(91, 20190000, 1, 72, 0, '2024-10-02 05:31:38'),
(92, 20211524, 1, 72, 0, '2024-10-02 05:31:38'),
(93, 20211525, 1, 72, 0, '2024-10-02 05:31:38'),
(94, 20211526, 1, 72, 0, '2024-10-02 05:31:38'),
(95, 20211527, 1, 72, 0, '2024-10-02 05:31:38'),
(96, 20191124, 1, 73, 0, '2024-10-02 05:38:20'),
(97, 20191115, 1, 73, 0, '2024-10-02 05:38:20'),
(98, 20190000, 1, 73, 0, '2024-10-02 05:38:20'),
(99, 20211524, 1, 73, 0, '2024-10-02 05:38:20'),
(100, 20211525, 1, 73, 0, '2024-10-02 05:38:20'),
(101, 20211526, 1, 73, 0, '2024-10-02 05:38:20'),
(102, 20211527, 1, 73, 0, '2024-10-02 05:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_officers`
--

CREATE TABLE `tbl_officers` (
  `officer_id` int(20) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `middleName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `position` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `profilePic` varchar(200) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_officers`
--

INSERT INTO `tbl_officers` (`officer_id`, `firstName`, `middleName`, `lastName`, `position`, `type`, `department`, `profilePic`, `dateAdded`, `dateModified`) VALUES
(1, 'Kenneth LLoyd', '', 'Licuanan', 'Governor', 'SBO', 'CCS', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 14:12:12'),
(2, 'Lady Doone', '', 'Bahaynon', 'Vice-Governor', 'SBO', 'CCS', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 14:12:20'),
(3, 'Pauline May', '', 'Coming', 'Secretary', 'SBO', 'CCS', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 13:07:59'),
(4, 'Ahleah', '', 'Almonte', 'Treasurer', 'SBO', 'CCS', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 13:08:21'),
(5, 'June', '', 'Sabaldana', 'Auditor', 'SBO', 'CCS', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 13:08:46'),
(6, 'Janette', '', 'Dulfo', 'PIO', 'SBO', 'CCS', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 13:09:05'),
(7, 'Jieselle', '', 'Vicariato', 'Governor', 'SBO', 'TEP', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 14:10:30'),
(8, 'Joshua Mikko', '', 'Quino', 'Vice-Governor', 'SBO', 'TEP', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 14:10:37'),
(9, 'Kristine Joy', '', 'Onahon', 'First Year Councilor', 'SBO', 'TEP', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 14:11:23'),
(10, 'Mark', '', 'Paradero', 'Second Year Councilor', 'SBO', 'TEP', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 14:11:44'),
(11, 'Sophia', '', 'Sombrio', 'Third Year Councilor', 'SBO', 'TEP', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 14:11:52'),
(12, 'Jun Arthur', '', 'Binayao', 'Fourth Year Councilor', 'SBO', 'TEP', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 14:12:00'),
(13, 'Firstname', 'Middlename', 'Lastname', 'Position', 'SBO', 'BSBA', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 13:02:07'),
(14, 'Firstname', 'Middlename', 'Lastname', 'Position', 'SBO', 'BSBA', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 13:02:11'),
(15, 'Firstname', 'Middlename', 'Lastname', 'Position', 'SBO', 'BSBA', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 13:02:12'),
(16, 'Firstname', 'Middlename', 'Lastname', 'Position', 'SBO', 'BSBA', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 13:02:14'),
(17, 'Firstname', 'Middlename', 'Lastname', 'Position', 'SBO', 'BSBA', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 13:02:16'),
(18, 'Firstname', 'Middlename', 'Lastname', 'Position', 'SBO', 'BSBA', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 13:02:19'),
(19, 'Firstname', 'Middlename', 'Lastname', 'Position', 'CSG', '', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 07:47:07'),
(20, 'Firstname', 'Middlename', 'Lastname', 'Position', 'CSG', '', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 07:47:07'),
(21, 'Firstname', 'Middlename', 'Lastname', 'Position', 'CSG', '', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 07:47:07'),
(22, 'Firstname', 'Middlename', 'Lastname', 'Position', 'CSG', '', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 07:48:15'),
(23, 'Firstname', 'Middlename', 'Lastname', 'Position', 'CSG', '', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 07:47:07'),
(25, 'Firstname', 'Middlename', 'Lastname Lastname', 'Position', 'CSG', '', 'PROF_PIC.png', '2024-09-12 23:32:40', '2024-09-13 14:13:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posts`
--

CREATE TABLE `tbl_posts` (
  `post_id` int(20) NOT NULL,
  `post` text NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `club_id` int(20) NOT NULL,
  `moderator_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_posts`
--

INSERT INTO `tbl_posts` (`post_id`, `post`, `dateAdded`, `dateModified`, `club_id`, `moderator_id`) VALUES
(1, 'quick response post 1', '2024-07-18 03:26:19', '2024-10-02 12:34:21', 1, 22230001),
(2, 'quick response post 2', '2024-07-18 03:30:19', '2024-10-02 12:34:41', 1, 22230001),
(3, '🎵 Welcome to the NBSC Band! 🎵\r\n\r\nHello NBSC Band members and music enthusiasts,\r\n\r\nWelcome to our official NBSC Band club page! We are thrilled to have you join our musical family. Whether you play an instrument, sing, or simply love music, there\'s a place for you here.\r\n\r\nAs the newest members of our band, you are now part of a vibrant community that values creativity, collaboration, and the joy of making music together. Our club is not just about playing instruments; it\'s about creating unforgettable experiences, forming lifelong friendships, and expressing ourselves through the universal language of music.\r\n\r\nHere’s what you can look forward to:\r\n\r\n🎶 Weekly Jam Sessions: Every [insert day], join us for a fun and relaxed jam session where we can play our favorite tunes, learn new ones, and just enjoy making music together.\r\n\r\n🎤 Workshops and Masterclasses: Improve your skills with workshops led by talented musicians and guest artists. From technique to performance tips, there’s always something new to learn.\r\n\r\n🎸 Performances and Gigs: Showcase your talent at various events throughout the year. Whether it’s a school concert, community event, or a spontaneous performance, there are plenty of opportunities to shine.\r\n\r\n👥 Community and Friendship: Meet fellow music lovers, make new friends, and be part of a supportive community that celebrates each other’s growth and achievements.\r\n\r\n📅 Exciting Events: Stay tuned for our upcoming events, including music competitions, collaborative projects with other clubs, and much more!\r\n\r\nYour First Steps:\r\n\r\nIntroduce Yourself: Comment below with your name, the instrument you play or your favorite genre of music, and what you’re looking forward to the most in our band.\r\n\r\nGet Involved: Check out our calendar for upcoming events and practice sessions. We encourage you to participate as much as you can.\r\n\r\nStay Connected: Follow our social media pages [insert links] to stay updated on the latest news and events.\r\n\r\nRemember, whether you’re a seasoned musician or just starting out, everyone is welcome in the NBSC Band. Let’s make beautiful music and unforgettable memories together!\r\n\r\nIf you have any questions or need assistance, feel free to reach out to [Contact Person] or any of our club officers.\r\n\r\nWelcome aboard, and let’s rock the NBSC Band!\r\n\r\n#NBSCBand #MusicLovers #JoinTheBand #Welcome', '2024-07-18 03:26:19', '2024-10-02 12:36:41', 2, 22230003),
(4, '**🎶 Hello NBSC Band! 🎶**\r\n\r\nWe’re thrilled to have you join our musical journey! Get ready for amazing practices, fun performances, and lots of opportunities to showcase your talents. \r\n\r\nLet’s make some unforgettable music together! 🎵\r\n\r\nStay tuned for upcoming events and updates!\r\n\r\n---', '2024-07-18 03:30:19', '2024-10-02 12:36:45', 2, 22230003),
(5, 'mas-amicus post 1', '2024-07-18 03:26:19', '2024-10-02 12:39:35', 3, 23240001),
(6, 'mas-amicus post 2', '2024-07-26 03:30:19', '2024-10-02 12:39:38', 3, 23240001),
(7, 'quick response post 3', '2024-07-18 03:31:19', '2024-10-02 12:34:46', 1, 22230001),
(8, 'quick response post 4', '2024-07-18 03:35:19', '2024-10-02 12:37:12', 1, 22230002),
(9, 'quick response post 5', '2024-07-19 03:26:19', '2024-10-02 12:34:49', 1, 22230001),
(10, 'quick response post 6', '2024-07-19 03:30:19', '2024-10-02 12:34:52', 1, 22230001),
(11, 'quick response post 7', '2024-07-20 03:26:19', '2024-10-02 12:34:54', 1, 22230001),
(12, 'dramatic post 1', '2024-07-18 03:46:19', '2024-10-02 12:41:38', 5, 23240003),
(13, 'muslim post 1', '2024-07-18 03:27:19', '2024-10-02 12:40:47', 4, 23240002),
(16, 'muslim post 2', '2024-07-18 04:26:19', '2024-10-02 12:40:49', 4, 23240002),
(20, '🌟 NBSC Band Update 🌟 A huge shoutout to everyone for the fantastic turnout at our first rehearsal! Your energy and enthusiasm were amazing. Next practice is on August 1, 2024 at 9:00am—don’t miss it! Keep your instruments ready and your spirits high! 🎺🥁🎻', '2024-07-26 03:26:19', '2024-10-02 12:36:52', 2, 22230003),
(21, 'Welcome to the NBSC Mountaineering Society! 🏞️ Hello Adventurers! We\'re thrilled to have you join us on this exhilarating journey of exploration and adventure. Whether you\'re a seasoned mountaineer or new to the thrill of climbing, our club is here to support and inspire you. Get ready for exciting hikes, breathtaking views, and unforgettable experiences. Stay tuned for upcoming events, meetups, and tips to help you reach new heights. Let\'s conquer mountains together and make incredible memories! Happy climbing! Your Mountaineering Club Team 🌄', '2024-07-18 03:16:19', '2024-10-02 12:34:57', 10, 22230001),
(22, 'Welcome to the NBSC ARTS Society! 🎨\r\n\r\nHello Creatives!\r\n\r\nWe’re excited to welcome you to the vibrant world of the ARTS Society! Whether you’re a painter, sculptor, writer, or just someone with a passion for the arts, you’ve come to the right place.\r\n\r\nPrepare to dive into a whirlwind of creativity, inspiration, and collaboration. We have a fantastic lineup of workshops, exhibitions, and creative sessions coming your way. Let\'s explore new artistic horizons together, share our talents, and celebrate the beauty of art in all its forms.\r\n\r\nStay tuned for updates and events—your next masterpiece might just be around the corner!\r\n\r\nLet’s create something amazing together!\r\n\r\nThe ARTS Society Team 🎭', '2024-07-18 03:26:19', '2024-10-02 12:46:23', 12, 24250002),
(24, 'Hello ARTS Society Members!\r\n\r\nWe are thrilled to announce our upcoming art exhibition, \"Colors of Imagination,\" which will showcase the diverse and vibrant works of our talented artists.\r\n\r\nEvent Details:\r\n\r\nDate: August 15, 2024\r\nTime: 2:00 PM - 6:00 PM\r\nVenue: NBSC Art Gallery, Main Campus\r\nTheme: Abstract Art and Modern Impressions\r\nHighlights:\r\n\r\nGuest Speaker: Renowned artist and alumnus, John Doe, will be sharing his journey and insights into the world of abstract art.\r\nLive Art Demonstration: Watch live as artists create their masterpieces right before your eyes.\r\nInteractive Sessions: Participate in Q&A sessions with the artists and learn more about their techniques and inspirations.\r\nArt Sale: Get a chance to purchase unique artworks and support our local artists.\r\nContact Information:\r\n\r\nEmail: artssociety@nbsc.edu\r\nPhone: (123) 456-7890\r\nDon\'t miss this opportunity to immerse yourself in the world of abstract art and connect with fellow art enthusiasts. We look forward to seeing you there!\r\n\r\nBest regards,\r\n\r\nArts Moderator\r\nModerator, ARTS Society\r\nNBSC College', '2024-07-18 03:30:19', '2024-10-02 12:46:25', 12, 24250002),
(25, 'Hello Everyone!\r\n\r\nWelcome to the Sports Society at NBSC! We’re excited to have you join us for an action-packed year of sports, fitness, and fun. Whether you\'re a seasoned athlete or just looking to get active, there\'s something for everyone.\r\n\r\nStay tuned for upcoming events, activities, and opportunities to get involved. Let’s make this year a winning one!\r\n\r\nBest Regards,\r\nSports Moderator\r\nModerator, Sports Society\r\nNBSC College', '2024-07-18 02:06:28', '2024-10-02 12:49:50', 16, 24250006),
(26, 'Paging: Ryan P. Cepada to report to the office right now. Thank you!', '2024-07-26 03:26:19', '2024-10-02 12:46:29', 12, 24250002),
(29, 'Welcome to the NBSC Infotech Club! 💻\r\n\r\nHello Tech Enthusiasts! We\'re thrilled to welcome you to the dynamic world of the Infotech Club! Whether you\'re passionate about coding, cybersecurity, AI, or just tech in general, you\'ve landed in the right place. Get ready for a series of exciting workshops, hackathons, and tech talks designed to fuel your curiosity and expand your skills. Together, we\'ll dive into the latest technologies, tackle real-world challenges, and collaborate on innovative projects. Stay tuned for updates and events—your next tech breakthrough could be just around the corner! Let’s code, create, and revolutionize the tech world together!\r\n\r\nThe Infotech Club Team 🚀', '2024-09-12 11:58:17', '2024-10-02 12:52:26', 22, 24250010),
(58, 'hi', '2024-09-23 14:28:16', '2024-10-02 12:52:31', 22, 24250010),
(71, 'Hi everyone!', '2024-10-02 05:28:01', '2024-10-02 12:37:20', 1, 22230002),
(72, 'hi', '2024-10-02 05:31:38', '2024-10-02 12:37:22', 1, 22230002),
(73, 'hello', '2024-10-02 05:38:20', '2024-10-02 12:37:25', 1, 22230002);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_registration`
--

CREATE TABLE `tbl_registration` (
  `registration_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL,
  `question1` text NOT NULL,
  `question2` text NOT NULL,
  `question3` text NOT NULL,
  `status` varchar(200) NOT NULL,
  `dateApplied` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateApproved` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `club_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_registration`
--

INSERT INTO `tbl_registration` (`registration_id`, `student_id`, `question1`, `question2`, `question3`, `status`, `dateApplied`, `dateApproved`, `dateModified`, `club_id`) VALUES
(1, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2022-08-20 03:19:13', '2024-09-26 11:37:15', '2024-09-26 11:37:15', 1),
(2, 20201270, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-08-17 01:53:24', '2024-08-21 01:53:24', '2024-09-26 09:53:24', 16),
(3, 20211521, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-08-20 01:53:24', '2024-08-21 01:53:24', '2024-09-26 09:53:24', 14),
(4, 20201179, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2023-04-19 15:23:09', '2023-04-23 15:23:09', '2024-09-26 09:53:24', 2),
(5, 20191115, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '2024-09-15 09:20:15', '2024-09-15 09:20:15', '2024-09-26 09:53:24', 21),
(6, 20201270, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '2022-08-20 03:19:13', '2022-08-20 03:19:13', '2024-09-26 09:53:24', 1),
(7, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '2022-08-20 03:19:13', '2024-09-26 11:37:15', '2024-09-30 16:04:56', 4),
(8, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '2024-05-15 02:05:15', '2024-09-26 13:27:05', '2024-09-26 13:27:17', 10),
(9, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-15 09:20:15', '2024-09-26 11:37:15', '2024-09-26 11:37:15', 22),
(10, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '2022-08-20 03:19:13', '2024-09-26 11:37:15', '2024-09-30 16:04:43', 4),
(11, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '2022-08-20 03:19:13', '2024-09-26 11:37:15', '2024-09-30 16:04:39', 4),
(12, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '2024-09-25 13:40:18', '2024-09-26 11:37:15', '2024-09-26 11:38:18', 25),
(13, 20201179, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-15 09:20:15', '2024-09-18 09:20:15', '2024-09-26 09:53:24', 22),
(14, 20191115, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2022-08-20 03:19:13', '2022-08-22 03:19:13', '2024-09-26 09:53:24', 1),
(16, 20201270, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '2024-09-15 09:20:15', '2024-09-18 09:20:15', '2024-09-26 09:53:24', 22),
(17, 20190000, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-03-15 06:26:41', '2024-03-15 06:27:46', '2024-09-26 09:53:24', 1),
(18, 20211524, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-16 04:23:09', '2024-09-16 04:23:09', '2024-09-26 09:53:24', 1),
(19, 20211525, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-16 04:25:46', '2024-09-16 04:26:20', '2024-09-26 09:53:24', 1),
(20, 20211526, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-16 04:28:19', '2024-09-16 04:28:19', '2024-09-26 09:53:24', 1),
(21, 20211527, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-16 04:29:59', '2024-09-16 04:29:59', '2024-09-26 09:53:24', 1),
(22, 20211521, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '2024-05-15 02:05:15', '2024-09-26 14:05:45', '2024-09-26 14:05:58', 10),
(23, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '2024-05-15 02:05:15', '2024-09-26 11:33:52', '2024-09-26 11:33:52', 10),
(24, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '2024-09-18 07:49:09', '2024-09-26 11:33:52', '2024-09-26 11:34:35', 1),
(25, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '2024-09-26 12:14:43', '2024-09-30 16:31:18', '2024-09-30 16:31:49', 4),
(26, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '2024-09-26 12:16:26', '2024-09-30 16:31:18', '2024-09-30 16:31:55', 4),
(28, 111, 'a', 'a', 'a', 'pending', '2024-09-30 16:23:44', '2024-10-01 10:06:42', '2024-10-01 10:07:15', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_registration_old`
--

CREATE TABLE `tbl_registration_old` (
  `registration_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `middleName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `age` varchar(200) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(200) NOT NULL,
  `instiEmail` varchar(200) NOT NULL,
  `phoneNumber` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `course` varchar(200) NOT NULL,
  `year` varchar(200) NOT NULL,
  `street` varchar(200) NOT NULL,
  `barangay` varchar(200) NOT NULL,
  `municipality` varchar(200) NOT NULL,
  `province` varchar(200) NOT NULL,
  `zipcode` varchar(200) NOT NULL,
  `profilePic` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `club_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_registration_old`
--

INSERT INTO `tbl_registration_old` (`registration_id`, `student_id`, `firstName`, `middleName`, `lastName`, `age`, `birthday`, `gender`, `instiEmail`, `phoneNumber`, `department`, `course`, `year`, `street`, `barangay`, `municipality`, `province`, `zipcode`, `profilePic`, `status`, `dateAdded`, `dateModified`, `club_id`) VALUES
(1, 20191124, 'Ryan', 'Palmares', 'Cepada', '32', '1992-09-09', 'Male', '20191124@nbsc.edu.ph', '09614588546', 'CCS', 'BSIT', '3rd Year', 'Zone 2', 'Diclum', 'Manolo Fortich', 'Province 1', '8703', 'ARSY_CIRCLE_GREEN_PNG.png', 'active', '2024-07-21 15:56:21', '2024-09-12 02:36:00', 12),
(2, 20201270, 'Andrie Jose', 'Ipulan', 'Macas', '22', '2001-12-09', 'Male', '20201270@nbsc.edu.ph', '09288134312', 'CCS', 'BSIT', '3rd Year', 'Zone 3', 'Lunocan', 'Manolo Fortich', 'Province 1', '8703', 'ANDRIE.png', 'active', '2024-07-21 12:44:57', '2024-09-06 01:41:08', 16),
(3, 20211521, 'Merlinda', 'Yepes', 'Magno', '22', '2001-12-18', 'Female', '20211521@nbsc.edu.ph', '09288134312', 'CCS', 'BSIT', '1st Year', 'ZONE 9', 'Tankulan', 'Manolo Fortich', 'Province 2', '8703', 'MERLINDA.png', 'active', '2024-07-21 12:45:16', '2024-09-06 01:41:08', 14),
(4, 20201179, 'Angela', 'Naive', 'Libay', '22', '2002-01-10', 'Female', '20201179@nbsc.edu.ph', '09173308336', 'CCS', 'BSIT', '3rd Year', 'Blk. 5 Lt.11', 'Damilag', 'Manolo Fortich', 'Bukidnon', '8703', 'ANGELA.png', 'active', '2024-07-21 12:45:29', '2024-09-06 01:41:08', 2),
(20, 20191115, 'Lovely Nicole', 'Sapong', 'Figueroa', '23', '2001-03-26', 'Female', '20191115@nbsc.edu.ph', '09097989765', 'TEP', 'BSEE', '1st Year', 'Zone 9', 'Agusan Canyon', 'Manolo Fortich', 'Bukidnon', '8703', 'LOVELYNICOLE.png', 'pending', '2024-09-06 02:13:17', '2024-09-06 02:13:17', 21),
(23, 20201270, 'andrie jose', 'ipulan', 'macas', '22', '2001-09-18', 'Male', '20201270@nbsc.edu.ph', '09288134312', 'CCS', 'BSIT', '4th Year', 'Zone-3', 'Lunocan', 'Manolo Fortich', 'Bukidnon', '8703', 'ANDRIE.png', 'pending', '2024-09-06 07:19:44', '2024-09-11 06:10:34', 1),
(27, 20191124, 'Ryan', 'Palmares', 'Cepada', '31', '1992-09-09', 'Male', '20191124@nbsc.edu.ph', '09614588546', 'TEP', 'BSEE', '1st Year', 'Zone 2', 'Agusan Canyon', 'Manolo Fortich', 'Bukidnon', '8703', 'ARSY_CIRCLE_GREEN_PNG.png', 'disapproved', '2024-08-11 00:22:23', '2024-09-11 07:54:27', 1),
(28, 20191124, 'Ryan', 'Palmares', 'Cepada', '31', '1992-09-09', 'Male', '20191124@nbsc.edu.ph', '09614588546', 'TEP', 'BSEE', '1st Year', 'Zone 2', 'Agusan Canyon', 'Manolo Fortich', 'Bukidnon', '8703', 'ARSY_CIRCLE_GREEN_PNG.png', 'pending', '2024-08-11 05:39:11', '2024-09-11 08:12:28', 10),
(29, 20191124, 'Ryan', 'Palmares', 'Cepada', '31', '1992-09-09', 'Male', '20191124@nbsc.edu.ph', '09614588546', 'TEP', 'BSEE', '1st Year', 'Zone 2', 'Agusan Canyon', 'Manolo Fortich', 'Bukidnon', '8703', 'ARSY_CIRCLE_GREEN_PNG.png', 'active', '2024-08-11 05:40:49', '2024-09-11 23:01:40', 22),
(30, 20191124, 'Ryan', 'Palmares', 'Cepada', '31', '1992-09-09', 'Male', '20191124@nbsc.edu.ph', '09614588546', 'TEP', 'BSEE', '1st Year', 'Zone 2', 'Agusan Canyon', 'Manolo Fortich', 'Bukidnon', '8703', 'ARSY_CIRCLE_GREEN_PNG.png', 'disapproved', '2024-08-11 06:40:06', '2024-10-11 07:37:32', 1),
(31, 20191124, 'Ryan', 'Palmares', 'Cepada', '31', '1992-09-09', 'Male', '20191124@nbsc.edu.ph', '09614588546', 'TEP', 'BSEE', '1st Year', 'Zone 2', 'Agusan Canyon', 'Manolo Fortich', 'Bukidnon', '8703', 'ARSY_CIRCLE_GREEN_PNG.png', 'disapproved', '2024-09-11 09:00:19', '2024-09-11 09:00:56', 1),
(32, 20191124, 'Ryan', 'Palmares', 'Cepada', '31', '1992-09-09', 'Male', '20191124@nbsc.edu.ph', '09614588546', 'TEP', 'BSEE', '1st Year', 'Zone 2', 'Agusan Canyon', 'Manolo Fortich', 'Bukidnon', '8703', 'ARSY_CIRCLE_GREEN_PNG.png', 'pending', '2024-09-11 13:47:15', '2024-09-11 22:57:09', 25);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_students`
--

CREATE TABLE `tbl_students` (
  `student_id` int(20) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `middleName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `age` varchar(200) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(200) NOT NULL,
  `instiEmail` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `phoneNumber` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `course` varchar(200) NOT NULL,
  `year` varchar(200) NOT NULL,
  `street` varchar(200) NOT NULL,
  `barangay` varchar(200) NOT NULL,
  `municipality` varchar(200) NOT NULL,
  `province` varchar(200) NOT NULL,
  `zipcode` varchar(200) NOT NULL,
  `profilePic` varchar(200) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_students`
--

INSERT INTO `tbl_students` (`student_id`, `firstName`, `middleName`, `lastName`, `age`, `birthday`, `gender`, `instiEmail`, `password`, `phoneNumber`, `department`, `course`, `year`, `street`, `barangay`, `municipality`, `province`, `zipcode`, `profilePic`, `dateAdded`, `dateModified`) VALUES
(8, 'sample', 'sample', 'sample', '1', '1111-11-11', 'Male', 'sample@gmail.com', '1', '1', 'BSBA', 'BSEE', '1st Year', 'Zone 1', 'Agusan Canyon', 'Baungon', 'Province 1', '8703', 'PROF_PIC.png', '2024-07-15 17:15:30', '2024-09-15 06:18:13'),
(111, 'hey', '', '', '', '2000-11-11', 'Male', 'hey@gmail.com', '1', '', 'CCS', '', '1st Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-16 10:17:26', '2024-09-20 10:26:13'),
(20190000, 'Sample', 'Sample', 'Sample', '32', '1992-09-09', 'Male', '20190000@nbsc.edu.ph', '1', '09614588546', 'BSBA', 'BSIT', '3rd Year', 'Zone 2', 'Diclum', 'Manolo Fortich', 'Province 1', '8703', 'PROF_PIC.png', '2024-07-19 17:14:25', '2024-09-15 06:25:42'),
(20191111, 'Sample', 'Sample', 'Sample', 'Sample', '1111-11-11', 'Female', '20191111@nbsc.edu.ph', '1', '09111111111', 'TEP', 'BSED', '1st Year', 'Zone 3', 'Agusan Canyon', 'Manolo Fortich', 'Province 1', '8703', 'PROF_PIC.png', '2024-07-15 17:42:37', '2024-08-23 07:48:39'),
(20191115, 'Lovely Nicole', 'Sapong', 'Figueroa', '23', '2001-03-26', 'Female', '20191115@nbsc.edu.ph', '1', '09097989765', 'TEP', 'BSECE', '4th Year', 'Zone 9', 'Lingion', 'Manolo Fortich', 'Province 1', '8703', 'LOVELYNICOLE.png', '2024-07-15 17:41:35', '2024-08-23 07:48:26'),
(20191124, 'Ryan', 'Palmares', 'Cepada', '32', '1992-09-09', 'Male', '20191124@nbsc.edu.ph', '1', '09614588546', 'CCS', 'BSIT', '4th Year', 'Zone 2', 'Diclum', 'Manolo Fortich', 'Province 1', '8703', 'ARSY_CIRCLE_GREEN_PNG.png', '2024-07-20 02:42:04', '2024-09-15 16:20:57'),
(20200000, 'Jomar', 'Jenisan', 'Yeri', '25', '1111-11-11', 'Male', '20211111@nbsc.edu.ph', '', '09876543210', 'CCS', 'BSIT', '4th Year', 'Zone 5', 'Agusan Canyon', 'Manolo Fortich', 'Province 1', '8703', 'PROF_PIC.png', '2024-07-15 17:42:37', '2024-08-23 07:49:34'),
(20201179, 'Angela', 'Naive', 'Libay', '23', '1111-11-11', 'Female', '20201179@nbsc.edu.ph', '1', '09876543210', 'CCS', 'BSIT', '4th Year', 'Zone 6', 'Damilag', 'Manolo Fortich', 'Province 1', '8703', 'ANGELA.png', '2024-07-15 17:40:23', '2024-09-15 16:21:17'),
(20201270, 'Andrie Jose', 'Ipulan', 'Macas', '22', '1111-11-11', 'Male', '20201270@nbsc.edu.ph', '1', '1', 'CCS', 'BSIT', '4th Year', 'Zone 0', 'Lunocan', 'Manolo Fortich', 'Bukidnon', '8703', 'ANDRIE.png', '2024-07-20 05:09:48', '2024-09-15 16:21:40'),
(20211521, 'Merlinda', 'Yepes', 'Magno', '22', '0000-00-00', 'Female', '20211521@nbsc.edu.ph', '1', '', 'CCS', 'BSIT', '4th Year', '', '', '', '', '', 'MERLINDA.png', '2024-07-20 05:10:05', '2024-09-15 16:21:52'),
(20211524, 'user_dummy_john', '', '', '', '0000-00-00', 'Male', '', '1', '', 'BSBA', '', '2nd Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-16 04:21:49', '2024-09-23 13:16:11'),
(20211525, 'user_dummy_jane', '', '', '', '0000-00-00', 'Female', '', '1', '', 'BSBA', '', '2nd Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-16 04:24:56', '2024-09-23 13:16:14'),
(20211526, 'dummy_user_carl', '', '', '', '0000-00-00', 'Male', '', '1', '', 'TEP', '', '1st Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-16 04:27:54', '2024-09-23 13:16:16'),
(20211527, 'dummy_user_jo', '', '', '', '0000-00-00', 'Male', '', '1', '', 'CCS', '', '2nd Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-16 04:29:35', '2024-09-23 13:16:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_activity_logs`
--
ALTER TABLE `tbl_activity_logs`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_clubs`
--
ALTER TABLE `tbl_clubs`
  ADD PRIMARY KEY (`club_id`);

--
-- Indexes for table `tbl_clubs_and_moderators`
--
ALTER TABLE `tbl_clubs_and_moderators`
  ADD PRIMARY KEY (`clubmod_id`);

--
-- Indexes for table `tbl_club_recommendations`
--
ALTER TABLE `tbl_club_recommendations`
  ADD PRIMARY KEY (`recommendation_id`);

--
-- Indexes for table `tbl_club_requests`
--
ALTER TABLE `tbl_club_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `tbl_moderators`
--
ALTER TABLE `tbl_moderators`
  ADD PRIMARY KEY (`moderator_id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `club_id` (`club_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `tbl_officers`
--
ALTER TABLE `tbl_officers`
  ADD PRIMARY KEY (`officer_id`);

--
-- Indexes for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `tbl_registration`
--
ALTER TABLE `tbl_registration`
  ADD PRIMARY KEY (`registration_id`);

--
-- Indexes for table `tbl_registration_old`
--
ALTER TABLE `tbl_registration_old`
  ADD PRIMARY KEY (`registration_id`);

--
-- Indexes for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_activity_logs`
--
ALTER TABLE `tbl_activity_logs`
  MODIFY `activity_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_clubs`
--
ALTER TABLE `tbl_clubs`
  MODIFY `club_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `tbl_clubs_and_moderators`
--
ALTER TABLE `tbl_clubs_and_moderators`
  MODIFY `clubmod_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT for table `tbl_club_recommendations`
--
ALTER TABLE `tbl_club_recommendations`
  MODIFY `recommendation_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tbl_club_requests`
--
ALTER TABLE `tbl_club_requests`
  MODIFY `request_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `tbl_moderators`
--
ALTER TABLE `tbl_moderators`
  MODIFY `moderator_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24250022;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `tbl_officers`
--
ALTER TABLE `tbl_officers`
  MODIFY `officer_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  MODIFY `post_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `tbl_registration`
--
ALTER TABLE `tbl_registration`
  MODIFY `registration_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_registration_old`
--
ALTER TABLE `tbl_registration_old`
  MODIFY `registration_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_students`
--
ALTER TABLE `tbl_students`
  MODIFY `student_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20211529;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD CONSTRAINT `tbl_notifications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tbl_students` (`student_id`),
  ADD CONSTRAINT `tbl_notifications_ibfk_2` FOREIGN KEY (`club_id`) REFERENCES `tbl_clubs` (`club_id`),
  ADD CONSTRAINT `tbl_notifications_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`post_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
