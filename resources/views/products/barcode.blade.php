<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcode</title>
</head>
<body>
    <div class="row">
        {!!DNS1D::getBarcodeSVG($product->barcode, 'C39+',1,55,'black', true) !!}
    </div>

    
</body>

<script>
    window.onload = function() { 
        window.print(); 
    }
</script>
</html>