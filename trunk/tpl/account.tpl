
{include file='header.tpl'}

    <div typeof="sioc:User" about="{$uri}">

      <a href="{$gravatar}" rel="foaf:depiction"><img src="{$gravatar}" alt="gravatar" class="gravatar" /></a>
      <h2 property="sioc:name"><a href="{$uri}">{$name}</a></h2>
      <a href="{$uri}">{$uri}</a>
      
      <div id="map" style="width: 300px; height: 250px; float: right;"></div>

      <dl class="description">

        <dt>Kudo rank:</dt>
        <dd><a href="http://www.ohloh.net/people?show={$id}"><img src="{$ROOT}resources/images/laurel_{$kudo_rank}.png" alt="#{$kudo_rank}" /></a></dd>

        <dt>Location:</dt>
        <dd>{$location}</dd>

        <dt>Homepage:</dt>
        <dd><a href="{$homepage}" rel="foaf:homepage">{$homepage}</a></dd>

        <dt>Ohloh:</dt>
        <dd><a href="{$ohloh_url}" rel="foaf:accountProfilePage">{$ohloh_url}</a></dd>

        <dt>Created:</dt>
        <dd property="dct:created">{$created}</dd>

        <dt>Updated:</dt>
        <dd property="dct:modified">{$updated}</dd>

      </dl>

    </div>

{include file='footer.tpl'} 

