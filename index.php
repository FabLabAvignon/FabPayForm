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
    <br>
    <div class="container">
        <h1>Oh, alors vous voulez devenir un maker, hein ?</h1>
        <br>
        <p>Vous y êtes presque ! Pour devenir adhérent, remplissez le formulaire
        ci dessous...</p>

        <form class="" action="validateForm.php" method="POST">

            <!-- Requiered  -->
            <div class="sticker">
                <h3>Le minimum syndical...</h3>
                <div class="arrow-right"></div>
            </div>
            <p class="indice">Les informations ci-dessous sont obligatoire.</p>
            <div class="input-block">
                <label for="gender">Civilité</label><br>
                <input type="radio" name="gender" value="man"> Mr<br>
                <input type="radio" name="gender" value="woman"> Mme
            </div>
            <div class="input-block">
                <label for="lastName">Nom</label><br>
                <input class="input-error" value="exemple d'erreur" type="text" name="lastName" placeholder="Votre nom de famille">
            </div>
            <div class="input-block">
                <label for="firstName">Prénom</label><br>
                <input class="input-ok" value="exemple de champ valide" type="text" name="firstName" placeholder="Votre prénom">
            </div>
            <div class="input-block">
                <label for="emailAddr">Email</label><br>
                <input type="email" name="emailAddr" placeholder="Votre email">
            </div>

            <div class="input-block">
                <label for="duration">Durée de votre adhésion</label><br>
                <?php
                  foreach($conf['payOptions'] as $key => $infos) {
                    if ($key != 'currencyCode') {
                      if(!isset($i))
                        $i = 0;

                      $i++;
                      echo "<input type=\"radio\" name=\"duration\" value=\"" . $i . "\">" . $key . " - " . $infos[0] . " " . $conf['payOptions']['currencyCode'] . "</option><br>";
                    }
                  }
                ?>
            </div>


            <div class="sticker">
                <h3>Vous êtes bavard ?</h3>
                <div class="arrow-right"></div>
            </div>
            <p class="indice">Les informations ci-dessous sont facultatives.</p>

            <!-- Optionals  -->
            <div class="input-block">
                <label for="birthDate">Date de naissance</label><br>
                <input type="date" name="birthDate" placeholder="jj/mm/aaaa">
            </div>
            <div class="input-block">
                <label for="address">Adresse</label><br>
                <input type="text" name="address" placeholder="Votre adresse">
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
                <label for="phoneNum">Téléphone</label><br>
                <input type="text" name="phoneNum" placeholder="Votre téléphone">
            </div>

            <button type="submit">Adhérer</button>
        </form>
    </div>

    <br>
</body>
</html>
