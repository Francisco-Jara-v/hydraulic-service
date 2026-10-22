<?php

namespace App\Filament\Widgets;

use App\Models\Arriendo;
use App\Models\Cliente;
use App\Models\EquipoItem;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $equiposDisponibles = EquipoItem::query()
            ->where('estado', 'Disponible')
            ->count();

        $clientesRegistrados = Cliente::query()
            ->count();

        $arriendosActivos = Arriendo::query()
            ->where('estado', 'Activo')
            ->count();

        return [
            Stat::make(
                'Equipos disponibles',
                $equiposDisponibles
            )
                ->description('Unidades disponibles para arriendo')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make(
                'Clientes registrados',
                $clientesRegistrados
            )
                ->description('Total de clientes registrados')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make(
                'Arriendos activos',
                $arriendosActivos
            )
                ->description('Contratos actualmente activos')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('warning'),
        ];
    }
}
