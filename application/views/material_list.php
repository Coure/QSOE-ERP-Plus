<div style="margin-left:20px">
    <!-- <form class="form-inline">
        <label for="searchBox" class="sr-only">Search</label>
        <input id="searchBox" type="text" class="form-control" placeholder="Keyword">
        <button class="btn btn-default">Search</button>
    </form>
    <br /> -->
    <table id="jqGrid"></table>
    <div id="jqGridPager"></div>
</div>
<script type="text/javascript"> 
$(document).ready( function () 
{
    $.jgrid.defaults.width = 1000;
    $("#jqGrid").jqGrid({
        url: '<?=site_url('material/list_json');?>',
        mtype: "GET",
        styleUI : 'Bootstrap',
        datatype: "json",
        colModel: [
            { label: 'Code', name: 'Code', width: 100 },
            {
                label: 'Name',
                name: 'Name',
                width: 400,
                searchoptions: { sopt: ['cn'] },
            },
            { label: 'UOM', name: 'UOM', width: 50 },
            { label: 'MSQ', name: 'MSQ', width: 50, align: 'right' },
            { label: 'Update', name: 'LastUpdate', width: 100, formatter:
                function(cellvalue, options, row){return new Date(cellvalue*1000).toLocaleDateString()}
            },
        ],
        viewrecords: true,
        height: 500,
        rowNum: 30,
        rowList: [20,30,50],
        sortname: 'Code',
        sortorder: "asc",
        pager: "#jqGridPager",
        loadonce: true,
        multiSort: true,
    });
    // activate the toolbar searching
    $('#jqGrid').jqGrid(
        'filterToolbar',
        {
            stringResult: true,
            searchOperators: true,
        }
    );
    // add navigation bar with some built in actions for the grid
    $('#jqGrid').navGrid(
        '#jqGridPager',
        {
            edit: false,
            add: false,
            del: false,
            search: true,
            refresh: true,
            view: true,
            position: "left",
            cloneToTop: false
        },
        {}, // edit options
        {}, // add options
        {}, // delete options
        {
            multipleSearch: true,
            multipleGroup: true, 
            showQuery: true,
            // set the names of the template
            // tmplNames: ["Template One", "Template Two"],
            // set the template contents
            // tmplFilters: [template1, template2],
        } // search options - define multiple search);
    );
});
</script>