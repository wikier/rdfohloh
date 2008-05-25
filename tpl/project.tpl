
{include file='header.tpl'} 

    <div typeof="doap:Project" about="{$uri}">

      <h2 property="doap:name">{$name}</h2>

      <p property="doap:description">{$description}</p>

      <dl class="description">

        <dt>Homepage:</dt>
        <dd><a href="{$homepage}" rel="doap:homepage">{$homepage}</a></dd>

        <dt>Download:</dt>
        <dd><a href="{$download}" rel="doap:download-page">{$download}</a></dd>

        <dt>Ohloh:</dt>
        <dd><a href="{$ohloh_url}">{$ohloh_url}</a></dd>

        <dt>Created:</dt>
        <dd property="doap:created">{$created}</dd>

        <dt>Updated:</dt>
        <dd property="dct:modified">{$updated}</dd>

        <dt>Language:</dt>
        <dd><a href="{$language_url}" property="programming-language">{$language}</a></dd>

        <dt>Contributors:</dt>
        {foreach from=$contributors item=item}
        <dd><a href="{$ROOT}user/{$item[0]}" rel="doap:developer">{$item[1]}</a></dd>
        {/foreach}

      </dl>

      <p><span href="{$sameAs}" rel="owl:sameAs"/></p>

    </div>

{include file='footer.tpl'} 

