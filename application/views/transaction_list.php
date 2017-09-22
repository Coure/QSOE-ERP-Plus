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
    $.jgrid.defaults.width = 1200;
    $("#jqGrid").jqGrid({
        url: '<?=site_url('transaction/list_json');?>',
        mtype: "GET",
        styleUI : 'Bootstrap',
        datatype: "json",
        colModel: [
            { label: '日期', name: 'TrnxDate', width: 75, formatter:
                function(cellvalue, options, row){return new Date(cellvalue*1000).toLocaleDateString()}
            },
            { label: '类型', name: 'TrnxSrc', width: 100 },
            { label: '来源', name: 'Source', width: 100 },
            { label: '料号', name: 'MaterialCode', width: 100 },
            {
                label: '物料描述',
                name: 'Name',
                width: 300,
                searchoptions: { sopt: ['cn'] },
            },
            { label: '数量', name: 'Qty', width: 50, align: 'right' },
            { label: '仓库', name: 'Subinv', width: 50 },
            { label: '货位', name: 'Locate', width: 50 },
            { label: '拥有方', name: 'Owner', width: 50 },
        ],
        viewrecords: true,
        height: 500,
        rowNum: 30,
        rowList: [20,30,50],
        sortname: 'TrnxDate',
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