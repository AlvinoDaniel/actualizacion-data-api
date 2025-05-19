<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReporteAllExport implements WithMultipleSheets
{
    use Exportable;

    protected $person;
    protected $bosses;

    public function __construct($person, $bosses)
    {
        $this->person = $person;
        $this->bosses = $bosses;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new PersonalExport($this->person);
        $sheets[] = new JefeExport($this->bosses);

        return $sheets;
    }
}
