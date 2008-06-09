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

define("RDFOHLOH_LIB_SMARTY", RDFOHLOH_LIBS . "smarty/");
define("SMARTY_DIR", RDFOHLOH_LIB_SMARTY);
include(RDFOHLOH_LIB_SMARTY . "Smarty.class.php");

class RDFohloh_Smarty extends Smarty { 
    function RDFohloh_Smarty() {
        $this->template_dir = RDFOHLOH_TPLS;
        $this->compile_dir = RDFOHLOH_TPLS . 'build';
        $this->config_dir = RDFOHLOH_TPLS . 'config';
        $this->cache_dir = RDFOHLOH_TPLS . 'cache';
    }
}

//FIXME????

class RDFohloh_Static_Section_Smarty extends RDFohloh_Smarty {

    function RDFohloh_Static_Section_Smarty() {
        parent::__construct();
    }

}

class RDFohloh_Project_Smarty extends RDFohloh_Smarty {

    function RDFohloh_Project_Smarty() {
        parent::__construct();
    }

}

?>
