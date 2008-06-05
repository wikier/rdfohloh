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
 * Foobar is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

define("OHLOHAPI_INCLUDE_DIR", RDFOHLOH_LIBS . "ohlohapi/");
define("RDFAPI_INCLUDE_DIR", RDFOHLOH_LIBS . "rap/");

include(RDFOHLOH_SRC . "templates.php");
include(OHLOHAPI_INCLUDE_DIR . "ohlohapi_class_inc.php");

class StaticSection {

    function StaticSection($name) {
        $this->name = $name;
    }

    function getHTML() {
        $tpl =& new RDFohloh_Static_Section_Smarty;
        $tpl->assign("name", $this->name);
        $tpl->assign("ROOT", RDFOHLOH_BASE_URI);
        $tpl->assign("bodyEvents", ' typeof="doap:Project" about="' . RDFOHLOH_BASE_URI . 'about#rdfohloh"');
        $tpl->display(RDFOHLOH_TPLS . $this->name . ".tpl");
    }

}

class Project {

    function Project($id) {
        $this->id = $id;
        $this->info = array();
        $this->exists = $this->getInfo();
    }

    function getInfo() {
        $ohloh = new ohlohapi(OHLOH_API_KEY, $this->id, NULL, NULL, NULL);
        $project = $ohloh->getProjectInfo($this->id)->project;
        if ($project instanceof SimpleXMLElement) {
            $this->name = $project->name;
            $this->info["url_name"] = $project->url_name;
            if (((string)(int)$this->id)==($this->id)) {
                if (strlen($this->info["url_name"])>0) {
                    $this->info["sameAs"] = RDFOHLOH_BASE_URI . "project/" . $this->info["url_name"];
                } else {
                    $this->info["sameAs"] = "";
                }
            } else {
                $this->info["sameAs"] = RDFOHLOH_BASE_URI . "project/" . $project->id;
            }
            $this->info["created"] = $project->created_at;
            $this->info["updated"] = $project->updated_at;
            $this->info["description"] = $project->description;
            $this->info["homepage"] = $project->homepage_url;
            $this->info["download"] = $project->download_url;
            $this->info["ncontributors"] = $project->analysis->twelve_month_contributor_count;
            $this->info["language_url"] = "http://www.ohloh.net/languages/" . $project->analysis->main_language_id;
            $this->info["language"] = $project->analysis->main_language_name;
            $this->info["contributors"] = array();
            $contributors = $ohloh->getProjectContributors($this->id);
            foreach ($contributors->contributor_fact as $contributor) {
                if ($contributor->account_id[0] != NULL)
                    $this->info["contributors"][] = array($contributor->account_id[0], $contributor->account_name[0]);
            }
            return true;
        } else {
            return false;
        }
    }

    function getRDFAutodiscovery($uri) {

        $autodiscovery = <<<EXCERPT

    <link rel="meta" type="application/rdf+xml" title="DOAP" href="$uri/rdf" />
    <link rel="meta" type="text/rdf+n3" title="DOAP" href="$uri/n3" />

EXCERPT;

        return $autodiscovery;

    }

    function getHTML() {
        $tpl =& new RDFohloh_Project_Smarty;
        $tpl->assign("ROOT", RDFOHLOH_BASE_URI);
        if ($this->exists) { 
            $uri = RDFOHLOH_BASE_URI . "project/" . strtolower($this->id);
            $tpl->assign("name", $this->name); 
            $tpl->assign("uri", $uri);
            if (strlen($this->info["sameAs"])>0)
                $tpl->assign("sameAs", $this->info["sameAs"]);
            $tpl->assign("created", $this->info["created"]);
            $tpl->assign("updated", $this->info["updated"]);
            $tpl->assign("description", $this->info["description"]);
            $tpl->assign("homepage", $this->info["homepage"]);
            if (strlen($this->info["download"])>0)
                $tpl->assign("download", $this->info["download"]);
            if (strlen($this->info["url_name"])>0)
                $tpl->assign("ohloh_url", "http://www.ohloh.net/projects/" . $this->info["url_name"]);
            $tpl->assign("ncontributors", $this->info["ncontributors"]);
            $tpl->assign("language_url", $this->info["language_url"]);
            if (strlen($this->info["language"])>0)
                $tpl->assign("language", $this->info["language"]);
            else
                $tpl->assign("language", "no language");
            $tpl->assign("contributors", $this->info["contributors"]);
            $tpl->assign("include", $this->getRDFAutodiscovery($uri));
            $tpl->display(RDFOHLOH_TPLS . "project.tpl");
        } else {
            $tpl->display(RDFOHLOH_TPLS . "noproject.tpl");
        }
    }

    function getModel($uri, $format) {
        include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");
        $ns = array(
                    "rdf" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#",
                    "rdfs" => "http://www.w3.org/2000/01/rdf-schema#",
                    "owl" => "http://www.w3.org/2002/07/owl#",
                    "dct" => "http://purl.org/dc/terms/",
                    "doap" => "http://usefulinc.com/ns/doap#" ,
                    "sioc" => "http://rdfs.org/sioc/ns#" ,
                    "foaf" => "http://xmlns.com/foaf/0.1/" ,
                    "rdfohloh" => RDFOHLOH_BASE_URI . "ns#"
                   );
        $model = ModelFactory::getDefaultModel();
        $model->addNamespace("dct", $ns["dct"]);
        $model->addNamespace("doap", $ns["doap"]);
        $model->addNamespace("sioc", $ns["sioc"]);
        $model->addNamespace("foaf", $ns["foaf"]);
        $model->addNamespace("rdfohloh", $ns["rdfohloh"]);
        $project = new Resource($uri);
        $doc = new Resource($uri . "/" . $format);
        $model->add(new Statement($doc, new Resource($ns["rdf"], "type"), new Resource($ns["foaf"], "Document")));
        $model->add(new Statement($doc, new Resource($ns["rdfs"], "label"), new Literal($this->name . "'s DOAP document serialized in " . (($format==N3) ? "n3" : "RDF/XML"))));
        $model->add(new Statement($doc, new Resource($ns["foaf"], "primaryTopic"), $project));
        $model->add(new Statement($project, new Resource($ns["rdf"], "type"), new Resource($ns["doap"], "Project")));
        $model->add(new Statement($project, new Resource($ns["doap"], "name"), new Literal((string)$this->name)));
        if (strlen($this->info["sameAs"])>0)
            $model->add(new Statement($project, new Resource($ns["owl"], "sameAs"), new Resource((string)$this->info["sameAs"])));
        $model->add(new Statement($project, new Resource($ns["doap"], "created"), new Literal((string)$this->info["created"])));
        $model->add(new Statement($project, new Resource($ns["dct"], "updated"), new Literal((string)$this->info["updated"])));
        $model->add(new Statement($project, new Resource($ns["doap"], "description"), new Literal((string)$this->info["description"])));
        $model->add(new Statement($project, new Resource($ns["doap"], "homepage"), new Resource((string)$this->info["homepage"])));
        if (strlen($this->info["download"])>0)
            $model->add(new Statement($project, new Resource($ns["doap"], "download-page"), new Resource((string)$this->info["download"])));
        if (strlen($this->info["url_name"])>0)
            $model->add(new Statement($project, new Resource($ns["rdfohloh"], "ohloh-page"), new Resource((string)"http://www.ohloh.net/projects/" .$this->info["url_name"])));
        if (strlen($this->info["language"])>0)
            $model->add(new Statement($project, new Resource($ns["doap"], "programming-language"), new Literal((string)$this->info["language"])));
        foreach ($this->info["contributors"] as $contributor) {
            $uri = RDFOHLOH_BASE_URI . "user/" . (string)$contributor[0];
            $user = new Resource($uri);
            $model->add(new Statement($project, new Resource($ns["doap"], "developer"), $user));
            $model->add(new Statement($user, new Resource($ns["rdf"], "type"), new Resource($ns["sioc"], "User")));
            $model->add(new Statement($user, new Resource($ns["rdfs"], "seeAlso"), new Resource($uri . "/rdf")));
        }
        return $model;
    }

    function getRDF() {
        include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");
        include(RDFAPI_INCLUDE_DIR . "syntax/RdfSerializer.php");
        $project = RDFOHLOH_BASE_URI . PROJECT . "/" . $this->id;
        $model = $this->getModel($project, RDF);
        $ser = new RDFSerializer();
        $rdf =& $ser->serialize($model);
        $model->close();
        header("Content-Type: application/rdf+xml; charset=utf-8");
        echo $rdf;  
    }

    function getN3() {
        include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");
        include(RDFAPI_INCLUDE_DIR . "syntax/N3Serializer.php");
        $project = RDFOHLOH_BASE_URI . PROJECT . "/" . $this->id;
        $model = $this->getModel($project, N3);
        $ser = new N3Serializer();
        $n3 =& $ser->serialize($model);
        $model->close();
        header("Content-Type: text/rdf+n3; charset=utf-8");
        echo $n3;  
    }

}

class User {

    function User($id) {
        $this->id = $id;
        $this->info = array();
        $this->exists = $this->getInfo();
    }

    function getInfo() {
        $ohloh = new ohlohapi(OHLOH_API_KEY, NULL, $this->id, NULL, NULL);
        $account = $ohloh->getSingleAccount($this->id);
        if ($account instanceof SimpleXMLElement) {
            $this->name = $account->name;
            $this->info["kudo_rank"] = $account->kudo_score->kudo_rank;
            $this->info["gravatar"] = $account->avatar_url;
            $this->info["created"] = $account->created_at;
            $this->info["updated"] = $account->updated_at;
            $this->info["homepage"] = $account->homepage_url;
            $this->info["ohloh_url"] = "http://www.ohloh.net/accounts/" . $this->id;
            $this->info["location"] = $account->location;
            $this->info["geo"] = array($account->latitude, $account->longitude);
            return true;
        } else {
            return false;
        }
    }

    function getRDFAutodiscovery($uri) {

        $autodiscovery = <<<EXCERPT

    <link rel="meta" type="application/rdf+xml" title="SIOC" href="$uri/rdf" />
    <link rel="meta" type="text/rdf+n3" title="SIOC" href="$uri/n3" />

EXCERPT;

        return $autodiscovery;

    }

    function getGoogleMaps($name, $lat, $lon) {

        $key = GOOGLEMAPS_API_KEY;

        $scripts = <<<EXCERPT

    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=$key"
      type="text/javascript"></script>
    <script type="text/javascript">

    //<![CDATA[

    function load() {
      if (GBrowserIsCompatible()) {

        function createMarker(point, nombre) {
        	var marker = new GMarker(point);
        	GEvent.addListener(marker, 'click', function() {
            	marker.openInfoWindowHtml(nombre);
        	});
        	return marker;
        } 

        var map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
        map.setCenter(new GLatLng($lat, $lon), 4);

        var point = new GLatLng($lat, $lon)
        var marker = createMarker(point, "$name");
        map.addOverlay(marker); 
      }
    }

    //]]>
    </script>

EXCERPT;

        return $scripts;

    }

    function getHTML() {
        $tpl =& new RDFohloh_Project_Smarty;
        $tpl->assign("ROOT", RDFOHLOH_BASE_URI);
        if ($this->exists) {
            $uri = RDFOHLOH_BASE_URI . "user/" . strtolower($this->id);
            $tpl->assign("id", $this->id); 
            $tpl->assign("name", $this->name); 
            $tpl->assign("uri", $uri);
            $tpl->assign("kudo_rank", $this->info["kudo_rank"]);
            $tpl->assign("gravatar", $this->info["gravatar"]); 
            $tpl->assign("created", $this->info["created"]);
            $tpl->assign("updated", $this->info["updated"]);
            if (strlen($this->info["homepage"])>0)
                $tpl->assign("homepage", $this->info["homepage"]);
            else
                $tpl->assign("homepage", "no homepage");
            $tpl->assign("ohloh_url", $this->info["ohloh_url"]);
            if (strlen($this->info["location"])>0)
                $tpl->assign("location", $this->info["location"]);
            else
                $tpl->assign("location", "no location");
            $tpl->assign("include", $this->getRDFAutodiscovery($uri) . $this->getGoogleMaps($this->name, $this->info["geo"][0], $this->info["geo"][1]));
            $tpl->assign("bodyEvents", ' onload="load()" onunload="GUnload()"');
            $tpl->assign("ROOT", RDFOHLOH_BASE_URI);
            $tpl->display(RDFOHLOH_TPLS . "account.tpl");
        } else {
            $tpl->display(RDFOHLOH_TPLS . "noaccount.tpl");
        }
    }

    function getModel($uri, $format) {
        include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");
        $ns = array(
                    "rdf" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#",
                    "rdfs" => "http://www.w3.org/2000/01/rdf-schema#",
                    "owl" => "http://www.w3.org/2002/07/owl#",
                    "dct" => "http://purl.org/dc/terms/",
                    "doap" => "http://usefulinc.com/ns/doap#" ,
                    "sioc" => "http://rdfs.org/sioc/ns#" ,
                    "foaf" => "http://xmlns.com/foaf/0.1/" ,
                    "rdfohloh" => RDFOHLOH_BASE_URI . "ns#"
                   );
        $model = ModelFactory::getDefaultModel();
        $model->addNamespace("dct", $ns["dct"]);
        $model->addNamespace("doap", $ns["doap"]);
        $model->addNamespace("sioc", $ns["sioc"]);
        $model->addNamespace("foaf", $ns["foaf"]);
        $model->addNamespace("rdfohloh", $ns["rdfohloh"]);
        $user = new Resource($uri);
        $doc = new Resource($uri . "/" . $format);
        $model->add(new Statement($doc, new Resource($ns["rdf"], "type"), new Resource($ns["foaf"], "Document")));
        $model->add(new Statement($doc, new Resource($ns["rdfs"], "label"), new Literal($this->name . "'s SIOC document serialized in " . (($format==N3) ? "n3" : "RDF/XML"))));
        $model->add(new Statement($doc, new Resource($ns["foaf"], "primaryTopic"), $user));
        $model->add(new Statement($user, new Resource($ns["rdf"], "type"), new Resource($ns["sioc"], "User")));
        $model->add(new Statement($user, new Resource($ns["sioc"], "id"), new Literal((string)$this->id)));
        $model->add(new Statement($user, new Resource($ns["sioc"], "name"), new Literal((string)$this->name)));
        $model->add(new Statement($user, new Resource($ns["rdfohloh"], "kudo-rank"), new Literal((string)$this->info["kudo_rank"])));
        $model->add(new Statement($user, new Resource($ns["foaf"], "depiction"), new Resource((string)$this->info["gravatar"])));
        $model->add(new Statement($user, new Resource($ns["dct"], "created"), new Literal((string)$this->info["created"])));
        $model->add(new Statement($user, new Resource($ns["dct"], "updated"), new Literal((string)$this->info["updated"])));
        if (strlen($this->info["homepage"])>0)
            $model->add(new Statement($user, new Resource($ns["foaf"], "homepage"), new Resource((string)$this->info["homepage"])));
        $model->add(new Statement($user, new Resource($ns["rdfohloh"], "ohloh-page"), new Resource((string)$this->info["ohloh_url"])));
        $model->add(new Statement($user, new Resource($ns["sioc"], "link"), new Resource($uri . "/html")));
        //FIXME geo
        return $model;
    }

    function getRDF() {
        include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");
        include(RDFAPI_INCLUDE_DIR . "syntax/RdfSerializer.php");
        $user = RDFOHLOH_BASE_URI . USER . "/" . $this->id;
        $model = $this->getModel($user, RDF);
        $ser = new RDFSerializer();
        $rdf =& $ser->serialize($model);
        $model->close();
        header("Content-Type: application/rdf+xml; charset=utf-8");
        echo $rdf; 
    }

    function getN3() {
        include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");
        include(RDFAPI_INCLUDE_DIR . "syntax/N3Serializer.php");
        $user = RDFOHLOH_BASE_URI . USER . "/" . $this->id;
        $model = $this->getModel($user, N3);
        $ser = new N3Serializer();
        $n3 =& $ser->serialize($model);
        $model->close();
        header("Content-Type: text/rdf+n3; charset=utf-8");
        echo $n3;  
    }

}

?>
