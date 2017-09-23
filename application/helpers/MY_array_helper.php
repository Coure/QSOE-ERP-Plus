<?php
/*******
Transfer table array to a dictionary array, with header as key and following rows as value.
Parameters:
    dataTable: 2-dimensional array.
    headers: A string array. Each element represents the header of each column. If null, the first row in dataTable will be the header.
Return:	An array with keys and values.
/*******/
function table_to_dict($dataTable, $headers = NULL)
{
    if ($headers === NULL)
    {
        // Take the first row as header.
        $headers = $dataTable[1];
        // Remove the first row.
        unset($dataTable[1]);
    }
    foreach ($dataTable as &$dataLine)
    {
        // Combine header as keys and dataLine as values.
        $dataLine = array_combine($headers, $dataLine);
    }
    return $dataTable;
}

function csv_to_array( $filename, $delimiter=',' )
{
    // read the CSV lines into a numerically indexed array
    $all_lines = @file( $filename );
    if( !$all_lines ) {
        return FALSE;
    }
    $csv = array_map( function( &$line ) use ( $delimiter ) {
        return str_getcsv( $line, $delimiter );
    }, $all_lines );

    // use the first row's values as keys for all other rows
    array_walk( $csv, function( &$a ) use ( $csv ) {
        $a = array_combine( $csv[0], $a );
    });
    array_shift( $csv ); // remove column header row

    return $csv;
}
?>