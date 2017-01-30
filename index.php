<?php
  $conf = require("lib/config.php");
?>

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

        <form class="" action="validateForm.php" method="POST">

            <!-- Requiered  -->
            <h2>Donnez nous quelques informations à propos de vous</h2>
            <h3>Le minimum syndical</h3>

            <div class="input-block">
                <label for="familyName">Nom</label><br>
                <input type="text" name="familyName" placeholder="Votre nom de famille">
            </div>
            <div class="input-block">
                <label for="firstName">Prénom</label><br>
                <input type="text" name="firstName" placeholder="Votre prénom">
            </div>
            <div class="input-block">
                <label for="email">Email</label><br>
                <input type="email" name="email" placeholder="Votre email">
            </div>

            <div class="input-block">
                <label for="frequence">Durée de votre adhésion</label><br>
                <select name="duration">
                  <?php
                    foreach($conf['payOptions'] as $key => $infos) {
                      if ($key != 'currencyCode')
                        echo "<option value=\"\">" . $key . " - " . $infos[0] . " " . $conf['payOptions']['currencyCode'] . "</option>";
                    }
                  ?>
                </select>
            </div>

            <h3>Si vous êtes bavard, vous pouvez nous en dire plus...</h3>
            <!-- Optionals  -->

            <div class="input-block">
                <label for="gender">Civilité</label><br>
                <input type="radio" name="gender" value="homme"> Mr<br>
                <input type="radio" name="gender" value="femme"> Mme
            </div>
            <div class="input-block">
                <label for="birthday">Date de naissance</label><br>
                <input type="date" name="birthday" placeholder="jj/mm/aaaa">
            </div>
            <div class="input-block">
                <label for="adress">Adresse</label><br>
                <input type="text" name="adress" placeholder="Votre adresse">
            </div>
            <div class="input-block">
                <label for="city">Ville</label><br>
                <input type="text" name="city" placeholder="Votre ville">
            </div>
            <div class="input-block">
                <label for="postCode">Code postal</label><br>
                <input type="text" name="postCode" placeholder="Votre code postal">
            </div>
            <div class="input-block">
                <!-- Contry ? ...  -->
                <label for="country">Pays</label><br>
                <input type="text" name="country" placeholder="Votre pays">
            </div>
            <div class="input-block">
                <label for="tel">Téléphone</label><br>
                <input type="text" name="tel" placeholder="Votre téléphone">
            </div>

            <button type="submit" name="button">Adhérer</button>
        </form>
    </div>

</body>
</html>
