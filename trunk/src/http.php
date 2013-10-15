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

define("RDFOHLOH_LIB_CONTENTNEGOTIATION", RDFOHLOH_LIBS . "contentnegotiation/");
include(RDFOHLOH_LIB_CONTENTNEGOTIATION . "content_negotiation.inc.php");

function rdfohloh_get_destination() {
    if (isset($_POST["type"]) && $_POST["value"]) {
        return rdfohloh_get_form_destination($_POST["type"], $_POST["value"]);
    } else {
        return rdfohloh_get_entitiy_destination();
    }
}

function rdfohloh_get_entitiy_destination() {
    $path = isset($_GET["uri"]) ? $_GET["uri"] : "";
    $sections = array(HOME, ABOUT);
    $types = array(PROJECT, USER);
    $formats = array(HTML, RDF, N3);
    $splitted =  preg_split("/", $path);
    switch(count($splitted)) {

        case 1: 
                $section = $splitted[0];
                if ($section == "") {
                    $section = HOME;
                }
                if (in_array($section, $sections)) {
                    return array(200, $section);
                } else {
                    return array(404, HOME);
                }
                break;

        case 2: 
                $type = $splitted[0];
                $dest = $splitted[1];
                if (contains($dest, ".")) {
                    $splitted2 = preg_split("\.", $dest);
                    $resource = $splitted2[0];
                    $format = $splitted2[1];
                    if (in_array($type, $types) && in_array($format, $formats)) {
                        return array(200, $type . "/" . $resource . "." . $format);
                    } else {
                        return array(404, HOME);
                    }    
                } else {
                    $format = http_get_preferred_content();
                    return array(303, RDFOHLOH_BASE_URI . $type . "/" . $dest . "." . $format);
                }
                break;

        case 3: 
                //backward compatibility
                $type = $splitted[0];
                $resource = $splitted[1];
                $format = $splitted[2];
                return array(301, RDFOHLOH_BASE_URI . $type . "/" . $resource . "." . $format);
                break;

        default:
                return array(404, HOME);

    }
}

function rdfohloh_get_form_destination($type, $value) {
    $location = NULL;
    switch($type) {

        case "projectname":
        case "projectid":
                            $location = RDFOHLOH_BASE_URI . "project/" . $value;
                            break;

        case "userid":
                            $location = RDFOHLOH_BASE_URI . "user/" . $value;
                            break;

        default:
                            $location = RDFOHLOH_BASE_URI;
    }

    return array(303, $location);
}

/**
 * Get the best content type requested
 */
function http_get_preferred_content() {
    $accept = $_SERVER["HTTP_ACCEPT"];
    if (isset($accept)) {
        $appTypes = array();
        $appTypes["type"][0]       = "application/rdf+xml";
        $appTypes["qFactorApp"][0] = 1;
        $appTypes["type"][1]       = "text/n3";
        $appTypes["qFactorApp"][1] = 0.8;
        $appTypes["type"][2]       = "text/html";
        $appTypes["qFactorApp"][2] = 0.3;
        $best = content_negotiation::mime_best_negotiation($appTypes);
        if ($best == "application/rdf+xml" || $best == "application/xml")
            return RDF;
        else if ($best == "text/rdf+n3" || $best == "text/n3" || $best == "text/turtle")
            return N3;
        else
            return HTML;
    } else {
        return RDF;
    }
}

function http_set_headers($code, $dest) {
    header("HTTP/1.1 " . $code);
    if ($code >= 300 && $code <400 && isset($dest)) {
        header("Vary: Accept");
        header("Location: " . $dest);
        return true;
    }
    return false;
}

function contains($string, $substring) {
    $pos = strpos($string, $substring);
    if($pos === false) {
        return false;
    } else {
        return true;
    }
}

?>
