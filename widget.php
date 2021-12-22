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

//Contenuto dati da API
$features=$decoded->features[0]->attributes;

$stati=[
    0=>[
        'desc'=>'LIVELLO 0 - Solo limitazioni permanenti',
        'colore'=>'green'
    ],
    1=>[
        'desc'=>'LIVELLO 1 - Limitazioni di livello 1 (previsti 3 giorni consecutivi sopra il valore di 50 ug/m3)',
        'colore'=>'orange'
    ],
    2=>[
        'desc'=>'LIVELLO 2 - Limitazioni di livello 2 (previsti 3 giorni consecutivi sopra il valore di 75 ug/m3)',
        'colore'=>'red'
    ]
    ];


/*DEBUG per dati
 *
 * foreach ($decoded as $key=>$decode){
    echo sprintf('<br><strong>%s</strong><br>',$key);
    print_r($decode);
    echo '<hr>';
    if($key=='features'){
       // $features=$decode[0];
        foreach ($decode[0] as $key=>$decode){
            echo sprintf('<br><strong>%s</strong><br>',$key);
            print_r($decode);
            echo '<hr>';
        }
    }
}*/

echo '<hr><h1>';
print_r($features);
echo'</h1>';

$nome_comune=$features->COMUNE_NOM;
$teplate_struct='<br><span style="text-transform: uppercase; font-weight: 800">Comune di %s</span><br>';
echo sprintf($teplate_struct,$nome_comune);

$template_info="<span style='text-transform: uppercase'>Valido per %s (%s)</span><br> <span style='color:%s'>%s</span><br> ";
$oggi=$stati[$features->LIMIT_GG_CORRENTE];
$domani=$stati[$features->LIMIT_GG_SUCCESSIVA];

echo sprintf($template_info,'oggi',date("Y-m-d",$features->DATA_OGGI/1000),$oggi['colore'],$oggi['desc']);
echo sprintf($template_info,'domani',date("Y-m-d",$features->DATA_DOMANI/1000),$domani['colore'],$domani['desc']);

$data_aggiornamento=date("Y-m-d",$features->DATA_AGG/1000);
$teplate_struct='<br><span style="text-transform: uppercase; font-weight: 800">Data aggiornamento %s</span>';
echo sprintf($teplate_struct,$data_aggiornamento);

$url_sito=$features->URL_SITO;
$teplate_struct='<br><a href="%s" target="_blank" style="font-weight: 800">Maggiori informazioni</a>';
echo sprintf($teplate_struct,$url_sito);


$data_prox=date("d m Y",$features->DATA_PROX_EMISS/1000);
$teplate_struct='<br><span style="text-transform: uppercase; font-weight: 800">Data prossima emissione %s</span>';
echo sprintf($teplate_struct,$data_prox);