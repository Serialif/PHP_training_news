<?php


class DateManipulation
{
    static public function getDates(NewsEntity $news){
        $dates['dateCreated'] = date('d/m/y', strtotime($news->getCreatedAt()));
        $dates['timeCreated'] = date('H\hi', strtotime($news->getCreatedAt()));
        $dates['dateModified'] = date('d/m/y', strtotime($news->getModifiedAt()));
        $dates['timeModified'] = date('H\hi', strtotime($news->getModifiedAt()));

        return $dates;
    }
}