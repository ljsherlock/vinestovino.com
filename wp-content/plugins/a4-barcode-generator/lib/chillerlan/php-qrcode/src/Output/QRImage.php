<?php

namespace chillerlan\QRCode\Output;

use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\{QRCode, QRCodeException};
use chillerlan\Settings\SettingsContainerInterface;
use Exception;

use function array_values, base64_encode, call_user_func, count, extension_loaded, imagecolorallocate, imagecolortransparent,
	imagecreatetruecolor, imagedestroy, imagefilledrectangle, imagegif, imagejpeg, imagepng, in_array,
	is_array, ob_end_clean, ob_get_contents, ob_start, range, sprintf;

class QRImage extends QROutputAbstract{

	protected const TRANSPARENCY_TYPES = [
		QRCode::OUTPUT_IMAGE_PNG,
		QRCode::OUTPUT_IMAGE_GIF,
	];

	protected string $defaultMode = QRCode::OUTPUT_IMAGE_PNG;

	protected $image;

	public function __construct(SettingsContainerInterface $options, QRMatrix $matrix){

		if(!extension_loaded('gd')){
			throw new QRCodeException('ext-gd not loaded'); 
		}

		parent::__construct($options, $matrix);
	}

	protected function setModuleValues():void{

		foreach($this::DEFAULT_MODULE_VALUES as $M_TYPE => $defaultValue){
			$v = $this->options->moduleValues[$M_TYPE] ?? null;

			if(!is_array($v) || count($v) < 3){
				$this->moduleValues[$M_TYPE] = $defaultValue
					? [0, 0, 0]
					: [255, 255, 255];
			}
			else{
				$this->moduleValues[$M_TYPE] = array_values($v);
			}

		}

	}

	public function dump(string $file = null){
		$file ??= $this->options->cachefile;

		$this->image = imagecreatetruecolor($this->length, $this->length);

		$tbg        = $this->options->imageTransparencyBG;
		$background = imagecolorallocate($this->image, ...$tbg);

		if((bool)$this->options->imageTransparent && in_array($this->options->outputType, $this::TRANSPARENCY_TYPES, true)){
			imagecolortransparent($this->image, $background);
		}

		imagefilledrectangle($this->image, 0, 0, $this->length, $this->length, $background);

		foreach($this->matrix->matrix() as $y => $row){
			foreach($row as $x => $M_TYPE){
				$this->setPixel($x, $y, $this->moduleValues[$M_TYPE]);
			}
		}

		if($this->options->returnResource){
			return $this->image;
		}

		$imageData = $this->dumpImage();

		if($file !== null){
			$this->saveToFile($imageData, $file);
		}

		if($this->options->imageBase64){
			$imageData = sprintf('data:image/%s;base64,%s', $this->options->outputType, base64_encode($imageData));
		}

		return $imageData;
	}

	protected function setPixel(int $x, int $y, array $rgb):void{
		imagefilledrectangle(
			$this->image,
			$x * $this->scale,
			$y * $this->scale,
			($x + 1) * $this->scale,
			($y + 1) * $this->scale,
			imagecolorallocate($this->image, ...$rgb)
		);
	}

	protected function dumpImage():string{
		ob_start();

		try{
			call_user_func([$this, $this->outputMode ?? $this->defaultMode]);
		}
		catch(Exception $e){
			throw new QRCodeOutputException($e->getMessage());
		}

		$imageData = ob_get_contents();
		imagedestroy($this->image);

		ob_end_clean();

		return $imageData;
	}

	protected function png():void{
		imagepng(
			$this->image,
			null,
			in_array($this->options->pngCompression, range(-1, 9), true)
				? $this->options->pngCompression
				: -1
		);
	}

	protected function gif():void{
		imagegif($this->image);
	}

	protected function jpg():void{
		imagejpeg(
			$this->image,
			null,
			in_array($this->options->jpegQuality, range(0, 100), true)
				? $this->options->jpegQuality
				: 85
		);
	}

}
