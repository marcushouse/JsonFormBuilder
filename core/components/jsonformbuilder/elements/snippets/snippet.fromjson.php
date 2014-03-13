<?php
require_once $modx->getOption('core_path',null,MODX_CORE_PATH).'components/jsonformbuilder/model/jsonformbuilder/JsonFormBuilderFromJson.class.php';
$o_JFBfJ = new JsonFormBuilderFromJson($modx);
if(isset($jsonTpl)){
    $o_JFBfJ->setJsonDataFromChunk($jsonTpl);
}elseif(isset($jsonFile)){
    $o_JFBfJ->setJsonDataFromFile($jsonFile);
}elseif(isset($json)){
    $o_JFBfJ->setJsonData($json);
}
return $o_JFBfJ->output();
