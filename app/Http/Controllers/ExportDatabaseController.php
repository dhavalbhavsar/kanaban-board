<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExportDatabaseController extends Controller
{
    /**
     * Export database
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $fileName = 'dump-'.time().'.sql';
        $filePath = storage_path('app/public/download/'.$fileName);

        \Spatie\DbDumper\Databases\MySql::create()
            ->setDumpBinaryPath(env('DUMP_BINARY_PATH'))
            ->setHost(env('DB_HOST'))
            ->setDbName(env('DB_DATABASE'))
            ->setUserName(env('DB_USERNAME'))
            ->setPassword(env('DB_PASSWORD'))
            ->dumpToFile($filePath);

        return response()->download($filePath, $fileName);
    }
}
