# Plugin-Entwicklung

Dieses Dokument beschreibt, wie ein Plugin-Paket aufgebaut sein muss, damit
es über **Admin → Plugins → Upload Plugin** (`admin/add_plugin.php`)
installiert werden kann, und wie ein installiertes Plugin tatsächlich mit
dem Rest der Seite zusammenspielt.

## Wichtige Einschränkung zuerst

Der Installer kann Dateien ablegen, Datenbank-Änderungen ausführen und das
Plugin in der `plugins`-Tabelle registrieren. Er kann aber **keine neuen
Seiten oder Funktionen automatisch mit dem Rest der Seite verdrahten**.
Damit ein Plugin an einer bestimmten Stelle (z. B. im Checkout, im
Admin-Menü) tatsächlich etwas tut, muss an dieser Stelle im Core-Code ein
entsprechender, durch `checkPlugin()` abgesicherter `include()`/`require()`-
Aufruf existieren. Das bestehende `paymentGateway`-Plugin ist an rund 15
Stellen im Code fest verankert (`checkout.php`, `revenue.php`,
`admin/includes/body.php`, …) — es ist kein Drag-and-drop-System für völlig
beliebige neue Funktionen, sondern eher ein Baukasten für optionale
Erweiterungen an vorgesehenen, im Core bereits vorbereiteten Stellen.

Ein Plugin-Paket sicher zu installieren ist also der leichte Teil; die
eigentliche Integration an neuen Stellen erfordert weiterhin eine
Code-Änderung im Core.

## Format des Installationspakets (ZIP)

Die hochgeladene ZIP-Datei muss genau drei Dinge enthalten:

```
mein-paket.zip
├── readme.txt
├── plugin.sql
└── files.zip
```

### `readme.txt`

Reiner Text, je eine Zeile pro Feld:

```
Plugin Name: Mein Plugin
Folder: meinPlugin
Compatible Gigtodo Version: 1.5
```

- **`Folder`** muss `^[a-zA-Z0-9_-]+$` entsprechen (keine Sonderzeichen,
  keine Pfade) — das wird vom Installer streng geprüft und bestimmt den
  Zielordner `plugins/<Folder>/`.
- **`Compatible Gigtodo Version`** wird gegen den aktuellen Versionsstand
  (`app_info.version`) geprüft; ist die installierte Version niedriger,
  wird die Installation abgelehnt.

### `plugin.sql`

Rohes SQL, das bei der Installation einmalig ausgeführt wird. Typischer
Inhalt:

1. `CREATE TABLE`/`ALTER TABLE` für die eigenen Tabellen/Spalten des
   Plugins.
2. Eine `INSERT INTO plugins (...)`-Zeile, die das Plugin registriert
   (siehe Tabellenschema unten) — **das übernimmt sonst niemand
   automatisch**.

Aus Sicherheitsgründen werden folgende Schlüsselwörter im SQL abgelehnt
(die Installation bricht dann komplett ab, es wird nichts geschrieben):
`DROP DATABASE`, `GRANT`, `LOAD_FILE`, `INTO OUTFILE`, `INTO DUMPFILE`,
`LOAD DATA`. Ein Plugin braucht keines davon, um seine eigenen Tabellen
anzulegen.

### `files.zip`

Ein **verschachteltes** ZIP mit den eigentlichen Plugin-Dateien. Alle
Einträge darin müssen unter `<Folder>/` liegen, z. B.:

```
files.zip
└── meinPlugin/
    ├── uninstall.php        (optional)
    ├── admin/                (optional, admin-seitige Fragmente)
    └── ... beliebige weitere Dateien
```

Der Installer prüft dabei streng:
- **Kein Path Traversal** (`..`-Segmente werden abgelehnt, keine
  Absolut-Pfade).
- **Kein Eintrag außerhalb von `<Folder>/`** — ein Plugin kann keine
  Dateien in andere Plugin-Ordner oder außerhalb von `plugins/` schreiben.
- **Keine versteckten Dateien** (`.htaccess`, `.env` u. Ä. werden
  abgelehnt).
- **Erlaubte Dateitypen:** `php css js png jpg jpeg gif svg webp json txt
  woff woff2 ttf eot`. Alles andere wird abgelehnt.

Jeder Verstoß bricht die gesamte Installation ab, bevor irgendetwas auf die
Festplatte geschrieben wird.

## Die `plugins`-Tabelle

```sql
CREATE TABLE `plugins` (
  `id`          int(10)      NOT NULL AUTO_INCREMENT,
  `name`        text         NOT NULL,   -- Anzeigename
  `description` text         NOT NULL,
  `folder`      text         NOT NULL,   -- muss exakt `<Folder>` aus readme.txt entsprechen
  `version`     varchar(255) NOT NULL,
  `author`      varchar(255) NOT NULL,
  `author_url`  varchar(255) NOT NULL,
  `status`      int(10)      NOT NULL    -- 0 = deaktiviert, 1 = aktiv
);
```

Diese Zeile muss das Plugin selbst über `plugin.sql` anlegen (mit
`status` üblicherweise `1`, damit es direkt nach der Installation aktiv
ist).

## Laufzeit-Prüfung: `checkPlugin()`

Core-Code prüft die Aktivierung eines Plugins über
`functions/Core.php::checkPlugin($folder, $site = "")`, z. B.:

```php
$paymentGateway = $core->checkPlugin("paymentGateway", "site");
if ($paymentGateway == 1) {
    require($dir.'/plugins/paymentGateway/...');
}
```

`$folder` muss exakt dem `folder`-Wert in der Datenbank entsprechen.
`activate_plugin.php`/`deactivate_plugin.php` schalten `status` einfach
zwischen `1` und `0` um — kein Datei-Zugriff nötig, wirkt sich aber erst
dort aus, wo Core-Code diesen Check tatsächlich abfragt.

## Deinstallation

Legt das Plugin eine `uninstall.php` im eigenen Ordner ab (Top-Level,
`plugins/<Folder>/uninstall.php`), wird diese beim Löschen über
`admin/delete/delete_plugin.php` automatisch eingebunden — dort gehören
z. B. `ALTER TABLE ... DROP` / `DROP TABLE`-Aufräumarbeiten hin, die die
Installation in `plugin.sql` rückgängig machen. Anschließend wird der
gesamte Plugin-Ordner gelöscht.

## Beispiel: bestehendes `paymentGateway`-Plugin

Als Referenz für eine echte Integration lohnt sich ein Blick auf
`plugins/paymentGateway/` und seine Einbindungen in `checkout.php`,
`revenue.php`, `withdrawal_requests.php`, `admin/includes/body.php` und
`admin/includes/payment_settings.php` — dort ist sowohl die Datei- und
Ordnerstruktur als auch das Muster für frontend- und admin-seitige
Einbindung über `checkPlugin()` sichtbar.
