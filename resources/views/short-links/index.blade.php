<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendekin - URL Shortener</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e6effe 100%);
            min-height: 100vh;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 16px;
        }

        .container {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            padding: 36px 32px;
            border: 1px solid #e2e8f0;
        }

        .header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-wordmark {
            display: inline-block;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #2563eb;
            background-color: #eff6ff;
            padding: 3px 10px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .header p {
            font-size: 15px;
            color: #64748b;
        }

        .alert-banner {
            margin-bottom: 24px;
            padding: 14px 18px;
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            border-radius: 10px;
            color: #166534;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 13px 16px;
            font-size: 15px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
            color: #1e293b;
            background-color: #f8fafc;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .form-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
        }

        .error-message {
            margin-top: 6px;
            font-size: 13px;
            color: #ef4444;
            font-weight: 500;
        }

        .btn-submit {
            width: 100%;
            padding: 14px 20px;
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            background-color: #2563eb;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-submit:hover {
            background-color: #1d4ed8;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .alert-success {
            margin-top: 24px;
            padding: 20px;
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            border-radius: 12px;
        }

        .alert-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #166534;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 12px;
        }

        .result-box {
            background: #ffffff;
            border: 1px solid #dcfce7;
            border-radius: 8px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            word-break: break-all;
            margin-bottom: 12px;
        }

        .result-url {
            color: #15803d;
            font-weight: 600;
            font-size: 16px;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .result-url:hover {
            color: #166534;
        }

        .btn-copy {
            background: #16a34a;
            color: #ffffff;
            border: none;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s ease;
        }

        .btn-copy:hover {
            background: #15803d;
        }

        .result-meta {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.6;
        }

        .result-meta span {
            font-weight: 600;
            color: #1f2937;
        }

        .meta-destination {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }

        /* Links List Section */
        .links-section {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 32px;
        }

        .links-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 18px;
        }

        /* Responsive Table Container */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
        }

        .links-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
            min-width: 860px;
        }

        .links-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 14px 16px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            font-size: 13px;
            letter-spacing: 0.3px;
        }

        .links-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #334155;
        }

        .links-table tr:last-child td {
            border-bottom: none;
        }

        .links-table tr:hover td {
            background-color: #f8fafc;
        }

        /* Column Specific Constraints */
        .cell-truncate-url {
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
            color: #334155;
            font-size: 13px;
        }

        .code-badge {
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 4px 8px;
            border-radius: 6px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 600;
            font-size: 13px;
            border: 1px solid #e2e8f0;
            display: inline-block;
            white-space: nowrap;
        }

        .short-url-cell {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .short-url-link {
            color: #2563eb;
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 2px;
            font-size: 13px;
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }

        .short-url-link:hover {
            color: #1d4ed8;
        }

        .btn-copy-sm {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-copy-sm:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        /* Status Badges */
        .status-badge-active {
            background-color: #dcfce7;
            color: #15803d;
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .status-badge-inactive {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .status-badge-expired {
            background-color: #fef3c7;
            color: #b45309;
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .col-expires {
            color: #64748b;
            font-size: 13px;
            white-space: nowrap;
        }

        .col-clicks {
            text-align: center;
        }

        .clicks-count {
            font-weight: 700;
            color: #0f172a;
            font-size: 14px;
        }

        .col-action-group {
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .btn-stats {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-stats:hover {
            background-color: #dbeafe;
            border-color: #93c5fd;
        }

        .btn-deactivate {
            background-color: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-deactivate:hover {
            background-color: #ea580c;
            color: #ffffff;
            border-color: #ea580c;
        }

        .btn-delete {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-delete:hover {
            background-color: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        .empty-state {
            text-align: center;
            padding: 40px 16px;
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 12px;
        }

        .empty-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .empty-title {
            font-size: 16px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 4px;
        }

        .empty-subtitle {
            font-size: 14px;
            color: #64748b;
        }

        /* Modal Styling (Completely outside the table) */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(2px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-card {
            background: #ffffff;
            border-radius: 14px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 24px;
            animation: modalFadeIn 0.2s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.96);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 12px;
        }

        .modal-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }

        .modal-close {
            background: transparent;
            border: none;
            font-size: 24px;
            color: #94a3b8;
            cursor: pointer;
            line-height: 1;
        }

        .modal-close:hover {
            color: #0f172a;
        }

        .modal-meta {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .modal-meta strong {
            color: #0f172a;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .stats-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 10px 14px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }

        .stats-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .empty-stats {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            padding: 24px 16px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .modal-footer {
            text-align: right;
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: #334155;
            border: none;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #cbd5e1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="brand-wordmark">pndk</div>
                <h1>Pendekin</h1>
                <p>Masukkan URL panjang untuk membuat short link instan.</p>
            </div>

            @if (session('success') && ! session('created_link'))
                <div class="alert-banner">
                    <span>✓</span> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('short-links.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="original_url" class="form-label">URL panjang</label>
                    <input
                        type="text"
                        name="original_url"
                        id="original_url"
                        class="form-control @error('original_url') is-invalid @enderror"
                        placeholder="https://contoh.com/halaman"
                        value="{{ old('original_url') }}"
                        required
                        autocomplete="off"
                    >
                    @error('original_url')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="custom_alias" class="form-label">Custom alias (opsional)</label>
                    <input
                        type="text"
                        name="custom_alias"
                        id="custom_alias"
                        class="form-control @error('custom_alias') is-invalid @enderror"
                        placeholder="promo2026"
                        value="{{ old('custom_alias') }}"
                        autocomplete="off"
                    >
                    <div class="form-hint">Kosongkan jika ingin menggunakan kode otomatis.</div>
                    @error('custom_alias')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="expires_at" class="form-label">Kadaluarsa (opsional)</label>
                    <input
                        type="datetime-local"
                        name="expires_at"
                        id="expires_at"
                        class="form-control @error('expires_at') is-invalid @enderror"
                        value="{{ old('expires_at') }}"
                    >
                    <div class="form-hint">Kosongkan jika ingin link aktif tanpa batas waktu kedaluwarsa.</div>
                    @error('expires_at')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Pendekin URL</button>
            </form>

            @if (session('created_link'))
                @php
                    $createdLink = session('created_link');
                    $code = is_array($createdLink) ? $createdLink['code'] : $createdLink->code;
                    $originalUrl = is_array($createdLink) ? $createdLink['original_url'] : $createdLink->original_url;
                    $isActive = is_array($createdLink) ? ($createdLink['is_active'] ?? true) : $createdLink->is_active;
                    $expiresAt = is_array($createdLink) ? ($createdLink['expires_at'] ?? null) : $createdLink->expires_at;
                    $shortUrl = url('/' . $code);
                @endphp
                <div class="alert-success">
                    <div class="alert-title">
                        <span>✓</span> Short link berhasil dibuat!
                    </div>

                    <div class="result-box">
                        <a href="{{ $shortUrl }}" target="_blank" rel="noopener noreferrer" class="result-url" id="shortUrlLink">
                            {{ $shortUrl }}
                        </a>
                        <button type="button" class="btn-copy" onclick="copyLink('{{ $shortUrl }}')">
                            Salin Link
                        </button>
                    </div>

                    <div class="result-meta">
                        <div>Kode / Alias: <span>{{ $code }}</span></div>
                        <div class="meta-destination">Tujuan: <span>{{ $originalUrl }}</span></div>
                        <div>Status: <span>{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span></div>
                        @if ($expiresAt)
                            <div>Kedaluwarsa: <span>{{ $expiresAt }}</span></div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="links-section">
                <h2 class="links-title">Daftar Short Link</h2>

                @if (! isset($shortLinks) || $shortLinks->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">📂</div>
                        <div class="empty-title">Belum ada short link.</div>
                        <div class="empty-subtitle">Silakan buat short link terlebih dahulu.</div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="links-table">
                            <thead>
                                <tr>
                                    <th>URL Asli</th>
                                    <th>Kode</th>
                                    <th>Short URL</th>
                                    <th>Status</th>
                                    <th>Kadaluarsa</th>
                                    <th style="text-align: center;">Total Klik</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shortLinks as $link)
                                    @php
                                        $shortUrl = url('/' . $link->code);
                                        $status = $link->status_label;
                                        $clicksCount = $link->clicks_count ?? $link->clicks()->count();
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="cell-truncate-url" title="{{ $link->original_url }}">
                                                {{ $link->original_url }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="code-badge">{{ $link->code }}</span>
                                        </td>
                                        <td>
                                            <div class="short-url-cell">
                                                <a href="{{ $shortUrl }}" target="_blank" rel="noopener noreferrer" class="short-url-link" title="{{ $shortUrl }}">
                                                    {{ $shortUrl }}
                                                </a>
                                                <button type="button" class="btn-copy-sm" onclick="copyLink('{{ $shortUrl }}')">
                                                    Salin
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($status === 'Aktif')
                                                <span class="status-badge-active">🟢 Aktif</span>
                                            @elseif ($status === 'Expired')
                                                <span class="status-badge-expired">🟠 Expired</span>
                                            @else
                                                <span class="status-badge-inactive">🔴 Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="col-expires">
                                            {{ $link->formatted_expires_at }}
                                        </td>
                                        <td class="col-clicks">
                                            <span class="clicks-count">{{ $clicksCount }}</span>
                                        </td>
                                        <td>
                                            <div class="col-action-group">
                                                <button type="button" class="btn-stats" onclick="openStatsModal({{ $link->id }})">
                                                    Lihat Statistik
                                                </button>

                                                @if ($link->canBeDeactivated())
                                                    <form action="{{ route('short-links.deactivate', $link->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan short link ini?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn-deactivate">Nonaktifkan</button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('short-links.destroy', $link->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus short link ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-delete">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modals rendered completely OUTSIDE the table to prevent foster-parenting HTML parser bugs --}}
    @if (isset($shortLinks) && $shortLinks->isNotEmpty())
        @foreach ($shortLinks as $link)
            @php
                $clicksCount = $link->clicks_count ?? $link->clicks()->count();
                $dailyStats = $link->getDailyStats();
            @endphp
            <div id="stats-modal-{{ $link->id }}" class="modal-overlay" style="display:none;" onclick="if(event.target===this) closeStatsModal({{ $link->id }})">
                <div class="modal-card">
                    <div class="modal-header">
                        <h3>Statistik Klik: {{ $link->code }}</h3>
                        <button type="button" class="modal-close" onclick="closeStatsModal({{ $link->id }})">&times;</button>
                    </div>

                    <div class="modal-meta">
                        <div>Kode: <strong>{{ $link->code }}</strong></div>
                        <div class="cell-truncate-url" style="max-width: 100%;" title="{{ $link->original_url }}">Tujuan: {{ $link->original_url }}</div>
                        <div>Total Klik: <strong>{{ $clicksCount }}</strong></div>
                    </div>

                    @if ($dailyStats->isEmpty())
                        <div class="empty-stats">
                            Belum ada klik.
                        </div>
                    @else
                        <table class="stats-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th style="text-align: right;">Jumlah Klik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dailyStats as $stat)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($stat->click_date)->locale('id')->translatedFormat('d M Y') }}</td>
                                        <td style="text-align: right; font-weight: 700;">{{ $stat->total_clicks }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" onclick="closeStatsModal({{ $link->id }})">Tutup</button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <script>
        function copyLink(url) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(showCopiedFeedback);
            } else {
                const tempInput = document.createElement('input');
                tempInput.value = url;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                showCopiedFeedback();
            }
        }

        function showCopiedFeedback() {
            const btn = document.querySelector('.btn-copy');
            if (btn) {
                const originalText = btn.innerText;
                btn.innerText = 'Tersalin!';
                btn.style.backgroundColor = '#047857';
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.style.backgroundColor = '#16a34a';
                }, 2000);
            }
        }

        function openStatsModal(id) {
            const modal = document.getElementById('stats-modal-' + id);
            if (modal) {
                modal.style.display = 'flex';
            }
        }

        function closeStatsModal(id) {
            const modal = document.getElementById('stats-modal-' + id);
            if (modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
