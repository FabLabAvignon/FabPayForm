<?php require_once "lib/form.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Devenez adhérent</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <h1>Oh, alors vous voulez devenir un maker, hein ?</h1>
        <p>Vous y êtes presque ! Pour devenir adhérent, suivez les instructions
        ci dessous...</p>

        <form class="" action="" method="post">

            <!-- OBLIGATOIRES  -->
            <h2>Donnez nous quelques informations à propos de vous</h2>
            <h3>Le minimum syndical</h3>

            <div class="input-block">
                <label for="familyName">Nom</label><br>
                <input type="text" name="familyName" placeholder="Votre nom de famille" value="">
            </div>
            <div class="input-block">
                <label for="firstName">Prénom</label><br>
                <input type="text" name="firstName" placeholder="Votre prénom" value="">
            </div>
            <div class="input-block">
                <label for="email">Email</label><br>
                <input type="email" name="email" placeholder="Votre email" value="">
            </div>

            <h3>Si vous êtes bavard, vous pouvez nous en dire plus...</h3>
            <!-- FACULTATIFS  -->

            <div class="input-block">
                <!-- Value à vérifier auprès de Nico ! -->
                <label for="gender">Civilité</label><br>
                <input type="radio" name="gender" value="homme"> Mr<br>
                <input type="radio" name="gender" value="femme"> Mme
            </div>
            <div class="input-block">
                <label for="birthday">Date de naissance</label><br>
                <input type="date" name="birthday" placeholder="jj/mm/aaaa" value="">
            </div>
            <div class="input-block">
                <!-- Address avec 2 d ? .. -->
                <label for="adress">Adresse</label><br>
                <input type="text" name="adress" placeholder="Votre adresse" value="">
            </div>
            <div class="input-block">
                <label for="city">Ville</label><br>
                <input type="text" name="city" placeholder="Votre ville" value="">
            </div>
            <div class="input-block">
                <label for="postCode">Code postal</label><br>
                <input type="text" name="postCode" placeholder="Votre code postal" value="">
            </div>
            <div class="input-block">
                <!-- Contry ? ...  -->
                <label for="country">Pays</label><br>
                <input type="text" name="country" placeholder="Votre pays" value="">
            </div>
            <div class="input-block">
                <label for="gsm">GSM</label><br>
                <input type="text" name="gsm" placeholder="Votre GSM" value="">
            </div>
            <div class="input-block">
                <!-- gsm ET tel ? quel différence ? ... -->
                <label for="tel">Téléphone</label><br>
                <input type="text" name="tel" placeholder="Votre téléphone" value="">
            </div>
            <div class="input-block">
                <!-- Durée de l'abonnement ? -->
                <label for="frequence">Durée de votre abo (1-12)</label><br>
                <input type="text" name="frequence" placeholder="Combien de mois" class="input-error" value="">
            </div>

            <button type="submit" name="button">Adhérer</button>
        </form>
    </div>

</body>
</html>
