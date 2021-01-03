<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/app-style.css">
    <title>Communitly - dashboard</title>
</head>
<body>
    <div class="header-and-nav">
        <header>
            <a class="logo" href="/">Communitly</a>
        </header>
        <nav>
            <button class="hamburger-menu-icon"></button>
            <ul class="nav-menu">
                <li><a class="dashboard menu-link" href="dashboard">Dashboard</a></li>
                <li><a class="menu-group" href="#">PK WIiT Informatyka 3 rok</a></li>
                <li><a class="subgroup menu-link" href="wdpai">WdPAI</a></li>
                <p class="gap"></p>
                <li><a class="account menu-link" href="settings">Paweł</a></li>
            </ul>
        </nav>
    </div>
    <main>
        <div class="main-title">
            <h1>Wstęp do projektowania aplikacji internetowych</h1>
        </div>
        <div class="statements">
            <!-- TODO: zmienić dodawanie komunikatu -->
            <!-- <div class="widget-group-header"> -->
                <h3 class="widget-group">Komunikaty</h3>
                <a href="#" class="add-content">sfff</a>
            <!-- </div> -->
            <div class="widget add">
                <form action="addStatement" method="post" enctype="multipart/form-data">
                    <?php 
                    echo '1234567';
                        if(isset($messages)) {
                            foreach ($messages as $message) {
                                echo $message;
                            }
                        }
                    ?><!-- TODO: change ↑ (wyświetlanie błędu) ↓ (usunięcie value) -->
                    <input type="text" name='statement-header' placeholder="Nagłówek">
                    <textarea name="statement-content" placeholder="Treść nowego komunikatu" autofocus></textarea>
                    <input type="file" name="attachment">
                    <input type="submit" value="Wyślij wiadomość">
                </form>

            </div>
            <div class="widget">
                <h2>Lorem ipsum</h2>
                <p class="date-and-source">
                    26.10.2020 13:45 z 
                    <a href="https://example.com">example.com</a>
                </p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Porro sint temporibus magni cupiditate similique corrupti saepe, voluptatibus reprehenderit tenetur amet, excepturi sequi optio perspiciatis laborum ea consequuntur libero provident numquam ad magnam voluptatum vel dolorem. Eos quibusdam dignissimos dolore maiores numquam sapiente nihil. Animi harum necessitatibus, fugiat facere voluptatum iure. Quae sapiente quos, ullam vel quo distinctio esse id in eos repellat dolorem suscipit tenetur labore, ab consequuntur eligendi aspernatur veniam soluta. Dolorem inventore iste totam doloremque eligendi at nesciunt eveniet consequuntur sequi provident beatae magni, dignissimos ut, voluptate modi sapiente mollitia! Repudiandae consectetur blanditiis voluptatum vitae modi nemo rem?</p>
            </div>
            <div class="widget">
                <h3><a class="archived" href="#">Zobacz zarchiwizowane komunikaty</a></h3>
            </div>

            <!-- TODO: delete dialog -->
            <!-- <div class="dialog-background">
                <div class="dialog">
                    <form action="login" method="post" enctype="multipart/form-data">
                        <?php 
                            if(isset($messages)) {
                                foreach ($messages as $message) {
                                    echo $message;
                                }
                            }
                        ?><!-- TODO: change ↑ (wyświetlanie błędu) ↓ (usunięcie value) --><!--
                        <input type="text" name='statement-header' placeholder="Nagłówek">
                        <input type="url" name='statement-url' placeholder="Link do źródła">
                        <textarea name="statement-content" placeholder="Treść komunikatu" autofocus></textarea>
                        <input type="file" name="attachment">
                        <input type="submit" value="Wyślij wiadomość">
                        <inp
                    </form>
                </div>
            </div>
 -->
        </div>
        <div class="links">
            <h3 class="widget-group">Linki</h3>
            <div class="widget">
                <h2>Wykłady</h2>
                <ul>
                    <li class="record-in-links">
                        <a href="https://example.com">Zdalne wykłady (app)</a>
                        <!-- TODO: dodać odpowiednie kolory dla ikon [JavaScript] -->
                    </li>
                    <li class="record-in-links">
                        <a href="https://example.com">Zdalne wykłady (www)</a>
                    </li>
                    <li class="record-in-links">
                        <a href="https://example.com">Materiały z zajęć (www)</a>
                    </li>
                    <li class="record-in-links">
                        <!-- TODO: zmienić styl, zmienić action="" -->
                        <form action="login" method="post" enctype="multipart/form-data">
                            <?php 
                                if(isset($messages)) {
                                    foreach ($messages as $message) {
                                        echo $message;
                                    }
                                }
                            ?><!-- TODO: change ↑ (wyświetlanie błędu) ↓ (usunięcie value) -->
                            <input type="url" name='link-url' placeholder="Link">
                            <input type="text" name='link-header' placeholder="Nazwa">
                            <textarea name="link-note" placeholder="Notatka"></textarea>
                            <input type="submit" value="Wyślij wiadomość">
                        </form>
                    </li>
                </ul>
            </div>
            <div class="widget">
                <h2>Laboratoria</h2>
                <ul>
                    <li class="record-in-links">
                        <a href="https://example.com">Zdalne laboratoria (Zoom)</a>
                    </li>
                    <li class="record-in-links">
                        <a href="https://example.com">Materiały z zajęć (www)</a>
                    </li>
                    <li class="record-in-links">
                        <a href="mailto:example@example.com">Przesyłanie zadań (email)</a>
                        <p>Na adres email: example@example.com<br>
                        Temat i nazwa pliku: [_] Imie Nazwisko</p>
                    </li>
                </ul>
            </div>

        </div>
    </main>
</body>
</html>