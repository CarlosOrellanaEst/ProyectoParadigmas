<?php


class GeneralValidations {
    
    public function accountNumberFormat($input) {
        // Expresión regular para validar el formato de la cuenta (ej: CR12345678901234567890)
        $pattern = '/^[A-Z]{2}[0-9]{20}$/';
        return !preg_match($pattern, $input); // Devuelve true si no coincide con el patrón
    }
        
    public function validateText($input) {
        $input = trim($input); 
        $length = strlen($input);
    
        
        for ($i = 0; $i < $length; $i++) {
            $char = $input[$i]; 
    
        
            if (!(($char >= 'A' && $char <= 'Z') || 
                  ($char >= 'a' && $char <= 'z') || 
                  $char == ' ')) {
                return false;
            }
        }
    
        return true; 
    }
    
    public function validateNumbers($input) {
        $input = trim($input); 
        $length = strlen($input);

        
        for ($i = 0; $i < $length; $i++) {
            $char = $input[$i];

            
            if (!($char >= '0' && $char <= '9')) {
                return false; 
            }
        }

        return true;
    }
}

?>