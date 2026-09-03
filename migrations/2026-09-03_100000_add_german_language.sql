-- Adds German (language_id 2) as a selectable site language and provides
-- German translations for the seed content: categories, subcategories,
-- terms, support/contact text, seller level badges, footer links, and the
-- homepage sections. Safe to run more than once (each step clears its own
-- language_id=2 rows before re-inserting).

INSERT IGNORE INTO `languages` (`id`, `title`, `image`, `default_lang`, `direction`, `template_folder`, `isS3`) VALUES
(2, 'Deutsch', 'german.png', 0, 'left', 'de', 0);

DELETE FROM `cats_meta` WHERE `language_id` = 2;
INSERT INTO `cats_meta` (`cat_id`, `language_id`, `cat_title`, `cat_desc`) VALUES
(1, 2, 'Grafik & Design', 'Entdecke die Welt der visuellen Ästhetik und gestalterischen Kreativität. Von Grafikdesign bis zu ansprechenden Layouts findest du hier Inspiration und Werkzeuge, um deine künstlerischen Visionen zu verwirklichen.'),
(2, 2, 'Digitales Marketing', 'Erfahre, wie du in der digitalen Welt erfolgreich vermarktest. Von Strategien für Social Media bis hin zu effektiven Online-Kampagnen – hier bekommst du Einblicke und Tipps, um deine Zielgruppe zu erreichen.'),
(3, 2, 'Schreiben & Übersetzen', 'Tauche ein in die Welt von Worten und Sprache. Ob kreatives Schreiben, professionelle Übersetzungen oder Redaktionsarbeit – hier findest du Ressourcen, um deine schriftlichen Fähigkeiten zu perfektionieren.'),
(4, 2, 'Video & Animation', 'Erwecke deine Ideen zum Leben mit beeindruckenden Videos und Animationen. Von Storyboarding bis zur postproduktiven Bearbeitung – hier entdeckst du Werkzeuge und Techniken, um visuelle Meisterwerke zu schaffen.'),
(5, 2, 'Programmierung & Technik', 'Erforsche die Welt der Codes und Technologien. Von Programmiersprachen bis zu innovativen Technologien – hier findest du Ressourcen, um deine Fähigkeiten in der Softwareentwicklung und Technik zu vertiefen.'),
(6, 2, 'Business', 'Entwickle deine unternehmerischen Fähigkeiten und strategischen Kenntnisse weiter. Von Unternehmensführung bis zu Marketingstrategien – hier bekommst du Einblicke und Ratschläge, um im Geschäftsbereich erfolgreich zu sein.'),
(7, 2, 'Spaß & Lifestyle', 'Entdecke eine Vielfalt von Inhalten, die dein Leben bereichern. Von Freizeitaktivitäten bis hin zu Lifestyle-Tipps – hier findest du Inspiration und Informationen, um dein Leben mit Spaß und Freude zu gestalten.'),
(8, 2, 'Musik & Audio', 'Tauche ein in die Welt der Klänge und Musik. Von Komposition bis zu Audioproduktion – hier bekommst du Einblicke und Tools, um deine künstlerische Seite im Bereich Musik und Audio zu entfalten.');

DELETE FROM `child_cats_meta` WHERE `language_id` = 2;
INSERT INTO `child_cats_meta` (`child_id`, `child_parent_id`, `language_id`, `child_title`, `child_desc`) VALUES
(1, 1, 2, 'Logo Design', 'Unser Logo-Design-Service bietet maßgeschneiderte und professionelle Logos, die deine Marke effektiv repräsentieren. Lass dich von unserem talentierten Team kreativer Designer inspirieren und stärke deinen visuellen Auftritt.'),
(2, 1, 2, 'Geschäftskarten & Briefpapier', 'Entdecke die Kunst der geschäftlichen Kommunikation mit individuellen Geschäftskarten und Briefpapier. Unsere Designer gestalten einzigartige visuelle Identitäten, die einen bleibenden Eindruck hinterlassen.'),
(3, 1, 2, 'Illustration', 'Tauche ein in die Welt der Illustration mit unseren kreativen und einzigartigen Designs. Unsere Illustratoren verwandeln Ideen in visuelle Meisterwerke, die Geschichten erzählen und Emotionen wecken.'),
(4, 1, 2, 'Cartoons & Karikaturen', 'Bring deine Botschaft mit Humor zum Ausdruck! Unsere Cartoons und Karikaturen verleihen deiner Marke eine spielerische Note. Entdecke, wie unser Team von Künstlern deine Ideen lebendig werden lässt.'),
(5, 1, 2, 'Flyer & Poster', 'Verleih deinen Werbebotschaften Flügel mit unseren ansprechenden Flyern und Postern. Wir bieten Designs, die Aufmerksamkeit erregen und deine Veranstaltungen, Produkte oder Dienstleistungen optimal präsentieren.'),
(6, 1, 2, 'Buchcover & Verpackungen', 'Gestalte beeindruckende Buchcover und Verpackungen, die deine Werke effektiv präsentieren. Unsere Designer verstehen die Kunst, mit visuellen Elementen die Essenz deiner Geschichte oder deines Produkts einzufangen.'),
(7, 1, 2, 'Web- & Mobile-Design', 'Erforsche innovative Designs für Webseiten und mobile Anwendungen, die Benutzer begeistern. Unsere Designer kombinieren Ästhetik und Funktionalität, um digitale Erlebnisse zu schaffen, die im Gedächtnis bleiben.'),
(8, 1, 2, 'Social-Media-Design', 'Optimiere deine Präsenz in sozialen Medien mit ansprechenden Designs. Unsere kreativen Lösungen helfen dabei, eine konsistente und ansprechende Markenidentität zu schaffen, die in den sozialen Netzwerken hervorsticht.'),
(9, 1, 2, 'Bannerwerbung', 'Maximiere deine Online-Sichtbarkeit mit auffälligen Bannerwerbungen. Unsere Designer entwickeln Banner, die Aufmerksamkeit erregen und deine Zielgruppe dazu inspirieren, auf deine Botschaft zu reagieren.'),
(10, 2, 2, 'Social-Media-Marketing', 'Entdecke die Kraft des Social-Media-Marketings mit maßgeschneiderten Strategien. Wir helfen dir, deine Marke auf Plattformen wie Facebook, Instagram und Twitter effektiv zu präsentieren und deine Zielgruppe zu erreichen.'),
(11, 6, 2, 'WordPress', 'Erweitere die Möglichkeiten deiner Website mit professionellem WordPress-Design. Unsere Experten optimieren die Benutzerfreundlichkeit und Ästhetik deiner Website, um einen nachhaltigen Eindruck zu hinterlassen.'),
(12, 1, 2, 'Photoshop-Bearbeitung', 'Verwandle deine Fotos mit unserer Photoshop-Bearbeitung in beeindruckende Kunstwerke. Unsere Experten beherrschen die Feinheiten der Bildbearbeitung, um Bilder zu verbessern und visuelle Geschichten zu erzählen.'),
(13, 1, 2, '3D- & 2D-Modelle', 'Erlebe beeindruckende 3D- und 2D-Modelle, die deine Visionen zum Leben erwecken. Unsere Designer setzen fortschrittliche Techniken ein, um realistische Modelle zu schaffen, die beeindrucken und begeistern.'),
(14, 1, 2, 'T-Shirts', 'Gestalte individuelle T-Shirts mit unseren kreativen Designs. Wir bieten einzigartige Grafiken und Botschaften, damit deine T-Shirts nicht nur getragen, sondern auch als Ausdruck deiner Persönlichkeit wahrgenommen werden.'),
(15, 1, 2, 'Präsentationsdesign', 'Mach einen bleibenden Eindruck mit ansprechendem Präsentationsdesign. Unsere Designer entwickeln visuell ansprechende Präsentationen, die Inhalte effektiv vermitteln und dein Publikum beeindrucken.'),
(16, 1, 2, 'Sonstiges', 'Entdecke eine Vielfalt von Dienstleistungen, die deine kreativen Bedürfnisse erfüllen. Unser Team steht bereit, um auch deine individuellen Anforderungen zu erfüllen und einzigartige Lösungen für deine Projekte zu entwickeln.'),
(17, 2, 2, 'Suchmaschinenoptimierung (SEO)', 'Steigere die Sichtbarkeit deiner Website mit unserer professionellen SEO-Expertise. Wir optimieren deine Inhalte und Struktur, damit deine Website von Suchmaschinen gut gerankt und von deiner Zielgruppe gefunden wird.'),
(18, 2, 2, 'Web-Traffic', 'Generiere gezielten Web-Traffic mit effektiven Strategien. Unsere Experten helfen dir, die richtigen Kanäle zu nutzen und qualitativ hochwertigen Traffic zu gewinnen, um die Sichtbarkeit deiner Website zu steigern.'),
(19, 2, 2, 'Content-Marketing', 'Nutze die Kraft des Content-Marketings, um deine Zielgruppe zu erreichen. Wir entwickeln überzeugende Inhalte, die deine Botschaft vermitteln und dein Publikum dazu inspirieren, sich mit deiner Marke zu beschäftigen.'),
(20, 2, 2, 'Video-Marketing', 'Erzähl deine Geschichte mit packendem Video-Marketing. Unsere Experten produzieren hochwertige Videos, die deine Botschaft effektiv vermitteln und dein Publikum ansprechen.'),
(21, 2, 2, 'E-Mail-Marketing', 'Entdecke die Wirksamkeit unseres E-Mail-Marketing-Services. Unsere Experten entwickeln maßgeschneiderte Strategien, um deine Zielgruppe effektiv anzusprechen und deine Marketingziele zu erreichen.'),
(22, 2, 2, 'Such- und Display-Marketing', 'Optimiere deine Online-Sichtbarkeit mit unserer Such- und Display-Marketing-Expertise. Wir entwickeln gezielte Kampagnen, um potenzielle Kunden anzusprechen und deine Markenbekanntheit zu steigern.'),
(23, 2, 2, 'Marketingstrategie', 'Erforsche innovative Marketingstrategien, um deine Geschäftsziele zu erreichen. Unsere Berater entwickeln maßgeschneiderte Pläne, die auf deine einzigartigen Anforderungen zugeschnitten sind.'),
(24, 2, 2, 'Webanalyse', 'Versteh das Verhalten deiner Website-Besucher mit unserer Webanalyse. Unsere Experten liefern dir wertvolle Einblicke, um die Leistung deiner Online-Präsenz zu verbessern.'),
(25, 2, 2, 'Influencer-Marketing', 'Nutze die Reichweite von Influencern, um deine Zielgruppe zu erreichen. Unsere Experten helfen dir bei der Identifizierung und Zusammenarbeit mit passenden Influencern.'),
(26, 2, 2, 'Lokale Einträge', 'Optimiere deine lokale Präsenz mit unseren lokalen Eintragsdiensten. Erscheine in den relevanten Suchergebnissen und zieh lokale Kunden an.'),
(27, 2, 2, 'Domain-Forschung', 'Finde die perfekte Domain für dein Online-Geschäft mit unserer Domain-Forschung. Unsere Experten unterstützen dich bei der Auswahl einer aussagekräftigen und leicht merkbaren Domain.'),
(28, 2, 2, 'E-Commerce-Marketing', 'Steigere deine Umsätze mit unserem E-Commerce-Marketing. Wir entwickeln maßgeschneiderte Strategien, um deine Produkte online erfolgreich zu vermarkten.'),
(29, 2, 2, 'Mobile Werbung', 'Erreiche deine Zielgruppe auch unterwegs mit unserer mobilen Werbung. Unsere Kampagnen sind darauf ausgerichtet, mobile Nutzer anzusprechen und deine Botschaft effektiv zu vermitteln.'),
(30, 3, 2, 'Lebensläufe & Anschreiben', 'Präsentier dich professionell mit unseren erstklassigen Lebenslauf- und Anschreiben-Services. Unsere Experten helfen dir dabei, dich von der besten Seite zu zeigen und potenzielle Arbeitgeber zu beeindrucken.'),
(31, 3, 2, 'Lektorat & Bearbeitung', 'Verleih deinen Texten Klarheit und Präzision mit unserem Lektorat- und Bearbeitungsservice. Unsere Experten sorgen dafür, dass deine Inhalte fehlerfrei und wirkungsvoll sind.'),
(32, 3, 2, 'Übersetzung', 'Beseitige Sprachbarrieren mit unserem professionellen Übersetzungsservice. Unsere muttersprachlichen Übersetzer garantieren präzise und treffende Übersetzungen.'),
(33, 3, 2, 'Kreatives Schreiben', 'Erwecke deine kreativen Ideen zum Leben mit unserem kreativen Schreibservice. Unsere Autoren helfen dir dabei, packende Geschichten zu entwickeln und innovative Inhalte zu erstellen.'),
(34, 3, 2, 'Business Copywriting', 'Überzeug mit professionellem Business Copywriting. Unsere Experten entwickeln wirkungsvolle Texte, die deine Geschäftsziele unterstützen und deine Zielgruppe ansprechen.'),
(35, 3, 2, 'Recherche & Zusammenfassungen', 'Erschließ dir umfassende Informationen mit unseren Recherche- und Zusammenfassungsdiensten. Unsere Experten liefern prägnante Zusammenfassungen zu verschiedenen Themen.'),
(36, 3, 2, 'Artikel & Blog-Beiträge', 'Bereichere deine Website mit ansprechenden Artikeln und Blog-Beiträgen. Unsere Autoren erstellen hochwertige Inhalte, die deine Leser informieren und begeistern.'),
(37, 3, 2, 'Pressemitteilungen', 'Verbreite wichtige Nachrichten und Ereignisse mit unseren Press Release Services. Unsere Experten helfen dir dabei, Aufmerksamkeit in den Medien zu erlangen und deine Botschaft zu verbreiten.'),
(38, 3, 2, 'Transkription', 'Verwandle gesprochene Worte in schriftliche Texte mit unserem Transkriptionsservice. Unsere Experten bieten genaue und zuverlässige Transkriptionsdienste für verschiedene Medien.'),
(39, 3, 2, 'Juristisches Schreiben', 'Setz auf präzise und professionelle juristische Texte mit unserem juristischen Schreibservice. Unsere Experten unterstützen dich bei der Erstellung rechtlich fundierter Dokumente.'),
(40, 3, 2, 'Sonstiges', 'Entdecke eine Vielfalt von Dienstleistungen, die deine kreativen Bedürfnisse erfüllen. Unser Team steht bereit, um auch deine individuellen Anforderungen zu erfüllen und einzigartige Lösungen für deine Projekte zu entwickeln.'),
(41, 4, 2, 'Whiteboard & Erklärungsvideos', 'Präsentier deine Ideen mit animierten Whiteboard- und Erklärungsvideos. Unsere Videokünstler erstellen ansprechende Visualisierungen, um komplexe Konzepte einfach und verständlich zu erklären.'),
(42, 4, 2, 'Intros & Animierte Logos', 'Mach einen starken Eindruck mit individuellen Intros und animierten Logos. Unsere Designer entwickeln visuell beeindruckende Animationen, die dein Markenimage stärken.'),
(43, 4, 2, 'Werbe- & Markenvideos', 'Steigere die Bekanntheit deiner Marke mit überzeugenden Werbe- und Markenvideos. Unsere Experten entwickeln maßgeschneiderte Inhalte, die dein Unternehmen effektiv präsentieren.'),
(44, 4, 2, 'Bearbeitung & Postproduktion', 'Verleih deinen Videos den letzten Schliff mit professioneller Bearbeitung und Postproduktion. Unsere Videografen sorgen für nahtlose Übergänge und visuelle Brillanz.'),
(45, 4, 2, 'Lyrik- & Musikvideos', 'Bring deine Musik zum Leben mit beeindruckenden Lyrik- und Musikvideos. Unsere Videoproduzenten setzen deine kreativen Visionen in bewegende visuelle Erlebnisse um.'),
(46, 4, 2, 'Sprecher & Testimonials', 'Bau Vertrauen auf und überzeuge dein Publikum mit authentischen Sprechern und Testimonials. Unsere Sprecher verleihen deinen Botschaften Glaubwürdigkeit und Emotionalität.'),
(48, 4, 2, 'Sonstiges', 'Entdecke eine Vielfalt von Video- und Audioproduktionen, die deine kreativen Anforderungen erfüllen. Unser Team steht bereit, um auch deine individuellen Projekte zu realisieren und einzigartige Lösungen zu entwickeln.'),
(49, 9, 2, 'Voice-Over', 'Verleih deinem Projekt eine professionelle Stimme mit unseren Voice-Over-Dienstleistungen. Unsere Sprecher liefern klare und überzeugende Sprachaufnahmen.'),
(50, 9, 2, 'Mixing & Mastering', 'Veredle deinen Sound mit erstklassigem Mixing und Mastering. Unsere Toningenieure optimieren den Klang deiner Aufnahmen für ein beeindruckendes Hörerlebnis.'),
(51, 9, 2, 'Produzenten & Komponisten', 'Erschaffe beeindruckende Musik mit erfahrenen Produzenten und Komponisten. Unsere Musikexperten unterstützen dich bei der Realisierung deiner kreativen Vision.'),
(52, 9, 2, 'Singer-Songwriter', 'Erzähl deine Geschichten mit emotionalen Singer-Songwriter-Performances. Unsere Musiker schaffen authentische und berührende musikalische Erlebnisse.'),
(53, 9, 2, 'Sessionmusiker & Sänger', 'Verleih deinen Aufnahmen Professionalität mit erfahrenen Sessionmusikern und Sängern. Unsere Musiker tragen dazu bei, den gewünschten Klang zu erreichen.'),
(54, 9, 2, 'Jingles & Drops', 'Präg dich im Gedächtnis deiner Zielgruppe ein mit individuellen Jingles und Drops. Unsere Audioprofis entwickeln einprägsame Klänge, die deine Marke unverwechselbar machen.'),
(55, 9, 2, 'Sound Effects', 'Verleih deinen Projekten Tiefe mit passgenauen Sound-Effekten. Unsere Audioprofis erstellen und bearbeiten Klänge, die deine Videos, Spiele oder Präsentationen lebendig wirken lassen.'),
(56, 6, 2, 'Webprogrammierung', 'Gestalte dynamische und funktionale Websites mit unserer Webprogrammierung. Unsere Entwickler setzen innovative Technologien ein, um maßgeschneiderte Lösungen für deine Online-Präsenz zu schaffen.'),
(58, 6, 2, 'Website-Baukästen & CMS', 'Erstelle deine Website mühelos mit Website-Baukästen und Content-Management-Systemen. Unsere Experten unterstützen dich bei der Auswahl und Anpassung der besten Plattform für deine Bedürfnisse.'),
(60, 6, 2, 'E-Commerce', 'Starte erfolgreich in den E-Commerce mit unseren spezialisierten Dienstleistungen. Unsere E-Commerce-Experten helfen dir beim Aufbau und der Optimierung deines Online-Shops.'),
(61, 6, 2, 'Mobile Apps & Web', 'Erobere die mobile Welt mit ansprechenden Apps und responsiven Websites. Unsere Entwickler gestalten benutzerfreundliche Lösungen für mobile Endgeräte.'),
(62, 6, 2, 'Desktopanwendungen', 'Entwickle leistungsstarke Desktopanwendungen für verschiedene Plattformen. Unsere Programmierer setzen innovative Technologien ein, um maßgeschneiderte Softwarelösungen zu schaffen.'),
(63, 6, 2, 'Support & IT', 'Sicher dir zuverlässigen Support und IT-Dienstleistungen für deine digitale Infrastruktur. Unsere Experten stehen dir mit Rat und Tat zur Seite, um reibungslose Abläufe zu gewährleisten.'),
(64, 6, 2, 'Chatbots', 'Optimiere die Interaktion mit deinen Kunden durch intelligente Chatbots. Unsere Entwickler erstellen maßgeschneiderte Lösungen für automatisierte Kommunikation und Support.'),
(65, 6, 2, 'Datenanalyse & Berichte', 'Gewinne wertvolle Erkenntnisse aus deinen Daten mit professioneller Datenanalyse und Berichterstellung. Unsere Experten unterstützen dich bei der Auswertung und Interpretation deiner Informationen.'),
(66, 6, 2, 'Dateikonvertierung', 'Konvertiere Dateien mühelos in verschiedene Formate mit unseren Konvertierungsdiensten. Unsere Techniker sorgen für reibungslose und präzise Umwandlungen.'),
(67, 6, 2, 'Datenbanken', 'Entwickle leistungsstarke Datenbanklösungen für dein Unternehmen. Unsere Datenbankexperten gestalten effiziente Systeme, die deine Daten sicher verwalten und zugänglich machen.'),
(68, 6, 2, 'Nutzertests', 'Optimiere die Benutzerfreundlichkeit deiner Plattform mit umfassenden Nutzertests. Unsere Tester liefern wertvolles Feedback, um ein herausragendes Benutzererlebnis zu gewährleisten.'),
(69, 6, 2, 'Sonstiges', 'Entdecke eine Vielfalt von Dienstleistungen im Bereich Technologie und Daten. Unser Team bietet individuelle Lösungen für deine spezifischen Anforderungen.'),
(70, 7, 2, 'Virtueller Assistent', 'Ermögliche dir effizientes Arbeiten mit einem virtuellen Assistenten. Unsere Assistenten unterstützen dich bei alltäglichen Aufgaben, damit du dich auf das Wesentliche konzentrieren kannst.'),
(71, 7, 2, 'Marktforschung', 'Erfahre mehr über deinen Markt mit umfassender Marktforschung. Unsere Analysten liefern fundierte Einblicke, um strategische Entscheidungen für dein Unternehmen zu erleichtern.'),
(72, 7, 2, 'Businesspläne', 'Entwickle solide Geschäftspläne für deinen Erfolg. Unsere Berater helfen dir bei der Erstellung von Geschäftsplänen, die deine Ziele und Visionen widerspiegeln.'),
(73, 7, 2, 'Markenbildungsdienstleistungen', 'Stärke deine Marke mit professionellen Dienstleistungen zur Markenbildung. Unsere Experten unterstützen dich bei der Entwicklung einer starken und einprägsamen Markenidentität.'),
(74, 7, 2, 'Rechtsberatung', 'Hol dir rechtliche Unterstützung für dein Unternehmen. Unsere Anwälte bieten Beratungsdienstleistungen, damit dein Unternehmen rechtlich geschützt ist.'),
(75, 7, 2, 'Finanzberatung', 'Optimiere deine Finanzstrategie mit professioneller Finanzberatung. Unsere Berater helfen dir bei der Planung und Steuerung deiner finanziellen Ressourcen.'),
(76, 7, 2, 'Business-Tipps', 'Erfahre wertvolle Tipps und Ratschläge für dein Unternehmen. Unsere Experten teilen ihr Wissen, um dir dabei zu helfen, erfolgreich zu wachsen und zu agieren.'),
(77, 7, 2, 'Präsentationen', 'Beeindrucke dein Publikum mit überzeugenden Präsentationen. Unsere Designer und Redner arbeiten zusammen, um Inhalte visuell ansprechend und informativ zu präsentieren.'),
(78, 7, 2, 'Karrieretipps', 'Gestalte deine berufliche Laufbahn mit hilfreichen Karrieretipps. Unsere Berater unterstützen dich bei der Entwicklung deiner Fähigkeiten und der Planung deiner beruflichen Entwicklung.'),
(79, 7, 2, 'Flyer-Verteilung', 'Erreiche deine Zielgruppe mit effektiver Flyer-Verteilung. Unsere Vertriebsexperten helfen dir dabei, deine Botschaften gezielt zu verbreiten.'),
(80, 7, 2, 'Sonstiges', 'Entdecke eine Vielfalt von Dienstleistungen im Bereich Unternehmensberatung. Unser Team steht bereit, um auch deine individuellen Projekte zu realisieren und einzigartige Lösungen zu entwickeln.'),
(81, 8, 2, 'Online-Unterricht', 'Bild dich online weiter mit unseren qualifizierten Lehrern. Unsere Online-Lektionen bieten eine flexible und individuelle Lernumgebung für verschiedene Fachgebiete.'),
(82, 8, 2, 'Kunst & Handwerk', 'Entdecke deine kreative Seite mit Kunst- und Handwerksunterricht. Unsere Lehrer führen dich durch verschiedene Techniken und Projekte, um deine künstlerischen Fähigkeiten zu entwickeln.'),
(83, 8, 2, 'Beziehungstipps', 'Stärke deine Beziehungen mit professionellen Beziehungstipps. Unsere Berater helfen dir dabei, eine gesunde und erfüllende Beziehung aufzubauen und zu pflegen.'),
(84, 8, 2, 'Gesundheit, Ernährung & Fitness', 'Investiere in deine Gesundheit mit professionellen Dienstleistungen im Bereich Gesundheit, Ernährung und Fitness. Unsere Experten unterstützen dich bei der Erreichung deiner persönlichen Ziele.'),
(85, 8, 2, 'Astrologie & Lesungen', 'Erhalte Einblicke in deine Zukunft und Persönlichkeit mit astrologischen Dienstleistungen und Lesungen. Unsere Astrologen bieten individuelle Analysen und Beratungen an.'),
(86, 8, 2, 'Spiritualität & Heilung', 'Entdecke spirituelle Wege zur inneren Balance und Heilung. Unsere spirituellen Berater bieten Unterstützung bei der Entwicklung deiner spirituellen Praxis.'),
(87, 8, 2, 'Familie & Genealogie', 'Erforsche deine familiäre Herkunft und Geschichte mit Dienstleistungen im Bereich Familie und Genealogie. Unsere Experten helfen dir dabei, deine Wurzeln zu verstehen und zu dokumentieren.'),
(88, 8, 2, 'Sammlerstücke', 'Entdecke einzigartige Sammlerstücke und Raritäten. Unsere Anbieter bieten eine vielfältige Auswahl an Sammlerobjekten für passionierte Sammler.'),
(89, 8, 2, 'Grußkarten & Videos', 'Überrasche deine Lieben mit individuellen Grußkarten und Videos. Unsere Künstler gestalten personalisierte Botschaften, um besondere Momente unvergesslich zu machen.'),
(91, 8, 2, 'Viralvideos', 'Entdecke unterhaltsame und virale Videos, die die Aufmerksamkeit auf sich ziehen. Unsere Videokünstler kreieren Inhalte, die im Internet für Furore sorgen.'),
(92, 8, 2, 'Streiche & Stunts', 'Erlebe Spaß und Nervenkitzel mit Streichen und Stunts. Unsere Entertainer sorgen für unvergessliche Momente voller Lachen und Staunen.'),
(93, 8, 2, 'Promi-Imitatoren', 'Genieß beeindruckende Imitationen von Prominenten. Unsere Künstler beherrschen die Kunst der Celebrity-Impersonation und bieten unterhaltsame Darbietungen.'),
(94, 8, 2, 'Sonstiges', 'Entdecke eine Vielfalt von Dienstleistungen und Unterhaltung. Unser Team steht bereit, um auch deine individuellen Projekte zu realisieren und einzigartige Lösungen zu entwickeln.');

DELETE FROM `terms` WHERE `language_id` = 2;
INSERT INTO `terms` (`language_id`, `term_title`, `term_link`, `term_description`) VALUES
(2, 'Allgemeine Geschäftsbedingungen', 'AGB', '
Es ist eine seit langem etablierte Tatsache, dass ein Leser vom lesbaren Inhalt einer Seite abgelenkt wird, wenn er deren Layout betrachtet. Der Sinn der Verwendung von Lorem Ipsum besteht darin, dass es eine mehr oder weniger normale Buchstabenverteilung aufweist, im Gegensatz zu „Hier Text, hier Text“, wodurch es wie lesbarer, echter Text wirkt.<p><br></p><p>Viele Desktop-Publishing-Programme und Webseiten-Editoren verwenden Lorem Ipsum inzwischen als Standard-Blindtext, und eine Suche nach „lorem ipsum“ fördert zahlreiche Websites zutage, die sich noch im Aufbau befinden. Im Laufe der Jahre haben sich verschiedene Versionen entwickelt, teils durch Zufall, teils absichtlich. Warum verwenden wir es? Es ist eine seit langem etablierte Tatsache, dass ein Leser vom lesbaren Inhalt einer Seite abgelenkt wird, wenn er deren Layout betrachtet.</p><p><br></p><p>Woher kommt es? Entgegen der landläufigen Meinung ist Lorem Ipsum kein reiner Zufallstext. Es hat seine Wurzeln in einem Stück klassischer lateinischer Literatur aus dem Jahr 45 v. Chr. und ist damit über 2000 Jahre alt. Richard McClintock, ein Lateinprofessor am Hampden-Sydney College in Virginia, untersuchte eines der weniger gebräuchlichen lateinischen Wörter, consectetur, aus einer Lorem-Ipsum-Passage und entdeckte bei der Recherche nach dessen Verwendung in der klassischen Literatur die zweifelsfreie Quelle: die Abschnitte 1.10.32 und 1.10.33 von „de Finibus Bonorum et Malorum“ (Von den Grenzen des Guten und Bösen) von Cicero, geschrieben im Jahr 45 v. Chr. Dieses Buch ist eine Abhandlung über die Ethiktheorie und war während der Renaissance sehr beliebt.</p>




'),
(2, 'Rückerstattungsrichtlinie', 'rueckerstattung', '
<p><span style="color: rgb(0, 0, 0);">Es ist eine seit langem etablierte Tatsache, dass ein Leser vom lesbaren Inhalt einer Seite abgelenkt wird, wenn er deren Layout betrachtet. Der Sinn der Verwendung von Lorem Ipsum besteht darin, dass es eine mehr oder weniger normale Buchstabenverteilung aufweist, im Gegensatz zu „Hier Text, hier Text“, wodurch es wie lesbarer, echter Text wirkt.</span></p><p><span style="color: rgb(0, 0, 0);">Viele Desktop-Publishing-Programme und Webseiten-Editoren verwenden Lorem Ipsum inzwischen als Standard-Blindtext, und eine Suche nach „lorem ipsum“ fördert zahlreiche Websites zutage, die sich noch im Aufbau befinden.</span></p><p><span style="color: rgb(0, 0, 0);">Warum verwenden wir es? Es ist eine seit langem etablierte Tatsache, dass ein Leser vom lesbaren Inhalt einer Seite abgelenkt wird, wenn er deren Layout betrachtet.</span></p><p><span style="color: rgb(0, 0, 0);">Woher kommt es? Entgegen der landläufigen Meinung ist Lorem Ipsum kein reiner Zufallstext. Es hat seine Wurzeln in einem Stück klassischer lateinischer Literatur aus dem Jahr 45 v. Chr. und ist damit über 2000 Jahre alt.</span><br></p>



');

DELETE FROM `contact_support_meta` WHERE `language_id` = 2;
INSERT INTO `contact_support_meta` (`language_id`, `contact_heading`, `contact_desc`) VALUES
(2, 'Support-Anfrage senden', 'Wenn du Fragen hast, kontaktiere uns gerne. Unser Kundenservice ist rund um die Uhr für dich da.

'); 

DELETE FROM `seller_levels_meta` WHERE `language_id` = 2;
INSERT INTO `seller_levels_meta` (`language_id`, `level_id`, `title`) VALUES
(2, 1, 'Neuer Anbieter'),
(2, 2, 'Level Eins'),
(2, 3, 'Level Zwei'),
(2, 4, 'Top Bewertet A');

DELETE FROM `footer_links` WHERE `language_id` = 2;
INSERT INTO `footer_links` (`language_id`, `icon_class`, `link_title`, `link_url`, `link_section`) VALUES
(2, '', 'Grafik & Design', '/categories/graphics-design', 'categories'),
(2, '', 'Digitales Marketing', '/categories/digital-marketing', 'categories'),
(2, '', 'Schreiben & Übersetzen', '/categories/writing-translation', 'categories'),
(2, '', 'Video & Animation', '/categories/video-animation', 'categories'),
(2, '', 'Musik & Audio', '/categories/music-audio', 'categories'),
(2, '', 'Programmierung & Technik', '/categories/programming-tech', 'categories'),
(2, '', 'Business', '/categories/business', 'categories'),
(2, '', 'Spaß & Lifestyle', '/categories/fun-lifestyle', 'categories'),
(2, 'fa-file-text-o', 'Allgemeine Geschäftsbedingungen', '/terms_and_conditions', 'about'),
(2, 'fa-google-plus-official', 'fa-google-plus-official', '#', 'follow'),
(2, 'fa-twitter', '', '#', 'follow'),
(2, 'fa-facebook', '', '#', 'follow'),
(2, 'fa-linkedin', '', '#', 'follow'),
(2, 'fa-pinterest', '', '#', 'follow'),
(2, 'fa fa-life-ring', 'Kundensupport', '/customer_support', 'about'),
(2, 'fa-question-circle', 'Wie es funktioniert', '/how-it-works', 'about'),
(2, 'fa-book', 'Wissensdatenbank', '/knowledge_bank/', 'about'),
(2, 'fa-rss', 'Blog', '/blog/', 'about'),
(2, 'fa fa-comments-o', 'Feedback', '/feedback/', 'about');

DELETE FROM `home_cards` WHERE `language_id` = 2;
INSERT INTO `home_cards` (`language_id`, `card_title`, `card_desc`, `card_link`, `card_image`, `isS3`) VALUES
(2, 'Logo-Design', 'Baue deine Marke auf', 'https://www.gigtodo.com/categories/graphics-design/logo-design', '1.jpg', 0),
(2, 'Social Media', 'Erreiche mehr Kunden', 'https://www.gigtodo.com/categories/digital-marketing/social-media-marketing', '2.jpg', 0),
(2, 'Sprechertalent', 'Der perfekte Voiceover', 'https://www.gigtodo.com/categories/video-animation', '7.jpg', 0),
(2, 'Übersetzung', 'Werde international.', 'https://www.gigtodo.com/categories/writing-translation/translation', '4.jpg', 0),
(2, 'Illustration', 'Verleih deinen Träumen Farbe', 'https://www.gigtodo.com/categories/graphics-design/illustration', '5.jpg', 0),
(2, 'Photoshop-Experte', 'Engagiere einen Designer', 'https://www.gigtodo.com/categories/graphics-design/photoshop-editing', '6.jpg', 0);

DELETE FROM `home_section` WHERE `language_id` = 2;
INSERT INTO `home_section` (`language_id`, `section_heading`, `section_short_heading`) VALUES
(2, 'TRÄUM NICHT NUR, LEG LOS.', 'Freelance-Services. Auf Abruf.'); 

DELETE FROM `section_boxes` WHERE `language_id` = 2;
INSERT INTO `section_boxes` (`language_id`, `box_title`, `box_desc`, `box_image`, `isS3`) VALUES
(2, 'Deine Bedingungen', 'Was auch immer du brauchst, um deine To-do-Liste zu vereinfachen&lt;br&gt; ganz gleich, welches Budget du hast.', 'time.png', 0),
(2, 'Dein Zeitplan', 'Finde Dienstleistungen passend zu deinen Zielen und Deadlines&lt;br&gt; so einfach ist das.', 'desk.png', 0),
(2, 'Deine Sicherheit', 'Deine Zahlung ist immer sicher, für ein gutes Gefühl bei jeder Bestellung.', 'tv.png', 0);

DELETE FROM `announcement_bar` WHERE `language_id` = 2;
INSERT INTO `announcement_bar` (`enable_bar`, `bg_color`, `text_color`, `bar_text`, `last_updated`, `language_id`) VALUES
(0, '#2ca35b', '#ffffff', '', '', 2);
