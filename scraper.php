<html>
    <head>
        <script src='js/jquery-2.0.0.min.js'></script>
        <script type="text/javascript">
            var timer = 0;
            incrementTimer = function()
            {
                $('#counter').text(timer);
                timer++;
            }
            
            $(function(){
                $('#scraper').on('click', function(){
                    $('#content').html('');
                    $('#loading').show();
                    incrementTimer();
                    var timer_interval = setInterval(incrementTimer, 1000);
                    $.post('DataScraper.php', function(data, status, jq){
                        $('#loading').hide();
                        $('#content').html(data);
                        timer = 0;
                        clearInterval(timer_interval);
                    });
                })
            });
        </script>
        <style>
            #loading{
                display: none;
            }
        </style>
    </head>
    <body>
        <input type='button' id='scraper' value='Scrape' />
        <span id='loading'>Processing...</span><span id='counter'></span>
        <br><br>
        <div id='content'></div>
    </body>
</html>