<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        .standardCSS{
            color:#505050;
            font-size:15px;
        }

        @media only screen and (max-width:480px){
            .mediaQueryCSS{
                color:#CCCCCC;
                font-size:20px;
            }
        }
        .logo {
            padding: 10px;
            background-color: #f8f8f4;
            display: flex;
        }

        .logo p {
            font-family: sans-serif;
            font-size: 20px;
        }

        .content {
            padding: 10px;
        }

        .make_strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="content">
    <h1>Hurray! Your email setting worked.</h1>
    <h3>Save the setting and start using it</h3>
</div>
</body>
</html>
