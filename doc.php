<?php
    class cClass{
        public $id;
        public $sName;
        public $aMember;
        public function __construct(string $name){
            $this->id = $name;
            $this->sName = $name;
            $this->aMember = [];
        }
        public function add(string $name, string $text, string $return = ''){
            $oMember = new cMember($name, $text,$return);
            array_push($this->aMember,$oMember);
        }

        public function getHTML(){
            $members = '';
            foreach ($this->aMember as $member) {
                $members .= $member->getHTML();
            }
            return '
            <div id="'.$this->id.'" class="tab-pane fade" role="tabpanel" aria-labelledby="tab-'.$this->id.'">
                <h2>'.$this->sName.'</h2>
                '.$members.'
            </div>
        ';
        }
    }

    class cMember{
        public $sName;
        public $sText;
        public function __construct(string $name, string $text, string $return = ''){
            $this->sName = $name;
            $this->sText = $text;
            if ($return == '') $return = 'Pas de valeur de retour';
            $this->sReturn = $return;
        }

        public function getHTML(){
            $br_text = '';
            if ($this->sText != '') $br_text = '<br>';
            return '
                <br>    
                <h4><strong>'.$this->sName.'</strong></h4>
                <i>'.$this->sReturn.'</i>'.$br_text.'
                '.$this->sText.'
                <br><br>
            ';
        }
    }

    $aClass = [];

    $myPath = getcwd();
    $path = 'assets/php/';
    $files = glob($path.'c*.php');

    //pour tout fichier
    foreach ($files as $file) {
        $titlepos = strpos($file, '/c')+1;
        $title = substr($file,$titlepos);
        $title = str_replace('.php', '', $title);

        $file = $myPath.'./'.$file;
        $file = fopen($file,'r');

        $aClassTemp = new cClass($title);

        //pour toute ligne
        while(!feof($file)){
            $line = fgets($file);
            $name='';
            $return='';
            $desc='';

            if (strpos($line,'//-') > 0){
                $line = fgets($file);
                $name = str_replace('//','',$line);
                $line = fgets($file);
                $return = str_replace('//','',$line);
                $line = fgets($file);
                $desc = str_replace('//','',$line);
                //$line = fgets($file);
                //$name = str_replace('{','',$line);

                $name = trim($name);
                $return = trim($return);
                $desc = trim($desc);
                $aClassTemp->add($name,$desc,$return);
            }
        }
        array_push($aClass,$aClassTemp);
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Documentation classes</title>
  <link rel="shortcut icon" href="https://fr.wikipedia.org/static/favicon/wikipedia.ico"/>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<style>
li{
    margin-bottom:10px;
}

</style>


<div class="container">
    <h1>Documentation classes</h1>
    <p></p>
    
  <ul class="nav nav-tabs">
    <?php 

    ?>
  </ul>

  <div class="tab-content">
    <?php 
        foreach ($aClass as $class) {
            //echo '<li><a data-toggle="tab" href="#'.$class->id.'" id="tab-'.$class->id.'" aria-controls="'.$class->id.'">'.$class->sName.'</a></li>';
            echo '<h1>'.$class->sName.'</h1>';
            echo '<ul>';
            foreach ($class->aMember as $member) {
                echo '<li>'
                .$member->sName.'<br>'.
                $member->sText.
                
                '</li>';
            }
            echo '</ul>';
        }
    ?>
  </div>
</div>

</body>
</html>
