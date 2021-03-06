<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Authorization");
require "/var/www/html/codeigniter/application/Service/ProfilepicService.php";

class PicController
{

    public $ProfilePicServiceRef;
    public function __construct()
    {
        $this->ProfilePicServiceRef = new ProfilepicService();
    }

    /**
     * @method fetchImage() fetch the user profile pic
     * @return void
     */
    public function fetchImage()
    {
        $email = $_POST["email"];
        $this->ProfilePicServiceRef->fetchImage($email);
    }
    /**
     * @method saveImage() upload the profile pic
     * @return void
     */
    public function saveImage()
    {
        $email = $_POST["email"];
        $image = $_POST["image"];
        $this->ProfilePicServiceRef->saveImage($image, $email);
    }
}
