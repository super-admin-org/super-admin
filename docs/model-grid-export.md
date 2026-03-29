# Data Export

Super Admin supports exporting grid data to CSV and Excel formats.

## Built-in Exporters

### CSV Export (Default)

```php
use SuperAdmin\Admin\Grid\Exporters\CsvExporter;

$grid->exporter(new CsvExporter());
```

### Excel Export

```php
use SuperAdmin\Admin\Grid\Exporters\ExcelExporter;

$grid->exporter(new ExcelExporter());
```

## Disable Export

```php
$grid->disableExport();
```

## Export Options

The export button provides three options:

- **All** - Export all records (ignoring pagination)
- **Current page** - Export only the current page
- **Selected rows** - Export only selected rows

## Custom Exporter

Create a custom exporter by implementing the `ExporterInterface`:

```php
<?php

namespace App\Admin\Exporters;

use SuperAdmin\Admin\Grid\Exporters\ExporterInterface;

class PdfExporter implements ExporterInterface
{
    public function export()
    {
        // Your export logic here
    }
}
```

Use it:

```php
$grid->exporter(new PdfExporter());
```
