<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>{{ config('app.name') }}</title>
</head>
<body>
<div id="app"></div>
<script type="application/javascript" src="{{ asset('js/vendor.js') }}{{ '?v=' . config('app.version') }}"></script>
<script type="application/javascript" src="{{ asset('js/app.js') }}{{ '?v=' . config('app.version') }}"></script>
</body>
</html>
