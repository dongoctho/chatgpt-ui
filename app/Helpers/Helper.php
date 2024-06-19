<?php 

if( !function_exists('get_value_from_array') ){
    /**
     * 
     * @param mixed $value 
     * @param mixed $keys 
     * @return mixed 
     */
    function get_value_from_array($value, $keys)
    {
        foreach ($keys as $key) {
            if (!is_array($value) || !array_key_exists($key, $value)) {
                return null;
            }
            $value = $value[$key];
        }

        return $value;
    }
}
