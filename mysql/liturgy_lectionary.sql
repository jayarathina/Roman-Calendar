-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2020 at 11:54 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `liturgy_lectionary`
--

-- --------------------------------------------------------

--
-- Table structure for table `generalcalendar`
--

CREATE TABLE `generalcalendar` (
  `feast_month` tinyint(2) NOT NULL,
  `feast_date` tinyint(2) NOT NULL,
  `feast_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `feast_ta` text COLLATE utf8_unicode_ci NOT NULL,
  `feast_type` enum('Solemnity','Feast-Lord','Feast','Mem','OpMem','Solemnity-Lord') COLLATE utf8_unicode_ci NOT NULL,
  `common` text COLLATE utf8_unicode_ci NOT NULL,
  `proper` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `generalcalendar`
--

INSERT INTO `generalcalendar` (`feast_month`, `feast_date`, `feast_code`, `feast_ta`, `feast_type`, `common`, `proper`) VALUES
(1, 1, 'Blessed Virgin Mary, the Mother of God', 'இறைவனின் அன்னையாகிய தூய கன்னி மரியா', 'Solemnity', '', ''),
(1, 2, 'Saints Basil the Great and Gregory Nazianzen, bishops and doctors', 'புனிதர்கள் பெரிய பசிலியார், நசியான்சன் கிரகோரியார் - ஆயர்கள், மறைவல்லுநர்கள்', 'Mem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(1, 3, 'The Most Holy Name of Jesus', 'இயேசுவின் திருப்பெயர்', 'OpMem', 'Votive Mass (See Lectionary IV)', ''),
(1, 7, 'Saint Raymond of Penyafort, priest', 'புனித பெனாப்போர்த்து இரேய்முந்து - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர்', ''),
(1, 13, 'Saint Hilary of Poitiers, bishop and doctor', 'புனித இலாரியார் - ஆயர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(1, 17, 'Saint Anthony of Egypt, abbot', 'புனித வனத்து அந்தோணியார் - ஆதீனத் தலைவர்', 'Mem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(1, 20, 'Saint Fabian, pope and martyr', 'புனித பபியான் - திருத்தந்தை, மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர் (திருத்தந்தை)', ''),
(1, 20, 'Saint Sebastian, martyr', 'புனித செபஸ்தியார் - மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர்', ''),
(1, 21, 'Saint Agnes, virgin and martyr', 'புனித ஆக்னெஸ் - கன்னியர், மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர் or கன்னியர்', ''),
(1, 22, 'Saint Vincent, deacon and martyr', 'புனித வின்சென்ட் - திருத்தொண்டர், மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர்', ''),
(1, 24, 'Saint Francis de Sales, bishop and doctor', 'புனித பிரான்சிஸ் சலேசியார் - ஆயர், மறைவல்லுநர்', 'Mem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(1, 25, 'The Conversion of Saint Paul, apostle', 'திருத்தூதர் பவுல் மனமாற்றம்', 'Feast', '', ''),
(1, 26, 'Saints Timothy and Titus, bishops', 'புனிதர்கள் திமொத்தேயு, தீத்து - ஆயர்கள்', 'Mem', 'மேய்ப்பர்', 'reading1'),
(1, 27, 'Saint Angela Merici, virgin', 'புனித மெர்சி ஆஞ்சலா - கன்னியர்', 'OpMem', 'கன்னியர் or புனிதர், புனிதையர் (கல்விப் பணியாற்றியோர்)', ''),
(1, 28, 'Saint Thomas Aquinas, priest and doctor', 'அக்குவினோ நகர் புனித தோமா - மறைப்பணியாளர், மறைவல்லுநர்', 'Mem', 'மறைவல்லுநர் or மேய்ப்பர்', ''),
(1, 31, 'Saint John Bosco, priest', 'புனித ஜான் போஸ்கோ - மறைப்பணியாளர்', 'Mem', 'மேய்ப்பர் or புனிதர், புனிதையர் (கல்விப் பணியாற்றியோர்)', ''),
(2, 2, 'Presentation of the Lord', 'ஆண்டவரைக் காணிக்கையாக அர்ப்பணித்தல்', 'Feast-Lord', '', ''),
(2, 3, 'Saint Ansgar, bishop', 'புனித ஆன்ஸ்காரியு - ஆயர்', 'OpMem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', ''),
(2, 3, 'Saint Blase, bishop and martyr', 'புனித பிளாசியு - ஆயர், மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(2, 5, 'Saint Agatha, virgin and martyr', 'புனித ஆகத்தா - கன்னியர், மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர் or கன்னியர்', ''),
(2, 6, 'Saints Paul Miki and companions, martyrs', 'புனிதர்கள் மறைப்பணியாளர் பவுல் மீகி, தோழர்கள் - மறைச்சாட்சியர்', 'Mem', 'மறைச்சாட்சியர்', ''),
(2, 8, 'Saint Jerome Emiliani, priest', 'புனித எரோணிமுஸ் எமிலியன் - மறைப்பணியாளர்', 'OpMem', 'புனிதர், புனிதையர் (கல்விப் பணியாற்றியோர்)', ''),
(2, 8, 'Saint Josephine Bakhita, virgin', 'புனித ஜோஸ்பின் பக்கீத்தா - கன்னியர்', 'OpMem', 'கன்னியர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(2, 10, 'Saint Scholastica, virgin', 'புனித ஸ்கொலாஸ்திக்கா - கன்னியர்', 'Mem', 'கன்னியர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(2, 11, 'Our Lady of Lourdes', 'தூய லூர்து அன்னை', 'OpMem', 'தூய கன்னி மரியா', ''),
(2, 14, 'Saints Cyril, monk, and Methodius, bishop', 'புனிதர்கள் சிரில் - துறவி, மெத்தோடியுஸ் - ஆயர்', 'Mem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்) or புனிதர், புனிதையர்', ''),
(2, 17, 'Seven Holy Founders of the Servite Order', 'தூய கன்னி மரியாவின் ஊழியர் சபையை நிறுவிய புனிதர் எழுவர்', 'OpMem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(2, 21, 'Saint Peter Damian, bishop and doctor of the Church', 'புனித பீட்டர் தமியான் - ஆயர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(2, 22, 'Chair of Saint Peter, apostle', 'திருத்தூதர் பேதுருவின் தலைமைப் பீடம்', 'Feast', '', ''),
(2, 23, 'Saint Polycarp, bishop and martyr', 'புனித பொலிக்கார்ப்பு - ஆயர், மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(3, 4, 'Saint Casimir', 'புனித கசிமீர்', 'OpMem', 'புனிதர், புனிதையர்', ''),
(3, 7, 'Saints Perpetua and Felicity, martyrs', 'புனிதையர் பெர்பெத்துவா, பெலிசித்தா - மறைச்சாட்சியர்', 'Mem', 'மறைச்சாட்சியர்', ''),
(3, 8, 'Saint John of God, religious', 'புனித இறை யோவான் - துறவி', 'OpMem', 'புனிதர், புனிதையர் (துறவியர் or அறச்செயலில் ஈடுபட்டோர்)', ''),
(3, 9, 'Saint Frances of Rome, religious', 'உரோமை நகர் புனித பிரான்சிஸ்கா - துறவி', 'OpMem', 'புனிதர், புனிதையர்', ''),
(3, 17, 'Saint Patrick, bishop', 'புனித பேட்ரிக் - ஆயர்', 'OpMem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', ''),
(3, 18, 'Saint Cyril of Jerusalem, bishop and doctor', 'எருசலேம் நகர் புனித சிரில் - ஆயர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(3, 19, 'Saint Joseph Husband of the Blessed Virgin Mary', 'புனித யோசேப்பு - தூய கன்னி மரியாவின் கணவர்', 'Solemnity', '', ''),
(3, 23, 'Saint Turibius of Mogrovejo, bishop', 'புனித மாங்ரோவேகோ துரீபியு - ஆயர்', 'OpMem', 'மேய்ப்பர்', ''),
(3, 25, 'Annunciation of the Lord', 'கிறிஸ்து பிறப்பின் அறிவிப்பு', 'Solemnity', '', ''),
(4, 2, 'Saint Francis of Paola, hermit', 'புனித பவோலா பிரான்சிஸ் - வனத்துறவி', 'OpMem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(4, 4, 'Saint Isidore, bishop and doctor of the Church', 'புனித இசிதோர் - ஆயர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(4, 5, 'Saint Vincent Ferrer, priest', 'புனித வின்சென்ட் பெரர் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', ''),
(4, 7, 'Saint John Baptist de la Salle, priest', 'லசால் நகர் புனித ஜான் பாப்டிஸ்ட் - மறைப்பணியாளர்', 'Mem', 'மேய்ப்பர் or புனிதர், புனிதையர் (கல்விப் பணியாற்றியோர்)', ''),
(4, 11, 'Saint Stanislaus, bishop and martyr', 'புனித தனிஸ்லாஸ் - ஆயர், மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(4, 13, 'Saint Martin I, pope and martyr', 'புனித முதலாம் மார்ட்டின் - திருத்தந்தை, மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர் (திருத்தந்தை)', ''),
(4, 21, 'Saint Anselm of Canterbury, bishop and doctor of the Church', 'புனித ஆன்சலம் - ஆயர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(4, 23, 'Saint Adalbert, bishop and martyr', 'புனித அடால்பெர்ட் - ஆயர், மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(4, 23, 'Saint George, martyr', 'புனித ஜார்ஜ் - மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர்', ''),
(4, 24, 'Saint Fidelis of Sigmaringen, priest and martyr', 'சிக்மரிங்கன் நகர் புனித பிதேலிஸ் - மறைப்பணியாளர், மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(4, 25, 'Saint Mark the Evangelist', 'புனித மாற்கு - நற்செய்தியாளர்', 'Feast', '', ''),
(4, 28, 'Saint Louis Grignon de Montfort, priest', 'மான்போர்ட் நகர் புனித லூயி மரிய கிரிஞ்ஞோ, மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர்', ''),
(4, 28, 'Saint Peter Chanel, priest and martyr', 'புனித பியர் சானல் - மறைப்பணியாளர், மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', ''),
(4, 29, 'Saint Catherine of Siena, virgin and doctor of the Church', 'சியன்னா நகர் புனித கேத்தரின் - கன்னியர், மறைவல்லுநர்', 'Mem', 'கன்னியர்', ''),
(4, 30, 'Saint Pius V, pope', 'புனித ஐந்தாம் பயஸ் - திருத்தந்தை', 'OpMem', 'மேய்ப்பர் (திருத்தந்தை)', ''),
(5, 1, 'Saint Joseph the Worker', 'தொழிலாளரான புனித யோசேப்பு', 'OpMem', '', 'gospel'),
(5, 2, 'Saint Athanasius, bishop and doctor', 'புனித அத்தனாசியு - ஆயர், மறைவல்லுநர்', 'Mem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(5, 3, 'Saints Philip and James, Apostles', 'புனிதர்கள் பிலிப்பு, யாக்கோபு - திருத்தூதர்கள்', 'Feast', '', ''),
(5, 12, 'Saint Pancras, martyr', 'புனித பங்கிராஸ் - மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர்', ''),
(5, 12, 'Saints Nereus and Achilleus, martyrs', 'புனிதர்கள் நெரேயு, அக்கிலேயு - மறைச்சாட்சியர்', 'OpMem', 'மறைச்சாட்சியர்', ''),
(5, 13, 'Our Lady of Fatima', 'தூய பாத்திமா அன்னை', 'OpMem', 'தூய கன்னி மரியா', ''),
(5, 14, 'Saint Matthias the Apostle', 'புனித மத்தியா - திருத்தூதர்', 'Feast', '', ''),
(5, 18, 'Saint John I, pope and martyr', 'புனித முதலாம் யோவான் - திருத்தந்தை, மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர் (திருத்தந்தை)', ''),
(5, 20, 'Saint Bernardine of Siena, priest', 'சியன்னா நகர் புனித பெர்னார்தீன் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', ''),
(5, 21, 'Saint Christopher Magallanes and companions, martyrs', 'புனிதர்கள் மறைப்பணியாளர் கிறிஸ்டோபர் மெகாலன், தோழர்கள் - மறைச்சாட்சியர்', 'OpMem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்) or மறைச்சாட்சியர்', ''),
(5, 22, 'Saint Rita of Cascia', 'காசியா சபை புனித ரீத்தா - துறவி', 'OpMem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(5, 25, 'Saint Bede the Venerable, priest and doctor', 'வணக்கத்துக்குரிய புனித பீடு - மறைப்பணியாளர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(5, 25, 'Saint Gregory VII, pope', 'புனித ஏழாம் கிரகோரி - திருத்தந்தை', 'OpMem', 'மேய்ப்பர் (திருத்தந்தை)', ''),
(5, 25, 'Saint Mary Magdalene de Pazzi, virgin', 'பாசி நகர் புனித மகதலா மரியா - கன்னியர்', 'OpMem', 'கன்னியர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(5, 26, 'Saint Philip Neri, priest', 'புனித பிலிப்பு நேரி - மறைப்பணியாளர்', 'Mem', 'மேய்ப்பர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(5, 27, 'Saint Augustine (Austin) of Canterbury, bishop', 'கான்றர்பரி நகர் புனித அகுஸ்தீன் - ஆயர்', 'OpMem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', ''),
(5, 31, 'Visitation of the Blessed Virgin Mary', 'தூய கன்னி மரியா எலிசபெத்தைச் சந்தித்தல்', 'Feast', '', ''),
(6, 1, 'Saint Justin Martyr', 'புனித ஜஸ்டின் - மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர்', ''),
(6, 2, 'Saints Marcellinus and Peter, martyrs', 'புனிதர்கள் மார்சலின், பீட்டர் - மறைச்சாட்சியர்', 'OpMem', 'மறைச்சாட்சியர்', ''),
(6, 3, 'Saints Charles Lwanga and companions, martyrs', 'புனிதர்கள் சார்லஸ் லுவாங்கா, தோழர்கள் - மறைச்சாட்சியர்', 'Mem', 'மறைச்சாட்சியர்', ''),
(6, 5, 'Saint Boniface, bishop and martyr', 'புனித போனிப்பாஸ் - ஆயர், மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர் or மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', ''),
(6, 6, 'Saint Norbert, bishop', 'புனித நார்பெர்ட் - ஆயர்', 'OpMem', 'மேய்ப்பர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(6, 9, 'Saint Ephrem, deacon and doctor', 'புனித எபிரேம் - திருத்தொண்டர், மறைவல்லுநர்', 'OpMem', 'மறைவல்லுநர்', ''),
(6, 11, 'Saint Barnabas the Apostle', 'புனித பர்னபா - திருத்தூதர்', 'Mem', '', 'reading1'),
(6, 13, 'Saint Anthony of Padua, priest and doctor', 'பதுவா நகர் புனித அந்தோனியார் - மறைப்பணியாளர், மறைவல்லுநர்', 'Mem', 'மேய்ப்பர் or மறைவல்லுநர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(6, 19, 'Saint Romuald, abbot', 'புனித ரோமுவால்து - ஆதீனத் தலைவர்', 'OpMem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(6, 21, 'Saint Aloysius Gonzaga, religious', 'புனித அலோசியுஸ் கொன்சாகா - துறவி', 'Mem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(6, 22, 'Saint Paulinus of Nola, bishop', 'புனித பவுலீனு நோலா - ஆயர்', 'OpMem', 'மேய்ப்பர்', ''),
(6, 22, 'Saints John Fisher, bishop and martyr and Thomas More, martyr', 'புனிதர்கள் ஆயர் ஜான் பிசர், தாமஸ் மூர் - மறைச்சாட்சியர்', 'OpMem', 'மறைச்சாட்சியர்', ''),
(6, 24, 'Birth of Saint John the Baptist', 'திருமுழுக்கு யோவானின் பிறப்பு', 'Solemnity', '', ''),
(6, 27, 'Saint Cyril of Alexandria, bishop and doctor', 'அலெக்சாந்திரிய நகர் புனித சிரில் - ஆயர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(6, 28, 'Saint Irenaeus, bishop and martyr', 'புனித இரனேயு - ஆயர், மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர் or மறைவல்லுநர்', ''),
(6, 29, 'Saints Peter and Paul, Apostles', 'புனிதர்கள் பேதுரு, பவுல் - திருத்தூதர்கள்', 'Solemnity', '', ''),
(6, 30, 'First Martyrs of the Church of Rome', 'உரோமைத் திருச்சபையின் முதல் மறைச்சாட்சியர்', 'OpMem', 'மறைச்சாட்சியர்', ''),
(7, 3, 'Saint Thomas the Apostle', 'புனித தோமா - திருத்தூதர்', 'Feast', '', ''),
(7, 4, 'Saint Elizabeth of Portugal', 'லுஸ்தானியா நகர் புனித எலிசபெத்து', 'OpMem', 'புனிதர், புனிதையர் (அறச்செயலில் ஈடுபட்டோர்)', ''),
(7, 5, 'Saint Anthony Zaccaria, priest', 'புனித அந்தோணி மரிய செக்கரியா - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் or புனிதர், புனிதையர் (கல்விப் பணியாற்றியோர் and துறவியர்)', ''),
(7, 6, 'Saint Maria Goretti, virgin and martyr', 'புனித மரிய கொரற்றி - கன்னியர், மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or கன்னியர்', ''),
(7, 9, 'Saint Augustine Zhao Rong and companions, martyrs', 'புனிதர்கள் அகஸ்டின் ஜாவோ ரோங்கு, தோழர்கள் - மறைச்சாட்சியர்', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(7, 11, 'Saint Benedict, abbot', 'புனித பெனடிக்ட் - ஆதீனத் தலைவர்', 'Mem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(7, 13, 'Saint Henry', 'புனித என்றி', 'OpMem', 'புனிதர், புனிதையர்', ''),
(7, 14, 'Saint Camillus de Lellis, priest', 'புனித கமில்லஸ் தெ லெல்லிஸ் - மறைப்பணியாளர்', 'OpMem', 'புனிதர், புனிதையர் (அறச்செயலில் ஈடுபட்டோர்)', ''),
(7, 15, 'Saint Bonaventure, bishop and doctor', 'புனித பொனவெந்தூர் - ஆயர், மறைவல்லுநர்', 'Mem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(7, 16, 'Our Lady of Mount Carmel', 'தூய கார்மேல் அன்னை', 'OpMem', 'தூய கன்னி மரியா', ''),
(7, 20, 'Saint Apollinaris, bishop and martyr', 'புனித அப்போலினாரிஸ் - ஆயர், மறைச்சாட்சி', 'OpMem', 'மேய்ப்பர் or மறைச்சாட்சியர்', ''),
(7, 21, 'Saint Lawrence of Brindisi, priest and doctor', 'புனித பிரிந்திசி நகர் லாரன்ஸ் - மறைப்பணியாளர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(7, 22, 'Saint Mary Magdalene', 'புனித மகதலா மரியா', 'Feast', '', ''),
(7, 23, 'Saint Birgitta, religious', 'புனித பிரிசித்தா - துறவி', 'OpMem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(7, 24, 'Saint Sharbel Makhluf, hermit', 'புனித சார்பெல் மாக்லுப் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(7, 25, 'Saint James, apostle', 'புனித யாக்கோபு - திருத்தூதர்', 'Feast', '', ''),
(7, 26, 'Saints Joachim and Anne', 'புனிதர்கள் சுவக்கீம், அன்னா - தூய மரியாவின் பெற்றோர்', 'Mem', '', ''),
(7, 29, 'Saint Martha', 'புனித மார்த்தா', 'Mem', 'புனிதர், புனிதையர்', 'gospel'),
(7, 30, 'Saint Peter Chrysologus, bishop and doctor', 'புனித பீட்டர் கிறிசோலோகு - ஆயர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(7, 31, 'Saint Ignatius of Loyola, priest', 'புனித லொயோலா இஞ்ஞாசி - மறைப்பணியாளர்', 'Mem', 'மேய்ப்பர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(8, 1, 'Saint Alphonsus Maria de Liguori, bishop and doctor of the Church', 'புனித அல்போன்ஸ் மரிய லிகோரி - ஆயர், மறைவல்லுநர்', 'Mem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(8, 2, 'Saint Eusebius of Vercelli, bishop', 'வெர்செல்லி நகர் புனித யுசேபியு - ஆயர்', 'OpMem', 'மேய்ப்பர்', ''),
(8, 2, 'Saint Peter Julian Eymard, priest', 'புனித பீட்டர் ஜூலியன் எய்மார்ட் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர்', ''),
(8, 4, 'Saint Jean Vianney (the Curé of Ars), priest', 'புனித ஜான் மரிய வியான்னி - மறைப்பணியாளர்', 'Mem', 'மேய்ப்பர்', ''),
(8, 5, 'Dedication of the Basilica of Saint Mary Major', 'தூய கன்னி மரியாவின் பேராலய நேர்ந்தளிப்பு (பனிமய அன்னை)', 'OpMem', 'தூய கன்னி மரியா', ''),
(8, 6, 'Transfiguration of the Lord', 'ஆண்டவரின் தோற்றமாற்றம்', 'Feast-Lord', '', ''),
(8, 7, 'Saint Cajetan, priest', 'புனித கயத்தான் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(8, 7, 'Saint Sixtus II, pope, and companions, martyrs', 'புனிதர்கள் திருத்தந்தை இரண்டாம் சிக்ஸ்து, தோழர்கள் - மறைச்சாட்சியர்', 'OpMem', 'மறைச்சாட்சியர்', ''),
(8, 8, 'Saint Dominic, priest', 'புனித தோமினிக் - மறைப்பணியாளர்', 'Mem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்) or புனிதர், புனிதையர் (துறவியர்)', ''),
(8, 9, 'Saint Teresa Benedicta of the Cross (Edith Stein), virgin and martyr', 'திருச்சிலுவையின் புனித தெரெசா பெனடிக்டா - கன்னியர், மறைச்சாட்சி', 'OpMem', 'கன்னியர் or மறைசாட்சியர்', ''),
(8, 10, 'Saint Lawrence, deacon and martyr', 'புனித லாரன்ஸ் - திருத்தொண்டர், மறைச்சாட்சி', 'Feast', '', ''),
(8, 11, 'Saint Clare, virgin', 'புனித கிளாரா - கன்னியர்', 'Mem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(8, 12, 'Saint Jane Frances de Chantal, religious', 'சாந்தால் நகர் புனித ஜான் பிரான்சிஸ்கா - துறவி', 'OpMem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(8, 13, 'Saints Pontian, pope, and Hippolytus, priest, martyrs', 'புனிதர்கள் திருத்தந்தை போன்சியானு, மறைப்பணியாளர் இப்போலித்து - மறைச்சாட்சியர்', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(8, 14, 'Saint Maximilian Mary Kolbe, priest and martyr', 'புனித மாக்சிமிலியன் மரிய கோல்பே - மறைப்பணியாளர், மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(8, 15, 'Assumption of the Blessed Virgin Mary', 'தூய கன்னி மரியாவின் விண்ணேற்பு', 'Solemnity', '', ''),
(8, 16, 'Saint Stephen of Hungary', 'அங்கேரி புனித ஸ்தேவான்', 'OpMem', 'புனிதர், புனிதையர்', ''),
(8, 19, 'Saint John Eudes, priest', 'புனித ஜான் யூட்ஸ் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் or புனிதர், புனிதையர்', ''),
(8, 20, 'Saint Bernard of Clairvaux, abbot and doctor of the Church', 'புனித பெர்நார்ட் - ஆதீனத் தலைவர், மறைவல்லுநர்', 'Mem', 'மறைவல்லுநர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(8, 21, 'Saint Pius X, pope', 'புனித பத்தாம் பயஸ் - திருத்தந்தை', 'Mem', 'மேய்ப்பர் (திருத்தந்தை)', ''),
(8, 22, 'Queenship of Blessed Virgin Mary', 'அரசியான தூய கன்னி மரியா', 'Mem', 'தூய கன்னி மரியா', ''),
(8, 23, 'Saint Rose of Lima, virgin', 'லீமா நகர் புனித ரோசா - கன்னியர்', 'OpMem', 'கன்னியர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(8, 24, 'Saint Bartholomew the Apostle', 'புனித பர்த்தலமேயு - திருத்தூதர்', 'Feast', '', ''),
(8, 25, 'Saint Joseph of Calasanz, priest', 'கலசான்ஸ் நகர் புனித யோசேப்பு - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் or புனிதர், புனிதையர் (கல்விப் பணியாற்றியோர்)', ''),
(8, 25, 'Saint Louis', 'புனித லூயி - மறைப்பணியாளர்', 'OpMem', 'புனிதர், புனிதையர்', ''),
(8, 27, 'Saint Monica', 'புனித மோனிக்கா', 'Mem', 'புனிதர், புனிதையர்', ''),
(8, 28, 'Saint Augustine of Hippo, bishop and doctor of the Church', 'புனித அகுஸ்தீன் - ஆயர், மறைவல்லுநர்', 'Mem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(8, 29, 'The Beheading of Saint John the Baptist, martyr', 'புனித திருமுழுக்கு யோவானின் பாடுகள்', 'Mem', '', 'gospel'),
(9, 3, 'Saint Gregory the Great, pope and doctor', 'புனித பெரிய கிரகோரி - திருத்தந்தை, மறைவல்லுநர்', 'Mem', 'மேய்ப்பர் (திருத்தந்தை) or மறைவல்லுநர்', ''),
(9, 8, 'Birth of the Blessed Virgin Mary', 'தூய கன்னி மரியாவின் பிறப்பு (ஆரோக்கிய அன்னை)', 'Feast', '', ''),
(9, 9, 'Saint Peter Claver, priest', 'புனித பீட்டர் கிளாவர் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர்', ''),
(9, 12, 'Holy Name of the Blessed Virgin Mary', 'மரியாவின் திருப்பெயர்', 'OpMem', 'தூய கன்னி மரியா', ''),
(9, 13, 'Saint John Chrysostom, bishop and doctor', 'புனித யோவான் கிறிசோஸ்தோம் - ஆயர்', 'Mem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(9, 14, 'Exaltation of the Holy Cross', 'திருச்சிலுவையின் மகிமை', 'Feast-Lord', '', ''),
(9, 15, 'Our Lady of Sorrows', 'புனித மரியாவின் துயரங்கள் (தூய வியாகுல அன்னை)', 'Mem', '', 'gospel'),
(9, 16, 'Saints Cornelius, pope, and Cyprian, bishop, martyrs', 'புனிதர்கள் திருத்தந்தை கொர்னேலியு, ஆயர் சிப்பிரியன் - மறைச்சாட்சியர்', 'Mem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(9, 17, 'Saint Robert Bellarmine, bishop and doctor', 'புனித ராபர்ட் பெல்லார்மின் - ஆயர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(9, 19, 'Saint Januarius, bishop and martyr', 'புனித சனுவாரியு - ஆயர், மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(9, 20, 'Saint Andrew Kim Taegon, priest, and Paul Chong Hasang and companions, martyrs', 'புனிதர்கள் மறைப்பணியாளர் ஆன்ரு கிம் தே கோன், பவுல் சோங் காசாங், தோழர்கள் - மறைச்சாட்சியர்', 'Mem', 'மறைசாட்சியர்', ''),
(9, 21, 'Saint Matthew the Evangelist, Apostle, Evangelist', 'புனித மத்தேயு - திருத்தூதர், நற்செய்தியாளர்', 'Feast', '', ''),
(9, 23, 'Saint Pio of Pietrelcina (Padre Pio), priest', 'புனித பியோ, மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(9, 26, 'Saints Cosmas and Damian, martyrs', 'புனிதர்கள் கோஸ்மாஸ், தமியான் - மறைச்சாட்சியர்', 'OpMem', 'மறைச்சாட்சியர்', ''),
(9, 27, 'Saint Vincent de Paul, priest', 'புனித வின்சென்ட் தே பவுல் - மறைப்பணியாளர்', 'Mem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்) or புனிதர், புனிதையர் (அறச்செயலில் ஈடுபட்டோர்)', ''),
(9, 28, 'Saint Wenceslaus, martyr', 'புனித வென்செஸ்லாஸ் - மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர்', ''),
(9, 28, 'Saints Lawrence Ruiz and companions, martyrs', 'புனிதர்கள் லாரன்ஸ் ரூய்ஸ், தோழர்கள் - மறைச்சாட்சியர்', 'OpMem', 'மறைச்சாட்சியர்', ''),
(9, 29, 'Saints Michael, Gabriel and Raphael, Archangels', 'தூய மிக்கேல், கபிரியேல், ரபேல் - அதிதூதர்கள்', 'Feast', '', ''),
(9, 30, 'Saint Jerome, priest and doctor', 'புனித எரோணிமுஸ் (ஜெரோம்) - மறைப்பணியாளர், மறைவல்லுநர்', 'Mem', 'மறைவல்லுநர் or மேய்ப்பர்', ''),
(10, 1, 'Saint Thérèse of the Child Jesus, virgin and doctor', 'குழந்தை இயேசுவின் புனித தெரேசா - கன்னியர்', 'Mem', 'கன்னியர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(10, 2, 'Guardian Angels', 'தூய காவல் தூதர்கள்', 'Mem', '', 'gospel'),
(10, 4, 'Saint Francis of Assisi', 'அசிசி நகர் புனித பிரான்சிஸ்', 'Mem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(10, 6, 'Saint Bruno, priest', 'புனித புரூனோ - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(10, 7, 'Our Lady of the Rosary', 'தூய செபமாலை அன்னை', 'Mem', 'தூய கன்னி மரியா', ''),
(10, 9, 'Saint Denis and companions, martyrs', 'புனிதர்கள் ஆயர் தியோனியுசு, தோழர்கள் - மறைச்சாட்சியர்', 'OpMem', 'மறைச்சாட்சியர்', ''),
(10, 9, 'Saint John Leonardi, priest', 'புனித யோவான் லெயோனார்ட் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் or புனிதர், புனிதையர் (அறச்செயலில் ஈடுபட்டோர்)', ''),
(10, 11, 'Saint John XXIII, pope', 'புனித இருபத்தி மூன்றாம் யோவான், திருத்தந்தை', 'OpMem', 'மேய்ப்பர் (திருத்தந்தை)', ''),
(10, 14, 'Saint Callistus I, pope and martyr', 'புனித முதலாம் கலிஸ்து - திருத்தந்தை, மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர் (திருத்தந்தை)', ''),
(10, 15, 'Saint Teresa of Jesus, virgin and doctor', 'இயேசுவின் (அவிலா நகர்) புனித தெரேசா - கன்னியர், மறைவல்லுநர்', 'Mem', 'கன்னியர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(10, 16, 'Saint Hedwig, religious', 'புனித எட்விஜ் - துறவி', 'OpMem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(10, 16, 'Saint Margaret Mary Alacoque, virgin', 'புனித மார்கரீத் மரியா அலக்கோக்கு - கன்னியர்', 'OpMem', 'கன்னியர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(10, 17, 'Saint Ignatius of Antioch, bishop and martyr', 'அந்தியோக்கு நகர் புனித இஞ்ஞாசி - ஆயர், மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(10, 18, 'Saint Luke the Evangelist', 'புனித லூக்கா - நற்செய்தியாளர்', 'Feast', '', ''),
(10, 19, 'Saint Paul of the Cross, priest', 'சிலுவையின் புனித பவுல் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(10, 19, 'Saints Jean de Brébeuf, Isaac Jogues, priests and martyrs; and their companions, martyrs', 'புனிதர்கள் மறைப்பணியாளர்கள் பிரபூபு ஜான், ஈசாக்கு ஜோகு, தோழர்கள் - மறைச்சாட்சியர்', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', ''),
(10, 22, 'Saint John Paul II, pope', 'புனித இரண்டாம் அருள் சின்னப்பர், திருத்தந்தை', 'OpMem', '', ''),
(10, 23, 'Saint John of Capistrano, priest', 'கப்பெஸ்த்தரானோ நகர் புனித யோவான் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', ''),
(10, 24, 'Saint Anthony Mary Claret, bishop', 'புனித அந்தோனி மரிய கிளாரட் - ஆயர்', 'OpMem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', ''),
(10, 28, 'Saint Simon and Saint Jude, apostles', 'புனிதர்கள் சீமோன், யூதா - திருத்தூதர்கள்', 'Feast', '', ''),
(11, 1, 'All Saints', 'புனிதர் அனைவர்', 'Solemnity', '', ''),
(11, 2, 'All Souls', 'இறந்த விசுவாசிகள் அனைவர்', 'Solemnity', '(See the readings in the Masses for the dead, below, nos. 789-793)', ''),
(11, 3, 'Saint Martin de Porres, religious', 'புனித மார்ட்டின் தெ போரஸ் - துறவி', 'OpMem', 'புனிதர், புனிதையர் (துறவியர்)', ''),
(11, 4, 'Saint Charles Borromeo, bishop', 'புனித சார்லஸ் பொரோமியோ - ஆயர்', 'Mem', 'மேய்ப்பர்', ''),
(11, 9, 'Dedication of the Lateran basilica', 'இலாத்தரன் பேராலய நேர்ந்தளிப்பு', 'Feast-Lord', 'The dedication of a church', ''),
(11, 10, 'Saint Leo the Great, pope and doctor', 'புனித பெரிய லெயோ - திருத்தந்தை, மறைவல்லுநர்', 'Mem', 'மேய்ப்பர் (திருத்தந்தை) or மறைவல்லுநர்', ''),
(11, 11, 'Saint Martin of Tours, bishop', 'தூரின் நகர் புனித மார்ட்டின் - ஆயர்', 'Mem', 'மேய்ப்பர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(11, 12, 'Saint Josaphat, bishop and martyr', 'புனித யோசபாத்து - ஆயர், மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(11, 15, 'Saint Albert the Great, bishop and doctor', 'புனித பெரிய ஆல்பர்ட் - ஆயர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(11, 16, 'Saint Gertrude the Great, virgin', 'புனித ஜெர்த்ரூது - கன்னியர்', 'OpMem', 'கன்னியர் or புனிதர், புனிதையர் (துறவியர்)', ''),
(11, 16, 'Saint Margaret of Scotland', 'ஸ்காட்லாந்து புனித மார்கரீத்', 'OpMem', 'புனிதர், புனிதையர் (அறச்செயலில் ஈடுபட்டோர்)', ''),
(11, 17, 'Saint Elizabeth of Hungary, religious', 'அங்கேரி புனித எலிசபெத்து - துறவி', 'Mem', 'புனிதர், புனிதையர் (அறச்செயலில் ஈடுபட்டோர், or துறவியர்)', ''),
(11, 18, 'Dedication of the basilicas of Saints Peter and Paul, Apostles', 'திருத்தூதர்கள் பேதுரு, பவுல் பேராலயங்களின் நேர்ந்தளிப்பு', 'OpMem', '', 'All'),
(11, 21, 'Presentation of the Blessed Virgin Mary', 'தூய கன்னி மரியாவைக் காணிக்கையாக அர்ப்பணித்தல்', 'Mem', 'தூய கன்னி மரியா', ''),
(11, 22, 'Saint Cecilia', 'புனித செசிலியா - கன்னியர், மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர் or கன்னியர்', ''),
(11, 23, 'Saint Clement I, pope and martyr', 'புனித முதலாம் கிளமெண்ட், திருத்தந்தை, மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர் (திருத்தந்தை)', ''),
(11, 23, 'Saint Columban, religious', 'புனித கொலும்பன் - ஆதீனத் தலைவர்', 'OpMem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்) or புனிதர், புனிதையர் (துறவியர்)', ''),
(11, 24, 'Saint Andrew Dung-Lac and his companions, martyrs', 'புனிதர்கள் மறைப்பணியாளர் ஆன்ரு டுங் லாக், தோழர்கள் - மறைச்சாட்சியர்', 'Mem', 'மேய்ப்பர் or மறைச்சாட்சியர்', ''),
(11, 25, 'Saint Catherine of Alexandria', 'அலெக்சாந்திரியா நகர் புனித கேத்தரின் - கன்னியர், மறைச்சாட்சி', 'OpMem', 'கன்னியர் or மறைச்சாட்சியர்', ''),
(11, 30, 'Saint Andrew the Apostle', 'புனித அந்திரேயா, திருத்தூதர்', 'Feast', '', ''),
(12, 3, 'Saint Francis Xavier, priest', 'புனித பிரான்சிஸ் சவேரியார் - மறைப்பணியாளர்', 'Mem', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', ''),
(12, 4, 'Saint John Damascene, priest and doctor', 'புனித யோவான் தமசேன் - மறைப்பணியாளர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(12, 6, 'Saint Nicholas, bishop', 'புனித நிக்கோலாஸ் - ஆயர்', 'OpMem', 'மேய்ப்பர்', ''),
(12, 7, 'Saint Ambrose, bishop and doctor', 'புனித அம்புரோஸ் - ஆயர், மறைவல்லுநர்', 'Mem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(12, 8, 'Immaculate Conception of the Blessed Virgin Mary', 'தூய கன்னி மரியாவின் அமலோற்பவம்', 'Solemnity', '', ''),
(12, 9, 'Saint Juan Diego', 'புனித ஜான் தியேகோ', 'OpMem', 'புனிதர், புனிதையர்', ''),
(12, 11, 'Saint Damasus I, pope', 'புனித முதலாம் தமசுஸ் - திருத்தந்தை', 'OpMem', 'மேய்ப்பர் (திருத்தந்தை}', ''),
(12, 12, 'Our Lady of Guadalupe', 'புனித குவாதெலுப் அன்னை', 'OpMem', 'தூய கன்னி மரியா', ''),
(12, 13, 'Saint Lucy of Syracuse, virgin and martyr', 'புனித லூசியா - கன்னியர், மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர் or கன்னியர்', ''),
(12, 14, 'Saint John of the Cross, priest and doctor', 'சிலுவையின் புனித யோவான் - மறைப்பணியாளர், மறைவல்லுநர்', 'Mem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(12, 21, 'Saint Peter Canisius, priest and doctor', 'புனித பீட்டர் கனீசியு - மறைப்பணியாளர், மறைவல்லுநர்', 'OpMem', 'மேய்ப்பர் or மறைவல்லுநர்', ''),
(12, 23, 'Saint John of Kanty, priest', 'கான்டி நகர் புனித யோவான் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர் (அறச்செயலில் ஈடுபட்டோர்)', ''),
(12, 25, 'Nativity of the Lord', 'கர்த்தரின் பிறப்பு', 'Solemnity', '', ''),
(12, 26, 'Saint Stephen, the first martyr', 'புனித ஸ்தேவான் - முதல் மறைச்சாட்சி', 'Feast', '', ''),
(12, 27, 'Saint John the Apostle and evangelist', 'புனித யோவான் - திருத்தூதர், நற்செய்தியாளர்', 'Feast', '', ''),
(12, 28, 'Holy Innocents, martyrs', 'புனித மாசில்லாக் குழந்தைகள் - மறைச்சாட்சியர்', 'Feast', '', ''),
(12, 29, 'Saint Thomas Becket, bishop and martyr', 'புனித தாமஸ் பெக்கட் - ஆயர் மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர் or மேய்ப்பர்', ''),
(12, 31, 'Saint Sylvester I, pope', 'புனித முதலாம் சில்வெஸ்தர் - திருத்தந்தை', 'OpMem', 'மேய்ப்பர் (திருத்தந்தை)', '');

-- --------------------------------------------------------

--
-- Table structure for table `generalcalendar__india`
--

CREATE TABLE `generalcalendar__india` (
  `feast_month` tinyint(2) NOT NULL,
  `feast_date` tinyint(2) NOT NULL,
  `feast_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `feast_ta` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `feast_type` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `common` text COLLATE utf8_unicode_ci NOT NULL,
  `proper` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `generalcalendar__india`
--

INSERT INTO `generalcalendar__india` (`feast_month`, `feast_date`, `feast_code`, `feast_ta`, `feast_type`, `common`, `proper`) VALUES
(1, 3, 'IN Saint Kuriakose Elias Chavara, priest', 'புனித குரியாக்கோஸ் எலியாஸ் சவரா - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர்', ''),
(1, 14, 'IN Blessed Devasahayam Pillai, martyr', 'முத்தி. தேவசகாயம் பிள்ளை, மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர்', ''),
(1, 16, 'IN Saint Joseph Vaz, priest', 'புனித ஜோசப் வாஸ் - மறைப்பணியாளர்', 'Mem', 'மேய்ப்பர்', ''),
(2, 4, 'IN Saint John de Brito, priest and martyr', 'புனித ஜான் தெ பிரிட்டோ (அருளானந்தர்) - மறைப்பணியாளர், மறைச்சாட்சி', 'Mem', 'மேய்ப்பர்', ''),
(2, 6, 'IN Saint Gonsalo Garcia, martyr', 'புனித கொன்சாலோ கார்சியா - மறைச்சாட்சி', 'Mem', 'மறைச்சாட்சியர்', ''),
(2, 25, 'IN Blessed Rani Maria, virgin, martyr', 'முத்தி. இராணி மரியா, கன்னியர், மறைச்சாட்சி', 'OpMem', 'மறைச்சாட்சியர்', ''),
(6, 8, 'IN Blessed Maria Theresa Chiramel, virgin', 'முத்தி. மரிய தெரேசா சிராமெல் - கன்னியர்', 'OpMem', '	\r\nகன்னியர்', ''),
(7, 3, 'IN Saint Thomas the Apostle', 'புனித தோமா - இந்தியாவின் திருத்தூதர்', 'Solemnity-PrincipalPartron-Place', 'PROPER', ''),
(7, 28, 'IN Saint Alphonsa of the Immaculate Conception (Alphonsa Muttathupadathu), virgin', 'அமலோற்பவத்தின் புனித அல்போன்சா முட்டாத்துபாடாத் - கன்னியர்', 'Mem', 'கன்னியர்', ''),
(8, 30, 'IN Saint Euphrasia, virgin', 'புனித யூப்ரேசியா, கன்னியர்', 'OpMem', 'கன்னியர்', ''),
(9, 5, 'IN Saint Teresa of Calcutta, virgin', 'புனித அன்னை தெரேசா - கன்னியர்', 'Mem', 'கன்னியர்', ''),
(10, 16, 'IN Blessed Augustine Thevarparambil, priest', 'முத்தி. அகுஸ்தின் தேவர்பரம்பில் - மறைப்பணியாளர்', 'OpMem', 'மேய்ப்பர்', ''),
(12, 3, 'IN Saint Francis Xavier, priest', 'புனித பிரான்சிஸ் சவேரியார் - மறைப்பணியாளர், இந்தியாவின் பாதுகாவலர்', 'Solemnity-PrincipalPartron-Place', 'மேய்ப்பர் (மறைபரப்புப் பணியாளர்)', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `generalcalendar`
--
ALTER TABLE `generalcalendar`
  ADD PRIMARY KEY (`feast_month`,`feast_date`,`feast_code`),
  ADD UNIQUE KEY `feast_en` (`feast_code`);

--
-- Indexes for table `generalcalendar__india`
--
ALTER TABLE `generalcalendar__india`
  ADD PRIMARY KEY (`feast_month`,`feast_date`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
