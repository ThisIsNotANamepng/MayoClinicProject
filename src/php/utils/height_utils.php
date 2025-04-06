<?php
    /**
     * Returns a decimal representing height in ft
     * @param int feet - the height in feet 
     * @param int inches - the height in inches
     * @return float decimal representing the height in feet
     */
    function toDecimal($feet, $inches): float {
        return $feet + ($inches/12);
    }

    /**
     * Returns the height in feet
     * @param float height - the height decimal
     * @return float rounded height in feet
     */
    function getFeet($height): float {
        return floor($height);
    }

    /**
     * Returns the remaining inches (after feet has been acounted for)
     * @param float height - the height decimal
     * @return float the rounded remaining height in inches
     */
    function getInches($height): float {
        return floor(($height - floor($height)) * 12);
    }
?>