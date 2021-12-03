<?php

$title = "page de profil";
require 'header.php';
require 'base de donnée.php';

obliger_utilisateur_connecte();


// informations de session venant de la base de donnée
$id = $_SESSION['user-connecte']['id'];
$login = $_SESSION['user-connecte']['login'];
$password = $_SESSION['user-connecte']['password'];


//var_dump($_SESSION);

$message = null;


if (!empty($_POST)) {

    //les informations qui seront saisie par l'utilisateur pour faire les modifications
    $loginp =  strip_tags($_POST['login']);
    $passwordp = strip_tags($_POST['password']);
    $confirm_password = strip_tags($_POST['confirm-password']);
    
    
    if ($confirm_password == $passwordp) {
        
        if ($loginp == $login) {
            $passwordp =  password_hash($passworndp, PASSWORD_ARGON2I);

            // $passwordp = password_hash($_POST['password'], PASSWORD_ARGON2I);
            //Requète sql pour maj des informations dans la base de donnée
            $sql = "UPDATE `utilisateurs` SET `login`='$loginp',`password`='$passwordp' WHERE id = $id";
            $requete = mysqli_query($bdd, $sql);

            //mettre a jour les infos dans ma session
            $_SESSION['user-connecte']['login'] = $loginp;
            $_SESSION['user-connecte']['password'] = $passwordp;

            // var_dump($requete);
            header('Location: index.php ');
            exit();
        } elseif ($loginp != $login) {

            // verification des login correspondant 
            $sqlVerif =  "SELECT * FROM utilisateurs WHERE login = '$loginp'";
            $select = mysqli_query($bdd, $sqlVerif);

            //si on a une ligne correspondant a notre requete
            if (mysqli_num_rows($select)) {
                $message = "Ce login existe déjà , choisissez un autre";
            } else {

                //Requète sql pour mettre a jour les informations dans la base de donnée
                $sql = "UPDATE `utilisateurs` SET `login`='$loginp',`password`='$passwordp' WHERE id = $id";
                $requete = mysqli_query($bdd, $sql);

                //mettre a jour les infos dans ma session
                $_SESSION['user-connecte']['login'] = $loginp;
                $_SESSION['user-connecte']['password'] = $passwordp;

                // var_dump($requete);
                header('Location: index.php ');
                exit();
            }
        }
    } else {
        $message = "Votre mot de passe est incorrect";
    }
}







//var_dump(est_connecte());
?>


<section class="sectioncon">
    <div>
        <?php if ($message) : ?><p class="messagealert"> <?= $message; ?></p>
        <?php endif; ?>

        <form class="formcon" action="profil.php" method="POST">
            <fieldset class="fieldsetprofil">
                <div>
                    <legend>Profil</legend>
                </div>

                <div>
                    <input class="inputelmt" type="text" name="login" value="<?= htmlentities($login); ?>" required>
                </div>

                <div>
                    <input class="inputelmt" type="password" name="password" placeholder="***********" required>
                </div>

                <div>
                    <input class="inputelmt" type="password" name="confirm-password" placeholder="Confirmez votre mot de passe" required>
                </div>

                <div>
                    <input class="submitbutton" type="submit" value="Mettre a jour mon profil">
                </div>



            </fieldset>
        </form>

    </div>
    <div>
        <a href="index.php"><button class="element3">Annuler</button></a>
    </div>
</section>


<?php

require 'footer.php';

?>





<!-- num row  retourne le nombre de ligne -->