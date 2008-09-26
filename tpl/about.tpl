
{include file='header.tpl'} 

    <h2>About</h2>

    <p>
      <a rel="doap:homepage" href="http://rdfohloh.wikier.org/" property="doap:name">RDFohloh</a> 
      is a <a href="http://www.w3.org/RDF/">RDF</a> wrapper of <a href="http://rdfohloh.net/">Ohloh</a>.    
      Using content negotiation, it provides as <a href="http://linkeddata.org/">Linked Data</a> Ohloh's 
      information in <a href="http://www.w3.org/RDF/">RDF</a>, serialized in 
      <a href="http://www.w3.org/TR/rdfa-syntax/">XHTML+RDFa</a>, 
      <a href="http://www.w3.org/TR/rdf-syntax-grammar/">RDF/XML</a> and 
      <a href="http://www.w3.org/TeamSubmission/n3/">N3</a>,
      and interlinked with <a href="http://dbpedia.org/">DBpedia</a> concepts.
    </p>

    <p>
      This project is free software, licensed under <a href="http://www.gnu.org/copyleft/gpl.html">GPLv3</a>
      <span rel="doap:license" resource="http://usefulinc.com/doap/licenses/gpl"/>,
      and <a href="http://code.google.com/p/rdfohloh/downloads" rel="doap:download-page">it can 
      be downloaded</a> from <a href="http://rdfohloh.googlecode.com/">Google Code</a>. 
      It has been developed by 
      <span rel="doap:developer" href="http://www.wikier.org/foaf#wikier">
        <a rel="foaf:homepage" href="http://www.wikier.org/" property="foaf:name">Sergio Fernández</a>
        (<a rel="foaf:workplaceHomepage" href="http://www.fundacionctic.org/">CTIC Foudation</a>)
      </span>. 
      The service is provided without any warranty and subject to the 
      <a href="http://www.ohloh.net/api/terms">terms</a> of 
      <a href="http://www.ohloh.net/api">Ohloh's API</a>.
    </p>

    <h3 id="uris">URIs schema</h3>

    <p>
      RDFohloh generates URIs in this form: <tt>http://rdfohloh.wikier.org/ENTITY/ID</tt>,
      replacing <tt>ENTITY</tt> for the name of the entity in singular and <tt>ID</tt> for
      entity's ID in Ohloh. Mainly in Ohloh there are two kind of entities (projects and
      users), so RDFohloh generates two kind of RDF instances:
    </p>
    
    <dl>
      <dt>
        <tt>doap:Project</tt>
      </dt>
      <dd>
        <tt>http://rdfohloh.wikier.org/project/ID</tt>
        (<a href="http://rdfohloh.wikier.org/project/13509">example</a>), where ID is
        the numerical ID of the project in Ohloh. But if the project also has a URL name 
        in Ohloh (it's not mandatory), ID could also be that name 
        (<a href="http://rdfohloh.wikier.org/project/swaml">example</a>); in that cases 
        <tt>owl:sameAs</tt> asserts are automatically generated.
      </dd>
      <dt>
        <tt>sioc:User</tt>
      </dt>
      <dd>
        <tt>http://rdfohloh.wikier.org/user/ID</tt>
        (<a href="http://rdfohloh.wikier.org/user/17685">example</a>), where ID
        is the numerical ID of the user in Ohloh.
      </dd>
    </dl>

    <p>
      For each URI, using content negotiation, it's generated three representations
      of RDF of the entity following the next schema:
    </p>

    <dl>
      <dt>
        <a href="http://www.w3.org/TR/rdfa-syntax/">XHTML+RDFa</a>
      </dt>
      <dd>
        http://rdfohloh.wikier.org/ENTITY/ID/html
        (<a href="http://rdfohloh.wikier.org/project/swaml/html">example</a>)
      </dd>
      <dt>
        <a href="http://www.w3.org/TR/rdf-syntax-grammar/">RDF/XML</a>
      </dt>
      <dd>
        http://rdfohloh.wikier.org/ENTITY/ID/rdf
        (<a href="http://rdfohloh.wikier.org/project/swaml/rdf">example</a>)
      </dd>
      <dt>
        <a href="http://www.w3.org/TeamSubmission/n3/">RDF in N3</a>
      </dt>
      <dd>
        http://rdfohloh.wikier.org/ENTITY/ID/n3
        (<a href="http://rdfohloh.wikier.org/project/swaml/n3">example</a>)
      </dd>
    </dl>

<!--
    <h3 id="dumps">Dumps</h3>

    <p>
      <a href="http://www.ohloh.net/api">Ohloh's API</a> has some limitation of usage,
      so if you want to get all the data we are not able to serve all the data, for 
      example, for a crawling process. So we periodically (yearly, see
      <a href="http://rdfohloh.wikier.org/sitemap.xml">our semantic sitemap</a>) publish 
      dumps of all the data.
    </p>

    <p>
      Here you can see a list of the dumps available in (gziped) 
      <a href="http://www.w3.org/2001/sw/RDFCore/ntriples/">N-Triples</a> 
      format:
    </p>

    <dl>
      <dt>
        <a href="http://rdfohloh.wikier.org/dumps/rdfohloh-20080622.nt.gz">rdfohloh-20080622.nt.gz</a>
      </dt>
      <dd>
        A dump generated at June 22th of 2008 with 13,961 instances of <tt>doap:Project</tt>
        and 138,228 instances of <tt>sioc:User</tt>.
      </dd>
    </dl>

-->

    <h3 id="libs">Libraries used</h3>
    <p>
      The project is written in <span property="doap:programming-language">PHP</span>, and it 
      uses some third part free software libraries:
    </p>
    <ul>
      <li>
        <a href="http://www.phpclasses.org/browse/package/4261.html">Ohloh API for PHP</a>
      </li>
      <li>
        <a href="http://www.php.net/curl">cURL</a>
      </li>
      <li>
        <a href="http://ptlis.net/source/php-content-negotiation/">PHP Content Negotiation library</a>
      </li>
      <li>
        <a href="http://www4.wiwiss.fu-berlin.de/bizer/rdfapi/"><abbr title="RDF API for PHP">RAP</abbr></a>
      </li>
      <li>
        <a href="http://www.smarty.net/">Smarty Template Engine</a>
      </li>
    </ul>

    <p>
      If you need more information <a href="{$ABOUT}about">about</a> this service, you can
      also read <a href="http://www.wikier.org/blog/rdfohloh">this post</a>.
    </p>

{include file='footer.tpl'}

