<?php

use App\Providers\AppServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;
use RealRashid\SweetAlert\SweetAlertServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;




return [
    AppServiceProvider::class,
    DataTablesServiceProvider::class,
    SweetAlertServiceProvider::class,
    ExcelServiceProvider::class,
];
