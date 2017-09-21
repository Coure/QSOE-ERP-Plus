<div style="margin-left:20px">
    <table id="jqGrid"></table>
    <div id="jqGridPager"></div>
</div>
<script type="text/javascript"> 
$(document).ready(function () {
    $.jgrid.defaults.width = 1000;
    $("#jqGrid").jqGrid({
        url: '<?=site_url('material/list_json');?>',
        mtype: "GET",
        styleUI : 'Bootstrap',
        datatype: "json",
        colModel: [
            { label: 'id', name: 'id', width: 75 },
            { label: 'Code', name: 'Code', width: 150 },
            { label: 'Description', name: 'Description', width: 300 },
            { label: 'UOM', name: 'UOM', width: 50 },
            { label: 'MinStock', name: 'MinStock', width: 50 },
        ],
        viewrecords: true,
        height: 500,
        rowNum: 30,
        pager: "#jqGridPager",
        loadonce: true
    });
    // add navigation bar with some built in actions for the grid
    $('#jqGrid').navGrid('#jqGridPager',
        {
            edit: false,
            add: false,
            del: false,
            search: true,
            refresh: true,
            view: true,
            position: "left",
            cloneToTop: false
        });
});
</script>