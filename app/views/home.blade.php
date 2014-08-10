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
        <h1>Turt2Live's UUID Service</h1>
    </div>
    <div class="content">
        <p>This service is provided free of charge so long as there is no abuse. The design is known to be lacking and may be replaced by anyone with the willpower. You can see the source on <a href="http://github.com/turt2live/MinecraftUUID">GitHub</a>.</p>
        <p>Any occurrence of an error will appear as the following response (where the message varies): <code>{{ file_get_contents(URL::route('testError')) }}</code> <br/>Please contact Travis (<a href="mailto:travis@turt2live.com">travis@turt2live.com</a>) for assistance with errors.</p>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/uuid/&lt;player name&gt;</div>
            <div class="example">
                <b>Request: </b> {{ URL::route('uuid', ['turt2live']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('uuid', ['turt2live'])) }}
                </code>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/name/&lt;player uuid&gt;</div>
            <div class="example">
                <b>Request: </b> {{ URL::route('name', ['c465b1543c294dbfa7e3e0869504b8d8']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('name', ['c465b1543c294dbfa7e3e0869504b8d8'])) }}
                </code>
            </div>
        </div>
        <div class="endpoint">
            <div class='method'>GET</div><div class="title">/api/v2/name/list/&lt;player uuid 1&gt;;&lt;player uuid 2&gt;;&lt;...&gt;</div>
            <div class="example">
                <b>Request: </b> {{ URL::route('nameList', ['c465b1543c294dbfa7e3e0869504b8d8;7afc5cdfaebd43e5ac7c9c7e48243c6a;795a605316a742f2bdd29e8e33ff0333']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('nameList', ['c465b1543c294dbfa7e3e0869504b8d8;7afc5cdfaebd43e5ac7c9c7e48243c6a;795a605316a742f2bdd29e8e33ff0333'])) }}
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
                <b>Request: </b> {{ URL::route('history', ['c465b1543c294dbfa7e3e0869504b8d8']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('history', ['c465b1543c294dbfa7e3e0869504b8d8'])) }}
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
                </code>
            </div>
            <div class="example">
                <b>Request: </b> {{ URL::route('randomAmount', ['4']) }}<br/>
                <b>Output: </b>
                <code>
                    {{ file_get_contents(URL::route('randomAmount', ['4'])) }}
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
                <b>Request Data: </b> <code>{"uuids": ["c465b1543c294dbfa7e3e0869504b8d8","7afc5cdfaebd43e5ac7c9c7e48243c6a","795a605316a742f2bdd29e8e33ff0333"]}</code><br/>
                <b>Output: </b>
                <code class='do-post' data-url="{{ URL::route('namePost') }}" data-data='{"uuids": ["c465b1543c294dbfa7e3e0869504b8d8","7afc5cdfaebd43e5ac7c9c7e48243c6a","795a605316a742f2bdd29e8e33ff0333"]}'>
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