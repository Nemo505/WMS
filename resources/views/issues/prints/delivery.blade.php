<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Print</title>
</head>
<style>
    /* Set print page size and margins */
    @page {
        size: A4;
    }

</style>
<body onload="window.print()" style="font-family:Times New Roman, sans-serif; margin:40px; font-size:13px; color:#000000;">
    @include('issues.prints.sale')
    @include('issues.prints.repair')
    @include('issues.prints.return')
</body>
</html>