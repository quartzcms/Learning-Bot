<?php
	/* Function to format word and remove and convert all special characters to _ */
	function format_word($al_txt = null) {
        $al_transliterationTable = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', 'ç' => 'c', 'Ç' => 'C', 'Ð' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'ê' => 'e', 'Ê' => 'E', 'ë' => 'e', 'Ë' => 'E', 'ƒ' => 'f', 'ƒ' => 'F', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ñ' => 'n', 'Ñ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'ö' => 'oe', 'Ö' => 'OE', 'š' => 's', 'Š' => 'S', 'ß' => 'SS', 'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'û' => 'u', 'Û' => 'U', 'ü' => 'ue', 'Ü' => 'UE', 'ý' => 'y', 'Ý' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', 'ž' => 'z', 'Ž' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u');
        $al_txt = str_replace(array_keys($al_transliterationTable), array_values($al_transliterationTable), html_entity_decode($al_txt));
        $al_txt = preg_replace_callback("/[^a-zA-Z0-9]/", function() {
            return "_";
        }, $al_txt);
        return $al_txt;
    }
