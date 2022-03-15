<?php
class CategoryContainers{

    private $conn;
    private $username;
    public function __construct($conn, $username){
        $this->conn = $conn;
        $this->username = $username;
 
   }

   public function showAllCategories() {
       $query = $this->conn->prepare("SELECT * FROM categories");
       $query->execute();

       $html = "<div class='previewCategories'>";
       while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $html .= $this->getCategoryHtml($row, null, true, true);
       }
       return $html . "</div>";
   }


   public function showCategories($categoryId, $title = null){
        $query = $this->conn->prepare("SELECT * FROM categories WHERE id=:id");
        $query->bindValue("id", $categoryId);
        $query->execute();

        $html = "<div class='previewCategories noScroll'>";
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $html .= $this->getCategoryHtml($row, $title, true, true);
        }
        return $html . "</div>";
   }


   private function getCategoryHtml($sqlData, $title, $tvShows, $movies) {
        $categoryId = $sqlData["id"];
        $title = $title == null ? $sqlData["name"] : $title;

        if($tvShows && $movies){
            $entities = EntityProvider::getEntities($this->conn, $categoryId, 30);
        }

        else if ($tvShows){

        }

        else{

        }

        if (sizeof($entities) == 0){
            return;
        }

        $enitiesHtml = "";
        $previewProvider = new PreviewProvider($this->conn, $this->username);

        foreach($entities as $entity){
            $enitiesHtml .= $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>
                    <a href='category.php?id=$categoryId'>
                        <h3>$title</h3>
                    </a>

                    <div class='entities'>
                    $enitiesHtml
                    </div>
                </div>";
   }
}
?>