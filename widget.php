<?php

/***
 * Sviluppo Riccardo Testa - 2021
 * Dati Regione Piemonte
 */

$comune="Acqui Terme"; //Indicazione del nome del comune
//Ritorna tutti i dati del servizio in formato Json
$rest_query="https://webgis.arpa.piemonte.it/ags/rest/services/aria/Protocollo_aria_fase2/FeatureServer/3/query?where=COMUNE_NOM%3D%27".urlencode($comune)."%27&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&distance=&units=esriSRUnit_Foot&relationParam=&outFields=*&returnGeometry=true&maxAllowableOffset=&geometryPrecision=&outSR=&havingClause=&gdbVersion=&historicMoment=&returnDistinctValues=false&returnIdsOnly=false&returnCountOnly=false&returnExtentOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&multipatchOption=xyFootprint&resultOffset=&resultRecordCount=&returnTrueCurves=false&returnExceededLimitFeatures=false&quantizationParameters=&returnCentroid=false&sqlFormat=none&resultType=&featureEncoding=esriDefault&datumTransformation=&f=json";
echo $rest_query;
$json_data=file_get_contents($rest_query);

$decoded=json_decode($json_data);
//print_r($decoded);

foreach ($decoded as $key=>$decode){
    echo sprintf('<br><strong>%s</strong><br>',$key);
    print_r($decode);
    echo '<hr>';
}

