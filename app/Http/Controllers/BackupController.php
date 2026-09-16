<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    public function index()
    {
        $backupPath = storage_path('app/backups');

        if (! File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $files = collect(File::files($backupPath))
            ->filter(fn ($file) => $file->getExtension() === 'sql')
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->values()
            ->map(fn ($file) => [
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
                'created_at' => date(
                    'Y-m-d H:i:s',
                    $file->getMTime()
                ),
            ]);

        return view('backups.index', compact('files'));
    }

    public function store(Request $request)
    {
        $backupPath = storage_path('app/backups');

        if (! File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $filename = 'rental_backup_' .
            now()->format('Y-m-d_H-i-s') .
            '.sql';

        $filePath = $backupPath . DIRECTORY_SEPARATOR . $filename;

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');

        $command = [
            '/usr/bin/mysqldump',
            '--host=' . $host,
            '--port=' . $port,
            '--user=' . $username,
            '--password=' . $password,
            '--single-transaction',
            '--routines',
            '--triggers',
            $database,
        ];

        $process = new Process($command);
        $process->setTimeout(300);
        $process->run(function ($type, $buffer) use ($filePath) {
            if ($type === Process::OUT) {
                File::append($filePath, $buffer);
            }
        });

        if (! $process->isSuccessful()) {
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Backup database gagal.',
                'error' => trim($process->getErrorOutput()),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Backup database berhasil dibuat.',
            'filename' => $filename,
        ]);
    }

    public function download(string $filename)
    {
        $filename = basename($filename);

        $path = storage_path(
            'app/backups/' . $filename
        );

        abort_unless(
            File::exists($path),
            404
        );

        return response()->download(
            $path,
            $filename,
            [
                'Content-Type' => 'application/sql',
            ]
        );
    }
}