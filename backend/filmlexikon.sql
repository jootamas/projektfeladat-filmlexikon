-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Gép: localhost
-- Létrehozás ideje: 2024. Jan 08. 11:55
-- Kiszolgáló verziója: 8.0.35-0ubuntu0.22.04.1
-- PHP verzió: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `filmlexikon`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `categories`
--

CREATE TABLE `categories` (
  `categories_id` smallint NOT NULL,
  `categories_title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `categories`
--

INSERT INTO `categories` (`categories_id`, `categories_title`) VALUES
(1, 'történelmi'),
(2, 'dráma'),
(3, 'vígjáték'),
(4, 'akció'),
(5, 'kaland'),
(6, 'fantasy'),
(7, 'romantikus'),
(8, 'animáció'),
(9, 'krimi'),
(10, 'thriller'),
(11, 'musical'),
(12, 'életrajzi'),
(13, 'sci-fi'),
(14, 'horror'),
(15, 'háborús'),
(16, 'nincsfilm');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `countries`
--

CREATE TABLE `countries` (
  `countries_id` smallint NOT NULL,
  `countries_title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `countries`
--

INSERT INTO `countries` (`countries_id`, `countries_title`) VALUES
(1, 'amerikai'),
(2, 'angol'),
(3, 'francia'),
(4, 'magyar'),
(5, 'német'),
(6, 'kubai'),
(7, 'ír'),
(8, 'ausztrál');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `meta`
--

CREATE TABLE `meta` (
  `meta_id` mediumint NOT NULL,
  `meta_table` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_table_id` mediumint NOT NULL,
  `meta_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `meta`
--

INSERT INTO `meta` (`meta_id`, `meta_table`, `meta_table_id`, `meta_key`, `meta_value`) VALUES
(5, 'persons', 5, 'persons_birth', '1970-07-30'),
(6, 'persons', 5, 'photo_filename', 'photo-5-1702214925.jpg'),
(9, 'movies', 5, 'countries', '|1|'),
(10, 'movies', 5, 'categories', '|1|;|2|'),
(11, 'movies', 5, 'plot', ''),
(12, 'movies', 5, 'poster_filename', 'poster-5-1703701907.jpg'),
(13, 'movies', 6, 'countries', '|1|'),
(14, 'movies', 6, 'categories', '|3|'),
(15, 'movies', 6, 'plot', 'Van ám olyan világ, ahol minden csodálatos! Mindig süt a nap, lenn a parton mindig lágyan hullámzik a tenger, és szörfdeszkákon, a nyugágyakban meg a bulizósabb helyeken sokféle, de mindig gyönyörű strandoló gyűlik össze: Barbie (Margot Robbie) és Ken (Ryan Gosling) meg a többi Barbie és Ken.'),
(16, 'movies', 6, 'poster_filename', 'poster-6-1703701976.jpg'),
(17, 'movies', 7, 'countries', '|1|'),
(18, 'movies', 7, 'categories', '|4|;|5|;|6|'),
(19, 'movies', 7, 'plot', 'Black Manta megpróbálta már legyőzni Aquamant, a világ tengereinek urát (Jason Momoa), de kudarcot vallott. Továbbra is meg akarja bosszulni az apja halálát, így aztán bármire hajlandó, és bárkivel szövetkezik, hogy elpusztítsa ősellenségét. És most erősebb, mint eddig bármikor, mert megszerezte a legendák csodafegyverét, a fekete szigonyt, amelynek segítségével egy ősi, gonosz erőt szabadít a világra. Aquaman kénytelen kiszabadítani börtönbe zárt testvérét, Orm királyt (Patrick Wilson), Atlantisz egykori uralkodóját. Békét nem kötnek, de átmeneti szövetséget igen - ha nem teszik meg, akkor Aquaman családja, azután az összes tenger, végül az egész világ elpusztul.'),
(20, 'movies', 7, 'poster_filename', 'poster-7-1703710957.jpg'),
(21, 'movies', 8, 'countries', '|1|'),
(22, 'movies', 8, 'categories', '|4|;|5|'),
(23, 'movies', 8, 'plot', '1969-et írunk. Indiana Jones úgy dönt, ennyi volt. Miután több mint egy évtizeden át tanított a New York-i Hunter Főiskolán, a neves professzor és régész nyugdíjas éveire készül szerény kis lakásában, ahol egyedül tengeti napjait. A dolgok azonban váratlan fordulatot vesznek, mikor régen látott keresztlánya, Helena Shaw felbukkan. Egy ritka ereklyéről érdeklődik, amit apja bízott Indyre sok évvel ezelőtt - Arkhimédész hírhedt tárcsájáról, egy olyan szerkezetről, ami állítólag képes megtalálni az időn keletkezett repedéseket. Helena tapasztalt szélhámos, és eltulajdonítja a tárcsát, majd egy távoli országba indul, ahol a legtöbbet ajánlónak kínálhatja. Indynek nem marad más, mint utána eredni, tehát leporolja kalapját, bőrkabátját egy utolsó kalandhoz. Eközben egy régi ellensége, Jürgen Voller, a korábbi náci, aki most az amerikai űrprogramban dolgozik fizikusként, maga is tervet sző a tárcsával, méghozzá olyan félelmeteset, ami a világtörténelem menetét változtathatja meg.'),
(24, 'movies', 8, 'poster_filename', 'poster-8-1703711133.jpg'),
(25, 'movies', 9, 'countries', '|1|'),
(26, 'movies', 9, 'categories', '|7|;|2|'),
(27, 'movies', 9, 'plot', ''),
(28, 'movies', 10, 'countries', '|1|'),
(29, 'movies', 10, 'categories', '|8|'),
(30, 'movies', 10, 'plot', ''),
(31, 'movies', 11, 'countries', '|1|'),
(32, 'movies', 11, 'categories', '|9|;|2|'),
(33, 'movies', 11, 'plot', ''),
(34, 'movies', 12, 'countries', '|1|'),
(35, 'movies', 12, 'categories', '|3|'),
(36, 'movies', 12, 'plot', 'A pénz sosem elég. Jordan Belfort (Leonardo DiCaprio) becsületes tőzsdeügynökként kezdte pályafutását, de az amerikai álom őt is utolérte. A 80-as évek végére az egyik legnagyobb brókercég tulajdonosa lett, 26 évesen heti 1 millió dollárt keresett. Az idáig vezető út azonban korrupcióval és tisztességtelen üzletekkel kikövezett csábító hullámvasútnak bizonyult. Mert minél nagyobb volt a kísértés ő annál többet akart, mit sem törődve az illegális üzelmekkel és a nyomában loholó FBI ügynökökkel. Még több pénz, még több hatalom, még több nő és megint még több pénz: ez Jordan életfilozófiája. És hogy a szerénység egy túlértékelt erény. Jordan és falkája azt sem tudták mit kezdjenek az illegálisan megszerzett milliárdokkal, de vajon a jéghegy csúcsáról merre vezet az út?'),
(37, 'movies', 13, 'countries', '|1|'),
(38, 'movies', 13, 'categories', '|5|;|3|'),
(39, 'movies', 13, 'plot', ''),
(40, 'movies', 14, 'countries', '|1|'),
(41, 'movies', 14, 'categories', '|9|;|2|;|10|'),
(42, 'movies', 14, 'plot', 'Dr. Hannibal \"Kannibál\" Lecter az egyik legveszedelmesebb pszichopata gyilkos. Évek óta szigorúan őrzött börtönben ül, de az FBI-nak most a segítségére van szüksége. Valaki ugyanis a módszereit utánozza, és remélik, hogy az őrült orvos segítségükre tud lenni. Clarice Starling különleges ügynök kapja a feladatot, hogy férkőzzön Dr. Lecter bizalmába, és próbálja meg rávenni a segítségre. Ám a dolog nem ilyen egyszerű: Dr. Lecter nem csak őrült, de hihetetlenül intelligens is, így Clarice-nek nincs könnyű dolga.'),
(43, 'movies', 15, 'countries', '|1|'),
(44, 'movies', 15, 'categories', '|9|;|10|'),
(45, 'movies', 15, 'plot', 'Vincent Hanna (Al Pacino), a Los Angeles-i zsaru egy háromszoros gyilkossággal végződő fegyveres rablás tettesei után nyomoz. Nyomára is akad Neil McCauley (Robert De Niro) bandájának, mely éppen egy újabb, még nagyobb zsákmányt ígérő bűntény tervén dolgozik. A baj csak az, hogy a két szembenálló férfi egyaránt mestere szakmájának. Profi rendőr küzd a profi rabló ellen. Bár tudják, hogy párharcukat csak egyikük élheti túl, olyan kapocs alakul ki köztük, ami túlmutat szakmájukon. Az összecsapás azonban mindenképp elkerülhetetlen ...'),
(46, 'movies', 16, 'countries', '|2|'),
(47, 'movies', 16, 'categories', '|1|;|2|'),
(48, 'movies', 16, 'plot', 'A király beszéde egy férfi története, aki VI. György brit királyként, II. Erzsébet királynő apjaként vonult be a világtörténelembe. Miután bátyja lemondott, George azaz Bertie foglalta el trónt - igen kelletlenül. A király nem érezte méltónak magát élete legfontosabb szerepére, mert borzalmas dadogása miatt képtelen volt nyilvánosan beszédet tartani. Számos eredménytelen beszédterápiás kezelés után találkozott a liberális szellemiségű Lionel Logue-gal, aki csöppet sem hagyományos ülésein nemcsak királya hangját, hanem bátorságát is segített visszaszerezni.'),
(49, 'movies', 17, 'countries', '|1|'),
(50, 'movies', 17, 'categories', '|11|;|3|'),
(51, 'movies', 17, 'plot', ''),
(52, 'movies', 18, 'countries', '|2|;|3|'),
(53, 'movies', 18, 'categories', '|12|;|2|'),
(54, 'movies', 18, 'plot', 'Margaret Thatcher hosszú idő után úgy dönt, megszabadul néhai férje ruháitól. Fontos nap ez számára, és miközben a ruhákat válogatja, megrohanják az emlékek. A konzervatívok jelöltjeként 1959-ben jutott be az alsóházba. 1970-ben oktatásért és tudományért felelős miniszter lett Edward Heath kormányában. Öt évvel később már ő vezeti a Konzervatív Pártot. A kommunizmus elleni határozott kiállása miatt ekkortájt kezdik Vasladyként emlegetni. Azután 1979-ben győzelemre vezeti a konzervatívokat a választásokon és ő lesz Nagy-Britannia első női miniszterelnöke.'),
(55, 'movies', 18, 'poster_filename', 'poster-18-1703712474.jpg'),
(56, 'movies', 19, 'countries', '|1|'),
(57, 'movies', 19, 'categories', '|3|'),
(58, 'movies', 19, 'plot', ''),
(59, 'movies', 20, 'countries', '|1|'),
(60, 'movies', 20, 'categories', '|7|;|3|'),
(61, 'movies', 20, 'plot', 'Karácsony este a rádióban egy talk-show műsorvezetője álmokról, vágyakról, kívánságokról kérdezi a hallgatókat. Egy seattle-i kisfiú betelefonál és az édesapjának kér segítséget, aki felesége halála óta magányosan neveli őt. Annie-t (Meg Ryan), a bájos, házasság előtt álló lányt, aki autóvezetés közben a rádió éjszakai műsorát hallgatja, valósággal elvarázsolják a kisfiú és az édesapa (Tom Hanks) megindító szavai. A messzi távolból úgy érzi, épp a nagy ő hangját hallja a rádió hullámhosszán. Elhatározza, hogy kerül, amibe kerül, elindul és megkeresi az ismeretlent, akiben felismerni vélte az \"igazit\".'),
(62, 'movies', 21, 'categories', '|13|;|4|'),
(63, 'movies', 21, 'plot', ''),
(64, 'movies', 22, 'countries', '|4|'),
(65, 'movies', 22, 'categories', '|12|;|2|'),
(66, 'movies', 22, 'plot', 'Semmelweis (Vecsei H. Miklós). Ma legendaként emlékszünk rá: bátor és tántoríthatatlan, egyszerre tudásvággyal és igazságvággyal teli emberként. Akit nem véletlenül emlegetnek így: az anyák megmentője. A saját korában viszont egészen másképp látták. A többi magyar orvos, akivel együtt dolgozott a bécsi szülészeti klinikán, őrültnek hitte: hiszen nem érdekelték a császárváros örömei, csak a munkájának élt. Az osztrák orvosok elviselhetetlenül erőszakosnak tartották: kizárólag egy cél lebegett a szeme előtt, a rábízott anyák egészsége; és a szent ügy érdekében áthágott minden akadályt, megszegett minden szabályt. Főnöke, a klinika vezetője (Gálffi László) számára pedig ő volt az élő, kellemetlen lelkiismeret: mert ha Semmelweis doktor elmélete igaz, és a szülészorvosok terjesztik a kórt, amelybe annyi szülő nő belehalt, akkor ők mind gyilkosok ... És dolgozott mellette egy szülésznő (Nagy Katica), aki meglátta benne azt, akit senki más: a szerelemre és megnyugvásra vágyó férfit.'),
(67, 'movies', 23, 'countries', '|1|'),
(68, 'movies', 23, 'categories', '|6|;|5|'),
(69, 'movies', 23, 'plot', 'Az igaz szerelem és a világ megmentése érdekében egy okos, ám halandó tolvaj (Brenton Thwaites) szövetkezik egy erős, de bosszúálló istennel (Nikolaj Coster-Waldau), hogy megállítsák a sötétség könyörtelen istenét (Gerard Butler), aki el akarja pusztítani az evilágot és a túlvilágot.'),
(70, 'movies', 23, 'poster_filename', 'poster-23-1703713102.jpg'),
(71, 'movies', 24, 'countries', '|1|'),
(72, 'movies', 24, 'categories', '|4|;|5|'),
(73, 'movies', 24, 'plot', 'Tony Stark, a zseniális feltaláló és különc milliárdos éppen legújabb szuperfegyverét mutatja be, amikor a csoportot támadás éri és Tony mellkasába vasszilánk fúródik, mely lassan halad a szíve felé. Ráadásul foglyul ejtik és azt követelik tőle, hogy építsen meg egy minden eddiginél pusztítóbb fegyvert. Tony meg is építi, azonban egy olyan páncélöltözet formájában, amely segítségére lehet a szökésben és távol tartja a vasszilánkot a szívétől. Így születik meg a legendás Vasember.'),
(74, 'movies', 24, 'poster_filename', 'poster-24-1703713277.jpg'),
(75, 'movies', 25, 'countries', '|1|'),
(76, 'movies', 25, 'categories', '|4|;|5|'),
(77, 'movies', 25, 'plot', ''),
(78, 'movies', 25, 'poster_filename', 'poster-25-1703713351.jpg'),
(79, 'movies', 26, 'countries', '|1|'),
(80, 'movies', 26, 'categories', '|9|;|3|'),
(81, 'movies', 26, 'plot', ''),
(82, 'movies', 26, 'poster_filename', 'poster-26-1703713480.jpg'),
(83, 'movies', 27, 'countries', '|1|;|5|'),
(84, 'movies', 27, 'categories', '|3|'),
(85, 'movies', 27, 'plot', 'Gustave a híres európai szálloda, a Grand Budapest Hotel legendás főportása a két világháború között. Az elegáns szállodában átlagosnak nemigen mondható vendégek fordulnak meg, arisztokraták, vénkisasszonyok és műkincstolvajok. A főportás összebarátkozik az egyszerű londinerrel, Zero Mustafával, és együtt keverednek bele az évszázad műkincsrablásába. Miközben a világ drámai módon kezd megáltozni körülöttük, a szálloda szinte minden vendége és dolgozója részese lesz az értékes kép utáni hajszának.'),
(86, 'movies', 28, 'countries', '|1|'),
(87, 'movies', 28, 'categories', '|2|;|14|'),
(88, 'movies', 28, 'plot', 'Dani és Christian évek óta együtt vannak, ám a fiú úgy érzi, ideje lenne lezárni a kapcsolatukat. Egy váratlan tragédia következtében végül úgy dönt, nem ez a legmegfelelőbb alkalom, hogy szakítson barátnőjével, inkább meghívja őt is arra a nyári fesztiválra, ahova barátaival készülnek. A nyári napforduló alkalmából rendezett mulatság különös közössége tárt karokkal fogadja a fiatalokat, akik számára hamar világossá válik, hogy a bizarr rituálékból ők sem maradhatnak ki.'),
(89, 'movies', 29, 'countries', '|1|'),
(90, 'movies', 29, 'categories', '|4|;|5|'),
(91, 'movies', 29, 'plot', ''),
(92, 'movies', 29, 'poster_filename', 'poster-29-1703715003.jpg'),
(93, 'movies', 30, 'countries', '|2|;|1|'),
(94, 'movies', 30, 'categories', '|13|;|2|'),
(95, 'movies', 30, 'plot', ''),
(96, 'movies', 31, 'countries', '|1|;|2|'),
(97, 'movies', 31, 'categories', '|5|;|2|'),
(98, 'movies', 31, 'plot', ''),
(99, 'movies', 32, 'categories', '|15|;|12|;|2|'),
(100, 'movies', 32, 'plot', ''),
(101, 'movies', 32, 'poster_filename', 'poster-32-1703715507.jpg'),
(102, 'persons', 71, 'persons_birth', '1973-04-14'),
(103, 'persons', 71, 'photo_filename', 'photo-71-1704546117.jpg'),
(104, 'persons', 35, 'persons_birth', '1940-04-25'),
(105, 'persons', 35, 'photo_filename', 'photo-35-1704546160.jpg'),
(106, 'persons', 64, 'persons_birth', '1981-06-13'),
(107, 'persons', 64, 'photo_filename', 'photo-64-1703715868.jpg'),
(108, 'movies', 14, 'poster_filename', 'poster-14-1704111749.jpg'),
(109, 'movies', 27, 'poster_filename', 'poster-27-1704111766.jpg'),
(110, 'movies', 16, 'poster_filename', 'poster-16-1704111796.jpg'),
(111, 'movies', 20, 'poster_filename', 'poster-20-1704111838.jpg'),
(112, 'movies', 12, 'poster_filename', 'poster-12-1704111873.jpg'),
(113, 'persons', 67, 'persons_birth', '1988-04-30'),
(114, 'persons', 67, 'photo_filename', 'photo-67-1704547242.jpg'),
(115, 'persons', 42, 'persons_birth', '1985-12-03'),
(116, 'persons', 42, 'photo_filename', 'photo-42-1704546055.jpg'),
(117, 'persons', 32, 'persons_birth', '1937-12-31'),
(118, 'persons', 32, 'photo_filename', 'photo-32-1704112047.jpg'),
(119, 'persons', 38, 'persons_birth', '1960-09-10'),
(120, 'persons', 38, 'photo_filename', 'photo-38-1704112087.jpg'),
(121, 'persons', 6, 'persons_birth', '1976-05-25'),
(122, 'persons', 6, 'photo_filename', 'photo-6-1704112126.jpg'),
(123, 'persons', 7, 'persons_birth', '1983-02-23'),
(124, 'persons', 7, 'photo_filename', 'photo-7-1704619637.jpg'),
(125, 'persons', 66, 'persons_birth', '1968-03-02'),
(126, 'persons', 66, 'photo_filename', 'photo-66-1704112193.jpg'),
(127, 'persons', 46, 'persons_birth', '1946-01-05'),
(128, 'persons', 46, 'photo_filename', 'photo-46-1704540966.jpg'),
(129, 'persons', 71, 'persons_country', '|1|'),
(130, 'persons', 67, 'persons_country', '|6|'),
(131, 'persons', 32, 'persons_country', '|2|'),
(132, 'persons', 64, 'persons_country', '|1|'),
(133, 'persons', 5, 'persons_country', '|2|'),
(134, 'persons', 6, 'persons_country', '|7|'),
(135, 'persons', 38, 'persons_country', '|2|'),
(136, 'persons', 66, 'persons_country', '|2|'),
(137, 'persons', 46, 'persons_country', '|1|'),
(138, 'persons', 51, 'persons_birth', '1964-04-24'),
(139, 'persons', 51, 'photo_filename', 'photo-51-1704541121.jpg'),
(140, 'persons', 51, 'persons_country', '|1|'),
(141, 'persons', 7, 'persons_country', '|1|'),
(142, 'persons', 73, 'persons_birth', '1996-01-03'),
(143, 'persons', 73, 'persons_country', '|2|'),
(144, 'persons', 39, 'persons_birth', '1951-07-06'),
(145, 'persons', 39, 'persons_country', '|8|'),
(146, 'movies', 28, 'poster_filename', 'poster-28-1704541378.jpg'),
(147, 'movies', 9, 'poster_filename', 'poster-9-1704541865.jpg'),
(148, 'movies', 31, 'poster_filename', 'poster-31-1704541901.jpg'),
(149, 'movies', 33, 'countries', '|1|'),
(150, 'movies', 33, 'categories', '|2|'),
(151, 'movies', 33, 'plot', 'Charlie (Brendan Fraser) egy 270 kilós középiskolai angol tanár, akinek élete nagyot fordul, mikor elhagyja családját férfi szeretője miatt, megromlik a kapcsolata tinédzser lányával, Ellie-vel (Sadie Sink) is. A férfi depressziója és kóros elhízása miatt régóta nem hagyta el az otthonát, online tartja meg óráit is. Egyetlen barátja Liz (Hong Chau) megpróbál neki segíteni, amiben csak tud. Egy nap Charlie úgy dönt, szeretne kapcsolatot teremteni elhidegült 17 éves lányával.'),
(152, 'movies', 33, 'poster_filename', 'poster-33-1704542916.jpg'),
(153, 'persons', 19, 'persons_birth', '1956-07-09'),
(154, 'persons', 19, 'photo_filename', 'photo-19-1704547274.jpg'),
(155, 'persons', 48, 'persons_birth', ''),
(156, 'persons', 48, 'photo_filename', 'photo-48-1704543098.jpg'),
(157, 'movies', 11, 'poster_filename', 'poster-11-1704545045.jpg'),
(158, 'persons', 19, 'persons_country', '|1|'),
(159, 'persons', 44, 'persons_birth', ''),
(160, 'persons', 84, 'persons_birth', ''),
(161, 'persons', 84, 'photo_filename', 'photo-84-1704546193.jpg'),
(162, 'movies', 34, 'categories', '|9|;|2|'),
(163, 'movies', 34, 'plot', 'Amikor az 1920-as évek Oklahomájában olajat találnak az osage törzs területén, a tagjait elkezdik egyenként meggyilkolni - amíg az FBI közbe nem lép, hogy megoldja a rejtélyt.'),
(164, 'movies', 34, 'poster_filename', 'poster-34-1704546440.jpg'),
(165, 'movies', 34, 'countries', '|1|'),
(170, 'movies', 13, 'poster_filename', 'poster-13-1704547030.jpg'),
(171, 'movies', 22, 'poster_filename', 'poster-22-1704547095.jpg'),
(172, 'movies', 21, 'poster_filename', 'poster-21-1704547142.jpg'),
(173, 'persons', 73, 'photo_filename', 'photo-73-1704619662.jpg'),
(174, 'persons', 39, 'photo_filename', 'photo-39-1704619688.jpg'),
(175, 'persons', 56, 'persons_birth', '1969-11-13'),
(176, 'persons', 56, 'persons_country', '|2|'),
(177, 'persons', 56, 'photo_filename', 'photo-56-1704619731.jpg'),
(178, 'persons', 9, 'persons_birth', '1983-08-04'),
(179, 'persons', 9, 'persons_country', '|1|'),
(180, 'persons', 9, 'photo_filename', 'photo-9-1704619769.jpg'),
(181, 'movies', 36, 'categories', '|4|;|5|'),
(182, 'movies', 36, 'plot', ''),
(183, 'movies', 36, 'poster_filename', 'poster-36-1704629363.jpg'),
(184, 'movies', 37, 'countries', '|1|'),
(185, 'movies', 37, 'categories', '|2|'),
(186, 'movies', 37, 'plot', 'Négy kívülálló észreveszi azt, amit a nagy bankok, a média és a kormány nem hajlandó tudomásul venni: hogy küszöbön áll a gazdaság globális összeomlása. Támad egy ötletük ... ami, ha bejön, óriási pénzt kaszálnak.'),
(187, 'movies', 37, 'poster_filename', 'poster-37-1704629505.jpg'),
(190, 'persons', 89, 'persons_birth', '1968-04-17'),
(191, 'persons', 89, 'persons_country', '|1|');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `movies`
--

CREATE TABLE `movies` (
  `movies_id` mediumint NOT NULL,
  `movies_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `movies_title_original` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `movies_year` smallint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `movies`
--

INSERT INTO `movies` (`movies_id`, `movies_title`, `movies_title_original`, `movies_year`) VALUES
(5, 'Oppenheimer', '', 2023),
(6, 'Barbie', '', 2023),
(7, 'Aquaman és az elveszett királyság', 'Aquaman and the Lost Kingdom', 2023),
(8, 'Indiana Jones és a sors tárcsája', 'Indiana Jones and the Dial of Destiny', 2023),
(9, 'Forrest Gump', '', 1994),
(10, 'Toy story - Játékháború', 'Toy Story', 1995),
(11, 'Kapj el, ha tudsz', 'Catch Me If You Can', 2002),
(12, 'A Wall Street farkasa', 'The Wolf of Wall Street', 2013),
(13, 'Wonka', '', 2023),
(14, 'A bárányok hallgatnak', 'The Silence of the Lambs', 1991),
(15, 'Szemtől szemben', 'Heat', 1995),
(16, 'A király beszéde', 'The King\'s Speech', 2010),
(17, 'Mamma Mia!', '', 2008),
(18, 'A Vaslady', 'The Iron Lady', 2011),
(19, 'Örömapa', 'Father of the Bride', 1991),
(20, 'A szerelem hullámhosszán', 'Sleepless in Seattle', 1993),
(21, 'Rebel Moon - 1. rész: A tűz gyermeke', 'Rebel Moon', 2023),
(22, 'Semmelweis', '', 2023),
(23, 'Egyiptom istenei', 'Gods of Egypt', 2016),
(24, 'A vasember', 'Iron Man', 2008),
(25, 'Bosszúállók', 'The Avengers', 2012),
(26, 'Tőrbe ejtve', 'Knives Out', 2019),
(27, 'A Grand Budapest Hotel', 'The Grand Budapest Hotel', 2014),
(28, 'Fehér éjszakák', 'Midsommar', 2019),
(29, 'Pókember: Hazatérés', 'Spider-Man: Homecoming', 2017),
(30, 'Mentőexpedíció', 'The Martian', 2015),
(31, 'Gladiátor', 'Gladiator', 2000),
(32, 'Napóleon', 'Napoleon', 2023),
(33, 'A bálna', 'The Whale', 2022),
(34, 'Megfojtott virágok', 'Killers of the Flower Moon', 2023),
(36, 'Exodus: Istenek és királyok', 'Exodus: Gods and Kings', 2014),
(37, 'A nagy dobás', 'The Big Short', 2015);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `movies_persons`
--

CREATE TABLE `movies_persons` (
  `movies_persons_id` mediumint NOT NULL,
  `movies_id` mediumint NOT NULL,
  `persons_id` mediumint NOT NULL,
  `movies_persons_role` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `movies_persons_character` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `movies_persons`
--

INSERT INTO `movies_persons` (`movies_persons_id`, `movies_id`, `persons_id`, `movies_persons_role`, `movies_persons_character`) VALUES
(11, 5, 5, 'director', ''),
(12, 5, 6, 'actor', 'Robert Oppenheimer'),
(13, 5, 7, 'actor', 'Katherine Oppenheimer'),
(14, 6, 9, 'director', ''),
(15, 6, 10, 'actor', 'Barbie'),
(16, 6, 11, 'actor', 'Ken'),
(17, 7, 12, 'director', ''),
(18, 7, 13, 'actor', 'Aquaman'),
(19, 7, 14, 'actor', 'Orm'),
(20, 8, 15, 'director', ''),
(21, 8, 16, 'actor', 'Indiana Jones'),
(22, 8, 17, 'actor', 'Voller'),
(23, 9, 18, 'director', ''),
(24, 9, 19, 'actor', 'Forrest Gump'),
(25, 9, 20, 'actor', 'Jenny'),
(26, 10, 21, 'director', ''),
(27, 10, 19, 'actor', 'Woody'),
(28, 10, 22, 'actor', 'Buzz'),
(29, 11, 23, 'director', ''),
(30, 11, 24, 'actor', 'Frank Abignale'),
(31, 11, 19, 'actor', 'Carl Hanratty'),
(32, 12, 25, 'director', ''),
(33, 12, 24, 'actor', 'Jordan Belfort'),
(34, 12, 26, 'actor', 'Donnie Azoff'),
(35, 12, 27, 'actor', 'Mark Hanna'),
(36, 12, 10, 'actor', 'Naomi Lapaglia'),
(37, 13, 28, 'director', ''),
(38, 13, 29, 'actor', 'Willy Wonka'),
(39, 14, 30, 'director', ''),
(40, 14, 31, 'actor', 'Clarice Starling'),
(41, 14, 32, 'actor', 'Dr. Hannibal Lecter'),
(42, 15, 33, 'director', ''),
(43, 15, 34, 'actor', 'Neil McCauley'),
(44, 15, 35, 'actor', 'Vincent Hanna'),
(45, 15, 36, 'actor', 'Chris Shiherlis'),
(46, 16, 37, 'director', ''),
(47, 16, 38, 'actor', 'Albert'),
(48, 16, 39, 'actor', 'Lionel'),
(49, 17, 40, 'director', ''),
(50, 17, 41, 'actor', 'Donna'),
(51, 17, 42, 'actor', 'Sophie'),
(52, 18, 40, 'director', ''),
(53, 18, 41, 'actor', 'Margaret Thatcher'),
(54, 18, 43, 'actor', 'Denis Thatcher'),
(55, 19, 44, 'director', ''),
(56, 19, 45, 'actor', 'George Banks'),
(57, 19, 46, 'actor', 'Nina Banks'),
(58, 20, 47, 'director', ''),
(59, 20, 19, 'actor', 'Sam'),
(60, 20, 48, 'actor', 'Annie'),
(61, 21, 49, 'director', ''),
(62, 21, 50, 'actor', 'Kora'),
(63, 21, 51, 'actor', 'Titus'),
(64, 22, 52, 'director', ''),
(65, 22, 53, 'actor', 'Semmelweis Ignác'),
(66, 22, 54, 'actor', 'Emma'),
(67, 23, 55, 'director', ''),
(68, 23, 56, 'actor', 'Széth'),
(69, 23, 57, 'actor', 'Hórusz'),
(70, 24, 58, 'director', ''),
(71, 24, 59, 'actor', 'Tony Stark / Vasember'),
(72, 24, 60, 'actor', 'Rhodey'),
(73, 24, 61, 'actor', 'Obadiah Stane'),
(74, 24, 62, 'actor', 'Pepper Potts'),
(75, 25, 63, 'director', ''),
(76, 25, 59, 'actor', 'Tony Stark / Vasember'),
(77, 25, 64, 'actor', 'Steve Rogers / Amerika Kapitány'),
(78, 26, 65, 'director', ''),
(79, 26, 64, 'actor', 'Alex Robinson'),
(80, 26, 66, 'actor', 'Benoit Blanc'),
(81, 26, 67, 'actor', 'Marta'),
(82, 26, 68, 'actor', 'Jack Bressler'),
(83, 27, 69, 'director', ''),
(84, 27, 70, 'actor', 'M. Gustave'),
(85, 27, 71, 'actor', 'Dimitri'),
(86, 28, 72, 'director', ''),
(87, 28, 73, 'actor', 'Dani'),
(88, 28, 74, 'actor', 'Christian'),
(89, 29, 75, 'director', ''),
(90, 29, 76, 'actor', 'Peter Parker / Pókember'),
(91, 29, 59, 'actor', 'Tony Stark / Vasember'),
(92, 30, 77, 'director', ''),
(93, 30, 8, 'actor', 'Mark'),
(94, 30, 78, 'actor', 'Melissa'),
(95, 31, 77, 'director', ''),
(96, 31, 79, 'actor', 'Maximus'),
(97, 31, 80, 'actor', 'Commodus'),
(98, 32, 77, 'director', ''),
(99, 32, 80, 'actor', 'Napoleon'),
(100, 32, 81, 'actor', 'Josephine'),
(101, 27, 82, 'actor', 'Jopling2'),
(102, 33, 83, 'director', ''),
(103, 33, 84, 'actor', 'Charlie'),
(104, 34, 25, 'director', ''),
(105, 34, 24, 'actor', 'Ernest'),
(106, 34, 34, 'actor', 'William'),
(108, 13, 85, 'actor', ''),
(109, 13, 86, 'actor', ''),
(110, 36, 77, 'director', ''),
(111, 36, 87, 'actor', 'Mózes'),
(112, 36, 88, 'actor', 'Ramszesz fáraó'),
(113, 37, 89, 'director', ''),
(114, 37, 87, 'actor', 'Michael Burry'),
(115, 37, 90, 'actor', 'Mark Baum'),
(116, 37, 11, 'actor', 'Jared'),
(117, 37, 91, 'actor', 'Ben');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `persons`
--

CREATE TABLE `persons` (
  `persons_id` mediumint NOT NULL,
  `persons_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `persons`
--

INSERT INTO `persons` (`persons_id`, `persons_name`) VALUES
(5, 'Christopher Nolan'),
(6, 'Cillian Murphy'),
(7, 'Emily Blunt'),
(8, 'Matt Damon'),
(9, 'Greta Gerwig'),
(10, 'Margot Robbie'),
(11, 'Ryan Gosling'),
(12, 'James Wan'),
(13, 'Jason Momoa'),
(14, 'Patrick Wilson'),
(15, 'James Mangold'),
(16, 'Harrison Ford'),
(17, 'Mads Mikkelsen'),
(18, 'Robert Zemeckis'),
(19, 'Tom Hanks'),
(20, 'Robin Wright'),
(21, 'John Lasseter'),
(22, 'Tim Allen'),
(23, 'Steven Spielberg'),
(24, 'Leonardo DiCaprio'),
(25, 'Martin Scorsese'),
(26, 'Jonah Hill'),
(27, 'Matthew McConaughey'),
(28, 'Paul King'),
(29, 'Timothée Chalamet'),
(30, 'Jonathan Demme'),
(31, 'Jodie Foster'),
(32, 'Anthony Hopkins'),
(33, 'Michael Mann'),
(34, 'Robert De Niro'),
(35, 'Al Pacino'),
(36, 'Val Kilmer'),
(37, 'Tom Hooper'),
(38, 'Colin Firth'),
(39, 'Geoffrey Rush'),
(40, 'Phyllida Lloyd'),
(41, 'Meryl Streep'),
(42, 'Amanda Seyfried'),
(43, 'Jim Broadbent'),
(44, 'Charles Shyer'),
(45, 'Steve Martin'),
(46, 'Diane Keaton'),
(47, 'Nora Ephron'),
(48, 'Meg Ryan'),
(49, 'Zack Snyder'),
(50, 'Sofia Boutella'),
(51, 'Djimon Hounsou'),
(52, 'Koltai Lajos'),
(53, 'Vecsei H. Miklós'),
(54, 'Nagy Katica'),
(55, 'Alex Proyas'),
(56, 'Gerard Butler'),
(57, 'Nikolaj Coster-Waldau'),
(58, 'Jon Favreau'),
(59, 'Robert Downey Jr.'),
(60, 'Terrence Howard'),
(61, 'Jeff Bridges'),
(62, 'Gwyneth Paltrow'),
(63, 'Joss Whedon'),
(64, 'Chris Evans'),
(65, 'Rian Johnson'),
(66, 'Daniel Craig'),
(67, 'Ana de Armas'),
(68, 'Michael Shannon'),
(69, 'Wes Anderson'),
(70, 'Ralph Fiennes'),
(71, 'Adrien Brody'),
(72, 'Ari Aster'),
(73, 'Florence Pugh'),
(74, 'Jack Reynor'),
(75, 'Jon Watts'),
(76, 'Tom Holland'),
(77, 'Ridley Scott'),
(78, 'Jessica Chastain'),
(79, 'Russell Crowe'),
(80, 'Joaquin Phoenix'),
(81, 'Vanessa Kirby'),
(82, 'Willem Dafoe'),
(83, 'Darren Aronofsky'),
(84, 'Brendan Fraser'),
(85, 'Rowan Atkinson'),
(86, 'Hugh Grant'),
(87, 'Christian Bale'),
(88, 'Joel Edgerton'),
(89, 'Adam McKay'),
(90, 'Steve Carell'),
(91, 'Brad Pitt');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_id`),
  ADD KEY `categories_id` (`categories_id`);

--
-- A tábla indexei `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`countries_id`),
  ADD KEY `countries_id` (`countries_id`);

--
-- A tábla indexei `meta`
--
ALTER TABLE `meta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `meta_table_id` (`meta_table_id`),
  ADD KEY `meta_key` (`meta_key`),
  ADD KEY `meta_table` (`meta_table`);

--
-- A tábla indexei `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movies_id`),
  ADD KEY `movies_id` (`movies_id`);

--
-- A tábla indexei `movies_persons`
--
ALTER TABLE `movies_persons`
  ADD PRIMARY KEY (`movies_persons_id`),
  ADD KEY `personsid` (`persons_id`),
  ADD KEY `moviesid` (`movies_id`);

--
-- A tábla indexei `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`persons_id`),
  ADD KEY `persons_id` (`persons_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_id` smallint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT a táblához `countries`
--
ALTER TABLE `countries`
  MODIFY `countries_id` smallint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT a táblához `meta`
--
ALTER TABLE `meta`
  MODIFY `meta_id` mediumint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT a táblához `movies`
--
ALTER TABLE `movies`
  MODIFY `movies_id` mediumint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT a táblához `movies_persons`
--
ALTER TABLE `movies_persons`
  MODIFY `movies_persons_id` mediumint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT a táblához `persons`
--
ALTER TABLE `persons`
  MODIFY `persons_id` mediumint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `movies_persons`
--
ALTER TABLE `movies_persons`
  ADD CONSTRAINT `moviesid` FOREIGN KEY (`movies_id`) REFERENCES `movies` (`movies_id`),
  ADD CONSTRAINT `personsid` FOREIGN KEY (`persons_id`) REFERENCES `persons` (`persons_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
