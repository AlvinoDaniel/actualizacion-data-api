<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class PersonalExport implements FromView, WithTitle
{
    protected $report;

    public function __construct($data)
    {
        $this->report = $data;
    }

    public function view(): View
    {
        return view('exports.personal', ["report" => $this->report]);
    }

    public function title(): string
    {
        return 'Personal Registrado';
    }
}
