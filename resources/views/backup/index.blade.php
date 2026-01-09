@extends('layout.app-dashboard')

@section('title', 'النسخ الاحتياطي واستعادة البيانات')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                    <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                    <span class="alert-text"><strong>نجاح!</strong> {{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                    <span class="alert-icon"><i class="ni ni-fat-remove"></i></span>
                    <span class="alert-text"><strong>خطأ!</strong> {{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Export Section -->
            <div class="card mb-4">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">
                            <i class="fas fa-download text-primary"></i> تصدير النسخة الاحتياطية
                        </h6>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <!-- Export All -->
                        <div class="col-md-6 mb-md-0 mb-4">
                            <div class="card card-body border h-100">
                                <h6 class="mb-3">
                                    <i class="fas fa-database text-info"></i> تصدير كامل قاعدة البيانات
                                </h6>
                                <p class="text-sm text-muted mb-3">تصدير جميع الجداول والبيانات بصيغة JSON</p>
                                <form action="{{ route('backup.export') }}" method="POST" class="mt-auto">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm w-100 mb-0">
                                        <i class="fas fa-file-export"></i> تصدير الكل
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Export Selective -->
                        <div class="col-md-6">
                            <div class="card card-body border h-100">
                                <h6 class="mb-3">
                                    <i class="fas fa-tasks text-success"></i> تصدير جداول محددة
                                </h6>
                                <form action="{{ route('backup.export.tables') }}" method="POST">
                                    @csrf
                                    <div class="mb-3" style="max-height: 200px; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 0.5rem; padding: 0.5rem;">
                                        @foreach($tables as $table)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="tables[]" value="{{ $table }}" 
                                                       id="table_{{ $table }}">
                                                <label class="custom-control-label text-sm" for="table_{{ $table }}">
                                                    {{ $table }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm w-100 mb-0">
                                        <i class="fas fa-check-circle"></i> تصدير المحدد
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Import Section -->
            <div class="card mb-4">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">
                            <i class="fas fa-upload text-warning"></i> استيراد النسخة الاحتياطية
                        </h6>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="alert alert-warning text-white" role="alert">
                        <i class="fas fa-exclamation-triangle ms-2"></i>
                        <strong>تحذير:</strong> سيتم حذف جميع البيانات الحالية واستبدالها بالبيانات من الملف المستورد.
                    </div>
                    
                    <form action="{{ route('backup.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="backup_file" class="form-label text-sm">اختر ملف النسخة الاحتياطية (JSON)</label>
                                    <input type="file" 
                                           class="form-control form-control-sm @error('backup_file') is-invalid @enderror" 
                                           id="backup_file" 
                                           name="backup_file" 
                                           accept=".json" 
                                           required>
                                    @error('backup_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm d-block">&nbsp;</label>
                                <button type="submit" 
                                        class="btn btn-warning btn-sm w-100 mb-0" 
                                        onclick="return confirm('هل أنت متأكد من استيراد هذه النسخة؟ سيتم حذف جميع البيانات الحالية!')">
                                    <i class="fas fa-file-import"></i> استيراد النسخة
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Backups List -->
            @if(isset($backups) && count($backups) > 0)
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">
                            <i class="fas fa-history text-secondary"></i> النسخ الاحتياطية المحفوظة على السيرفر
                        </h6>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        اسم الملف
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        الحجم
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        التاريخ
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        الإجراءات
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $backup)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">
                                                    <i class="fas fa-file-code text-primary ms-2"></i>
                                                    {{ $backup['name'] }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ number_format($backup['size'] / 1024, 2) }} KB
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ date('Y-m-d H:i:s', $backup['date']) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('backup.download', $backup['name']) }}" 
                                           class="btn btn-link text-primary px-3 mb-0">
                                            <i class="fas fa-download text-primary ms-2"></i> تحميل
                                        </a>
                                        <form action="{{ route('backup.delete', $backup['name']) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-link text-danger px-3 mb-0" 
                                                    onclick="return confirm('هل أنت متأكد من حذف هذه النسخة؟')">
                                                <i class="fas fa-trash text-danger ms-2"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('js')
<script>
    function openDownloadSettings() {
        const userAgent = navigator.userAgent.toLowerCase();
        let settingsUrl = 'chrome://settings/downloads';
        
        if (userAgent.includes('firefox')) {
            settingsUrl = 'about:preferences#general';
        } else if (userAgent.includes('safari')) {
            alert('لتغيير مسار التحميل في Safari:\n1. افتح Safari > إعدادات\n2. انتقل إلى "عام"\n3. اختر "موقع تنزيل الملفات"');
            return;
        }
        
        alert('سيتم فتح صفحة الإعدادات في نافذة جديدة.\n\nإذا لم تفتح تلقائياً، انسخ هذا الرابط:\n' + settingsUrl);
        
        try {
            window.open(settingsUrl, '_blank');
        } catch (e) {
            console.log('لم يتمكن من فتح الإعدادات تلقائياً');
        }
    }
</script>
@endpush

<style>
    .form-check {
        padding: 8px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }
    
    .form-check:hover {
        background-color: #f8f9fa;
    }
    
    .form-check:last-child {
        border-bottom: none;
    }
</style>
@endsection