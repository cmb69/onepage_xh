# Onepage\_XH

Onepage\_XH offers tools to design and administrate so-called "onepage"
websites. In the back-end you can administrate the site as usual, but in
the front-end a special template is used, which displays all visible
pages at once, and the menu links to automatically inserted anchors on
the same page.

  - [Requirements](#requirements)
  - [Download](#download)
  - [Installation](#installation)
  - [Settings](#settings)
  - [Usage](#usage)
      - [Template](#template)
      - [Page Data Tab](#page-data-tab)
  - [Limitations](#limitations)
  - [Troubleshooting](#troubleshooting)
  - [License](#license)
  - [Credits](#credits)

## Requirements

Onepage\_XH is a plugin for CMSimple\_XH. It requires CMSimple\_XH ≥
1.6.3 and PHP ≥ 5.3.0 with the *JSON* extension.

## Download

The [lastest release](https://github.com/cmb69/onepage_xh/releases/latest)
is available for download on Github.

## Installation

The installation is done as with many other CMSimple\_XH plugins. See
the [CMSimple\_XH
wiki](https://wiki.cmsimple-xh.org/doku.php/installation#plugins) for further
details.

1.  Backup the data on your server.
2.  Unzip the distribution on your computer.
3.  Upload the whole directory onepage/ to your server into the
    CMSimple\_XH plugins directory.
4.  Set write permissions for the subdirectories config/, css/ and
    languages/.

## Settings

The plugin's configuration is done as with many other CMSimple\_XH
plugins in the website's back-end. Select Plugins → Onepage.

You can change the default settings of Onepage\_XH under "Config". Hints
for the options will be displayed when hovering over the help icons with
your mouse.

Localization is done under "Language". You can translate the character
strings to your own language (if there is no appropriate language file
available), or customize them according to your needs.

The look of Onepage\_XH can be changed under "Stylesheet".

## Usage

### Template

"Onepage" websites require a special template where some of the standard
CMSimple\_XH template tags are replaced with alternatives provided by
Onepage\_XH.

#### `onepage_toc()`

This is a **required** replacement for `toc()`, which displays links to all
visible pages.

#### `onepage_content()`

This is a **required** replacement for `content()`, which shows all visible
pages on the start page of the CMSimple\_XH installation.

#### `onepage_toplink()`

This is an **optional** replacement for `top()`, which provides configurable
smooth scrolling and is only shown when the user has already scrolled
down a bit. Without JavaScript support the link is always shown, and
there's no smooth scrolling but rather a jump. The image of the link can
be changed by putting an image file named up.png into the images/ folder
of the template.

This template tag accepts one optional parameter, the ID of an element.
This way you can define the top of the page individually. If you don't
pass an argument to the function, the link navigates to the very top of
the page.

#### Unsupported Template Tags

Several template tags are unsupported for "onepage" templates:
`content()`, `li()`, `locator()`, `mailformlink()`, `nextpage()`, `previouspage()`,
`printlink()`, `searchbox()`, `sitemaplink()`, `submenu()`, `toc()`.

### Page Data Tab

In the tab "Onepage" (above the editor) you can optionally specify an
additional CSS class for the respective page. This allows for individual
and robust design of the page.

## Limitations

Most likely not all plugins will work seemlessly with "onepage"
websites. For instance, you can't use page\_param's site specific
templates and page redirections.

## Troubleshooting
Report bugs and ask for support either on [Github](https://github.com/cmb69/onepage_xh/issues)
or in the [CMSimple_XH Forum](https://cmsimpleforum.com/).

## License

Onepage\_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Onepage\_XH is distributed in the hope that it will be useful,
but *without any warranty*; without even the implied warranty of
*merchantibility* or *fitness for a particular purpose*. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Onepage\_XH.  If not, see <http://www.gnu.org/licenses/>.

Copyright © 2015-2017 Christoph M. Becker

## Credits

Onepage\_XH was inspired by *Ludwig* and *oldnema*.

The plugin logo was designed by
[Klem](http://commons.wikimedia.org/wiki/File:Yin_and_Yang.svg). Many
thanks for releasing this icon into the public domain.

The "scroll to top" icon is designed by the [Oxygen
Team](http://www.iconarchive.com/show/oxygen-icons-by-oxygen-icons.org.html).
Many thanks for releasing these icons under GPL.

Many thanks to the community at the
[CMSimple\_XH-Forum](http://www.cmsimpleforum.com/) for tips,
suggestions and testing. Especially, I want to thank *smaxle* and *knollsen*
for early testing and good suggestions. Also special thanks to *Holger*,
*lck* and *frase* for long and fruitful discussions with ample suggestions,
their testing and code contributions.

Last but not least many thanks to [Peter Harteg](http://www.harteg.dk/),
the "father" of CMSimple, and all developers of
[CMSimple\_XH](http://www.cmsimple-xh.org/), without whom this amazing
CMS wouldn't exist.
