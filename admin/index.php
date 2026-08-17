<?php
// =====================================================================
//  ESPACE ADMINISTRATEUR — Blog « Stéphanie ALIX »
//  Accès : https://votre-site.fr/admin/
//
//  ► POUR CHANGER LE MOT DE PASSE : modifiez la valeur ci-dessous.
// =====================================================================
$ADMIN_PASSWORD = 'Zack2026';

// Fichier contenant les articles (à la racine du site)
$DATA_FILE = dirname(__DIR__) . '/articles.json';

session_start();

// ---------------------------------------------------------------
//  Fonctions utilitaires
// ---------------------------------------------------------------
function e($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function articles_read() {
    global $DATA_FILE;
    if (!is_file($DATA_FILE)) { return array(); }
    $raw = @file_get_contents($DATA_FILE);
    if ($raw === false) { return array(); }
    $d = json_decode($raw, true);
    if (!is_array($d)) { return array(); }
    return (isset($d['articles']) && is_array($d['articles'])) ? $d['articles'] : array_values($d);
}

function articles_write(array $articles) {
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

// ---------------------------------------------------------------
//  Session / authentification
// ---------------------------------------------------------------
if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(16)); }
$csrf   = $_SESSION['csrf'];
$logged = isset($_SESSION['admin_ok']) && $_SESSION['admin_ok'] === true;

$error = '';
$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : '';
unset($_SESSION['flash']);

// ---------------------------------------------------------------
//  Traitement des actions (formulaires)
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'login') {
        if (hash_equals($ADMIN_PASSWORD, (string)(isset($_POST['password']) ? $_POST['password'] : ''))) {
            session_regenerate_id(true);
            $_SESSION['admin_ok'] = true;
            $_SESSION['csrf']     = bin2hex(random_bytes(16));
            $_SESSION['flash']    = 'Connexion réussie. Bienvenue !';
            header('Location: index.php');
            exit;
        } else {
            $error = 'Mot de passe incorrect.';
        }
    }
    elseif ($logged && hash_equals($csrf, (string)(isset($_POST['csrf']) ? $_POST['csrf'] : ''))) {

        if ($action === 'add') {
            $title = trim((string)(isset($_POST['title']) ? $_POST['title'] : ''));
            if ($title === '') {
                $error = 'Le titre est obligatoire.';
            } else {
                $articles   = articles_read();
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
                if (articles_write($articles)) {
                    $_SESSION['flash'] = 'Article ajouté : « ' . $title . ' ».';
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Impossible d’écrire le fichier articles.json. Vérifiez les permissions d’écriture du dossier.';
                }
            }
        }
        elseif ($action === 'delete') {
            $id   = intval(isset($_POST['id']) ? $_POST['id'] : 0);
            $all  = articles_read();
            $kept = array();
            foreach ($all as $a) {
                if (intval(isset($a['id']) ? $a['id'] : 0) !== $id) { $kept[] = $a; }
            }
            if (articles_write($kept)) {
                $_SESSION['flash'] = 'Article supprimé.';
                header('Location: index.php');
                exit;
            } else {
                $error = 'Impossible d’écrire le fichier articles.json.';
            }
        }
    }
}

// Déconnexion
if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    header('Location: index.php');
    exit;
}

$articles = articles_read();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Administration du blog • Stéphanie ALIX</title>
<style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background: #f6f2fc; color: #3a2e55; }
    a { color: inherit; text-decoration: none; }
    .wrap { max-width: 1120px; margin: 0 auto; padding: 26px 20px 70px; }
    .topbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 26px; flex-wrap: wrap; }
    .brand { font-weight: 700; font-size: 1.15rem; }
    .brand small { display: block; font-weight: 400; color: #8a73c4; font-size: .8rem; }
    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 999px; border: 1px solid transparent; cursor: pointer; font-size: .9rem; font-weight: 600; font-family: inherit; }
    .btn-primary { background: #6b4fa0; color: #fff; }
    .btn-primary:hover { background: #4c2f7a; }
    .btn-ghost { background: #efeafa; color: #4c2f7a; }
    .btn-ghost:hover { background: #e7ddf6; }
    .btn-danger { background: #fdecec; color: #b42318; }
    .btn-danger:hover { background: #f9d8d8; }
    .btn-tiny { padding: 6px 10px; font-size: .75rem; }
    .card { background: #fff; border: 1px solid #ddd0f0; border-radius: 20px; padding: 22px; }
    .banner { padding: 12px 16px; border-radius: 14px; margin-bottom: 18px; font-size: .92rem; }
    .banner-error { background: #fdecec; color: #b42318; border: 1px solid #f5c6c6; }
    .banner-ok { background: #ecfdf3; color: #067647; border: 1px solid #abefc6; }
    h1 { font-size: 1.4rem; margin: 0 0 6px; }
    .muted { color: #8a73c4; margin: 0; }
    .layout { display: grid; grid-template-columns: 5fr 7fr; gap: 20px; align-items: start; }
    @media (max-width: 900px) { .layout { grid-template-columns: 1fr; } }
    label { display: block; font-size: .85rem; font-weight: 600; margin: 14px 0 5px; }
    input[type=text], input[type=password], input[type=url], textarea {
        width: 100%; padding: 10px 12px; border: 1px solid #ddd0f0; border-radius: 12px;
        font-size: .95rem; font-family: inherit; color: #3a2e55; background: #fff;
    }
    textarea { min-height: 150px; resize: vertical; }
    input:focus, textarea:focus { outline: 2px solid #c9b8ee; border-color: #6b4fa0; }
    .hint { font-size: .78rem; color: #8a73c4; margin-top: 5px; }
    .field-row { display: flex; gap: 10px; }
    .field-row > div { flex: 1; }
    .article-row { display: flex; justify-content: space-between; gap: 12px; padding: 14px 0; border-bottom: 1px solid #f0eafa; align-items: flex-start; }
    .article-row:last-child { border-bottom: none; }
    .article-row h3 { margin: 0 0 4px; font-size: 1rem; }
    .article-row .meta { font-size: .8rem; color: #8a73c4; }
    .article-row .excerpt { font-size: .85rem; color: #5b5170; margin: 5px 0 0; }
    .login-card { max-width: 420px; margin: 10vh auto; }
</style>
</head>
<body>

<?php if (!$logged): ?>

    <div class="login-card card">
        <div class="brand">🔐 Administration du blog<small>Stéphanie ALIX — Psychologue clinicienne</small></div>
        <h1 style="margin-top:16px;">Connexion</h1>
        <p class="muted">Espace réservé à l’administrateur du site.</p>
        <?php if ($error): ?><div class="banner banner-error"><?= e($error) ?></div><?php endif; ?>
        <form method="post" action="index.php">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" autofocus required>
            <div style="margin-top:20px;">
                <button class="btn btn-primary" type="submit" name="action" value="login">Se connecter</button>
            </div>
        </form>
    </div>

<?php else: ?>

    <div class="wrap">
        <div class="topbar">
            <div class="brand">🔐 Administration du blog<small>Stéphanie ALIX — Psychologue clinicienne</small></div>
            <div style="display:flex; gap:8px;">
                <a class="btn btn-ghost" href="../blog.html" target="_blank" rel="noopener">Voir le blog ↗</a>
                <a class="btn btn-ghost" href="index.php?logout=1">Déconnexion</a>
            </div>
        </div>

        <?php if ($error): ?><div class="banner banner-error"><?= e($error) ?></div><?php endif; ?>
        <?php if ($flash): ?><div class="banner banner-ok"><?= e($flash) ?></div><?php endif; ?>

        <div class="layout">
            <div class="card">
                <h1>➕ Nouvel article</h1>
                <p class="muted">Remplissez le formulaire puis cliquez sur « Ajouter l’article ».</p>
                <form method="post" action="index.php">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

                    <label for="f_title">Titre *</label>
                    <input type="text" id="f_title" name="title" required placeholder="« Un titre d’article »">

                    <div class="field-row">
                        <div>
                            <label for="f_category">Catégorie</label>
                            <input type="text" id="f_category" name="category" placeholder="Anxiété, Deuil, Enfance…">
                        </div>
                        <div>
                            <label for="f_date">Date</label>
                            <input type="text" id="f_date" name="date" placeholder="12 janvier 2026">
                            <button class="btn btn-ghost btn-tiny" type="button"
                                onclick="document.getElementById('f_date').value = new Date().toLocaleDateString('fr-FR', {day:'numeric', month:'long', year:'numeric'});"
                                style="margin-top:6px;">Aujourd’hui</button>
                        </div>
                    </div>

                    <div class="field-row">
                        <div>
                            <label for="f_readtime">Temps de lecture</label>
                            <input type="text" id="f_readtime" name="readTime" placeholder="7 min">
                        </div>
                        <div>
                            <label for="f_image">Image (URL, optionnel)</label>
                            <input type="url" id="f_image" name="image" placeholder="https://…">
                        </div>
                    </div>

                    <label for="f_excerpt">Résumé court (affiché sur la carte)</label>
                    <input type="text" id="f_excerpt" name="excerpt" placeholder="Une phrase d’accroche…">

                    <label for="f_content">Contenu de l’article</label>
                    <textarea id="f_content" name="content" placeholder="<p>Premier paragraphe…</p><p class=&quot;mt-4&quot;>Deuxième paragraphe…</p>"></textarea>
                    <p class="hint">Le contenu accepte le HTML : utilisez &lt;p&gt;…&lt;/p&gt; pour chaque paragraphe. Les images (lien « https://… ») sont facultatives.</p>

                    <div style="margin-top:20px;">
                        <button class="btn btn-primary" type="submit" name="action" value="add">Ajouter l’article</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h1>📚 Articles publiés <span class="muted">(<?= count($articles) ?>)</span></h1>
                <p class="muted">Supprimez un article pour le retirer du blog.</p>
                <?php if (empty($articles)): ?>
                    <p class="muted" style="margin-top:18px;">Aucun article pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($articles as $a): ?>
                    <div class="article-row">
                        <div>
                            <h3><?= e(isset($a['title']) ? $a['title'] : '') ?></h3>
                            <div class="meta">
                                <?= e(isset($a['category']) ? $a['category'] : '') ?>
                                <?php if (!empty($a['date'])): ?> · <?= e($a['date']) ?><?php endif; ?>
                                <?php if (!empty($a['readTime'])): ?> · <?= e($a['readTime']) ?><?php endif; ?>
                            </div>
                            <p class="excerpt"><?= e(isset($a['excerpt']) ? $a['excerpt'] : '') ?></p>
                        </div>
                        <form method="post" action="index.php" onsubmit="return confirm('Supprimer définitivement cet article ?');">
                            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                            <input type="hidden" name="id" value="<?= (int)(isset($a['id']) ? $a['id'] : 0) ?>">
                            <button class="btn btn-danger btn-tiny" type="submit" name="action" value="delete">Supprimer</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php endif; ?>

</body>
</html>
