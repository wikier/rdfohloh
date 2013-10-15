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

include(RDFOHLOH_SRC . "entities.php");

function rdfohloh_show_debug($path) {
    echo $path;
}

function rdfohloh_show($path) {
    //FIXME
    $splitted =  preg_split("/", $path);
    $type = $splitted[0];
    switch (count($splitted)) {

        case 1:   
                show_static_section($type);
                break;

        case 2:
                $splitted2 = preg_split("\.", $splitted[1]);
                $resource = $splitted2[0];
                $format = $splitted2[1];
                show_entity($type, $resource, $format);
                break;

        default:
                show_static_section(HOME);

    }

}

function show_static_section($name) {
    $sec = new StaticSection($name);
    $sec->getHTML();
}

function show_entity($type, $id, $format) {
    $entity = null;
    if ($type == PROJECT) {
        $entity = new Project($id);
    } else if ($type == USER) {
        $entity = new User($id);
    }

    if ($format == RDF) {
        $entity->getRDF();
    } else if ($format == N3) {
        $entity->getN3();
    } else {
        $entity->getHTML();
    }
}

?>
