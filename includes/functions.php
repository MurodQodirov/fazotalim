<?php
/**
 * FazoTa'lim — umumiy yordamchi funksiyalar
 */

define('ROOT_PATH', dirname(__DIR__));
define('DATA_PATH', ROOT_PATH . '/data');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('SITE_AUTHOR', 'Gulbahor Xojibayeva');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function load_json(string $file): array
{
    $path = DATA_PATH . '/' . $file;
    if (!file_exists($path)) {
        return [];
    }
    $content = file_get_contents($path);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

function save_json(string $file, array $data): bool
{
    $path = DATA_PATH . '/' . $file;
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($path, $json) !== false;
}

function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function base_url(string $path = ''): string
{
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir = dirname($script);

    // admin/ yoki pages/ ichidan chaqirilganda ildizga chiqish
    if (str_contains($dir, '/admin') || str_contains($dir, '/pages') || str_contains($dir, '/api')) {
        $dir = dirname($dir);
    }

    $dir = rtrim(str_replace('\\', '/', $dir), '/');
    if ($dir === '/' || $dir === '.') {
        $dir = '';
    }

    return $dir . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    $users = load_json('users.json');
    foreach ($users as $user) {
        if ($user['id'] === $_SESSION['user_id']) {
            return $user;
        }
    }
    return null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function is_admin(): bool
{
    $user = current_user();
    return $user && ($user['role'] ?? '') === 'admin';
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect(base_url('pages/login.php'));
    }
}

function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        redirect(base_url('index.php'));
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function generate_id(string $prefix = ''): string
{
    return $prefix . bin2hex(random_bytes(8));
}

function find_by_id(array $items, string $id): ?array
{
    foreach ($items as $item) {
        if (($item['id'] ?? '') === $id) {
            return $item;
        }
    }
    return null;
}

function format_date(string $date): string
{
    $ts = strtotime($date);
    return $ts ? date('d.m.Y H:i', $ts) : $date;
}

function percent(int $correct, int $total): float
{
    if ($total <= 0) {
        return 0;
    }
    return round(($correct / $total) * 100, 1);
}

function youtube_id(?string $url): ?string
{
    if (!$url) {
        return null;
    }
    if (preg_match(
        '~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/|v/)|youtu\.be/)([A-Za-z0-9_-]{11})~',
        $url,
        $m
    )) {
        return $m[1];
    }
    return null;
}

function youtube_embed(string $id): string
{
    return 'https://www.youtube.com/embed/' . $id;
}

function youtube_thumb(string $id): string
{
    return 'https://img.youtube.com/vi/' . $id . '/hqdefault.jpg';
}

function youtube_fetch_title(string $url): string
{
    $endpoint = 'https://www.youtube.com/oembed?url=' . urlencode($url) . '&format=json';
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 6,
            'header' => "User-Agent: FazoTalim\n",
        ],
        'ssl' => [
            'verify_peer' => true,
            'verify_peer_name' => true,
        ],
    ]);
    $raw = @file_get_contents($endpoint, false, $ctx);
    if ($raw === false) {
        return '';
    }
    $data = json_decode($raw, true);
    return is_array($data) ? trim((string)($data['title'] ?? '')) : '';
}

function video_src(array $video): string
{
    $yt = youtube_id($video['url'] ?? '');
    if ($yt) {
        return youtube_embed($yt);
    }
    return $video['url'] ?? '';
}

function ensure_dir(string $path): void
{
    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

function course_sections(): array
{
    return [
        ['id' => 'tasvirlash', 'n' => '01', 'title' => 'Tasvirlash usullari', 'file' => 'pages/course.php?id=tasvirlash', 'lead' => 'Ortogonal, aksonometrik va perspektiv tasvirlash.'],
        ['id' => 'nuqta', 'n' => '02', 'title' => 'Nuqtaning ortogonal proyeksiyasi', 'file' => 'pages/course.php?id=nuqta', 'lead' => 'Nuqtaning H, V va W tekisliklaridagi tasviri.'],
        ['id' => 'chiziq', 'n' => '03', 'title' => 'To‘g‘ri chiziqning ortogonal proyeksiyasi', 'file' => 'pages/course.php?id=chiziq', 'lead' => 'Umumiy va xususiy holatdagi to‘g‘ri chiziq.'],
        ['id' => 'tekislik', 'n' => '04', 'title' => 'Tekislikning ortogonal proyeksiyasi', 'file' => 'pages/course.php?id=tekislik', 'lead' => 'Tekislik izlari, xususiy holatlar va nuqta tegishliligi.'],
        ['id' => 'epyur', 'n' => '05', 'title' => 'Epyurning qayta tuzilish usullari', 'file' => 'pages/course.php?id=epyur', 'lead' => 'Tekislik almashtirish, aylantirish va yoyish.'],
        ['id' => 'egri', 'n' => '06', 'title' => 'Egri chiziqlar', 'file' => 'pages/course.php?id=egri', 'lead' => 'Tekis va fazoviy egri chiziqlar, vint chizig‘i.'],
        ['id' => 'sirtlar', 'n' => '07', 'title' => 'Sirtlar', 'file' => 'pages/solids.php', 'lead' => 'Ko‘pyoqliklar va aylanma sirtlarning 3D modellari.'],
        ['id' => 'videodarslar', 'n' => '08', 'title' => 'Videodarslar', 'file' => 'pages/videos.php', 'lead' => 'Bo‘limlar bo‘yicha video materiallar.'],
        ['id' => 'testlar', 'n' => '09', 'title' => 'Testlar', 'file' => 'pages/tests.php', 'lead' => 'Diagnostika va mavzuli testlar.'],
        ['id' => 'animatsiyalar', 'n' => '10', 'title' => 'Animatsiyalar', 'file' => 'pages/animatsiyalar.php', 'lead' => '3D laboratoriya va harakatdagi proyeksiyalar.'],
        ['id' => 'prezentatsiyalar', 'n' => '11', 'title' => 'Prezentatsiyalar', 'file' => 'pages/prezentatsiyalar.php', 'lead' => 'Mavzular bo‘yicha slayd materiallari.'],
        ['id' => 'maruza', 'n' => '12', 'title' => 'Ma’ruza matnlari', 'file' => 'pages/maruza.php', 'lead' => 'Nazariy matnlar, formulalar va misollar.'],
        ['id' => 'metrik', 'n' => '13', 'title' => 'Metrik va pozitsion masalalar', 'file' => 'pages/practice.php', 'lead' => 'Masofa, haqiqiy uzunlik, tegishlilik va kesishish.'],
        ['id' => 'loyihalash', 'n' => '14', 'title' => 'Arxitekturada loyihalash', 'file' => 'pages/architecture.php', 'lead' => 'Reja, fasad va yon ko‘rinish.'],
    ];
}

function course_section(string $id): ?array
{
    foreach (course_sections() as $section) {
        if ($section['id'] === $id) {
            return $section;
        }
    }
    return null;
}

function course_labels(): array
{
    $labels = [];
    foreach (course_sections() as $section) {
        $labels[$section['id']] = $section['title'];
    }
    $labels['shapes'] = $labels['sirtlar'];
    $labels['projections'] = $labels['tasvirlash'];
    $labels['practice'] = $labels['metrik'];
    $labels['design'] = $labels['loyihalash'];
    $labels['other'] = 'Umumiy';
    return $labels;
}

function current_section_id(): string
{
    $page = basename($_SERVER['PHP_SELF'] ?? '');
    $map = [
        'course.php' => $_GET['id'] ?? '',
        'projections.php' => 'tasvirlash',
        'solids.php' => 'sirtlar',
        'lab.php' => 'animatsiyalar',
        'videos.php' => 'videodarslar',
        'watch.php' => 'videodarslar',
        'tests.php' => 'testlar',
        'take-test.php' => 'testlar',
        'test-result.php' => 'testlar',
        'results.php' => 'testlar',
        'animatsiyalar.php' => 'animatsiyalar',
        'prezentatsiyalar.php' => 'prezentatsiyalar',
        'maruza.php' => 'maruza',
        'theory.php' => 'maruza',
        'practice.php' => 'metrik',
        'architecture.php' => 'loyihalash',
    ];
    return $map[$page] ?? '';
}
