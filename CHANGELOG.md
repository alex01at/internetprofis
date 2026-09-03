# Changelog

Alle nennenswerten Änderungen an diesem Projekt werden hier dokumentiert.
Format angelehnt an [Keep a Changelog](https://keepachangelog.com/de/1.0.0/).

## 2026-09-03

### Geändert

- **`app_update.php`-Layout an die tatsächliche Mehrheits-Konvention
  angepasst.** Nutzer-Hinweis: Die Seite verwendete noch die alte
  `<div class="breadcrumbs">`-Kopfzeile. Nachgeprüft: von 140
  Admin-Fragment-Seiten nutzen das nur noch 27, die übrigen 113 –
  inklusive `dashboard.php`, `view_orders.php`, `view_sellers.php` –
  verwenden stattdessen ein einfaches `main-container`-Wrapper-Div mit
  Titel direkt in der Karte. `app_update.php` entsprechend angepasst.

### Rückgängig gemacht

- Die 9 im vorigen Punkt entfernten Dateien auf Wunsch wiederhergestellt.
  Statt sie zu löschen, sollen die noch sinnvollen darunter stattdessen
  ordentlich in die Navigation (`menu.php`) eingebunden werden.

### Entfernt (zwischenzeitlich, siehe oben)

- **9 weitere, bestätigt tote PHP-Dateien entfernt** — gefunden durch
  systematische Prüfung aller "Fragment"-Verzeichnisse (Dateien, die nur
  eingebunden, nie direkt aufgerufen werden sollen) gegen Referenzen in
  PHP **und** JS, inklusive erweiterungsloser AJAX-URLs (relative Pfade
  ohne Verzeichnis-Präfix wie `includes/msgHeader`). Diese zusätzliche
  Prüfung hat `conversations/includes/showSingle.php`/`msgHeader.php`
  sowie 9 `includes/comp/*.php`-Dateien vor einer fälschlichen Löschung
  bewahrt — die sind echte, aktiv genutzte AJAX-Endpunkte.
  Entfernt: `admin/view/view_countries.php` (ersetzt durch das geroutete
  `admin/countries.php`), `admin/view/view_pages.php` (ersetzt durch
  `admin/includes/pages.php`), `admin/view/view_suggestions.php`
  (zugehöriges Feature bereits in `admin/index.php` auskommentiert),
  `admin/includes/right_sidebar.php`, `admin/includes/profile_card.php`
  (verwaiste Fragmente), `includes/comp/mobile_nav.php` (ersetzt durch
  `includes/comp/mobile_menu.php`), `includes/no_cat.php`,
  `includes/process.php` und `includes/session_config.php` (die letzten
  beiden waren schon länger als unbenutzt bekannt, jetzt entfernt).
  **Bewusst nicht entfernt:** `admin/includes/download.php` — anders als
  die anderen keine tote Codeleiche, sondern eine vollständige,
  funktionierende Datei-Download-Funktion für Auftragsdateien (inkl.
  Wasserzeichen-Logik und S3-Unterstützung), die aktuell nur nirgends
  verlinkt ist.

## 2026-08-27

### Entfernt

- **Toter/doppelter CSS- und Vendor-Code entfernt** (353 Dateien,
  ~168.000 Zeilen). Vor jeder Löschung projektweit (alle `.php` **und**
  `.js`) geprüft, ob wirklich keine Referenz mehr existiert; auffällige
  Zufallstreffer einzeln nachgeprüft und als unabhängige
  Namensgleichheit bestätigt.
  - Frontend: `sweat_alert_backup.css` (byte-identisches Duplikat von
    `sweat_alert.css`), `footer.css` (0 Bytes), `chosen.css`,
    `home.css`, `jquery-ui.css` + `js/jquery-ui.js` (nirgends
    eingebunden). Nachtrag: `styles/summernote.css` zunächst stehen
    gelassen, da der Editor aktiv genutzt wird – Nachprüfung ergab
    aber, dass alle drei Editor-Seiten (`admin/insert/insert_article.php`,
    `admin/insert/insert_blog_posts.php`, `admin/edit/edit_term.php`)
    das Stylesheet bereits über ein CDN laden
    (`cdn.jsdelivr.net/npm/summernote@0.8.16`), kein fehlender Link
    also – die lokale Datei war wirklich unbenutzt und wurde
    nachträglich ebenfalls entfernt. Nebenbei aufgefallen: das ist die
    einzige Stelle im Admin-Panel, die nicht selbst gehostet wird
    (alles andere liegt lokal unter `admin/src`/`admin/vendors`) —
    nicht behoben, da nicht angefragt, aber erwähnenswert.
  - Admin: `core.min.css`, `icon-font.css`, `style.min.css`
    (unbenutzte Build-Varianten). Von 29 gebündelten Vendor-Plugins in
    `admin/src/plugins/` waren **26 komplett unreferenziert** (nur
    `apexcharts`, `cropperjs`, `datatables` werden benutzt) – u. a.
    Bootstrap, Select2, Fullcalendar, SweetAlert2, Fancybox, Dropzone,
    jVectorMap. Alle 5 Ordner in `admin/src/fonts/` waren unbenutzte
    Quell-Kopien der Icon-Fonts – die tatsächlich geladenen Dateien
    liegen komplett eigenständig in `admin/vendors/fonts/`.
- **`admin_old/` komplett entfernt** (11 MB, 181 Dateien). Systematischer
  Abgleich bestätigte: jede Seite ist im neuen `admin/` vorhanden (teils
  umbenannt/umstrukturiert), bewusst nicht mehr relevant (Kaufcode-/
  Lizenzprüfung fürs alte CodeCanyon-Produkt), oder war auch in
  `admin_old` nie fertig gebaut (S3-Datei-Browser, Datei-Pagination).
  Der einzige echte Fund dabei (`admin/change_password.php`, siehe oben)
  wurde vorher wiederhergestellt. Der Link „Old Admincenter" im
  Admin-Menü ist ebenfalls weg.

### Behoben (Bugs) – Navigation & doppelte Seitenstruktur

- **"Application Update"-Link war in der falschen Datei.** Der Link
  wurde ursprünglich in `admin/includes/sidebar.php` ergänzt – diese
  Datei wird aber in der echten Seite **gar nicht eingebunden**. Die
  tatsächlich angezeigte Navigation kommt aus `admin/includes/menu.php`.
  Alle Link-Korrekturen dort waren dadurch wirkungslos. Link jetzt an
  der richtigen Stelle (`menu.php`) ergänzt.
- **Doppelte alte Seitenstruktur bei "Filter Proposals" gefunden und
  behoben.** Zwei parallele Alt-Implementierungen derselben Funktion:
  `admin/filter_proposals.php` (über den Router erreichbar, aber gab
  mitten in der Seite ein eigenes `</head><body>` samt eigener
  Sidebar/Header aus – ungültiges, verschachteltes HTML) und
  `admin/includes/filter_proposals.php` (eine zweite, fast identische
  716-Zeilen-Kopie, auf die alle fünf "View Proposals"-Seiten ihr
  Filterformular direkt als eigenständigen Seitenaufruf schickten –
  hier wurde `sidebar.php` tatsächlich gerendert, mit den vorher
  "reparierten", aber wirkungslosen Links). Auf die geroutete Version
  konsolidiert, redundante Datei entfernt, Filterlogik selbst
  unverändert. `admin/includes/sidebar.php` danach komplett entfernt,
  da bestätigt tot.

### Geändert

- **Update-Anzeige reagiert jetzt auf veröffentlichte Releases statt auf
  jeden Commit.** Vorher zeigte `admin/app_update.php` jeden Push auf
  `main` sofort als "Update verfügbar" — jetzt erst, wenn jemand
  bewusst eine [GitHub Release](https://github.com/alex01at/internetprofis/releases)
  veröffentlicht. Ruft dafür `/releases/latest` statt `/commits/main`
  ab und löst den zugehörigen Tag über `/commits/{tag}` auf; zeigt
  zusätzlich den Release-Text über der Commit-Liste an. Klarer Hinweis,
  falls noch kein Release existiert. Tag `v1.0.0` wurde als erster
  Kandidat gepusht — **muss aber noch einmalig manuell auf GitHub als
  echter Release veröffentlicht werden** (Releases → Draft a new
  release → v1.0.0 auswählen → Publish), das kann ich von hier aus
  nicht ohne API-Token auslösen.

### Hinzugefügt

- **Echte, sichere Auto-Update-Funktion** in `admin/app_update.php` (ersetzt
  den bisherigen reinen Status-Checker). Der entscheidende Unterschied zur
  ursprünglich entfernten, gefährlichen Version: die Quelle ist fest auf
  euer eigenes GitHub-Repo per HTTPS begrenzt – kein Upload-Weg für ein
  präpariertes Paket, keine SQL-Ausführung aus einer Fremdquelle.
  - Verfolgt den deployten Stand über `admin/.deployed_version`
    (server-lokal, nicht im Repo) und fällt auf `.git/HEAD` zurück, falls
    vorhanden – funktioniert also sowohl bei SSH+Git-Deployment als auch
    bei reinem FTP-Upload ohne `.git`-Ordner.
  - Ohne bekannten Ausgangspunkt: ein Button markiert den aktuellen
    GitHub-Stand als Basis (ändert keine Dateien) – gedacht für direkt
    nach einem manuellen Sync.
  - Fürs eigentliche Update: holt die Datei-Diff-Liste über die
    GitHub-Compare-API zwischen deploytem und aktuellem Commit, lädt
    jede geänderte Datei einzeln über `raw.githubusercontent.com` und
    schreibt sie direkt, entfernte Dateien werden gelöscht.
  - Jeder Pfad läuft durch dieselbe Zip-Slip-Absicherung wie beim
    Plugin-Installer, plus eine explizite Sperrliste für `config.php`,
    `admin/.deployed_version`, `.git/` und alle fünf Ordner mit echten
    Nutzerinhalten.
  - Bricht ab, wenn der Diff zu groß ist, um ihn sicher aufzulisten
    (GitHub-Compare-API deckelt bei 300 Dateien).
  - Der Versions-Marker wird nur aktualisiert, wenn wirklich jede Datei
    erfolgreich war – bei einem Teilausfall bleibt er stehen, ein
    erneuter Versuch wiederholt genau dieselben Dateien statt still aus
    dem Takt zu geraten.
  - Verlangt eine explizite Bestätigung (JS-Dialog) vor der Ausführung.
  - **Gegen das echte Repo getestet**, nicht simuliert: eigener
    Test-Harness mit einer vor-Fix-Version von `home.php` auf der
    Festplatte, Update-Logik gegen die echte GitHub-API für zwei reale
    Commit-Bereiche laufen lassen – einmal eine Datei-Änderung
    (Byte-für-Byte-Ergebnis verifiziert), einmal eine Datei-Löschung
    (tatsächliches Verschwinden von der Festplatte verifiziert). Die
    Pfad-Absicherung direkt gegen `config.php`, `order_files/*` und
    `../`-Traversal getestet – korrekt abgelehnt, normale Datei
    korrekt akzeptiert.

### Behoben (Bugs)

- **Doppelt kodiertes HTML in der "Warum wir"-Sektion der Startseite.**
  Live-Review der Seite zeigte `no&lt;br&gt; matter your budget` statt
  eines Zeilenumbruchs im Text der "Your Terms"-Box. Ursache:
  `libs/input.php`'s `Input::post()` wendet bereits beim Speichern
  `htmlspecialchars()` auf jeden Textwert an (Admin-Formulare
  `insert_box.php`/`edit_box.php`), `home.php` hat beim Ausgeben
  zusätzlich noch mal escaped – macht aus einem eingegebenen `<br>`
  sichtbar `&lt;br&gt;`. Behoben, indem `home.php` den bereits sicher
  gespeicherten Wert unverändert ausgibt statt doppelt zu escapen.
  **Wichtig:** Dasselbe Muster (Escaping beim Speichern *und* beim
  Anzeigen) betrifft vermutlich weitere Felder im Projekt – z. B. im
  Footer sichtbar als "Graphics &amp; Design" statt "Graphics &
  Design". Nur die konkret gemeldete Stelle gefixt, nicht
  projektweit durchgezogen, da das viele Dateien betrifft und
  einzeln geprüft werden sollte.

### Geändert (PHP 8.5)

- **PHP-8.5-Kompatibilität geprüft.** Eigenen Code (nicht `vendor/`) gegen
  jede Deprecation und jeden Breaking Change aus dem offiziellen
  PHP-8.5-Migrationsleitfaden abgeglichen. Die meisten Kategorien: null
  Treffer (keine nicht-kanonischen Casts, kein Backtick-Shell-Exec,
  keine `__sleep()`/`__wakeup()`, kein `mysqli_execute()`, keine
  problematische `PDO::FETCH_CLASS`-Nutzung). Zwei echte Funde,
  beide behoben:
  - `libs/database.php`: `PDO::MYSQL_ATTR_INIT_COMMAND` ist ab 8.5
    deprecated (Ersatz: `Pdo\Mysql::ATTR_INIT_COMMAND`, existiert aber
    erst ab PHP 8.4). Da ihr weiterhin PHP 8.2+ als Ziel habt, direkt
    umstellen hätte auf 8.2/8.3 einen Fatal Error verursacht (Klasse
    existiert dort nicht) – stattdessen mit `class_exists()`-Prüfung
    versionssicher gemacht, lokal auf PHP 8.3 verifiziert (fällt korrekt
    auf die alte Konstante zurück).
  - 11 überflüssige `curl_close()`/`imagedestroy()`-Aufrufe in 10
    Dateien entfernt – beide Ressourcentypen werden seit PHP 8.0
    automatisch freigegeben, der explizite Aufruf ist seit 8.5 als
    deprecated markiert und war ohnehin nur ein No-op.
  - Composer-Abhängigkeiten (Stripe, Guzzle, AWS SDK, PHPMailer etc.)
    haben alle offene `php`-Versionsangaben (`>=X.Y`, keine
    Obergrenze) – blockieren die Installation auf 8.5 also nicht.
  - **Fazit: nichts gefunden, das auf PHP 8.5 tatsächlich brechen würde**
    – nur die zwei genannten Deprecation-Warnquellen, beide behoben.

### Behoben (Bugs)

- **4 Upload-Verzeichnisse hätten nach einem frischen `git clone` gar nicht
  existiert.** Git verfolgt keine leeren Verzeichnisse – von den 5 über
  `.gitignore` von echten Nutzerinhalten befreiten Ordnern hatte nur
  `order_files/` eine getrackte Platzhalter-Datei (`.htaccess`). Die
  anderen vier (`conversations/conversations_files`,
  `proposals/proposal_files`, `requests/request_files`, `ticket_files`)
  wären nach dem Klonen schlicht nicht vorhanden gewesen – jeder Upload
  dorthin (Bilder, Chat-Anhänge, Anfrage-/Ticket-Dateien) wäre
  fehlgeschlagen. `.gitkeep`-Platzhalter ergänzt.
  Dabei einen eigenen Fehler gefunden und korrigiert: Zuerst versehentlich
  `conversations_files` mit „Deny from all" gesperrt, in der Annahme, es
  bräuchte denselben Schutz wie `order_files`. Vor dem Commit im Code
  geprüft: `getImageUrl()` verlinkt Proposal-Bilder, Chat-Anhänge,
  Anfrage- und Ticket-Dateien überall direkt öffentlich (Schutz nur durch
  unerratbare Dateinamen, keine echte Zugriffskontrolle) – nur
  `order_files` läuft tatsächlich über einen authentifizierten
  Download-Pfad. Eine pauschale Sperre hätte Proposal-Bilder,
  Chat-Vorschauen und Anfrage-/Ticket-Downloads auf der Live-Seite
  kaputt gemacht. Stattdessen ein harmloses `.gitkeep` verwendet;
  `order_files/.htaccess` selbst auf eine Apache-2.2-und-2.4-kompatible
  Syntax modernisiert.

### Hinzugefügt

- **Alle Fremd-CDN-Assets lokal eingebunden.** Projektweit alle
  `http(s)`-Ressourcen durchsucht und in zwei Gruppen sortiert:
  - **Jetzt lokal:** Google Fonts (Roboto, Open Sans – Latin/Latin-Ext
    als woff2 unter `fonts/roboto/` bzw. `fonts/opensans/`),
    Summernote-Editor-CSS inkl. Icon-Font (beide im Einsatz befindlichen
    Versionen 0.8.16 und 0.8.18 unter `styles/summernote-0.8.16/` bzw.
    `-0.8.18/`, betraf 11 Frontend- und Admin-Seiten), Chosen.js
    (`js/chosen.jquery.min.js`, in `user.php`).
  - **Bewusst extern gelassen** (sind keine Assets, sondern
    Pflicht-Live-Dienste): Stripe.js/`checkout.stripe.com` (Stripe
    schreibt das Laden von der eigenen Domain als Teil der
    PCI-DSS-Compliance zwingend vor – selbst hosten würde Stripes
    Betrugserkennung aushebeln und deren Nutzungsbedingungen
    verletzen), das PayPal-SDK (gleicher Grund), Google reCAPTCHA
    (ein Live-Challenge-Dienst, kein statisches Skript) sowie Google
    Tag Manager/Google Translate. Reine `<a href>`-Doku-/Credit-Links
    (GitHub, Währungscode-Referenzen, Font-Awesome-Icon-Browser etc.)
    unangetastet, da keine geladenen Ressourcen.
- **Plugin-Update-Funktion wiederhergestellt, gehärtet** (`admin/update_plugin.php`).
  Gleiche Behandlung wie beim Installer: Zip-Slip-Schutz auf beide
  Entpack-Schritte, `files.zip` muss unter dem in der DB hinterlegten
  Plugin-Ordner bleiben, `update.sql` läuft über die bestehende
  DB-Verbindung mit derselben Blockliste. Dabei zwei zusätzliche Bugs
  gefunden und behoben: Der Plugin-Lookup baute rohes SQL aus dem
  unvalidierten `update_plugin`-GET-Parameter zusammen (`... where
  id='$plugin_id'`) – eine klassische SQL-Injection, jetzt über die
  parametrisierte `$db->select()`-Methode gelöst. Und
  `delete_files.txt` (optionale Lösch-Liste im Update-Paket) wurde ganz
  ohne Pfad-Prüfung per `unlink()` abgearbeitet – ein präpariertes
  Update-Paket hätte beliebige Dateien löschen können, für die der
  Webserver-Nutzer Schreibrechte hat. Jetzt wird jede Zeile gegen den
  Plugin-Ordner geprüft (`realpath`-Check), mit einer präparierten
  Test-Datei gegen einen Path-Traversal-Versuch verifiziert. Außerdem
  wurde die `version`-Spalte nach einem Update vorher nie in der
  Datenbank aktualisiert – jetzt behoben.
  [plugins/README.md](plugins/README.md) um das Update-Paket-Format
  ergänzt.
- **Admin-Passwort-Reset wiederhergestellt** (`admin/change_password.php`).
  Beim Check, ob `admin_old` entbehrlich ist, aufgefallen: Der
  „Passwort vergessen"-Ablauf war komplett kaputt – die E-Mail wird
  korrekt verschickt, aber der enthaltene Link
  (`admin/change_password?code=...`) zeigte ins Leere, da diese Datei
  beim Admin-Redesign nie übernommen wurde. Ohne diese Seite hätte sich
  ein Admin bei vergessenem Passwort komplett ausgesperrt. Logik
  unverändert aus `admin_old` übernommen (die Lookup-Query war bereits
  sicher parametrisiert), an das neue Login-Seiten-Design angepasst
  (die alte Version verwies auf einen inzwischen nicht mehr
  existierenden Asset-Pfad) und um eine Mindestlänge fürs neue Passwort
  ergänzt.
- **Plugin-Installer gehärtet statt entfernt** (`admin/add_plugin.php`).
  Anders als bei `app_update.php`/`update_plugin.php` wird diese Funktion
  weiterhin gebraucht, deshalb bleibt sie erhalten, aber mit denselben
  Schutzmaßnahmen: jeder ZIP-Eintrag wird vor dem Entpacken einzeln geprüft
  (kein Path Traversal, keine Absolut-Pfade, keine versteckten Dateien),
  alle Dateien aus `files.zip` müssen unter dem im Manifest deklarierten
  Ordnernamen liegen (validiert gegen `^[a-zA-Z0-9_-]+$`) und einer
  erlaubten Dateityp-Liste entsprechen, und `plugin.sql` wird auf eine
  kleine Blockliste gefährlicher Statements geprüft (`DROP DATABASE`,
  `GRANT`, `LOAD_FILE`, `INTO OUTFILE`/`DUMPFILE`, `LOAD DATA`). Schlägt
  der DB-Schritt fehl, wird ein bereits entpackter Plugin-Ordner jetzt
  wieder zurückgerollt statt halb installiert liegen zu bleiben. Die
  Zip-Slip-Erkennung wurde standalone gegen eine präparierte Test-ZIP mit
  Path-Traversal-Eintrag getestet – korrekt abgelehnt, nichts geschrieben.
  Dazu neu: [plugins/README.md](plugins/README.md) dokumentiert das
  geforderte Paket-Format, das `plugins`-Tabellenschema, den
  `checkPlugin()`-Aktivierungsmechanismus – und explizit die Grenze, dass
  der Installer ein Plugin nicht automatisch in Seiten einbindet, die noch
  keinen passenden, durch `checkPlugin()` abgesicherten `include()`-Aufruf
  dafür haben; das bleibt eine Core-Code-Änderung.
- **Sicherer, rein lesender Update-Checker als Ersatz für `app_update.php`.**
  Liest den lokal deployten Git-Commit direkt aus `.git/HEAD` (nur
  Datei-Lesezugriffe, kein `exec`/`shell_exec`) und vergleicht ihn über
  die GitHub-Compare-API mit `alex01at/internetprofis` – zeigt an, wie
  viele Commits das Deployment hinterherhängt, mit Links zu den
  einzelnen Änderungen. Es wird nichts heruntergeladen, entpackt oder
  ausgeführt; ein Update bleibt ein bewusstes `git pull` auf dem Server.
  Bei nicht erreichbarem GitHub oder fehlendem `.git`-Ordner (z. B. bei
  FTP-Deployment) zeigt die Seite eine freundliche Fallback-Meldung statt
  zu crashen. Gegen das echte Repo getestet – meldet korrekt „identical"
  auf dem aktuellen HEAD. Sidebar-Link „Application Update" wieder
  hergestellt.

### Behoben (Bugs)

- **14 fehlende Admin-Seiten aus `admin_old` nach `admin` portiert.**
  Betroffen: `activate_plugin`, `deactivate_plugin`, `cancel_order`,
  `approve_referral`, `approve_proposal_referral`, `get_provider_id`,
  `remove_feature_proposal`, `submit_modification`, `unapprove_request`,
  `view_withdrawals`, `customer_support_settings`, `order_reports`,
  `proposal_reports`, `completed_transactions`. Vor dem Kopieren jede
  Datei einzeln geprüft (keine unparametrisierten SQL-Stellen, keine
  unsicheren Dateizugriffe); `completed_transactions.php` bekam
  zusätzlich einen expliziten `(int)`-Cast auf den Pagination-Parameter.
- **Bewusst nicht portiert: `app_update.php` und `update_plugin.php`.**
  Beide nehmen einen ZIP-Upload entgegen, entpacken ihn ohne Schutz vor
  Path Traversal/Zip Slip und führen die darin enthaltene `update.sql`
  als rohe Mehrfach-Query über eine eigene, ungeschützte
  `new PDO(...)`-Verbindung aus – am Ende der Kette also potenziell
  beliebiges Schreiben/Löschen von Dateien plus beliebige SQL-Ausführung,
  nur durch eine aktive Admin-Session abgesichert. Das war für den
  Download signierter Update-Pakete vom ursprünglichen Codester-Angebot
  gedacht, was auf diesen GitHub-verwalteten Fork nicht mehr zutrifft.
  Nicht wiederhergestellt, um dieses Risiko nicht erneut einzuführen.
- **Kaputte Sidebar-Links im Admin-Panel.** Systematischer Abgleich von
  `admin/includes/sidebar.php` gegen die Router-Tabelle in
  `admin/includes/body.php` und die tatsächlich vorhandenen Dateien ergab:
  7 Sidebar-Links nutzten alte Parameter-Namen von vor dem Admin-Redesign,
  obwohl die jeweilige Funktion unter einem neuen Namen längst
  funktioniert – u. a. **Allgemeine Einstellungen, Zahlungseinstellungen
  und Mail-Server-Einstellungen waren über die Sidebar gar nicht
  erreichbar**. Korrigiert: `general_settings`→`general`,
  `payment_settings`→`payment`, `mail_settings`→`mail-server`,
  `email_templates`→`mail-templates`, `posts`→`blog`,
  `post_categories`→`blog_categories`, `post_comments`→`blog_comments`.
  Zusätzlich zwei tote Links entfernt: „Application License" (Lizenz-Check
  ist im Code bewusst auskommentiert – folgerichtig für den Open-Source-
  Fork) und „Layout Settings" (ersetzt durch die separaten Theme/Color/
  Logo-Settings-Seiten, über das obere Menü erreichbar).
- **Noch offen, nicht versteckt:** „Application Update", „Customer Support
  Settings", „Order Reports" und „Proposal Reports" sind weiterhin in der
  Sidebar verlinkt, die Zieldateien existieren im neuen `admin/` aber
  nicht – diese vier (plus rund ein Dutzend weiterer Aktionen wie
  Plugin-Aktivierung, Bestellung stornieren, Referral genehmigen,
  Auszahlungs-Übersicht) wurden beim Admin-Redesign nicht aus
  `admin_old/` übernommen. Bewusst nicht automatisch entfernt oder
  portiert, da echte fehlende Funktionalität, keine kaputten Links –
  siehe Konversation für die vollständige Liste.

### Sicherheit

- **Fehlerausgabe global abgeschaltet.** `.user.ini` setzte
  `display_errors = on` für die ganze Seite; nur `config.php` hat es lokal
  wieder deaktiviert. Viele Einstiegspunkte (`admin/`, `admin_old/,
  Direktaufrufe) binden `config.php` aber nie ein und hätten PHP-Fehler
  inkl. Dateipfaden an anonyme Besucher ausgegeben. Jetzt zusätzlich
  `log_errors = on`, damit Fehler weiterhin im Server-Log landen.
- **Session-Cookies gehärtet.** `session.cookie_httponly`,
  `session.cookie_secure` und `session.cookie_samesite=Lax` zentral über
  `.user.ini` gesetzt (nötig, da `session_start()` an über 600 Stellen ohne
  gemeinsamen Bootstrap aufgerufen wird). `Secure` ist unbedenklich, da
  `.htaccess` bereits auf HTTPS erzwingt; `Lax` statt `Strict`, damit der
  Facebook-/Google-OAuth-Redirect zurück auf `fb-callback.php`/
  `g-callback.php` das Cookie noch mitbekommt.
- **Session Fixation behoben.** An jedem Punkt, an dem eine neue
  authentifizierte Identität entsteht, wird jetzt vorher
  `session_regenerate_id(true)` aufgerufen: normaler Login/Registrierung
  (`login.php`, `includes/register_login_forgot.php`), Google-/Facebook-
  Login (`g-register.php`, `fb-register.php`), Admin-Login (`admin/login.php`,
  `admin_old/login.php`) und die Admin-Funktion „als Verkäufer einloggen"
  (`admin/seller_login.php`, `admin_old/seller_login.php`). Vorher blieb
  eine vor dem Login bestehende Session-ID nach dem Login gültig.
- `plugins/` überprüft (einziges vorhandenes Plugin: 2Checkout-Zahlungs-
  gateway, ~200 KB) – keine Auffälligkeiten, alle scheinbar ungeschützten
  Dateien sind Fragmente, die nur aus bereits abgesicherten Seiten
  eingebunden werden.
- Nebenbei entdeckt: `route.php` + `sites/login.php` sind ein komplett
  unerreichbares, totes Alt-Routing/Login-Duplikat (kein Verweis darauf in
  `.htaccess` oder irgendeiner anderen Datei). Nicht entfernt, da ohne
  echtes Risiko – aber als Aufräum-Kandidat notiert.
- **Kritisch – unbenutzte `apis/`-REST-API entfernt (Auth-Bypass).** `apis/`
  war eine über CodeIgniter 3 gebaute REST-API für eine Mobile App, die nie
  gebaut/eingesetzt wurde (keine einzige Referenz darauf sonst irgendwo im
  Code; Controller-Kommentare sprechen sogar von einer Arzt/Patient/Pfleger-
  Domäne – offensichtlich unangepasstes Boilerplate aus einer anderen
  Vorlage). Der Zugriffsschutz in
  `apis/application/core/MY_Controller.php` verglich den API-Key so:
  `if($this->input->post("apiKey") != $this->mobileApp_apiKey){ exit(); }`
  – lose Vergleich (`!=`). Da `mobileApp_apiKey` nie konfiguriert wurde
  (leerer String in der DB), lieferte ein Request ganz ohne `apiKey`-Feld
  `NULL` von `input->post()`, und `NULL != ''` ist in PHP `false` – die
  Prüfung ließ jeden unautorisierten Request durch. Dahinter lag u. a.
  `Apis::data($table, $id)`, ein generischer „gib mir jede Zeile aus jeder
  Tabelle zurück"-Endpunkt ohne Tabellen-Whitelist – erreichbar hätte das
  z. B. `admins` (Passwort-Hashes) oder `payment_settings` (echte Stripe/
  PayPal/AWS-Keys) offengelegt. Da keine App davon abhängt, wurde das
  gesamte Verzeichnis entfernt statt nur gepatcht.
- **Kritisch – PHP Object Injection behoben.** `checkout.php` hat die vom Käufer
  gewählten Bestell-Extras serialisiert und base64-kodiert in ein verstecktes
  Formularfeld geschrieben (`<input type="hidden" name="proposal_extras" ...>`).
  Dieses Feld läuft durch den Browser und ist damit vom Client vollständig
  manipulierbar, wurde beim Absenden aber direkt mit `unserialize()`
  eingelesen (`checkout.php:130`, `:287`) – ein klassischer
  [CWE-502](https://cwe.mitre.org/data/definitions/502.html)-Einfallspunkt.
  Da über Composer u. a. Guzzle, Symfony- und Doctrine-Komponenten geladen
  sind, war eine sogenannte POP-Gadget-Chain (bis zu Remote Code Execution)
  plausibel.
  Fix: alle `unserialize()`-Aufrufe auf potenziell manipulierten Daten rufen
  jetzt `unserialize($data, ['allowed_classes' => false])` auf – Arrays und
  einfache Werte (die einzigen hier je genutzten Datentypen) funktionieren
  weiter wie bisher, Objekt-Instanziierung ist aber komplett blockiert.
  Betroffen: `checkout.php`, `crypto_order.php`, `paypal_order.php`,
  `dusupay_order.php`, `functions/payment.php`.
  Gleiche Härtung vorsorglich auch auf die Auswertung der externen (nicht
  TLS-gesicherten) geoplugin.net-Antwort angewendet: `g-register.php`,
  `fb-register.php`, `apis/application/models/Database.php`,
  `includes/register_login_forgot.php`.
- Verwaistes Debug-Skript `admin/hash.php` entfernt – generierte ohne jede
  Zugriffskontrolle einen bcrypt-Hash für das hartkodierte Test-Passwort
  `gigtodo` und war öffentlich per URL erreichbar.
- Doppelte, tote Datei `admin/updateHtaccess.php` entfernt (identisch zu
  `admin/includes/updateHtaccess.php`, definierte nur eine nie aufgerufene
  Funktion – für sich genommen ungefährlich, aber unnötige Angriffsfläche).
- **Offen / empfohlen, noch nicht umgesetzt:** Im gesamten Code wurde kein
  einziges CSRF-Token gefunden (`grep -ri csrf` → 0 Treffer). Formulare für
  Login, Profil-/Passwortänderung, Admin-Einstellungen, Auszahlungen etc.
  sind dadurch grundsätzlich anfällig für Cross-Site-Request-Forgery. Fix
  wäre projektweit (viele Formulare) und sollte als eigenes Vorhaben
  geplant werden, nicht nebenbei.
- **Offen / empfohlen, noch nicht umgesetzt:** `.user.ini` setzt
  `display_errors = on` global; nur `config.php` selbst schaltet es lokal
  wieder ab. Andere Einstiegspunkte (z. B. direkte Aufrufe unter `admin/`,
  `apis/`) können PHP-Fehlermeldungen inkl. Pfaden an Besucher ausgeben.

### Geändert (Abhängigkeiten)

- Composer-Update auf den aktuellen, innerhalb der bestehenden
  Versionsgrenzen erlaubten Stand durchgeführt – `composer audit` meldet
  keine bekannten Sicherheitslücken mehr.
- `paypal/rest-api-sdk-php` entfernt (von PayPal selbst als *abandoned*
  markiert). Die einzige Verwendung war `paypal_adaptive.php`
  (PayPal-Adaptive-Payments-Auszahlung an Freelancer) – diese PayPal-API
  wurde von PayPal selbst schon vor Jahren abgeschaltet, der Weg war also
  ohnehin tot. `revenue.php` leitet PayPal-Auszahlungen jetzt immer über
  den bestehenden manuellen Auszahlungs-Workflow (`withdraw_manual`).
- `paypal/paypal-checkout-sdk` von fest gepinnt `1.0.1` auf `^1.0` gelockert
  und Patch-Release `1.0.2` gezogen.
- Nebenbei entdeckt und behoben: `composer.lock` hatte `symfony/filesystem`
  auf eine Version aufgelöst, die PHP ≥ 8.4 voraussetzt – unvereinbar mit
  dem dokumentierten Ziel PHP 8.2. `composer.json` pinnt die Zielplattform
  jetzt explizit auf PHP 8.2 (`config.platform.php`), Lockfile neu aufgelöst.
- Eingebettetes CodeIgniter-Framework in `apis/` von 3.1.10 auf 3.1.13
  aktualisiert (letzte verfügbare CI3-Version, CI3 selbst gilt als EOL).
  Bringt u. a. PHP-8-Session-Handler-Kompatibilität, die 3.1.10 fehlte.
  `apis/application` (eigener Anwendungscode) wurde dabei nicht angefasst.

### Behoben (Bugs)

- `includes/process.php`: ungültige Regex-Syntax (`/…/` ohne umschließende
  Anführungszeichen – das ist JavaScript-, nicht PHP-Syntax) führte bei
  jedem Aufruf zu einem PHP Fatal Parse Error.
- `admin_old/view_proposals_files.php`: fehlende schließende `}` einer
  if/else-Struktur, ebenfalls ein Fatal Parse Error. Datei ist über den
  Menüpunkt „Old Admincenter“ im aktiven Admin-Panel weiterhin erreichbar.
- Beide Funde stammen aus einem vollständigen `php -l`-Durchlauf über alle
  ~15.900 PHP-Dateien des Projekts (vorher nicht möglich, da auf diesem
  Rechner weder PHP noch Composer installiert waren).

### Entfernt (Aufräumen)

- `install.php`, `install2.php`, `install3.php` – Installer-Skripte, die
  nach der Ersteinrichtung im Live-Dokumentenverzeichnis keine Aufgabe mehr
  haben und potenziell eine DB-Neukonfiguration durch Dritte erlauben.
- `libs/database.php.bak` – Backup-Datei im Web-Root, legte den Quellcode
  der DB-Schicht offen.

### Sonstiges

- `.htaccess`: `.git`, `.bak`, `.old` und `.sql` werden jetzt per Regel vor
  direktem Web-Zugriff blockiert.
- Lokales Git-Repository für das Projekt eingerichtet (gab es vorher nicht)
  und mit dem bestehenden GitHub-Repo
  [alex01at/internetprofis](https://github.com/alex01at/internetprofis)
  synchronisiert. `config.php` (enthält die DB-Zugangsdaten) sowie
  Verzeichnisse mit echten Nutzerinhalten (`order_files`,
  `conversations/conversations_files`, `proposals/proposal_files`,
  `requests/request_files`, `ticket_files`) sind dauerhaft per
  `.gitignore` ausgeschlossen und wurden zu keinem Zeitpunkt in die
  Git-Historie aufgenommen (das öffentliche Repo enthielt vorher andere,
  ältere Commits – diese wurden auf Wunsch vollständig ersetzt).

### Nicht angefasst (bewusste Entscheidung)

- `admin_old/` bleibt bestehen – ist über einen aktiven Link im aktuellen
  Admin-Menü ("Old Admincenter") erreichbar, vermutlich als Fallback für
  Funktionen, die im Admin-Redesign noch fehlen.
- Pakete mit verfügbaren Major-Version-Sprüngen (Stripe 7→21, Twilio 6→8,
  PHPMailer 6→7, Guzzle 7→8, Mercadopago 2→3) sowie die als *abandoned*
  markierten `swiftmailer/swiftmailer` (→ `symfony/mailer`) und
  `paypal/paypal-checkout-sdk` (→ `paypal/paypal-server-sdk`) wurden nicht
  automatisch aktualisiert – das sind Breaking-Change-Releases in
  Zahlungs-/Mail-Code, die gezielte Tests brauchen.
