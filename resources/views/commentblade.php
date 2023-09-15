<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-6 offset-3">
                <div class="alert alert-secondary" role="alert">
                    <h1>Привет {{ $comment->user->name }}Код подтверждения электронной почты: {{ $code }}, </h1>
                    <p>введите в течение 2 минут.</p>
                  </div>
            </div>
        </div>
    </div>
</body>
</html>