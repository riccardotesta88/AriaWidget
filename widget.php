<?php

/***
 * Sviluppo Riccardo Testa - 2021
 * Dati Regione Piemonte - Dati servizio rest Regione Piemonte
 */

$comune = "Acqui Terme"; //Indicazione del nome del comune
//Ritorna tutti i dati del servizio in formato Json
$rest_query = "https://webgis.arpa.piemonte.it/ags/rest/services/aria/Protocollo_aria_fase2/FeatureServer/3/query?where=COMUNE_NOM%3D%27" . urlencode($comune) . "%27&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&distance=&units=esriSRUnit_Foot&relationParam=&outFields=*&returnGeometry=true&maxAllowableOffset=&geometryPrecision=&outSR=&havingClause=&gdbVersion=&historicMoment=&returnDistinctValues=false&returnIdsOnly=false&returnCountOnly=false&returnExtentOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&multipatchOption=xyFootprint&resultOffset=&resultRecordCount=&returnTrueCurves=false&returnExceededLimitFeatures=false&quantizationParameters=&returnCentroid=false&sqlFormat=none&resultType=&featureEncoding=esriDefault&datumTransformation=&f=json";
//echo $rest_query;
$json_data = file_get_contents($rest_query);

$decoded = json_decode($json_data);
//print_r($decoded);

//Contenuto dati da API
$features = $decoded->features[0]->attributes;

$stati = [
    0 => [
        'desc' => 'LIVELLO 0 - Solo limitazioni permanenti',
        'colore' => 'green'
    ],
    1 => [
        'desc' => 'LIVELLO 1 - Limitazioni di livello 1 (previsti 3 giorni consecutivi sopra il valore di 50 ug/m3)',
        'colore' => 'orange'
    ],
    2 => [
        'desc' => 'LIVELLO 2 - Limitazioni di livello 2 (previsti 3 giorni consecutivi sopra il valore di 75 ug/m3)',
        'colore' => 'red'
    ]
];

$nome_comune = $features->COMUNE_NOM;
$teplate_struct = '<span style="text-transform: uppercase; font-weight: 800">Comune di %s</span><br>';
$html_comune = sprintf($teplate_struct, $nome_comune);

//Date pubblicazione
$template_info = "<tr><td class='text-center'><span style='text-transform: uppercase'>Valido per %s <br>(<strong>%s</strong>)</span></td><td class='text-center'><span style='font-weight:800;color:%s'>%s</span></td></tr> ";
$oggi = $stati[$features->LIMIT_OGGI];
$domani = $stati[$features->LIMIT_DOMANI];
$html_oggi = sprintf($template_info, 'oggi', date("Y-m-d", $features->DATA_OGGI / 1000), $oggi['colore'], $oggi['desc']);
$html_domani = sprintf($template_info, 'domani', date("Y-m-d", $features->DATA_DOMANI / 1000), $domani['colore'], $domani['desc']);

//Data Aggiornamento
$data_aggiornamento = date("d-m-Y H:s", $features->DATA_AGG / 1000);
$teplate_struct = '<br><span style="text-transform: uppercase; font-weight: 800">Data aggiornamento %s</span>';
$html_data_agg = sprintf($teplate_struct, $data_aggiornamento);

//Prossimo Aggiornamento
$data_prox = date("d-m-Y H:s", $features->DATA_PROX_EMISS / 1000);
$teplate_struct = '<br><span style="text-transform: uppercase; font-weight: 800">Data prossima emissione %s</span>';
$html_data_prox = sprintf($teplate_struct, $data_prox);

//Url informazioni
$url_sito = $features->URL_SITO;
$teplate_struct = '<a href="%s" class="btn" target="_blank" style="font-weight: 800">Maggiori informazioni</a>';
$html_url_sito = sprintf($teplate_struct, $url_sito);

?>

<!-- Bootstrap
-->
<!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"-->
<!--      integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">-->

<div class="w-100 border-0">
    <div class="card-head text-center">
        <div class="row">
            <div class="col-md-12 text-uppercase">
                <h3>Allerta qualità dell'aria <?= $html_comune ?></h3></div>
        </div>
        <div class="table row">
            <div class="col-md-6"><?= $html_data_agg ?></div>
            <div class="col-md-6"><?= $html_data_prox ?></div>
        </div>


    </div>
    <div class="table dark card-body">

        <table class="table table-striped thead-dark">
            <thead class="text-center text-uppercase" >
            <tr>
                <th>Data</th>
                <th>Livello</th></tr>
            </thead>
            <tbody>
            <?= $html_oggi; ?>
            <?= $html_domani; ?>
            </tbody>
        </table>
        <?= $html_url_sito; ?>
    </div>
</div>

<p style="font-size:1.5rem">Legenda:</p><br>
<span style="color: #99cc00;"> 0 Nessuna allerta</span><br>
<span style="color: #ff6600;"> 1 Primo livello: 3 giorni consecutivi superiori al valore di 50 μg/m 3</span><br>
<span style="color: #ff0000;"> 2 Secondo livello: 3 giorni consecutivi superiori al valore di 75 μg/m 3</span>