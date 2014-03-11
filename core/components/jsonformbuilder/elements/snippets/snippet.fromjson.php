<?php
require_once $modx->getOption('core_path',null,MODX_CORE_PATH).'components/jsonformbuilder/model/jsonformbuilder/JsonFormBuilderFromJson.class.php';
$o_jsonFormBuilderFromJson = new JsonFormBuilderFromJson($modx);
$o_jsonFormBuilderFromJson->setJsonDataFromChunk($jsonTpl);
return $o_jsonFormBuilderFromJson->output();