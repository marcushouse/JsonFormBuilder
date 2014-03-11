<?php
$vFile = 'version.txt';
$s_oldVersion = file_get_contents('version.txt');
$a_versionSplit = explode('.',$s_oldVersion);
$a_versionSplit[count($a_versionSplit)-1]++;
$s_version = implode('.',$a_versionSplit);
file_put_contents($vFile, $s_version);

/* Build Info */
define('PKG_NAME','JsonFormBuilder');
define('PKG_VERSION',$s_version);
define('PKG_RELEASE','pl');
define('PKG_NAME_LOWER',strtolower(PKG_NAME));

/* config (might separate this at some point */
define('MODX_CORE_PATH', dirname(dirname(__FILE__)) . '/core/');

$tstart_pre = explode(' ', microtime());
$tstart = $tstart_pre[1] + $tstart_pre[0];
set_time_limit(0);

 
/* define build paths */
$root = dirname(dirname(__FILE__)).'/';
$sources = array(
    'root' => $root,
    'build' => $root . '_build/',
    'docs' => $root.'core/components/'.PKG_NAME_LOWER.'/docs/',
    'elements' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/',
    'source_assets' => $root.'assets/components/'.PKG_NAME_LOWER,
    'source_core' => $root.'core/components/'.PKG_NAME_LOWER,
);
unset($root);
 
/* override with your own defines here (see build.config.sample.php) */
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
 
$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');
 
$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER,false,true,'{core_path}components/'.PKG_NAME_LOWER.'/');

/* create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_NAME);


/* add snippets */
$modx->log(modX::LOG_LEVEL_INFO,'Packaging in snippets...');
$snippets = array();

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'JsonFormBuilder-fromJSON',
    'description' => 'Shorthand method of creating a simple form with JSON syntax.',
    'snippet' => trim(file_get_contents($sources['elements'].'snippets/snippet.fromjson.php')),
),'',true,true);

$category->addMany($snippets);


/* create category vehicle */
$attr = array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Snippets' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        ),
    ),
);
$vehicle = $builder->createVehicle($category,$attr);

$modx->log(modX::LOG_LEVEL_INFO,'Adding file resolvers to category...');
$vehicle->resolve('file',array(
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';",
));
$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));
$builder->putVehicle($vehicle);
unset($vehicle,$menu);

/* now pack in the license file, readme and setup options */
$modx->log(modX::LOG_LEVEL_INFO,'Adding package attributes and setup options...');
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
));
 
/* zip up package */
$modx->log(modX::LOG_LEVEL_INFO,'Packing up transport package zip...');
$builder->pack();
 
$tend_pre = explode(" ", microtime());
$tend = $tend_pre[1] + $tend_pre[0];
$totalTime = sprintf("%2.4f s",($tend - $tstart));
$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");
 
 
session_write_close();
exit ();