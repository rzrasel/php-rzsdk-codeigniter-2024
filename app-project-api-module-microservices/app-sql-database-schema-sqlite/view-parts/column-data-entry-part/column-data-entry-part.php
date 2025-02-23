<table class="form-heading">
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Column Data Entry</td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>
<table>
    <tr>
        <td class="data-entry-container">
            <?php
            if(!empty($_REQUEST)){
                if(!empty($_REQUEST["search_by_schema_id"]) && !empty($_REQUEST["search_by_table_id"])) {
                    require_once("column-data-entry-action-part.php");
                }
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="data-search-by-container">
            <?php
            require_once("column-data-entry-search-part.php");
            ?>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="data-search-result-container">
            <?php
            /*if(!empty($_REQUEST)){
                if(!empty($_REQUEST["search_by_table_id"])) {
                    require_once("column-data-update-search-result-part.php");
                }
            }*/
            ?>
        </td>
    </tr>
</table>