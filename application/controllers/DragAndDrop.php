<?php
header('Access-Control-Allow-Origin: *');
require "jwt.php";

class DragAndDrop extends CI_Controller
{
    /**
     * @var PDO
     */
    public $connect = null;
    public function __construct()
    {
        try {
            $this->connect = new PDO("mysql:host=localhost;dbname=Fundoo", "root", "root");
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    /**
     * @method  DragAndDropNotes()
     * @var string
     * @var int
     * @var int
     * @var string
     * @Description - This method swaps the notes based on ids in the notes table.
     */
    public function DragAndDropNotes()
    {
        $email     = $_POST["email"];
        $id        = $_POST["id"];
        $loop      = $_POST["loop"];
        $direction = $_POST["direction"];

        for ($i = 0; $i < $loop; $i++) {
            /**
             * If direction is upward get the note id which is less than current id
             */

            if ($direction == "upward") {
                $querry = "SELECT max(id) as nextid from Notes where id < '$id' and email='$email'";
            }
            /**
             * If direction is not upward get the note id which is greater than current id
             */
            else {
                $querry = "SELECT min(id) as nextid from Notes where id > '$id' and email='$email'";
            }
            $stmt   = $this->connect->prepare($querry);
            $var    = $stmt->execute();
            $noteid = $stmt->fetch(PDO::FETCH_ASSOC);
            $noteid = $noteid['nextid'];
            /**
             * Querry to Swap the notes.
             */
            $querry = "UPDATE Notes a inner join Notes b on a.id <> b.id set a.email = b.email,a.Title = b.Title,a.Note = b.Note,a.color = b.color,a.remainderDateTime = b.remainderDateTime,a.archived = b.archived,a.deleted = b.deleted where a.id in('$noteid','$id') and b.id in('$noteid','$id')";
            $stmt   = $this->connect->prepare($querry);
            $var    = $stmt->execute();
            /**
             * Swap the id's
             */
            $id = $noteid;
        }
    }
}
