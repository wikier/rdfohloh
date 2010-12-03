<?php

/*
 *
 * This file is part of RDFohloh.
 *
 * RDFohloh is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * RDFohloh is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with RDFohloh.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

define("RDFOHLOH_BASE_URI", "http://rdfohloh.wikier.org/");
define("RDFOHLOH_BASE_DIR", "/home/wikier/rdfohloh.wikier.org/");
define("RDFOHLOH_SRC", RDFOHLOH_BASE_DIR . "src/");
define("RDFOHLOH_LIBS", RDFOHLOH_BASE_DIR . "lib/");
define("RDFOHLOH_TPLS", RDFOHLOH_BASE_DIR . "tpl/");
define("OHLOH_API_KEY", "OHLOH_API_KEY");
define("GOOGLEMAPS_API_KEY", "GOOGLEMAPS_API_KEY");
define("HOME", "home");
define("ABOUT", "about");
define("PROJECT", "project");
define("USER", "user");
define("HTML", "html");
define("RDF", "rdf");
define("N3", "n3");

include(RDFOHLOH_SRC . "http.php");
include(RDFOHLOH_SRC . "show.php");

$dest = rdfohloh_get_destination();
$code = $dest[0];
$path = $dest[1];

if (!http_set_headers($code, $path)) {
    rdfohloh_show($path);
}

?>
