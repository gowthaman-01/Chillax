<?php
require_once("includes/header.php");
class VideoProvider {
    public static function getUpNext($conn, $currentVideo){
        $query = $conn->prepare("SELECT * FROM videos 
                                WHERE entityId=:entityId AND id != :videoId
                                AND(
                                    (season = :season AND episode > :episode) OR season > :season
                                    
                                )
                                ORDER BY season, episode ASC LIMIT 1");
        $query->bindValue(":entityId", $currentVideo->getEntityId());
        $query->bindValue(":season", $currentVideo->getSeason());
        $query->bindValue(":episode", $currentVideo->getEpisodeNumber());
        $query->bindValue(":videoId", $currentVideo->getId());

        $query->execute();

        if($query->rowCount() == 0){
            $query = $conn->prepare("SELECT * FROM vidoes
                                    WHERE season <=1 AND episode<=1
                                    AND id != :videoId
                                    ORDER BY views DESC LIMIT 1");
            $query->bindValue( ":videoId", $currentVideo->getId());
            $query->execute();
        }

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return new Video($conn, $row);
    }
}
?>