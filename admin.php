<?php
function autoload($classname)
{
    require 'class' . DIRECTORY_SEPARATOR . $classname . '.php';
}

spl_autoload_register('autoload');

$page = 'admin';
include 'component/header.php';

$errorConnexion = '';

if (isset($_GET['disconnect'])) {
    session_destroy();
    header('Location: .');
    exit();
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin') {
        $_SESSION['connected'] = 'connected';
    } else {
        $errorConnexion = '<p class="text-danger">Identifiant ou Mot de passe incorrect</p>';
    }
}

if (!isset($_SESSION['connected'])) { ?>
    <main class="container">
        <?= $errorConnexion ?>
        <form action="admin.php" method="post" class="mt-3">
            <div class="mb-3">
                <label for="username" class="form-label">Identifiant</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-success">Se connecter</button>
        </form>
    </main>
<?php } else {

$pdo = DBFactory::getMysqlConnexionWithPDO();

$manager = new NewsManagerPDO($pdo);

if (isset($_GET['delete']) && preg_match('#^[0-9]+$#', $_GET['delete']) === 1) {
    $manager->delete($_GET['delete']);
}

if (isset($_POST['author'])) {
    $news = new NewsEntity([
        'id' => (int)$_POST['id'] ?? null,
        'author' => htmlentities($_POST['author']),
        'title' => htmlentities($_POST['title']),
        'content' => str_replace(PHP_EOL, '<br>', htmlentities($_POST['content'])),
    ]);
    $manager->save($news);
}

?>
<div class="mt-3">
    <form action="" method="">
        <a href="?disconnect=y" class="float-end me-5 btn btn-sm btn-danger">Se déconnecter</a>
    </form>
</div>

<?php if ((isset($_GET['edit']) && preg_match('#^[0-9]+$#', $_GET['edit']) === 1) ||
isset($_GET['new'])) {
$author = '';
$title = '';
$content = '';
if (!isset($_GET['new'])) {
    $news = $manager->getUnique($_GET['edit']);
    $id = $news->getId();
    $author = $news->getAuthor();
    $title = $news->getTitle();
    $content = $news->getContent();
} ?>
<main class="container">
    <form action="admin.php" method="post" class="mt-3">
        <div class="mb-3">
            <label for="author" class="form-label">Auteur</label>
            <input type="text" class="form-control" id="author" name="author" value="<?= $author ?>">
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= $title ?>">
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Contenu</label>
            <textarea class="form-control" id="content" name="content"
                      rows="10"><?= $content ?></textarea>
        </div>
        <input type="hidden" name="id" value="<?= $id ?? null ?>">
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="admin.php" class="btn btn-danger">Annuler</a>
    </form>
    <?php } else { ?>
    <main class="container">
        <div class="d-grid gap-1 py-3">
            <a href="?new=y" class="btn btn-success">Nouvelle News</a>
        </div>
        <?php
        $newss = $manager->getList();
        if (!empty($newss)) {
            foreach ($newss as $news) {
                $dates = DateManipulation::getDates($news);
                $content = $news->getContent();
                if (strlen($content) > 200) {
                    $content = substr($content, 0, 199) . '...';
                }
                echo '<div class="card m-3 p-3 shadow w-auto">' .
                    '<div class="fw-bold mb-3">' . $news->getTitle() . '
                    <span class="float-end">
                        <a href="?edit=' . $news->getId() . '" class="btn btn-sm btn-warning">Modifier</a>
                        <a href="?delete=' . $news->getId() . '" class="btn btn-sm btn-danger delete">Supprimer</a>
                    </span></div><div>' . $content . '</div>' .
                    '<div class="text-end fst-italic text-muted fs-6"> <small>créé par <span class="fw-bold">' .
                    $news->getAuthor() . '</span> le ' . $dates['dateCreated'] . ' à ' . $dates['timeCreated'] . ', modifié le ' .
                    $dates['dateModified'] . ' à ' . $dates['timeModified'] . '</small></div>' .
                    '<input type="hidden" class="title4js" value="' . $news->getTitle() . '"></div>';
            }
        }
        }
        }
        include 'component/footer.php' ?>

