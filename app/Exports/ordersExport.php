<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ordersExport implements WithHeadings,FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::all();
    }

    public function headings(): array{
        return['ID','User ID','User email','Name','Surname','Address','City','State','Postal Code',
        'Country','Mobile','Shipping Charges','Coupon Code','Coupon Amount','Order Status',
        'Payment Method','Grand Total','Created At','Updated At'];
    }
}
