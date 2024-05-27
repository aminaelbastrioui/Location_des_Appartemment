<?php
session_start();
include_once('db_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="style.css">
     <link rel="icon" href="images/333.png">
    <title>Location de Maison</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: linear-gradient(rgba(0,0,0,.5),rgba(0,0,0,.5)), url(images/1.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        p {
            text-align: justify;
            margin-bottom: 1.5rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        header {
            text-align: center;
            color: white;
            padding: 1rem;
            width: 40%;
            height: 665px;
            position: relative;
        }

        header img {
            max-width: 60%;
            height: auto;
            display: block;
            margin: 0 auto;
            margin-top: 25%;
            width: 50%;
        }

        h1.h1,
        h1.h2 {
            color: #000000;
            display: inline; /* أو استخدام inline-block إذا كنت ترغب في إضافة هوامش */
            font-size: 3rem; /* حجم الخط الخاص بك */
            font-weight: bold;
            margin: 0; /* للتخلص من الهوامش الافتراضية للعناصر */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        h1.h2 {
            color: #2798D0; /* لون الخط الثاني */
            margin-left: 5px; /* تحديد المسافة بين العناصر */
        }

        .ling{
            margin-top: 80px;
            border: 2px solid white;
            height: 600px;
            border-radius: 50px;
        }

        .g {
            display: flex;
            height: 99.7vh;
            border: 1px solid;
        }

        main {
            color: white;
            padding: 2rem;
            padding-top: 0px;
            width: 45%;
            position: absolute;
            left: 47%;
            height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }


        .p1{
            width: 360px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid rgb(16, 108, 184);
            margin-bottom: 30px;
        }

        .p2[type="submit"] {
            background-color: #2291E0;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 1rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            width: 200px;
            height: 50px;
            box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.7);
        }

        .p2[type="submit"]:hover {
            background-color:rgb(7, 172, 255);
        }

        .inputs{
            width: 770px;
            height: 500px;
            text-align: center;
            display: flex;
            justify-content: center;
        }


        .section{
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hh{
            margin-bottom: 70px;
            color: white;
            text-shadow: 2px 2px 6px rgb(28, 152, 235);
        }
        form{
            background: none;
            margin-top: -30px;
        }
        a.rt {
            text-decoration: none; 
        }
        .div{
            display: flex;
            flex-direction:  column;
        }
    </style>
</head>
<body>
<div class="g">
    <header>
    <a href="index.php" class="rt">
        <img src="images/333.png" alt="Logo" class="logo">
        <h1 class="h1">LOCATION </h1><h1 class="h2">DE MAISON</h1>
    </a>
    </header>
    <div class="ling"></div>
    </header>
    <main class="main">
        <section class="section">
            <div class="inputs">
                <div class="div">
                    <h2 class="hh">Le Propriétaire</h2>
                    <form action="tr.php" method="post">
                        <input class="p1" type="text" name="nom" required placeholder="Entrez votre nom">
                        <input class="p1" type="text" name="prenom" required placeholder="Entrez votre prénom">
                        <input class="p1" type="text" name="tele" required placeholder="Téléphone">
                        <input class="p1" type="text" name="email" required placeholder="Entrez votre adresse e-mail">
                        <input class="p1" type="password" name="password" required placeholder="Entrez votre mot de passe">
                        <input class="p2" type="submit" value="Créer un compte" name="create1">
                    </form>
                </div>
            </div>
        </section>
</div>
</main>
</div> 
</body>
</html>
