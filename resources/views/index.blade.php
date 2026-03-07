<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laravel React App</title>
    @vite('resources/js/app.jsx')
</head>
<body>
    <div id="app"></div>
</body>
</html>
@push('scripts')
    <script>
        $.ajax({
            type: "method", // method type 
            url: "url",
            data: "data",
            dataType: "dataType",
            success: function (response) {
                
            }
        });
    </script>
@endpush