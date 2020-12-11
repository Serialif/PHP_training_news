<?php
function autoload($classname)
{
    require 'class' . DIRECTORY_SEPARATOR . $classname . '.php';
}

spl_autoload_register('autoload');

DBFactory::createTableIfNotExistsWithPDO();

$pdo = DBFactory::getMysqlConnexionWithPDO();


$manager = new NewsManagerPDO($pdo);

$page='index';
include 'component/header.php'
?>

<main class="container">
    <?php if (!isset($_GET['id']) || preg_match('#^[0-9]+$#', $_GET['id']) !== 1) {
        $newss = $manager->getList();
        foreach ($newss as $news) {
            $dates = DateManipulation::getDates($news);
            $content = $news->getContent();
            if (strlen($content) > 200) {
                $content = substr($content, 0, 199) . '...';
            }
            echo '<div class="card m-3 p-3 shadow w-auto">' .
                '<div class="fw-bold"><a href="?id=' . $news->getId() . '">' . $news->getTitle() . '</a></div>' .
                '<div>' . $content . '</div>' .
                '<div class="fst-italic text-muted fs-6 mt-3">
                <span class="float-end">
                <small>créé par <span class="fw-bold">' .
                $news->getAuthor() . '</span> le ' . $dates['dateCreated'] . ' à ' . $dates['timeCreated'] . ', modifié le ' .
                $dates['dateModified'] . ' à ' . $dates['timeModified'] . '</small></span></div></div>';
        }
    } else {
        $news = $manager->getUnique($_GET['id']);
        $dates = DateManipulation::getDates($news);
        echo '<div class="card m-3 p-3 shadow w-auto">' .
            '<div class="fs-4 fw-bold ">' . $news->getTitle() . '</div>' .
            '<div>' . $news->getContent() . '</div>
        <div><span class="float-start mt-3"><a href=".\">Retour à l\'accueil</a></span>
        <span class="float-end fst-italic text-muted fs-6 mt-3">
        <small>créé par <span class="fw-bold">' .
            $news->getAuthor() . '</span> le ' . $dates['dateCreated'] . ' à ' . $dates['timeCreated'] . ', modifié le ' .
            $dates['dateModified'] . ' à ' . $dates['timeModified'] . '</small></span></div></div>';
    }

    include 'component/footer.php' ?>
