<?php
/**
 * FazoTa'lim — umumiy yordamchi funksiyalar
 */

define('ROOT_PATH', dirname(__DIR__));
define('DATA_PATH', ROOT_PATH . '/data');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('SITE_AUTHOR', 'Gulbahor Xojibayeva');
define('SITE_SUPERVISOR', 'PhD, dotsent Tashimov Nurlan Erpolatovich');

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

function section_glyph(string $id): string
{
    $icons = [
        'tasvirlash' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"><path d="M7 26 L20 20 L33 26 L20 32 Z"/><path d="M7 26 V14 L20 8 V20"/><path d="M33 26 V14 L20 8"/></svg>',
        'nuqta' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.4"><circle cx="20" cy="20" r="2.6" fill="currentColor" stroke="none"/><path d="M6 20h28M20 6v28" stroke-dasharray="2 2.2"/><path d="M10 12l20 16"/></svg>',
        'chiziq' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M8 30 L32 10"/><circle cx="8" cy="30" r="2" fill="currentColor" stroke="none"/><circle cx="32" cy="10" r="2" fill="currentColor" stroke="none"/><path d="M8 30 L8 18 L32 10" opacity=".45"/></svg>',
        'tekislik' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"><path d="M8 28 L18 10 L34 18 L24 32 Z" fill="currentColor" fill-opacity=".12"/><path d="M8 28 L18 10 L34 18 L24 32 Z"/></svg>',
        'epyur' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 22 H34"/><path d="M10 22 V10 H22"/><path d="M10 22 V34 H22"/><circle cx="22" cy="10" r="1.6" fill="currentColor" stroke="none"/><circle cx="22" cy="34" r="1.6" fill="currentColor" stroke="none"/><path d="M22 10v12" stroke-dasharray="1.6 2"/></svg>',
        'egri' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M8 28 C12 8, 18 32, 22 16 S30 8, 33 22"/><circle cx="8" cy="28" r="1.6" fill="currentColor" stroke="none"/></svg>',
        'sirtlar' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"><path d="M10 16 L20 10 L30 16 V28 L20 34 L10 28 Z"/><path d="M10 16 L20 22 L30 16M20 22 V34"/></svg>',
        'videodarslar' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="7" y="11" width="26" height="18" rx="2"/><path d="M17 16 L26 20 L17 24 Z" fill="currentColor" stroke="none"/></svg>',
        'testlar' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 7 L33 20 L20 33 L7 20 Z"/><path d="M14 20 l4 4 8-9"/></svg>',
        'animatsiyalar' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M20 8 a12 12 0 1 1-8.5 3.5"/><path d="M20 8 l-3 6 7-1"/></svg>',
        'prezentatsiyalar' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="8" y="10" width="20" height="14"/><rect x="12" y="14" width="20" height="14"/><path d="M20 28 v4 M16 32h8"/></svg>',
        'maruza' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10 30 V10 h9 v20"/><path d="M19 10 h11 v20 H19"/><path d="M23 16h5M23 20h5"/></svg>',
        'metrik' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M8 28 L28 10"/><path d="M10 22 L8 28 L14 26"/><path d="M22 12 L28 10 L26 16"/><path d="M12 12 h16" stroke-dasharray="2 2"/></svg>',
        'loyihalash' => '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"><path d="M8 26 V16 L20 8 L32 16 V26 H8Z"/><rect x="17" y="20" width="6" height="6"/><path d="M8 26 H32"/></svg>',
    ];
    return $icons[$id] ?? $icons['sirtlar'];
}
