# Onepage\_XH

Onepage\_XH bietet Werkzeuge um so genannte "Onepage" Websites zu
erstellen und zu Administrieren. Im Backend können Sie die Site wie
gewohnt administrieren, aber im Frontend wird ein besonderes Template
verwendet, das alle sichtbaren Seiten auf einmal zeigt, und das Menü
verlinkt zu automatisch eingefügten Ankern auf der selben Seite.

  - [Voraussetzungen](#voraussetzungen)
  - [Installation](#installation)
  - [Einstellungen](#einstellungen)
  - [Verwendung](#verwendung)
      - [Template](#template)
      - [Page-Data Reiter](#page-data-reiter)
  - [Einschränkungen](#einschränkungen)
  - [Lizenz](#lizenz)
  - [Danksagung](#danksagung)

## Voraussetzungen

Onepage\_XH ist ein Plugin für CMSimple\_XH. Es benötigt CMSimple\_XH ≥
1.6.3 und PHP ≥ 5.3.0 mit der *JSON* Extension.

## Installation

Die Installation erfolgt wie bei vielen anderen CMSimple\_XH-Plugins
auch. Im
[CMSimple\_XH-Wiki](https://wiki.cmsimple-xh.org/doku.php/de:installation#plugins)
finden Sie weitere Details.

1.  Sichern Sie die Daten auf Ihrem Server.
2.  Entpacken Sie die ZIP-Datei auf Ihrem Rechner.
3.  Laden Sie das ganze Verzeichnis onepage/ auf Ihren Server in
    CMSimple\_XHs Plugin-Verzeichnis hoch.
4.  Machen Sie die Unterverzeichnisse config/, css/ und languages/
    beschreibbar.

## Einstellungen

Die Plugin-Konfiguration erfolgt wie bei vielen anderen
CMSimple\_XH-Plugins auch im Administrationsbereich der Website. Wählen
Sie Plugins → Onepage.

Sie können die Voreinstellungen von Onepage\_XH unter "Konfiguration"
ändern. Hinweise zu den Optionen werden beim Überfahren der Hilfe-Icons
mit der Maus angezeigt.

Die Lokalisierung wird unter "Sprache" vorgenommen. Sie können die
Sprachtexte in Ihre eigene Sprache übersetzen, falls keine entsprechende
Sprachdatei zur Verfügung steht, oder diese Ihren Wünschen gemäß
anpassen.

Das Aussehen von Onepage\_XH kann unter "Stylesheet" angepasst werden.

## Verwendung

### Template

"Onepage" Websites benötigen ein besonderes Template, bei dem einige der
normalen CMSimple\_XH Template-Tags durch Alternativen ersetzt sind, die
Onepage\_XH zur Verfügung stellt.

#### `onepage_toc()`

Dies ist ein **erforderlicher** Ersatz für `toc()`, der Links zu allen
sichtbaren Seiten anzeigt.

#### `onepage_content()`

Dies ist ein **erforderlicher** Ersatz für `content()`, der alle sichtbaren
Seiten auf der Startseite der CMSimple\_XH Installation anzeigt.

#### `onepage_toplink()`

Dies ist ein **optionaler** Ersatz für `top()`, der konfigurierbares sanftes
Scrollen anbietet, und nur angezeigt wird, wenn der Anwender bereits
etwas nach unten gescrollt hat. Ohne JavaScript-Unterstützung wird der
Link immer angezeigt, und statt des sanften Scrollens wird gesprungen.
Das Bild des Links kann geändert werden, indem Sie eine Bilddatei mit
dem Namen up.png im images/ Ordner des Templates ablegen.

Dieses Template-Tag akzeptiert einen optionalen Parameter, die ID eines
Elements. Auf diese Weise können Sie den Anfang der Seite individuell
definieren. Wenn Sie der Funktion kein Argument übergeben, verweist der
Link ganz oben auf die Seite.

#### Nicht unterstützte Template-Tags

Mehrere Template-Tags werden für "Onepage" Templates nicht unterstützt:
`content()`, `li()`, `locator()`, `mailformlink()`, `nextpage()`, `previouspage()`,
`printlink()`, `searchbox()`, `sitemaplink()`, `submenu()`, `toc()`.

### Page-Data Reiter

Im Reiter "Onepage" (oberhalb des Editors) kann optional eine
zusätzliche CSS Klasse für die jeweilige Seite vergeben werden. Dies
ermöglicht individuelles und robustes Seitendesign.

## Einschränkungen

Vermutlich werden nicht alle Plugins reibungslos unter "Onepage"
Websites funktionieren. Z.B. können Sie mit page\_params keine
seitenspezischen Templates wählen, und keine Seitenweiterleitung
konfigurieren.

## Lizenz

Onepage\_XH kann unter Einhaltung der
[GPLv3](http://www.gnu.org/licenses/gpl.html) verwendet werden.

Copyright © 2015-2017 Christoph M. Becker

## Danksagung

Onepage\_XH wurde von *Ludwig* und *oldnema* angeregt.

Das Plugin Logo wurde von
[Klem](http://commons.wikimedia.org/wiki/File:Yin_and_Yang.svg)
gestaltet. Vielen Dank für die Freigabe dieses Icons in die
Gemeinfreiheit.

Das "nach oben scrollen" Icon wurde vom [Oxygen
Team](http://www.iconarchive.com/show/oxygen-icons-by-oxygen-icons.org.html)
gestaltet. Vielen Dank für die Veröffentlichung unter GPL.

Vielen Dank an die Community im [CMSimple\_XH
forum](http://www.cmsimpleforum.com/) für Hinweise, Anregungen und das
Testen. Besonderer Dank gebührt *smaxle* und *knollsen* für frühes Testen
und gute Vorschläge. Ebenfalls geht ein besonderes Dankeschön an *Holger*,
*lck* und *frase* für lange und fruchtbare Diskussionen mit reichlich
Vorschlägen, Ihr Testen und den beigesteuerten Code.

Und zu guter letzt vielen Dank an [Peter Harteg](http://www.harteg.dk/),
den "Vater" von CMSimple, und allen Entwicklern von
[CMSimple\_XH](http://www.cmsimple-xh.org/de/) ohne die es dieses
phantastische CMS nicht gäbe.
