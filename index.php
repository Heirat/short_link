<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
          crossorigin="anonymous">
    <title>Short URL</title>

    <style>
        a {
            text-decoration: none;
            color: black;
        }
        a:hover {
            color: black;
        }
    </style>
</head>
<body>
    <?php require_once 'request.php' ?>
    <div class="container text-center">
        <div class="row mt-5">
            <a href="/">
                <h1>Short URL</h1>
            </a>
        </div>
        <div class="row">
            <form action="" method="get">
                <div class="input-group mb-3">
                    <input type="text" name="long_link" class="form-control" placeholder="Enter the link here" aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Shorten URL</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
