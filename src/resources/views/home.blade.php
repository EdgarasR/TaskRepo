<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Test Task</title>
</head>

<body>
    <div>
        <pre>{{ print_r($data, true) }}</pre>
    </div>
</body>

</html>