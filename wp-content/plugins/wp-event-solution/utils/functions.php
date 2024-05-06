<?php
if ( ! function_exists( 'etn_array_csv_column' ) ) {
    /**
     * Convert array to CSV column
     *
     * @param array $data
     *
     * @return string
     */
    function etn_array_csv_column( $data = [] ) {
        $result_string = '';

        foreach ( $data as $data_key => $value ) {
            if ( ! is_array( $value ) ) {
                return etn_is_associative_array( $data ) ? etn_single_array_csv_column( $data ) : implode( ',', $data );
            }

            if ( etn_is_associative_array( $value ) ) {
                $valueString = etn_single_array_csv_column( $value );
                $result_string .= rtrim( $valueString, ', ' ) . '|';
            } else {
                $result_string .= implode( ',', $value ) . '|';
            }
        }

        // Remove the trailing '|'
        $result_string = rtrim( $result_string, '|' );

        return $result_string;
    }
}

if ( ! function_exists( 'etn_is_associative_array' ) ) {
    /**
     * Check an associative array or not
     *
     * @param array $array
     *
     * @return bool
     */
    function etn_is_associative_array( $array ) {
        return is_array( $array ) && count( array_filter( array_keys( $array ), 'is_string' ) ) > 0;
    }
}

if ( ! function_exists( 'etn_single_array_csv_column' ) ) {
    /**
     * Convert single array to csv column
     *
     * @param array $data
     *
     * @return string
     */
    function etn_single_array_csv_column( $data ) {
        if ( ! is_array( $data ) ) {
            return false;
        }

        $result_string = '';

        foreach ( $data as $key => $value ) {
            if ( is_array( $value ) ) {
                $result_string .= implode( ',', $value );
            } else {
                $result_string .= "$key:$value,";
            }
        }

        return rtrim( $result_string, ',' );
    }
}

if ( ! function_exists( 'etn_csv_column_array' ) ) {
    /**
     * Convert CSV column to array
     *
     * @param string $csvColumn
     *
     * @return array|bool
     */
    function etn_csv_column_array( $csv_column, $separator = '|' ) {
        // Explode the CSV column by '|' to get individual array elements
        if ( strpos( $csv_column, $separator ) !== false ) {
            return etn_csv_column_multi_dimension_array( $csv_column );
        }

        return etn_csv_column_single_array( $csv_column );
    }
}

if ( ! function_exists( 'etn_csv_column_multi_dimension_array' ) ) {
    /**
     * Convert CSV column to multi dimensional array
     *
     * @param   string  $csv_column
     * @param   string  $separator
     *
     * @return  array
     */
    function etn_csv_column_multi_dimension_array( $csv_column, $separator = '|' ) {
        $array_strings = explode( $separator, $csv_column );
        $result_array  = [];

        foreach ( $array_strings as $array_string ) {
            // Add the temporary array to the result array
            $result_array[] = etn_csv_column_single_array( $array_string );
        }

        return $result_array;
    }
}

if ( ! function_exists( 'etn_csv_column_single_array' ) ) {
    /**
     * Convert CSV column to multi dimensional array
     *
     * @param   string  $csv_column
     * @param   string  $separator
     *
     * @return  array
     */
    function etn_csv_column_single_array( $csv_column, $separator = ',' ) {
        $temp_array = [];

        if ( false !== strpos( $csv_column, ':' ) ) {
            $csv_column = explode( $separator, $csv_column );

            foreach ( $csv_column as $pair ) {
                // Explode key-value pairs by ':' and populate the temporary array
                list( $key, $value ) = explode( ':', $pair );
                $temp_array[$key]  = $value;
            }

            return $temp_array;
        }

        return explode( $separator, $csv_column );
    }
}
