<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title>[[*pagetitle]] - [[++site_name]]</title>
<base href="[[++site_url]]" />

<!-- Include any preliminary CSS such as a reset (this reset included just for demonstration purposes -->
<link type="text/css" rel="stylesheet" href="assets/components/jsonformbuilder/css/reset.css" />
<!-- Include/copy/modify the JsonFormBuilder CSS (SCSS version also packaged in assets/components/jsonformbuilder/scss/ folder -->
<link type="text/css" rel="stylesheet" href="assets/components/jsonformbuilder/css/jsonformbuilder.css" />

<!-- Grab the latest jQuery, or link directly from a Content Delivery Network -->
<script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript"></script>
<!-- Grab the latest jQueryValidate Plugin, or link directly from a Content Delivery Network -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js" type="text/javascript"></script>
    
</head>
<body>
    
    <!-- output the resource content (which will contain our snippet calls) -->
    <div>[[*content]]</div>
    
     
    <!-- Set a placeholder in your template or content page that will receive any JavaScript from the form process. -->
    <script type="text/javascript">
    // <![CDATA[
    [[+JsonFormBuilder_myForm]]
    // ]]>
    </script>

</body>
</html>