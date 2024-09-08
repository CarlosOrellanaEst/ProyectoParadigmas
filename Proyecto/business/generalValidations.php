<?php


class GeneralValidations {

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