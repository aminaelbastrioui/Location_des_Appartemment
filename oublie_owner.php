<DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .continue {
            margin:0px;
            align-items: center;
            position: absolute;
            top: 60vh;
            width: 320px;
            height: 100px;
            border-radius: 6px;
            display: flex;
            flex-direction: column;

        }

        .article{
            display: flex;
            justify-content: center;
            color: white;
        }

        button{
            width: 200px;
            padding: 10px;
            color: white;
            border-radius: 8px;
            border: none;
            background-color: rgb(1, 121, 242);
            font-size: 15px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        .p1{
            width: 360px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid rgb(16, 108, 184);
            margin: 50px;
        }

        .p2[type="submit"] {
            background-color: #2291E0;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            /* margin-top: 0.5rem; */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            width: 200px;
            height: 50px;
            box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.7);
            margin-top: -30px;
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

        .images{
            display: flex;
            justify-content: center;
        }

        .img2{
            width: 40px;
            height: 40px;
            border-radius: 6px;
        }

        .a1{
            margin: 30;
            width: 40px;
            height: 40px;
        }

        .a2{
            text-decoration: none;
            margin: 30;
            text-align: center;
            color: white; 
            width: 140px;
            text-shadow: 0px 0px 6px rgb(95, 175, 240);
        }

        .section{
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hh{
            margin-bottom: 70px;
            text-shadow: 2px 2px 6px rgb(28, 152, 235);
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #f2f2f2;
            border: 1px solid #888;
            width: 300px;
            text-align: center;
        }
        a.rt {
            text-decoration: none; 
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
                        <input class="p1" type="email" name="email" id="email" placeholder="Email Address" required><br><br>
                        <input class="p2" type="submit" value="Envoyer un captcha" name="Connexion3">
                    </form>   
                </div>
            </div>
        </section>
</div>
</main>
</div>
<?php

if(isset($_GET['errmsg'])){
    $error_message = urldecode($_GET['errmsg']);
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '{$error_message}',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            });
          </script>";
}
?>
</body>
</html>