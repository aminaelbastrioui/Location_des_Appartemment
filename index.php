<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/333.png">
    <link rel="stylesheet" href="style/index.css">
    <title>Location de Maison</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url(images/1.jpg);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: 80vh;
    }
    </style>
</head>
<body>
    <div>
        <header>
            <img src="images/333.png" alt="Logo">
            <h1 class="h1">LOCATION </h1>
            <h1 class="h2">DE MAISON</h1>
        </header>
        <div class="ling"></div>
        <main>
            <p>
                Bienvenue sur le site de location de maisons, la destination principale pour ceux qui recherchent des
                maisons à louer et les propriétaires de biens immobiliers.
                Nous sommes fiers de vous offrir une expérience unique qui vous permet de trouver la maison idéale ou de
                répertorier votre propriété en toute simplicité.
            </p>
            <h1>Pour le propriétaire:</h1>
            <p>
                Cliquez sur le bouton "Le Propriétaire" pour commencer à commercialiser votre maison.
                Vous pouvez ajouter des informations détaillées et des photos attrayantes pour présenter votre propriété
                dans les annonces.
                Vous pouvez également définir des conditions de location et interagir directement avec les locataires
                intéressés.
            </p>
            <h1>Pour le locataire:</h1>
            <p>
                Utilisez le bouton "Le Locataire" pour parcourir les maisons disponibles.
                Créez votre compte personnel pour définir vos critères de recherche et recevoir des mises à jour
                instantanées sur les nouvelles annonces.
                Nous offrons une interface de recherche conviviale et des options de filtrage avancées pour spécifier
                vos besoins.
            </p>
            <form action="tr.php" method="post" class="in">
                <input type="submit" value="Le Propriétaire" name='owner'>
                <input type="submit" value="Le Locataire" class="in1" name='tenant'>
            </form>
        </main>
    </div>
    <footer class="site-footer">
        <section>
            <div class="divf">
                <p class="cop">&copy; 2024</p>
                <ul>
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Le Propriétaire</a></li>
                    <li><a href="#">Conditions d'utilisation</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                    <li><a href="#">Politique des cookies</a></li>
                    <li><a href="#">Politique de copyright</a></li>
                    <li><a href="#">Le Locataire</a></li>
                    <li><a href="#">Politique de marque</a></li>
                    <li><a href="#">Contrôles du visiteur</a></li>
                </ul>
                <select id="language" name="language" onchange="changeLanguage()">
                    <option value="en">Anglais</option>
                    <option value="fr" selected>Français</option>
                    <option value="ar">العربية</option>
                </select>
            </div>
        </section>
    </footer>
    <script>
    function changeLanguage() {
        var languageSelect = document.getElementById("language");
        var selectedLanguage = languageSelect.value;
        document.documentElement.lang = selectedLanguage;
        localStorage.setItem("selectedLanguage", selectedLanguage);
    }
    document.addEventListener("DOMContentLoaded", function() {
        var savedLanguage = localStorage.getItem("selectedLanguage");
        if (savedLanguage) {
            document.documentElement.lang = savedLanguage;
            document.getElementById("language").value = savedLanguage;
        }
    });
    </script>
</body>
</html>