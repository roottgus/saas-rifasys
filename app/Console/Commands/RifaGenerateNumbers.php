<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rifa;
use Illuminate\Support\Facades\DB;

class RifaGenerateNumbers extends Command
{
    protected $signature = 'rifa:generate-numbers {rifa_id} {--force : Regenera aunque existan}';
    protected $description = 'Genera los números 1..N para una rifa';

    public function handle(): int
    {
        $rifa = Rifa::find($this->argument('rifa_id'));
        if (! $rifa) { $this->error('Rifa no encontrada'); return self::FAILURE; }

        if (! $this->option('force') && $rifa->numeros()->exists()) {
            $this->warn('Ya existen números. Usa --force para regenerar.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($rifa) {
            if ($this->option('force')) {
                $rifa->numeros()->delete();
            }
            $now = now();
            $rows = [];
            for ($i = 1; $i <= $rifa->total_numeros; $i++) {
                $rows[] = [
                    'tenant_id' => $rifa->tenant_id,
                    'rifa_id'   => $rifa->id,
                    'numero'    => $i,
                    'estado'    => 'disponible',
                    'created_at'=> $now,
                    'updated_at'=> $now,
                ];
                if (count($rows) === 1000) { // inserciones en lotes
                    DB::table('rifa_numeros')->insert($rows);
                    $rows = [];
                }
            }
            if ($rows) DB::table('rifa_numeros')->insert($rows);
        });

        $this->info("Números generados: 1..{$rifa->total_numeros}");
        return self::SUCCESS;
    }
}
