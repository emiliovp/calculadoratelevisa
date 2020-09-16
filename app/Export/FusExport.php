<?php

namespace App\Export;

// use App\Comparelaboraconcilia;
// use App\Rdbmsqrys;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\FromArr;

class FusExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    private $data;
    private $campos;
    public function __construct($data,$campos)
    {
        $this->data = $data;
        $this->campos = $campos;
    }

    public function headings(): array
    {
        return $this->campos;
        // return [
        //     'Folio de la Solicitud',
        //     'Numero del empleado',
        //     'FECHABAJA',
        //     'NOMBRE',
        //     'EMPRESA',
        // ];
    }
    public function collection()
    {
        return collect($this->data);
    }
}
