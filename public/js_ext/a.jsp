<script src="SelectUtil.js"></script>

<% String jsondatastr = Json.toJson(session.getAttribute("aaa"));%>
<select id='xxx'></select>;
<script>
    var selectData =   <%= jsondatastr %>;
    SelectOptionGen("xxx", selectData, "value", "name", 2);
</script>