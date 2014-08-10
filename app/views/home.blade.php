<!doctype html>
<html lang="en">
<head>
    <title>Turt2Live UUID</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="{{ URL::asset('/js/jquery.json.js') }}"></script>
    <link href="{{  URL::asset('/css/home.css') }}" rel='stylesheet'>
    
    <script type='text/javascript'>
        $(document).ready(function(){
            var blocks = $(".do-post");
            blocks.each(function(index){
                var block = $(blocks[index]);
                var url = block.attr('data-url');
                var data = $.parseJSON(block.attr('data-data'));
                
                $.post(url, data, function(resp){       
                    resp = $.toJSON(resp);
                    block.html(resp);
                });
            });
        });
    </script>
</head>
<body>
    <div class="header">
        <h1>Turt2Live's UUID Caching Service</h1>
    </div>
    <div class="content">
        <p>This service is provided free of charge so long as there is no abuse. This service simply acts as a caching service between you and Mojang to ensure that you don't approach a rate limit problem. Anything returned from this service will be as old as 2 hours and is updated on-demand (meaning a name could be 2 hours old and well be re-fetched from Mojang once it has expired and it is being requested). The design is known to be lacking and may be replaced by anyone with the willpower. You can see the source on <a href="http://github.com/turt2live/MinecraftUUID">GitHub</a>.</p>
        <p>Any occurrence of an error will appear as the following response (where the message varies): <code>{{ file_get_contents(URL::route('testError')) }}</code> <br/>Please contact Travis (<a href="mailto:travis@turt2live.com">travis@turt2live.com</a>) for assistance with errors.</p>
        <p>UUIDs may either be in 'short' format (no dashes) or 'long' format (with dashes). However the API will always return a 'with dashes' version.</p>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/uuid/&lt;player name&gt;[/offline]</div>
            <div class="example">
                <b>Request: </b> {{ URL::route('uuid', ['turt2live']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('uuid', ['turt2live'])) }}
                </code>
            </div>
            <div class="example">
                <b>Request: </b> {{ URL::route('uuidOffline', ['turt2live']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('uuidOffline', ['turt2live'])) }}
                </code><br/>
                <i>Note: The uuid will always be 'unknown' for offline requests</i>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/name/&lt;player uuid&gt;</div>
            <div class="example">
                <b>Request: </b> {{ URL::route('name', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('name', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8'])) }}
                </code>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/name/list/&lt;player uuid 1&gt;;&lt;player uuid 2&gt;;&lt;...&gt;</div>
            <div class="example">
                <b>Request: </b> {{ URL::route('nameList', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8;7afc5cdf-aebd-43e5-ac7c-9c7e48243c6a;795a6053-16a7-42f2-bdd2-9e8e33ff0333']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('nameList', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8;7afc5cdf-aebd-43e5-ac7c-9c7e48243c6a;795a6053-16a7-42f2-bdd2-9e8e33ff0333'])) }}
                </code>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/uuid/list/&lt;player name 1&gt;;&lt;player name 2&gt;;&lt;...&gt;</div>
            <div class="example">
                <b>Request: </b> {{ URL::route('uuidList', ['turt2live;drtshock;Shadowwolf97']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('uuidList', ['turt2live;drtshock;Shadowwolf97'])) }}
                </code>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/history/&lt;player uuid&gt;</div>
            <div class="example">
                <b>Request: </b> {{ URL::route('history', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('history', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8'])) }}
                </code>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/random/[amount]</div>
            <div class="example">
                <b>Request: </b> {{ URL::route('random') }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('random')) }}
                </code><br/>
                <i>Warning: Potentially expired results may be included in the result set</i>
            </div>
            <div class="example">
                <b>Request: </b> {{ URL::route('randomAmount', ['4']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('randomAmount', ['4'])) }}
                </code><br/>
                <i>Warning: Potentially expired results may be included in the result set</i>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/info</div>
            <div class="example">
                <b>Request: </b> {{ URL::route('info') }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('info')) }}
                </code>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>POST</div><div class="title">/api/v2/uuid</div>
            <div class="example">
                <b>Request Data: </b> <code>{"names": ["turt2live", "drtshock", "Shadowwolf97"]}</code><br/>
                <b>Output: </b>
                <code class='do-post' data-url="{{ URL::route('uuidPost') }}" data-data='{"names": ["turt2live", "drtshock", "Shadowwolf97"]}'>
                    <!-- Populated by JavaScript -->
                    Loading...
                </code>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>POST</div><div class="title">/api/v2/name</div>
            <div class="example">
                <b>Request Data: </b> <code>{"uuids": ["c465b154-3c29-4dbf-a7e3-e0869504b8d8","7afc5cdfaebd43e5ac7c9c7e48243c6a","795a605316a742f2bdd29e8e33ff0333"]}</code><br/>
                <b>Output: </b>
                <code class='do-post' data-url="{{ URL::route('namePost') }}" data-data='{"uuids": ["c465b154-3c29-4dbf-a7e3-e0869504b8d8","7afc5cdfaebd43e5ac7c9c7e48243c6a","795a605316a742f2bdd29e8e33ff0333"]}'>
                    <!-- Populated by JavaScript -->
                    Loading...
                </code>
            </div>
        </div>
    </div>
    <div class="footer">
        Copyright &copy; 2014 Travis Ralston. This service is not affiliated with Mojang AB or their partners.
        Please contact Travis Ralston (<a href="mailto:travis@turt2live.com">travis@turt2live.com</a>) for any questions or concerns.
        This service is provided free of charge provided there is no abuse.
    </div>
</body>
</html>