<?php

namespace App\Enum;

enum PaymentMethodEnum: string
{
    case Contra_Entrega = 'Contra Entrega';
    case Transferencia_Bancaria = 'Transferencia Bancaria';
    case Wompi = 'Wompi';
    case Addi = 'Addi';
    case Sistecredito = 'Sistecredito';
    

}