# Changelog

Alle nennenswerten Änderungen an diesem Projekt werden hier dokumentiert.
Format angelehnt an [Keep a Changelog](https://keepachangelog.com/de/1.0.0/).

## 2026-08-27

### Hinzugefügt

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
