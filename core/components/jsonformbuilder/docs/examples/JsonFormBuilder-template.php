<html>
<head>
<title>[[++site_name]] - [[*pagetitle]]</title>
<base href="[[++site_url]]" />

<!-- Inclue any preliminary CSS such as a reset (this reset included just for demonstration porposes -->
<link type="text/css" rel="stylesheet" href="assets/components/jsonformbuilder/css/reset.css" />
<!-- Include/copy/modify the JsonFormBuilder CSS (SCSS version also packaged in assets/componenets/jsonformbuilder/scss/ folder -->
<link type="text/css" rel="stylesheet" href="assets/components/jsonformbuilder/css/jsonformbuilder.css" />

</head>
<body>
    
    <h1>[[*pagetitle]]</h1>
    <div>[[*content]]</div>
    
<!-- Grab the latest jQuery, or link directly from a Content Delivery Network -->
<script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript"></script>
<!-- Grab the latest jQueryValidate Plugin, or link directly from a Content Delivery Network -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js" type="text/javascript"></script>
<!-- Set a placeholder in you template or content page that will receive any JavaScript from the form process. -->
<script type="text/javascript">
// <![CDATA[
[[+JsonFormBuilder_contactForm]]
// ]]>
</script>

</body>
</html>