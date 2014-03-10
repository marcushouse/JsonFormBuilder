<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title>[[++site_name]] - [[*pagetitle]]</title>
<base href="[[++site_url]]" />

<!-- Include any preliminary CSS such as a reset (this reset included just for demonstration purposes -->
<link type="text/css" rel="stylesheet" href="assets/components/jsonformbuilder/css/reset.css" />
<!-- Include/copy/modify the JsonFormBuilder CSS (SCSS version also packaged in assets/componenets/jsonformbuilder/scss/ folder -->
<link type="text/css" rel="stylesheet" href="assets/components/jsonformbuilder/css/jsonformbuilder.css" />

</head>
<body>
    [[Wayfinder? &startId=`0`&level=`2`]]
    <h1>[[*pagetitle]]</h1>
    <div>[[*content]]</div>
    
<!-- Grab the latest jQuery, or link directly from a Content Delivery Network -->
<script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript"></script>
<!-- Grab the latest jQueryValidate Plugin, or link directly from a Content Delivery Network -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js" type="text/javascript"></script>

<!-- Add a library of your own custom validation methods if you want to create your own (can be created inline like this, or as an external JS file). -->
<script type="text/javascript">
// <![CDATA[
jQuery().ready(function() {
    //Make a custom validation method for jQuery Validate (Could be included as an additional JS file
    jQuery.validator.addMethod("customPhoneNum", function(phone_number, element, val) {
        phone_number = phone_number.replace(/\s+/g, "");
        var matchStr = '^\\(?(\\d{'+val[0]+'})\\)?[- ]?(\\d{'+val[1]+'})[- ]?(\\d{'+val[2]+'})$';
        //alert(matchStr);
        return this.optional(element) || phone_number.match(matchStr);
    }, "Phone number invalid");
});
// ]]>
</script>

<!-- Set a placeholder in your template or content page that will receive any JavaScript from the form process. -->
<script type="text/javascript">
// <![CDATA[
[[+JsonFormBuilder_myForm]]
// ]]>
</script>

</body>
</html>