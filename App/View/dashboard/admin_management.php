<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>

<link rel="stylesheet" href="css/input.css">

<div class="card">
    <div class="card-header">
        <h2>Manajemen Admin</h2>
        <p style="color: var(--text-secondary); margin-top: 4px;">Kelola akun admin dan super admin sistem</p>
    </div>

    <!-- Messages -->
    <?php if (!empty($message)): ?>
        <div class="alert-<?= htmlspecialchars($messageType) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; align-items: top;">
        <!-- Create Form -->
        <div style="margin-bottom: 32px; padding: 20px; background-color: var(--light-bg); border-radius: 12px;">
            <h3 style="margin-top: 0;">Tambah Admin Baru</h3>
            <form method="post" action="">
                <input type="hidden" name="action" value="create">
                
                <div style="display: flex; align-items:end; gap: 10px">
                    <div>
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-input" placeholder="Masukkan username" required>
                    </div>
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Masukkan password" required>
                    </div>
                    <div>   
                        <label class="form-label">Role</label>
                        <select name="role" class="form-input">
                            <option value="Admin">Admin</option>
                            <option value="sAdmin">Super Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="white-space: nowrap; height: fit-content; margin: 5px">Tambah Admin</button>
                </div>
            </form>
        </div>

        <!-- Admin List Table -->
        <div style="margin-left: 30px; overflow-x: auto;">
            <table class="table-d">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th style="width: 200px; text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($admins)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px;">Tidak ada data admin</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($admins as $admin): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($admin['username']) ?></strong></td>
                                <td>
                                    <span style="padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; 
                                        background-color: <?= $admin['role'] === 'sAdmin' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(59, 130, 246, 0.1)' ?>;
                                        color: <?= $admin['role'] === 'sAdmin' ? '#991b1b' : '#1e40af' ?>;">
                                        <?= htmlspecialchars($admin['role']) ?>
                                    </span>
                                </td>
                                <td style="display: flex; gap: 8px; width: 100%;">
                                    <button type="button" class="edit-btn" style="width: 50%"
                                        onclick="openEditModal(<?= htmlspecialchars(json_encode($admin)) ?>)">
                                        ✏️ Edit
                                    </button>
                                    <form method="post" style="all: revert; max-height: 40px; width: 50%; " onsubmit="return confirm('Yakin ingin menghapus admin ini?');">
                                        <input sty type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                                        <button type="submit" class="delete-btn">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 32px; border-radius: 12px; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto;">
        <h2 style="margin-top: 0;">Edit Admin</h2>
        <form method="post" action="">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="editId">
            
            <div style="margin-bottom: 5px; width: 100%;">
                <label class="form-label">Username</label>
                <input type="text" name="username" id="editUsername" class="form-input" required>
            </div>
            
            <div style="width: 100%;">
                <label class="form-label">Password <span style="font-weight: lighter; font-size: smaller;">(Kosongkan jika tidak ingin mengubah)</span></label>
                <input class="inpt" type="password" name="password" id="editPassword" class="form-input" placeholder="Password baru (opsional)">
            </div>
            
            <div style="margin-bottom: 5px; width: 100%;">
                <label class="form-label">Role</label>
                <select class="inpt" name="role" id="editRole" class="form-input">
                    <option value="Admin">Admin</option>
                    <option value="sAdmin">Super Admin</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 12px; width: 100%;">
                <button type="submit" class="btn btn-primary" style="flex: 2;">Simpan Perubahan</button>
                <button type="button" class="btn delete-btn" style="flex: 1;" onclick="closeEditModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(admin) {
    document.getElementById('editId').value = admin.id;
    document.getElementById('editUsername').value = admin.username;
    document.getElementById('editRole').value = admin.role;
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
