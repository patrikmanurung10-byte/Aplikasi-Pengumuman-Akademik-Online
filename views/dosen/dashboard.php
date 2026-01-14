<?php
/**
 * Dashboard Dosen View
 * Halaman dashboard khusus untuk dosen
 */

$page_title = $page_title ?? 'Dashboard Dosen';
$user = $user ?? [];
$stats = $stats ?? [];
$active_menu = $active_menu ?? 'dashboard';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt"></i> Dashboard Dosen
        </h1>
        <div class="text-muted">
            <i class="fas fa-clock"></i> <?= date('d F Y, H:i') ?>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-left-primary shadow mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Selamat Datang
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= htmlspecialchars($user['full_name'] ?? $user['username'] ?? 'Dosen') ?>
                            </div>
                            <div class="text-muted">
                                <i class="fas fa-user-tie"></i> Dosen
                                <?php if (isset($stats['user_info']['program_studi']) && !empty($stats['user_info']['program_studi'])): ?>
                                    | <?= htmlspecialchars($stats['user_info']['program_studi']) ?>
                                <?php endif; ?>
                            </div>
                            <?php if (isset($stats['academic_status'])): ?>
                                <div class="mt-2">
                                    <span class="badge badge-<?= $stats['academic_status'] === 'Aktif' ? 'success' : 'warning' ?>">
                                        Status: <?= htmlspecialchars($stats['academic_status']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Pengumuman Saya -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pengumuman Saya
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['my_total_announcements'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Aksi Cepat
                            </div>
                            <div class="mt-2">
                                <a href="/dosen/announcements/create" class="btn btn-info btn-sm">
                                    <i class="fas fa-plus"></i> Buat Pengumuman
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plus-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Status -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Profil
                            </div>
                            <div class="mt-2">
                                <a href="/dosen/profile" class="btn btn-warning btn-sm">
                                    <i class="fas fa-user-edit"></i> Edit Profil
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-cog fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Pengumuman -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Kelola Pengumuman
                            </div>
                            <div class="mt-2">
                                <a href="/dosen/announcements" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-list"></i> Lihat Semua
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Announcements -->
    <?php if (isset($stats['recent_announcements']) && !empty($stats['recent_announcements'])): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-newspaper"></i> Pengumuman Terbaru
                    </h6>
                    <a href="/dosen/announcements" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Prioritas</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($stats['recent_announcements'], 0, 5) as $announcement): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($announcement['title']) ?></strong>
                                        <?php if (strlen($announcement['content']) > 100): ?>
                                            <br><small class="text-muted">
                                                <?= htmlspecialchars(substr(strip_tags($announcement['content']), 0, 100)) ?>...
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($announcement['author_name'] ?? 'Unknown') ?></td>
                                    <td>
                                        <?php
                                        $priority_class = '';
                                        $priority_text = '';
                                        switch($announcement['priority']) {
                                            case 'high':
                                                $priority_class = 'badge-danger';
                                                $priority_text = 'Tinggi';
                                                break;
                                            case 'medium':
                                                $priority_class = 'badge-warning';
                                                $priority_text = 'Sedang';
                                                break;
                                            case 'low':
                                                $priority_class = 'badge-info';
                                                $priority_text = 'Rendah';
                                                break;
                                            default:
                                                $priority_class = 'badge-secondary';
                                                $priority_text = 'Normal';
                                        }
                                        ?>
                                        <span class="badge <?= $priority_class ?>"><?= $priority_text ?></span>
                                    </td>
                                    <td>
                                        <small>
                                            <?= date('d/m/Y H:i', strtotime($announcement['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($announcement['is_active']): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Quick Links -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-link"></i> Menu Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="/dosen/announcements/create" class="btn btn-success btn-block">
                                <i class="fas fa-plus-circle"></i><br>
                                Buat Pengumuman Baru
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/dosen/announcements" class="btn btn-info btn-block">
                                <i class="fas fa-list"></i><br>
                                Kelola Pengumuman
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/dosen/profile" class="btn btn-warning btn-block">
                                <i class="fas fa-user-edit"></i><br>
                                Edit Profil
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/help" class="btn btn-secondary btn-block">
                                <i class="fas fa-question-circle"></i><br>
                                Bantuan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-block {
    padding: 15px;
    text-align: center;
}

.btn-block i {
    font-size: 1.5em;
    margin-bottom: 5px;
}
</style>