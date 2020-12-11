<?php


class NewsManagerPDO extends NewsManager
{
    /**
     * Attribut contenant l'instance représentant la Base de Données.
     * @type PDO
     */
    protected PDO $pdo;

    /**
     * Constructeur étant chargé d'enregistrer l'instance de PDO dans l'attribut $pdo.
     * @param $pdo PDO Le DAO (Objet d'accès aux données / Data access object)
     * @return void
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function add(NewsEntity $news)
    {
        $query = $this->pdo->prepare('INSERT INTO news (author, title, content, createdAt, modifiedAt) 
            VALUES(:author, :title, :content, NOW(), NOW())');
        $query->bindValue(':author', $news->getAuthor(), PDO::PARAM_STR);
        $query->bindValue(':title', $news->getTitle(), PDO::PARAM_STR);
        $query->bindValue(':content', $news->getContent(), PDO::PARAM_STR);

        $query->execute();
    }

    protected function update(NewsEntity $news)
    {
        $query = $this->pdo->prepare(
            'UPDATE news 
            SET author=:author, title=:title, content=:content, modifiedAt=NOW() 
            WHERE id=:id');
        $query->bindValue(':id', $news->getId(), PDO::PARAM_INT);
        $query->bindValue(':author', $news->getAuthor(), PDO::PARAM_STR);
        $query->bindValue(':title', $news->getTitle(), PDO::PARAM_STR);
        $query->bindValue(':content', $news->getContent(), PDO::PARAM_STR);

        $query->execute();
    }

    public function delete(int $id)
    {
        $query = $this->pdo->prepare('DELETE FROM news WHERE id=:id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);

        $query->execute();
    }

    public function getUnique(int $id): NewsEntity
    {
        $query = $this->pdo->prepare('SELECT * FROM news WHERE id=:id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);

        $query->execute();

        $query->setFetchMode(PDO::FETCH_CLASS, NewsEntity::class);
        return $query->fetch();
    }

    public function getList(int $start = -1, int $limit = -1)
    {
        if ($start !== -1 && $limit !==-1){
            $query = $this->pdo->prepare('SELECT * FROM news LIMIT :limit OFFSET :start');
            $query->bindValue(':start', $start, PDO::PARAM_INT);
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        }else{
            $query = $this->pdo->prepare('SELECT * FROM news');
        }

        $query->execute();

        $query->setFetchMode(PDO::FETCH_CLASS, NewsEntity::class);
        return  $query->fetchAll();
    }

    public function count(): int
    {
        $query =  $this->pdo->query('SELECT COUNT(*) FROM news');
        return $query->fetchColumn();
    }
    public function isTableExists(string $tableName){

    }
}