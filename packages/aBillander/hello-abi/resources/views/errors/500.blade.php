<html>
    <head>
        <style>
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 400;
                font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
                font-size: 18px;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 32px;
                margin-bottom: 20px;
            }

            .requirements {
                border: 2px solid #d7dee2;
                border-radius: 8px;
                margin-top: 20px;
                text-align: left;
                padding: 10px 20px;
                font-size: 14px;
                line-height: 20px;
            }

            hr {
                border: 1px solid #d7dee2;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">An error has occurred (500).</div>
                <div>Make sure you meet the following requirements:</div>
                <div class="requirements">
                    <strong>PHP >= {{ config('installer.core.minPhpVersion') }}</strong><hr>
                    <em>OpenSSL PHP Extension</em><br>
                    <em>PDO PHP Extension</em><br>
                    <em>Mbstring PHP Extension</em><br>
                    <em>Tokenizer PHP Extension</em><br>
                    <em>XML PHP Extension</em>
                </div>
            </div>
        </div>
    </body>
</html>
