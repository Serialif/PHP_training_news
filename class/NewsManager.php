<?php


abstract class NewsManager
{
    public function save(NewsEntity $news){
        if($news->isValid()){
          if($news->exist()){
              $this->update($news);
          }  else{
              $this->add($news);
          }
        }
    }
    abstract protected function add(NewsEntity $news);
    abstract protected function update(NewsEntity $news);
    abstract public function delete(int $id);
    abstract public function getUnique(int $id);
    abstract public function getList(int $start = -1, int $limit = -1);
    abstract public function count();
    abstract public function isTableExists(string $tableName);
}