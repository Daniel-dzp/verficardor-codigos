<?php
    $data = [];

    if(isset($_POST['archivo1']) && isset($_POST['archivo2'])){
        $archivo1 = $_POST['archivo1'];
        $archivo2 = $_POST['archivo2'];

        if(strlen($archivo1)>0 && strlen($archivo1)>0){
            similar_text($archivo1, $archivo2, $similitud);
            $data['error'] = false;
            $data['mensaje'] = "El porcentaje de similitud entre los dos codigos es un ".intval($similitud)."%.";
            $data['similitud'] = intval($similitud);
        }
        else{
            $data['error'] = true;
            $data['mensaje'] = "Error se enviaron archivos sin contenido.";
        }

    }
    else{
        $data['error'] = true;
        $data['mensaje'] = "Error no se enviaron los archivos";
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);