<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClientPasswordOtp;
use Illuminate\Support\Facades\Log;

class CleanupExpiredOtpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup
                            {--days=1 : Número de días para considerar códigos como expirados}
                            {--dry-run : Mostrar qué se eliminaría sin eliminar realmente}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar códigos OTP expirados y usados de la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $dryRun = $this->option('dry-run');

        $this->info("🧹 Iniciando limpieza de códigos OTP...");

        // Buscar códigos expirados o usados más antiguos que X días
        $cutoffDate = now()->subDays($days);

        $query = ClientPasswordOtp::where(function ($q) use ($cutoffDate) {
            $q->where('expires_at', '<', now()) // Códigos expirados
              ->orWhere('is_used', true); // Códigos ya usados
        })->where('created_at', '<', $cutoffDate); // Más antiguos que X días

        $count = $query->count();

        if ($count === 0) {
            $this->info("✅ No hay códigos OTP para limpiar.");
            return 0;
        }

        $this->info("📊 Encontrados {$count} códigos OTP para limpiar:");

        // Mostrar estadísticas
        $expired = ClientPasswordOtp::where('expires_at', '<', now())
            ->where('created_at', '<', $cutoffDate)
            ->count();

        $used = ClientPasswordOtp::where('is_used', true)
            ->where('created_at', '<', $cutoffDate)
            ->count();

        $this->line("   - Códigos expirados: {$expired}");
        $this->line("   - Códigos usados: {$used}");

        if ($dryRun) {
            $this->warn("🔍 Modo DRY-RUN: No se eliminará nada realmente.");

            // Mostrar algunos ejemplos
            $examples = $query->take(5)->get(['email', 'created_at', 'expires_at', 'is_used']);

            if ($examples->count() > 0) {
                $this->line("\n📝 Ejemplos de códigos que se eliminarían:");

                $headers = ['Email', 'Creado', 'Expira', 'Usado'];
                $data = $examples->map(function ($otp) {
                    return [
                        $otp->email,
                        $otp->created_at->format('Y-m-d H:i'),
                        $otp->expires_at->format('Y-m-d H:i'),
                        $otp->is_used ? 'Sí' : 'No'
                    ];
                });

                $this->table($headers, $data);
            }

            return 0;
        }

        // Confirmar eliminación en producción
        if (app()->environment('production')) {
            if (!$this->confirm("¿Estás seguro de que quieres eliminar {$count} códigos OTP?")) {
                $this->info("❌ Operación cancelada.");
                return 1;
            }
        }

        // Realizar la eliminación
        try {
            $deleted = $query->delete();

            $this->info("✅ Eliminados {$deleted} códigos OTP exitosamente.");

            Log::info('OTP cleanup completed', [
                'deleted_count' => $deleted,
                'days_threshold' => $days,
                'cutoff_date' => $cutoffDate->format('Y-m-d H:i:s')
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error al limpiar códigos OTP: " . $e->getMessage());

            Log::error('OTP cleanup failed', [
                'error' => $e->getMessage(),
                'days_threshold' => $days,
                'cutoff_date' => $cutoffDate->format('Y-m-d H:i:s')
            ]);

            return 1;
        }
    }
}
