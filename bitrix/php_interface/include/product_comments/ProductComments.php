<?php


class ProductComments {

    protected $dbh;

    public function __construct() {
        global $DBLogin;
        global $DBPassword;
        global $DBHost;
        global $DBName;

        $this->dbh = new PDO('mysql:host=' . $DBHost . ';dbname=' . $DBName, $DBLogin, $DBPassword);
    }

    public function getMessages() {
        $result = [];

        $stmt = $this->dbh->prepare("select * from b_blog_comment order by id desc");
        $stmt->execute();

        while ($record = $stmt->fetch()) {
            $result[] = $record;
        }

        return $result;
    }

    public function viewMessage($id) {
        return $this->executeUpd([
            'status' => 'P',
            'id' => $id,
        ]);
    }

    public function hideMessage($id) {
        return $this->executeUpd([
            'status' => 'N',
            'id' => $id,
        ]);
    }

    public function deleteMessage($id) {
        $sql = "DELETE FROM b_blog_comment WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute([
            'id' => $id
        ]);
    }

    public function editMessage($text, $id) {
        $sql = "UPDATE b_blog_comment SET POST_TEXT=:text WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute([
            'text' => $text,
            'id' => $id,
        ]);
    }

    public function executeUpd($data) {
        $sql = "UPDATE b_blog_comment SET PUBLISH_STATUS=:status WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute($data);
    }

}