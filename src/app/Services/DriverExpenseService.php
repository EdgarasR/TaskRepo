<?php

namespace App\Services;

class DriverExpenseService
{
    public function calculateDriverExpenses(array $drivers, array $expenses): array
    {
        $expenseTypes = [
            'Fuel (EFS)',
            'Fuel (Comdata)',
            'Insurance (Truck)',
            'Insurance (Trailer)',
            'Engine oil',
            'Tires',
            'Truck wash',
            'Trailer wash',
            'Flight tickets',
        ];

        $driverExpenses1 = [];
        $driverExpenses2 = [];

        $sum1 = 0;
        $sum2 = 0;

        // $expenses masyve ateina stringai, tad kad išvengti nesusipratimų, paverčiu floatais.
        $floats = array_map('floatval', $expenses);

        $expenses = $this->convertExpensesToCents($floats);

        // Dalinu iš 200 kad gaučiau pusę išlaidų ir atversčiau iš centų
        $count = 0;
        foreach ($expenses as $value) {
            if ((($value % 2 !== 0)) && ($sum1 <= $sum2)) {
                $driverExpenses1[$expenseTypes[$count]] = (($value + 1) / 200);
                $driverExpenses2[$expenseTypes[$count]] = (($value - 1) / 200);
            } else if ((($value % 2 !== 0)) && ($sum1 > $sum2)) {
                $driverExpenses1[$expenseTypes[$count]] = (($value - 1) / 200);
                $driverExpenses2[$expenseTypes[$count]] = (($value + 1) / 200);
            } else {
                $driverExpenses1[$expenseTypes[$count]] = ($value / 200);
                $driverExpenses2[$expenseTypes[$count]] = ($value / 200);
            }
            $sum1 = array_sum($driverExpenses1);
            $sum2 = array_sum($driverExpenses2);
            $count++;
        }

        // Išlaidų palyginimui, tiesiog jas padalinus pusiau
        $fullExpenses = array_sum($expenses);
        $halfExpenses = $fullExpenses / 200;

        // Sukuriu masyvą su abiem vairuotojais ir jų atitinkamom išlaidom
        $driverExpenses[] = $driverExpenses1;
        $driverExpenses[] = $driverExpenses2;

        $bothExpenses = array_combine($drivers, $driverExpenses);

        $result = [
            'Vairuotojų tarpinės išlaidos' => $bothExpenses,
            'Pirmo vairuotojo pilnos išlaidos' => $sum1,
            'Antro vairuotojo pilnos išlaidos' => $sum2,
            'Pusė išlaidų(palyginimui)' => $halfExpenses
        ];

        return $result;
    }

    // Pasiverčiu visas išlaidas centais
    private function convertExpensesToCents(array $floats): array
    {
        $tmp = [];
        foreach ($floats as $expense) {
            $tmp[] = round(floatval($expense * 100));
        }

        return $tmp;
    }
}
