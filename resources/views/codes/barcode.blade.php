<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcode</title>
</head>
<body>
    <div style="display: inline-block; text-align: center;">
        {!! DNS1D::getBarcodeSVG($code->barcode, 'C39+', 1, 55, 'black', false) !!}
        <div style="color: black; font-size: 12px; margin-top: 5px;">
            {{ $code->name }}
        </div>
    </div>
</body>

<script>
    window.onload = function() { 
        window.print(); 
    }
</script>
</html>