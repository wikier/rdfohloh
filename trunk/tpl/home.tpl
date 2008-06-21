
{include file='header.tpl'} 

    <p>
      <a href="http://rdfohloh.wikier.org/">RDFohloh</a> is a <a href="http://www.w3.org/RDF/">RDF</a>
      wrapper of <a href="http://ohloh.net/">Ohloh</a>. Using content negotiation, it provides 
      Ohloh's information in <a href="http://www.w3.org/RDF/">RDF</a> data, serialized both in 
      <a href="http://www.w3.org/TR/rdfa-syntax/">XHTML+RDFa</a>, 
      <a href="http://www.w3.org/TR/rdf-syntax-grammar/">RDF/XML</a> and 
      <a href="http://www.w3.org/TeamSubmission/n3/">N3</a>.
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
