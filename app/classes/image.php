<?php
class Image extends Alert{
    public static function convertImage($originalImage, $outputImage, $quality){
	    $exploded = explode('.',$originalImage);
	    $ext = $exploded[count($exploded) - 1]; 

	    if(preg_match('/jpg|jpeg/i',$ext)){
			$imageTmp = imagecreatefromjpeg($originalImage);
		}else if(preg_match('/png/i',$ext)){
			$imageTmp = imagecreatefrompng($originalImage);
		}else if (preg_match('/gif/i',$ext)){
			$imageTmp = imagecreatefromgif($originalImage);
		}else if (preg_match('/bmp/i',$ext)){
			$imageTmp = imagecreatefrombmp($originalImage);
		}else{
			self::PushMessage("Данный формат изображения не поддерживается.");
			User::redirect("/");
			die();
		}

	    imagejpeg($imageTmp, $outputImage, $quality);
	    imagedestroy($imageTmp);

		unlink($originalImage);
	}

	public static function ImageCreateFromExt($originalImage){
		$exploded = explode('.',$originalImage);
	    $ext = $exploded[count($exploded) - 1]; 
		if(preg_match('/jpg|jpeg/i',$ext)){
			return imagecreatefromjpeg($originalImage);
		}else if(preg_match('/png/i',$ext)){
			return imagecreatefrompng($originalImage);
		}else if (preg_match('/gif/i',$ext)){
			return imagecreatefromgif($originalImage);
		}else if (preg_match('/bmp/i',$ext)){
			return imagecreatefrombmp($originalImage);
		}else{
			self::PushMessage("Данный формат изображения не поддерживается.");
			User::redirect("/");
			die();
		}
	}
	
	public static function cropAlign($image, $cropWidth, $cropHeight, $horizontalAlign = 'center', $verticalAlign = 'middle') {
		$width = imagesx($image);
		$height = imagesy($image);
		$horizontalAlignPixels = self::calculatePixelsForAlign($width, $cropWidth, $horizontalAlign);
		$verticalAlignPixels = self::calculatePixelsForAlign($height, $cropHeight, $verticalAlign);
		return imageCrop($image, [
			'x' => $horizontalAlignPixels[0],
			'y' => $verticalAlignPixels[0],
			'width' => $horizontalAlignPixels[1],
			'height' => $verticalAlignPixels[1]
		]);
	}
	
	public static function calculatePixelsForAlign($imageSize, $cropSize, $align) {
		switch ($align) {
			case 'left':
			case 'top':
				return [0, min($cropSize, $imageSize)];
			case 'right':
			case 'bottom':
				return [max(0, $imageSize - $cropSize), min($cropSize, $imageSize)];
			case 'center':
			case 'middle':
				return [
					max(0, floor(($imageSize / 2) - ($cropSize / 2))),
					min($cropSize, $imageSize),
				];
			default: return [0, $imageSize];
		}
	}
}