
{include file='header.tpl'} 

    <p>
      <a rel="doap:homepage" href="http://rdfohloh.wikier.org/" property="doap:name">RDFohloh</a> 
      is a <a href="http://www.w3.org/RDF/">RDF</a>izer of <a href="http://www.ohloh.net/">Ohloh</a>.    
      It provides <a href="http://linkeddata.org/">Linked Data</a> from <a href="http://rdfohloh.net/">Ohloh</a>.
      Using content negotiation, the <a href="http://www.w3.org/RDF/">RDF</a> data can be founded serialized in 
      <a href="http://www.w3.org/TR/rdfa-syntax/">XHTML+RDFa</a>, <a href="http://www.w3.org/TR/rdf-syntax-grammar/">RDF/XML</a>
      and <a href="http://www.w3.org/TeamSubmission/n3/">N3</a>, and interlinked with <a href="http://dbpedia.org/">DBpedia</a>
      concepts.
    </p>

    <p>
      Please, introduce here your project/user information:
    </p>

    <form action="{$ROOT}" method="post">
      <p>
        <select name="type">
          <option value="projectname" selected="selected">Project Name</option>
          <option value="projectid">Project ID</option>
          <option value="userid">User ID</option>
          <!--<option value="useremail">User email</option>-->
        </select>
        <input type="text" name="value" />
        <input type="submit" value="Go" />
      </p>
    </form>

    <p>
      If you need more information <a href="/about">about</a> this service, or 
      you can also read <a href="http://www.wikier.org/blog/rdfohloh">this post</a>.
    </p>

{include file='footer.tpl'}
