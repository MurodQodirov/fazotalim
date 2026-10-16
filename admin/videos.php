<?php
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$videos = load_json('videos.json');
$sections = course_labels();
$sectionOptions = [];
foreach (course_sections() as $s) {
    $sectionOptions[$s['id']] = $s['title'];
}
$sectionOptions['other'] = 'Umumiy';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $url = trim($_POST['url'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $section = $_POST['section'] ?? 'other';
        $yt = youtube_id($url);

        if (!$yt) {
            flash('error', 'To‘g‘ri YouTube havolasini kiriting.');
            redirect(base_url('admin/videos.php'));
        }

        $canonical = 'https://www.youtube.com/watch?v=' . $yt;
        if ($title === '') {
            $title = youtube_fetch_title($canonical);
        }
        if ($title === '') {
            $title = 'YouTube dars';
        }

        $videos[] = [
            'id' => generate_id('video_'),
            'title' => $title,
            'description' => $description,
            'section' => $section,
            'youtube_id' => $yt,
            'url' => $canonical,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        save_json('videos.json', $videos);
        flash('success', 'Video darslik qo‘shildi.');
        redirect(base_url('admin/videos.php'));
    }

    if ($action === 'delete') {
        $id = $_POST['id'] ?? '';
        $videos = array_values(array_filter($videos, fn($v) => ($v['id'] ?? '') !== $id));
        save_json('videos.json', $videos);
        flash('success', 'Video o‘chirildi.');
        redirect(base_url('admin/videos.php'));
    }
}

$page_title = "Video darsliklar";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container admin-layout">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div class="admin-content">
        <div class="admin-actions">
            <h1 style="margin:0;font-size:1.4rem">Video darsliklar</h1>
        </div>

        <div class="table-wrap" style="margin-bottom:1.5rem">
            <table>
                <thead>
                    <tr>
                        <th>Sarlavha</th>
                        <th>Bo‘lim</th>
                        <th>YouTube</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$videos): ?>
                        <tr><td colspan="4">Hali video yo‘q.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($videos as $v): ?>
                        <?php $yt = $v['youtube_id'] ?? youtube_id($v['url'] ?? ''); ?>
                        <tr>
                            <td><?= e($v['title']) ?></td>
                            <td><?= e($sections[$v['section'] ?? ''] ?? ($v['section'] ?? '')) ?></td>
                            <td><code><?= e($yt ?: '') ?></code></td>
                            <td>
                                <a href="<?= base_url('pages/watch.php?id=' . urlencode($v['id'])) ?>">Ko‘rish</a>
                                <form method="post" style="display:inline;margin-left:0.5rem" onsubmit="return confirm('O‘chirasizmi?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= e($v['id']) ?>">
                                    <button class="btn btn-danger btn-sm" type="submit">O‘chirish</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2 style="font-size:1.1rem">YouTube havolasi qo‘shish</h2>
        <form method="post" style="max-width:640px">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>YouTube havolasi</label>
                <input type="url" name="url" required placeholder="https://www.youtube.com/watch?v=...">
            </div>
            <div class="form-group">
                <label>Sarlavha (bo‘sh qoldirilsa, YouTube’dan olinadi)</label>
                <input type="text" name="title">
            </div>
            <div class="form-group">
                <label>Bo‘lim</label>
                <select name="section">
                    <?php foreach ($sectionOptions as $key => $label): ?>
                        <option value="<?= e($key) ?>"><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Qisqa tavsif</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Qo‘shish</button>
        </form>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
