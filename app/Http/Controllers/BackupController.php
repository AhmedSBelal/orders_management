<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Export database to JSON
     */
    public function export()
    {
        try {
            $tables = $this->getTablesList();
            $backup = [];
            
            foreach ($tables as $table) {
                $data = DB::table($table)->get()->toArray();
                $backup[$table] = $data;
            }
            
            $fileName = 'backup_' . date('Y-m-d_H-i-s') . '.json';
            
            // Create backups directory if not exists
            $backupDir = storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $filePath = $backupDir . '/' . $fileName;
            
            // Save backup file
            file_put_contents($filePath, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'فشل إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }
    
    /**
     * Import database from JSON
     */
    public function import(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json|max:102400' // 100MB max
        ]);
        
        try {
            DB::beginTransaction();
            
            $file = $request->file('backup_file');
            $content = file_get_contents($file->getRealPath());
            $backup = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('ملف JSON غير صالح');
            }
            
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            foreach ($backup as $table => $data) {
                if (Schema::hasTable($table)) {
                    // Truncate table
                    DB::table($table)->truncate();
                    
                    // Insert data in chunks to avoid memory issues
                    if (!empty($data)) {
                        $chunks = array_chunk($data, 500);
                        foreach ($chunks as $chunk) {
                            $insertData = array_map(function($item) {
                                return (array) $item;
                            }, $chunk);
                            DB::table($table)->insert($insertData);
                        }
                    }
                }
            }
            
            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            DB::commit();
            
            return back()->with('success', 'تم استعادة النسخة الاحتياطية بنجاح');
            
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', 'فشل استعادة النسخة الاحتياطية: ' . $e->getMessage());
        }
    }
    
    /**
     * Export specific tables
     */
    public function exportTables(Request $request)
    {
        $request->validate([
            'tables' => 'required|array',
            'tables.*' => 'string'
        ]);
        
        try {
            $backup = [];
            
            foreach ($request->tables as $table) {
                if (Schema::hasTable($table)) {
                    $data = DB::table($table)->get()->toArray();
                    $backup[$table] = $data;
                }
            }
            
            $fileName = 'backup_selective_' . date('Y-m-d_H-i-s') . '.json';
            
            // Create backups directory if not exists
            $backupDir = storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $filePath = $backupDir . '/' . $fileName;
            
            // Save backup file
            file_put_contents($filePath, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'فشل إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }
    
    /**
     * List all backups
     */
    public function listBackups()
    {
        $backupDir = storage_path('app/backups');
        $backups = [];
        
        if (file_exists($backupDir)) {
            $files = glob($backupDir . '/*.json');
            
            foreach ($files as $file) {
                $backups[] = [
                    'name' => basename($file),
                    'size' => filesize($file),
                    'date' => filemtime($file),
                    'path' => $file
                ];
            }
        }
        
        $tables = $this->getTablesList();
        return view('backup.index', compact('backups', 'tables'));
    }
    
    /**
     * Download existing backup
     */
    public function download($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);
        
        if (file_exists($filePath)) {
            return response()->download($filePath);
        }
        
        return back()->with('error', 'الملف غير موجود');
    }
    
    /**
     * Delete backup
     */
    public function delete($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);
        
        if (file_exists($filePath)) {
            unlink($filePath);
            return back()->with('success', 'تم حذف النسخة الاحتياطية بنجاح');
        }
        
        return back()->with('error', 'الملف غير موجود');
    }
    
    /**
     * Get list of all tables
     */
    private function getTablesList()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = 'Tables_in_' . env('DB_DATABASE');
        
        return array_map(function($table) use ($dbName) {
            return $table->$dbName;
        }, $tables);
    }
    
    /**
     * Show backup page
     */
    public function index()
    {
        $tables = $this->getTablesList();
        return view('backup.index', compact('tables'));
    }
}