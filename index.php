    <!DOCTYPE html>

    <html lang="en">
      <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <!-- Bootstrap core CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="all" href="css/style2.css" />
        <link rel="stylesheet" type="text/css" media="all" href="css/bootstrap-dialog.min.css" />
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="https://raw.githubusercontent.com/Illyism/jquery.vibrate.js/master/build/jquery/jquery.vibrate.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.js"></script>
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery.bootstrap-growl.js"></script>
        <script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>

        </head>
        <body>
        <div class="container">
            <div class="pull-left">
                <a href="index.php"><img src="img/ftwgroup.jpg" width="180" height="50" alt="Logo"></a>
            </div>
            <div class="clear-right"></div>
            <ul class="nav nav-pills pull-right no_margin" id="headmenu">
                <li ><a href="#">Contact</a></li>
            </ul>
            <br /><br /><br />
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
            </div>
            <br />
    <?php
       if(isset($_GET['token']) && ($_GET['token'] != "")) {
     
           require("include/config.inc.php");
           require("include/Database.class.php");
           require("include/encrypt.class.php");
           require('PHPMailer/class.phpmailer.php');
         
           $token = $_GET['token'];
     
           $encrypt = new encryptClass();
           $url = $encrypt->decrypt($token);
           
            $tmp = explode('!!', $url);
           
            $email = $tmp[0];
            $uniqueID = $tmp[1];
            $date = $tmp[2];
            $type = $tmp[4];
            $data = $tmp[3];
            //print_r($tmp);
            //$today = date("Y-m-d H:i:s");
            if(strcmp($data, "inscription") == 0) {

                $dbd = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
                $dbd->connect();
                $sql = 'SELECT *, count(*) FROM ' . _INSCRIPTIONTMP_ . ' WHERE email  = "' . mysql_real_escape_string($email) . '" AND ' .
                    'keyTMP = "' . mysql_real_escape_string($uniqueID) . '" AND date = "' . mysql_real_escape_string($date) . '"';
                    //echo $sql;
                $rows = $dbd->query($sql);
                $record = $dbd->fetch_array($rows);
                $dbd->close();
                $today = strtotime(date("Y-m-d H:i:s")) - (60 * 60 * 24);
                $dateTime = strtotime($date);
               
                if($record['count(*)'] > 0) {
               
                    if(!(isset($_GET['goOK']) && $_GET['goOK'] == "ok")) {
                       if($today > $dateTime) {
                           echo '<script type="text/javascript" src="js/index.js"></script>';
                            echo '<script type="text/javascript">alertShow("Temps de validit&eacute; de code &agrave; depacer 24h<br />Veuillez utilis&eacute; un nouveau code.", "danger", 10);';
                            echo 'window.location = "inscription.php";';
                            echo '</script>';
                            exit;
                        }
                    }
                   
    ?>
    <?php
           function mailing()
           {
               mail('$email', 'Nous avons bien recu votre demande', 'Nous vous recontacterons pour dire que nous avons accepte votre site');
           }
    ?>




        <center>
            <input type="hidden" id="token" name="token" value="<?php echo $_GET['token'];?>"/>
            <input type="hidden" id="goOK" name="goOK" value="<?php echo $_GET['goOK'];?>"/>
            <div class="form-horizontal" role="form" id="container" style="margin: auto;position: absolute;top: 0;bottom: 0;left: 0;right: 0;">
                <h2>Confirmation inscription</h2>
                <?php if($type == "1") { ?>
                <div class="form-group">
                    <label for="password1" class="col-sm-5 control-label">Login</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="login" name="login" placeholder="Login">
                    </div>
                </div>
                <?php } ?>
                <div class="form-group">
                    <label for="password" class="col-sm-5 control-label">Mot de passe</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password1" class="col-sm-5 control-label">Confirm&eacute; mot de passe</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" id="password1" name="password1" placeholder="Confirm&eacute; mot de passe">
                    </div>
                </div>
                <?php if($type == "2") { ?>
                <div class="form-group">
                    <label for="password1" class="col-sm-5 control-label">Matricule</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="matricule" name="matricule" placeholder="Matricule">
                    </div>
                </div>
                <?php } ?>
               
                <div class="form-group">
                    <label for="text" class="col-sm-5 control-label"><br /></label>
                    <div class="col-sm-6">
                        <button class="btn btn-lg btn-primary btn-block" id="inscriptionPassword" name="inscriptionPassword" type="submit">Enregistrer</button>
                    </div>
                </div>
            </div>
        </center>
    <?php
               } else {
                   echo '<script type="text/javascript" src="js/index.js"></script>';
                    echo '<script type="text/javascript">alertShow("Code non valide<br />Veuillez utilis&eacute; un nouveau code.", "danger", 10);';
                    //echo 'window.location = "inscription.php";';
                    echo '</script>';
                    exit;
                }
            }
        } else {
    ?>
        <center>
            <div class="form-horizontal" role="form" id="container" style="margin: auto;position: relative;top: 10;bottom: 0;left: 0;right: 0;">
                <br />
                <h2>Inscription</h2>
                <br />
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label">First Name </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="firstName" name="firstName">
                    </div>
                    <label class="col-sm-1 control-label">

                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label">Last Name</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="lastName" name="lastName" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label">Identifier </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="ident" name="ident">
                    </div>
                    <label class="col-sm-1 control-label">

                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">E-mail </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="mail" name="mail">
                    </div>
                    <label class="col-sm-1 control-label">

                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label">Skype </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="skype" name="skype">
                    </div>
                    <label class="col-sm-1 control-label">

                </div>
                <div class="form-group">
                    <label for="number_format" class="col-sm-4 control-label">Phone </label>
                    <div class="col-sm-6">
                        <input type="number_format" class="form-control" id="phone" name="phone">
                    </div>
                    <label class="col-sm-1 control-label">

                </div>
                <div class="form-group">
                    <label for="date" class="col-sm-4 control-label">Date of birth </label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control" id="birthDay" name="birthDay">
                    </div>
                    <label class="col-sm-1 control-label">

                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label">Category </label>
                    <div class="col-sm-6">
                <SELECT name="category" class="form-control" id="category">
                   
                    <OPTION>Directeur
                    <OPTION>Responsable
                    <OPTION>Co-responsable
                    <OPTION>Utilisateur
                   
                </SELECT>
                    </div>
                    <label class="col-sm-1 control-label">

                </div>
               
                <div class="form-group">
                    <label for="text" class="col-sm-4 control-label"><br /></label>
                    <div class="col-sm-6">
                   
                        <form action="ajout.php" method="post">
                        <button class="btn btn-lg btn-primary btn-block" data-toggle="modal" id="inscriptionSubmit" name="inscriptionSubmit" type="submit" >Inscription</button>
                        </form>
                    </div>
                </div>
            </div>
           
        <div class="modal fade" id="myModal" width="900" name="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <h1>Conditions d'utilisation</h1>
    <div align="justify">L'utilisation du site  My T&eacute;l&eacute;police  sont li&eacute;es &agrave; certaines conditions en plus de certaines  du contrat de base. Conditions d&eacute;crites ci-apr&eacute;s. Le fait pour vous d'utiliser ce site  My T&eacute;l&eacute;police  signifie que vous acceptez les conditions g&eacute;n&eacute;rales dans leur ensemble.</div>
    <h4>I. OBJECTIF DU SITE</h4>
    <div align="justify">Le site  My T&eacute;l&eacute;police  constitue une interphase offerte gratuitement aux membres en ordre de cotisation. Il a pour objectif d'am&eacute;liorer la s&eacute;curit&eacute; de nos membres en leur permettant de disposer d'un outil lui offrant la possibilit&eacute; de visualiser leurs &eacute;quipements qui leur ont &eacute;t&eacute; mis en location par l'interm&eacute;diaire d'un contrat de base du type T&eacute;l&eacute;police Vision, T&eacute;l&eacute;police Vision +, T&eacute;l&eacute;police Help PC.
    En fonction de votre type de relation, My T&eacute;l&eacute;police vous donnera acc&eacute;s &agrave; divers menus vous permettant d'administrer vos &eacute;quipements &agrave; distance et de visualiser vos syst&eacute;mes.
    Avertissement : L'utilisateur effectuant des op&eacute;rations de modification  de donn&eacute;es depuis l'interphase administration est seul responsable de l'exactitude des donn&eacute;es qu'il encodera ! Cela signifie que toute erreur qui serait  occasionn&eacute;e par une information erron&eacute;e, repr&eacute;sentera une cons&eacute;quence n&eacute;gative sur l'objet du contrat et notre mission.  Pour ce faire, le membre sera attentif &agrave; relire l'information communiqu&eacute;e avant d'en valider le contenu.
    L'utilisateur sera inform&eacute; automatiquement via son interphase des anomalies d&eacute;tect&eacute;es par nos services de mani&eacute;re &agrave; pouvoir vous avertir en direct. Dans ce cadre l'utilisateur sera attentif aux messages pouvant influencer l'efficacit&eacute; du syst&eacute;me mis &agrave;  sa disposition.</div>

           
        </div>

        </center>
    <?php
    }
    ?>
    </div>
        <center>
        <div id="footer">
            <div class="container">
                Copyright &copy; 2015 - FTW Group Corporate</p>
            </div>
        </div>
        </center>


    <script type="text/javascript" src="js/index.js"></script>


    </center>
    </body>
    </html>
     

