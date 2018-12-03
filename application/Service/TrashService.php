<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Authorization");

include "/var/www/html/codeigniter/application/Service/ReminderService.php";

class TrashService extends CI_Controller
{

    public function __construct()
    {
        $DBConstantReference = new DBConstants();
        try {
            $this->connect = new PDO("mysql:host=$DBConstantReference->host;dbname=$DBConstantReference->DatabaseName", "$DBConstantReference->username", "$DBConstantReference->password");
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    /**
     * @method deleteNotes()
     * @Description - This method sets variable deleted to true in DB
     */
    public function deleteNotes($id, $email)
    {
        $ref      = new JWT();
        $headers  = apache_request_headers();
        $jwt      = $headers['Authorization'];
        $jwttoken = explode(" ", $jwt);
        $verify   = $ref->verify($jwttoken[1]);
        if ($verify) {
            $query     = "Update Notes set deleted	 = 'true' where id = '$id'";
            $statement = $this->connect->prepare($query);
            if ($statement->execute()) {
                $query     = "Select * from Notes where email = '$email' and deleted = 'false' order by DragAndDropID DESC ";
                $statement = $this->connect->prepare($query);
                $statement->execute();
                $arr = $statement->fetchAll(PDO::FETCH_ASSOC);
                /**
                 * Send the array of Notes to frontend.
                 */
                print json_encode($arr);
            } else {
                $data = array(
                    "status" => "404",
                );
                print json_encode($data);
            }
        }
    }
    /**
     * @method getDeletedNotes()
     * @Description - This method sends notes array for which deleted is true
     */

    public function getDeletedNotes($email)
    {
        /**
         * store the querry in string format
         */
        $query     = "Select * from Notes where email = '$email' and deleted = 'true' order by DragAndDropID DESC ";
        $statement = $this->connect->prepare($query);
        /**
         * Execute the querry.
         */
        if ($statement->execute()) {
            $arr = $statement->fetchAll(PDO::FETCH_ASSOC);
            /**
             * Send the array of Notes to frontend.
             */
            print json_encode($arr);
        } else {
            $data = array(
                "msg" => "Not Success",
            );
            print json_encode($data);
        }
    }
    /**
     * @method restoreNote()
     * @Description - This method sets deleted to false in DB
     */

    public function restoreNote($id, $email)
    {
        $ref      = new JWT();
        $headers  = apache_request_headers();
        $jwt      = $headers['Authorization'];
        $jwttoken = explode(" ", $jwt);
        $verify   = $ref->verify($jwttoken[1]);
        if ($verify) {
            $query     = "Update Notes set deleted	 = 'false' where id = '$id'";
            $statement = $this->connect->prepare($query);
            if ($statement->execute()) {
                $query     = "Select * from Notes where email = '$email' and deleted = 'true' order by DragAndDropID DESC ";
                $statement = $this->connect->prepare($query);
                $statement->execute();
                $arr = $statement->fetchAll(PDO::FETCH_ASSOC);
                /**
                 * Send the array of Notes to frontend.
                 */
                print json_encode($arr);
            } else {
                $data = array(
                    "status" => "404",
                );
                print json_encode($data);
            }
        }
    }
    /**
     * @method deleteNotePermanently()
     * @Description - This method Deletes the notes from DB
     */
    public function deleteNotePermanently($id, $email)
    {
        $ref      = new JWT();
        $headers  = apache_request_headers();
        $jwt      = $headers['Authorization'];
        $jwttoken = explode(" ", $jwt);
        $verify   = $ref->verify($jwttoken[1]);
        if ($verify) {
            $query     = "delete from Notes where id = '$id'";
            $statement = $this->connect->prepare($query);
            if ($statement->execute()) {
                $query     = "Select * from Notes where email = '$email' and deleted = 'true' order by DragAndDropID DESC ";
                $statement = $this->connect->prepare($query);
                $statement->execute();
                $arr = $statement->fetchAll(PDO::FETCH_ASSOC);
                /**
                 * Send the array of Notes to frontend.
                 */
                print json_encode($arr);
            } else {
                $data = array(
                    "status" => "404",
                );
                print json_encode($data);
            }
        }
    }
}
