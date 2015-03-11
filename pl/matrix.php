<?php

    require_once('Data.php');

    class NameLang {
        public $name = "";
        public $lang = "";

        function __construct($name, $lang)
        {
            $this->name = $name;
            $this->lang = $lang;
        }


    }
    class Section {
        public $idName = "";
        public $displayName = array();

        public function __construct($idName, $displayName) {
            $this->idName = $idName;
            $this->displayName = $displayName;
        }
    }

    class Marketing {
        public $idName = "";
        public $displayName = array();
        public $required = false;
        public $dataType = "";

        public function __construct($idName, $displayName, $required, $dataType) {
            $this->idName = $idName;
            $this->displayName = $displayName;
            $this->required = $required;
            $this->dataType = $dataType;
        }
    }

    class SingleOption {
        public $idName = "";
        public $displayName = array();
        public $code = "";
        public $htmlCode = "";
        public $urlImage = "";

        public function __construct($idName, $displayName, $code, $htmlCode, $urlImage) {
            $this->idName = $idName;
            $this->displayName = $displayName;
            $this->code = $code;
            $this->htmlCode = $htmlCode;
            $this->urlImage = $urlImage;
        }

    }

    class Pivot {
        public $idName = "";
        public $displayName = array();
        public $dataType = "";
        public $values = array();

        public function __construct($idName, $displayName, $dataType, $values) {
            $this->idName = $idName;
            $this->displayName = $displayName;
            $this->dataType = $dataType;
            $this->values = $values;
        }
    }

    class Feature {
        public $idName = "";
        public $displayName = array();
        public $dataType = "";
        public $values = array();

        public function __construct($idName, $displayName, $dataType, $values) {
            $this->idName = $idName;
            $this->displayName = $displayName;
            $this->dataType = $dataType;
            $this->values = $values;
        }
    }

    class Matrix {
        public $idNode = "";
        public $marketing = array();
        public $pivots = array();
        public $features = array();

        function __construct($idNode, $marketing, $pivots, $features)
        {
            $this->idNode = $idNode;
            $this->marketing = $marketing;
            $this->pivots = $pivots;
            $this->features = $features;
        }
    }


    // The demo function
    function createTestMatrix() {

        // Create marketing attributes
        $marketingNameAttribute = new Marketing("name", array(new NameLang("Nom", "FR")), true, DATA_TYPE::TEXT);
        $marketingShortDescriptionAttribute = new Marketing("short_description", array(new NameLang("Description courte", "FR")), true, DATA_TYPE::RICHTEXT);
        $marketingLongDescriptionAttribute = new Marketing("long_description", array(new NameLang("Description longue", "FR")), true, DATA_TYPE::RICHTEXT);
        $marketing = array($marketingNameAttribute, $marketingShortDescriptionAttribute, $marketingLongDescriptionAttribute);

        // Create pivots attributes
        // Colors
        $red = new SingleOption("red", array(new NameLang("Rouge", "FR")), "102", "", "");
        $green = new SingleOption("green", array(new NameLang("Vert", "FR")), "103", "", "");
        $valuesColor = array($red, $green);
        $colors = new Pivot("color", array(new NameLang("Couleur", "FR")), DATA_TYPE::SINGLEOPTION, $valuesColor);
        // Sizes
        $large = new SingleOption("xl", array(new NameLang("xl", "FR")), "", "", "");
        $small = new SingleOption("s", array(new NameLang("s", "FR")), "", "", "");
        $valuesSizes = array($large, $small);
        $sizes = new Pivot("sizes", array(new NameLang("Taille", "FR")), DATA_TYPE::SINGLEOPTION, $valuesSizes);
        $pivots = array($colors, $sizes);

        // Create features attributes
        // Composition
        $polyester = new SingleOption("polyester", array(new NameLang("Polyester", "FR")), "", "", "");
        $wool = new SingleOption("wool", array(new NameLang("Laine", "FR"), "", "", ""), "", "", "");
        $valuesComposition = array($polyester, $wool);
        $composition = new Feature("main_composition", array(new NameLang("Composition principale", "FR")), DATA_TYPE::SINGLEOPTION, $valuesComposition);
        $features = array($composition);

        $matrix = new Matrix(
            "testNode",
            $marketing,
            $pivots,
            $features
            );
        $matrix_json = json_encode($matrix);
        return $matrix_json;
    }

    // From my system to Product-Live
    function createMyMatrix() {

        // Create marketing attributes
        $marketingNameAttribute = new Marketing("name", array(new NameLang("Nom", "FR")), true, Data.DATA_TYPE::TEXT);
        $marketingShortDescriptionAttribute = new Marketing("short_description", array(new NameLang("Description courte", "FR")), true, DATA_TYPE::RICHTEXT);
        $marketingLongDescriptionAttribute = new Marketing("long_description", array(new NameLang("Description longue", "FR")), true, DATA_TYPE::RICHTEXT);
        $marketing = array($marketingNameAttribute, $marketingShortDescriptionAttribute, $marketingLongDescriptionAttribute);

        // For pivots attributes, they are generally linked to a table in your system.
        // You have to call your system here and then transform the result.
        // Below is an example where colors and size are store in a mysql database called node.
        // You can uncomment the bloc below

        try
        {
            $bdd = new PDO('mysql:host=localhost;dbname=node;charset=utf8', 'root', '');    // Change with your settings
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
        // Find colors
        $valuesColor = array();
        $reponse = $bdd->query('SELECT * FROM mycolortable');   // Change with your color table
        while ($donnees = $reponse->fetch())
        {
            array_push($valuesColor, new SingleOption($donnees['id'], array(new NameLang($donnees['nameFR'], "FR")), $donnees['code'], "", ""));
        }
        $colors = new Pivot("color", array(new NameLang("Couleur", "FR")), DATA_TYPE::SINGLEOPTION, $valuesColor);

        // Find sizes
        $valuesSizes = array();
        $reponse = $bdd->query('SELECT * FROM mysizestable');   // Change with your color table
        while ($donnees = $reponse->fetch())
        {
            array_push($valuesSizes, new SingleOption($donnees['id'], array(new NameLang($donnees['nameFR'], "FR")), "", "", ""));
        }
        $sizes = new Pivot("sizes", array(new NameLang("Taille", "FR")), DATA_TYPE::SINGLEOPTION, $valuesSizes);

        $pivots = array($colors, $sizes);

        // For features attributes, they are generally linked to a table in your system.
        // As in the example for colors and size, call and transform the information as needed.
        $features = array();

        $matrix = new Matrix(
            "myNode",
            $marketing,
            $pivots,
            $features
        );
        $matrix_json = json_encode($matrix);
        return $matrix_json;
    }

    // From Product-Live to my system
    function updateMyMatrix($matrix_json) {
        $matrix = utf8_encode(json_decode($matrix_json));
    }
