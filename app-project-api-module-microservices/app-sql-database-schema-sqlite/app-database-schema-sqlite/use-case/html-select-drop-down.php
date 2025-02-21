<?php
namespace App\DatabaseSchema\Html\Select\DropDown;
?>
<?php
use App\DatabaseSchema\Html\Select\DropDown\SchemaSelectDropDown;
use App\DatabaseSchema\Html\Select\DropDown\TableSelectDropDown;
use App\DatabaseSchema\Html\Select\DropDown\DataTypeSelectDropDown;
use App\DatabaseSchema\Html\Select\DropDown\ColumnSelectDropDown;
use App\DatabaseSchema\Html\Select\DropDown\ColumnKeySelectDropDown;
use App\DatabaseSchema\Html\Select\DropDown\RelationalKeyTypeSelectDropDown;
use App\DatabaseSchema\Html\Select\DropDown\IsNullSelectDropDown;
use App\DatabaseSchema\Html\Select\DropDown\IsDefaultSelectDropDown;
use RzSDK\Log\DebugLog;
?>
<?php
class HtmlSelectDropDown {
    use SchemaSelectDropDown;
    use TableSelectDropDown;
    use DataTypeSelectDropDown;
    use ColumnSelectDropDown;
    use ColumnKeySelectDropDown;
    use RelationalKeyTypeSelectDropDown;
    use IsNullSelectDropDown;
    use IsDefaultSelectDropDown;
}
?>