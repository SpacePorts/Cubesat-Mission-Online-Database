<?php 

use Intervention\Image\ImageManagerStatic as Image;
class ImageStorage{

	function __construct() {
	}

	public function GetImageUrl($token)
	{
		$lsha =  sha1($token);
		$lfirstDirectory = substr($lsha,0,2);
		$lsecondDirectory = substr($lsha, 2,2);
		return  IMAGE_URL . "/" . $lfirstDirectory . "/" . $lsecondDirectory . "/" . $lsha . ".jpg";
	}

	private function GetImageDirectory($token)
	{
		$lsha =  sha1($token);
		$lfirstDirectory = substr($lsha,0,2);
		$lsecondDirectory = substr($lsha, 2,2);
		$ldir = IMAGE_DIRECTORY . "/" . $lfirstDirectory . "/" . $lsecondDirectory . "/";

		if (!file_exists($ldir)) {
			mkdir($ldir,0777, true);
		}
		return $ldir . "/" . $lsha . ".jpg";
	}

	public function CheckIfImageExist($token)
	{
		return file_exists ($this->GetImageDirectory($token));
	}

	public function DeleteImage($token)
	{
		if(file_exists($this->GetImageDirectory($token)))
		unlink($this->GetImageDirectory($token));
	}

	public function UploadImageFixedSize($file,$token,$width,$height,$replace = false)
	{
		if($replace)
			$this->DeleteImage($token);

		$limage = Image::make($_FILES[$file]['tmp_name']);
		$limage->fit($width,$height);
		$limage->save($this->GetImageDirectory($token));
	}

	public function UploadImageWidthRatio($file,$token,$widthRatio,$replace = false)
	{
		if($replace)
			$this->DeleteImage($token);

		$limage = Image::make($_FILES[$file]['tmp_name']);

		$limage->resize($widthRatio, null, function ($constraint) {
		    $constraint->aspectRatio();
		    $constraint->upsize();
		});

		$limage->save($this->GetImageDirectory($token));

	}


	public function UploadImageHeightRatio($file,$token,$heightRatio,$replace = false)
	{
		if($replace)
			$this->DeleteImage($token);

		$limage = Image::make($_FILES[$file]['tmp_name']);

		$limage->resize(null, $heightRatio, function ($constraint) {
		    $constraint->aspectRatio();
		    $constraint->upsize();
		});

		$limage->save($this->GetImageDirectory($token));

	}
}

?>