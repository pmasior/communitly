<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/all.css">
    <link rel="stylesheet" type="text/css" href="/public/css/app-style.css">
    <link rel="stylesheet" type="text/css" href="/public/css/dashboard-style.css">
    <title>Communitly - dashboard</title>
</head>
<body>
    <?php include('header-and-nav.php'); ?>
    <main>
        <div class="main-title">
            <h1>Witaj <?= $userFirstname;?>!</h1>
        </div>
        <div class="statements">
            <h3 class="widget-group">Nowe komunikaty</h3>
            <div class="widget">
                <p>
                    <span class="group-name">PK WIiT Informatyka 3 rok → </span>
                    <span><a href="wdpai">Wstęp do projektowania aplikacji internetowych</a></span>
                </p>
                <h2>Lorem ipsum</h2>
                <p class="date-and-source">
                    26.10.2020 13:45 z 
                    <a href="https://example.com">example.com</a>
                </p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Porro sint temporibus magni cupiditate similique corrupti saepe, voluptatibus reprehenderit tenetur amet, excepturi sequi optio perspiciatis laborum ea consequuntur libero provident numquam ad magnam voluptatum vel dolorem. Eos quibusdam dignissimos dolore maiores numquam sapiente nihil. Animi harum necessitatibus, fugiat facere voluptatum iure. Quae sapiente quos, ullam vel quo distinctio esse id in eos repellat dolorem suscipit tenetur labore, ab consequuntur eligendi aspernatur veniam soluta. Dolorem inventore iste totam doloremque eligendi at nesciunt eveniet consequuntur sequi provident beatae magni, dignissimos ut, voluptate modi sapiente mollitia! Repudiandae consectetur blanditiis voluptatum vitae modi nemo rem?</p>
            </div>
        </div>
        <div class="links">
            <h3 class="widget-group">Nowe powiadomienia</h3>
            <div class="widget">
                <p>
                    <span class="group-name">PK WIiT Informatyka 3 rok</span>
                    <span class="subgroup"><a href="wdpai">WdPAI</a></span>
                </p>
                <h2> Nowy link</h2>
                <p>Przesyłanie zadań (email)</p>
            </div>
        </div>
    </main>
</body>
</html>