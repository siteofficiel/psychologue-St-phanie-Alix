<?php
// =====================================================================
//  admin-api.php — API de l'espace administrateur du blog
//  (appelée par blog.html via JavaScript, en AJAX)
//
//  ► POUR CHANGER LE MOT DE PASSE : modifiez la valeur ci-dessous.
// =====================================================================
session_start();
header('Content-Type: application/json; charset=utf-8');

$ADMIN_PASSWORD = 'Zack2026';
$DATA_FILE = __DIR__ . '/articles.json';

function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function read_articles() {
    global $DATA_FILE;
    if (!is_file($DATA_FILE)) { return array(); }
    $raw = @file_get_contents($DATA_FILE);
    if ($raw === false) { return array(); }
    $d = json_decode($raw, true);
    if (!is_array($d)) { return array(); }
    return (isset($d['articles']) && is_array($d['articles'])) ? $d['articles'] : array_values($d);
}

function write_articles(array $articles) {
    global $DATA_FILE;
    $out = array();
    foreach ($articles as $i => $a) {
        if (!is_array($a)) { continue; }
        $out[] = array(
            'id'       => isset($a['id'])       ? intval($a['id'])                    : ($i + 1),
            'category' => isset($a['category']) ? trim((string)$a['category'])        : '',
            'date'     => isset($a['date'])     ? trim((string)$a['date'])            : '',
            'readTime' => isset($a['readTime']) ? trim((string)$a['readTime'])        : '',
            'title'    => isset($a['title'])    ? trim((string)$a['title'])           : '',
            'excerpt'  => isset($a['excerpt'])  ? trim((string)$a['excerpt'])         : '',
            'image'    => isset($a['image'])    ? trim((string)$a['image'])           : '',
            'content'  => isset($a['content'])  ? (string)$a['content']               : '',
        );
    }
    // Tri : le plus récent (id le plus élevé) en premier
    usort($out, function ($a, $b) { return intval($b['id']) - intval($a['id']); });
    $json = json_encode(array('articles' => $out), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    if ($json === false) { return false; }
    return @file_put_contents($DATA_FILE, $json, LOCK_EX) !== false;
}

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

// ---- Connexion ----
if ($action === 'login') {
    $p = isset($_POST['password']) ? (string)$_POST['password'] : '';
    if (hash_equals($ADMIN_PASSWORD, $p)) {
        session_regenerate_id(true);
        $_SESSION['admin_ok'] = true;
        respond(array('ok' => true));
    }
    respond(array('ok' => false, 'error' => 'Mot de passe incorrect.'), 401);
}

// ---- Déconnexion ----
if ($action === 'logout') {
    $_SESSION = array();
    session_destroy();
    respond(array('ok' => true));
}

// ---- Statut de session ----
if ($action === 'status') {
    respond(array('ok' => true, 'logged' => !empty($_SESSION['admin_ok'])));
}

// ---- Actions protégées (ajout / suppression) ----
if (empty($_SESSION['admin_ok'])) {
    respond(array('ok' => false, 'error' => 'auth'), 401);
}

if ($action === 'add') {
    $title = trim((string)(isset($_POST['title']) ? $_POST['title'] : ''));
    if ($title === '') { respond(array('ok' => false, 'error' => 'Le titre est obligatoire.'), 400); }

    $articles   = read_articles();
    $articles[] = array(
        'id'       => time(),
        'category' => trim((string)(isset($_POST['category']) ? $_POST['category'] : '')),
        'date'     => trim((string)(isset($_POST['date']) ? $_POST['date'] : '')),
        'readTime' => trim((string)(isset($_POST['readTime']) ? $_POST['readTime'] : '')),
        'title'    => $title,
        'excerpt'  => trim((string)(isset($_POST['excerpt']) ? $_POST['excerpt'] : '')),
        'image'    => trim((string)(isset($_POST['image']) ? $_POST['image'] : '')),
        'content'  => (string)(isset($_POST['content']) ? $_POST['content'] : ''),
    );
    if (!write_articles($articles)) {
        respond(array('ok' => false, 'error' => 'Impossible d’écrire articles.json (vérifiez les permissions d’écriture).'), 500);
    }
    respond(array('ok' => true));
}

if ($action === 'delete') {
    $id   = intval(isset($_POST['id']) ? $_POST['id'] : 0);
    $all  = read_articles();
    $kept = array();
    foreach ($all as $a) {
        if (intval(isset($a['id']) ? $a['id'] : 0) !== $id) { $kept[] = $a; }
    }
    if (!write_articles($kept)) {
        respond(array('ok' => false, 'error' => 'Impossible d’écrire articles.json (vérifiez les permissions d’écriture).'), 500);
    }
    respond(array('ok' => true));
}

respond(array('ok' => false, 'error' => 'Action inconnue.'), 400);
