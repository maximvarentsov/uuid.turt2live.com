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
        $.fn.digits = function(){ 
            return this.each(function(){ 
                $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
            })
        }
        $(document).ready(function(){
            var blocks = $(".do-post");
            blocks.each(function(index){
                var block = $(blocks[index]);
                var url = block.attr('data-url');
                var data = $.parseJSON(block.attr('data-data'));
                
                $.post(url, data, function(resp){       
                    //resp = $.toJSON(resp);
                    block.html(JSON.stringify(resp, undefined, 4));
                });
            });
            
            $.get("{{ URL::route('info')}}", function(resp){                
                var totalPlayers = resp["total-players-cached"];
                $("#totalPlayers").html(totalPlayers).digits();
            });
        });
    </script>
</head>
<body>
    <div class="header">
        <h1>Turt2Live's UUID Caching Service</h1>
    </div>
    <div class="header-under">
        <span id="totalPlayers">-1</span> total UUIDs cached
    </div>
    <div class="content">
        <p>This service is a free of charge Minecraft UUID caching service. What this means is that so long as no one abuses it, it will be free to access. This also means that the actual service caches UUID information for a period of time and is therefore a perfect solution to not attacking Mojang and getting hammered with rate limits because of your large server.</p>
        <p>All information on this service can be as old as 2 hours. Any data older than that becomes stale and when requested by someone it will be re-cached. That means every once and a while a request might take slightly longer because we have to re-cache the value from Mojang. Any information which has been cached will have two keys in the json response, like so: <code>{"expires_on":1407720229, "expires_in":6591}</code>. The "expires on" tag is the time value that the backend is using to determine if something has expired (see the "info" endpoint for information). The "expires in" key represents the number of seconds until the data is going to be stale.</p>
        <p>Errors can happen, and when they do the following sample response will be returned: <code>{{ file_get_contents(URL::route('testError')) }}</code>. The response should be pretty self-explanatory. For support with an error, please contact <a href="mailto:travis@turt2live.com">Travis (travis@turt2live.com)</a> with the error ID.</p>
        <p>The entire service's source is available for free on <a href="http://github.com/turt2live/uuid.turt2live.com">GitHub</a>.</p>
        <p>UUIDs may either be in 'short' format (no dashes) or 'long' format (with dashes). However the API will always return a 'with dashes' version. All sample data on this page was generated using the API.</p>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/uuid/&lt;player name&gt;[/offline]</div>
            <div class="example">
                <b>Request: </b><code>{{ URL::route('uuid', ['turt2live']) }}</code><br/>
                <b>Output: </b>
                <pre>{{ json_encode(json_decode(file_get_contents(URL::route('uuid', ['turt2live']))), JSON_PRETTY_PRINT) }}</pre>
            </div>
            <div class="example">
                <b>Request: </b><code>{{ URL::route('uuidOffline', ['turt2live']) }}</code><br/>
                <b>Output: </b>
                <pre>{{ json_encode(json_decode(file_get_contents(URL::route('uuidOffline', ['turt2live']))), JSON_PRETTY_PRINT) }}</pre><br/>
                <i>Note: The uuid will always be 'unknown' for offline requests</i>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/name/&lt;player uuid&gt;</div>
            <div class="example">
                <b>Request: </b><code>{{ URL::route('name', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8']) }}</code><br/>
                <b>Output: </b>
                <pre>{{ json_encode(json_decode(file_get_contents(URL::route('name', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8']))), JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/name/list/&lt;player uuid 1&gt;;&lt;player uuid 2&gt;;&lt;...&gt;</div>
            <div class="example">
                <b>Request: </b><code>{{ URL::route('nameList', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8;7afc5cdf-aebd-43e5-ac7c-9c7e48243c6a;795a6053-16a7-42f2-bdd2-9e8e33ff0333']) }}</code><br/>
                <b>Output: </b>
                <pre>{{ json_encode(json_decode(file_get_contents(URL::route('nameList', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8;7afc5cdf-aebd-43e5-ac7c-9c7e48243c6a;795a6053-16a7-42f2-bdd2-9e8e33ff0333']))), JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/uuid/list/&lt;player name 1&gt;;&lt;player name 2&gt;;&lt;...&gt;</div>
            <div class="example">
                <b>Request: </b><code>{{ URL::route('uuidList', ['turt2live;drtshock;Shadowwolf97']) }}</code><br/>
                <b>Output: </b>
                <pre>{{ json_encode(json_decode(file_get_contents(URL::route('uuidList', ['turt2live;drtshock;Shadowwolf97']))), JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/history/&lt;player uuid&gt;</div>
            <div class="example">
                <b>Request: </b><code>{{ URL::route('history', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8']) }}</code><br/>
                <b>Output: </b>
                <pre>{{ json_encode(json_decode(file_get_contents(URL::route('history', ['c465b154-3c29-4dbf-a7e3-e0869504b8d8']))), JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/random/[amount]</div>
            <div class="example">
                <b>Request: </b><code>{{ URL::route('random') }}</code><br/>
                <b>Output: </b>
                <pre>{{ json_encode(json_decode(file_get_contents(URL::route('random'))), JSON_PRETTY_PRINT) }}</pre><br/>
                <i>Warning: Potentially expired results may be included in the result set</i>
            </div>
            <div class="example">
                <b>Request: </b><code>{{ URL::route('randomAmount', ['4']) }}</code><br/>
                <b>Output: </b>
                <pre>{{ json_encode(json_decode(file_get_contents(URL::route('randomAmount', ['4']))), JSON_PRETTY_PRINT) }}</pre><br/>
                <i>Warning: Potentially expired results may be included in the result set</i>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/info</div>
            <div class="example">
                <b>Request: </b><code>{{ URL::route('info') }}</code><br/>
                <b>Output: </b>
                <pre>{{ json_encode(json_decode(file_get_contents(URL::route('info'))), JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>POST</div><div class="title">/api/v2/uuid</div>
            <div class="example">
                <b>Request Data: </b>
                <pre>{{ json_encode(json_decode("{\"names\": [\"turt2live\", \"drtshock\", \"Shadowwolf97\"]}"), JSON_PRETTY_PRINT); }}</pre><br/>
                <b>Output: </b>
                <pre class='do-post' data-url="{{ URL::route('uuidPost') }}" data-data='{"names": ["turt2live", "drtshock", "Shadowwolf97"]}'>
                    <!-- Populated by JavaScript -->
                    Loading...
                </pre>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>POST</div><div class="title">/api/v2/name</div>
            <div class="example">
                <b>Request Data: </b> 
                <pre>{{ json_encode(json_decode("{\"uuids\": [\"c465b154-3c29-4dbf-a7e3-e0869504b8d8\",\"7afc5cdfaebd43e5ac7c9c7e48243c6a\",\"795a605316a742f2bdd29e8e33ff0333\"]}"), JSON_PRETTY_PRINT) }}</pre><br/>
                <b>Output: </b>
                <pre class='do-post' data-url="{{ URL::route('namePost') }}" data-data='{"uuids": ["c465b154-3c29-4dbf-a7e3-e0869504b8d8","7afc5cdfaebd43e5ac7c9c7e48243c6a","795a605316a742f2bdd29e8e33ff0333"]}'>
                    <!-- Populated by JavaScript -->
                    Loading...
                </pre>
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