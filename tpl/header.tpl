<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
      xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
      xmlns:owl="http://www.w3.org/2002/07/owl#"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:dct="http://purl.org/dc/terms/"
      xmlns:doap="http://usefulinc.com/ns/doap#"
      xmlns:sioc="http://rdfs.org/sioc/ns#"
      xmlns:foaf="http://xmlns.com/foaf/0.1/"
      xml:lang="en"
>

  <head profile="http://www.w3.org/2003/g/data-view">
    <title property="dc:title">RDFohloh{$TITLE}</title>
    <meta http-equiv="content-type" content="text/xhtml+xml; charset=utf-8" />
    <link rel="transformation" href="http://www-sop.inria.fr/acacia/soft/RDFa2RDFXML.xsl"/>
    <link rel="stylesheet" type="text/css" href="{$ROOT}resources/style.css" />
    <link rel="shortcut icon" href="{$ROOT}resources/images/favicon.ico" />
    <link rel="meta" type="application/rdf+xml" title="DOAP" href="http://www.w3.org/2007/08/pyRdfa/extract?uri={$ROOT}about" />
    <link rel="dct:creator" type="application/rdf+xml" title="FOAF" href="http://www.wikier.org/foaf#wikier" />
    <script type="text/javascript" src="{$ROOT}resources/scripts.js"></script>
    {$include}
  </head>

  <body{$bodyEvents}>

    <h1><a href="{$ROOT}"><img src="{$ROOT}resources/images/rdfohloh.png" width="231" height="55" alt="RDFohloh" /></a></h1>

    <div id="content">

