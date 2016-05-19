<?php
class Barcode extends CI_Controller {

    public function __construct(){
		parent::__construct();
		require_once(APPPATH.'third_party/barcodegen/html/include/function.php');
		include_once(APPPATH.'third_party/barcodegen/html/config/BCGcode128.php');
		require_once(APPPATH.'third_party/barcodegen/class/BCGColor.php');
		require_once(APPPATH.'third_party/barcodegen/class/BCGBarcode.php');
		require_once(APPPATH.'third_party/barcodegen/class/BCGDrawing.php');
		require_once(APPPATH.'third_party/barcodegen/class/BCGFontFile.php');
		require_once(APPPATH.'third_party/barcodegen/class/BCGcode128.barcode.php');
		require_once(APPPATH.'third_party/barcodegen/class/BCGBarcode1D.php');
	}

	function draw($text = "1111111111111"){
		$_GET = array(
			'code' => "BCGcode128",
			'filetype' => "PNG",
			'dpi' => 72,
			'scale' => 1,
			'rotation' => 0,
			'font_family' => "Arial.ttf",
			'font_size' => 8,
			'text' => $text,
			'thickness' => 30
		);

		$filetypes = array('PNG' => BCGDrawing::IMG_FORMAT_PNG, 'JPEG' => BCGDrawing::IMG_FORMAT_JPEG, 'GIF' => BCGDrawing::IMG_FORMAT_GIF);

		$drawException = null;
		try {
		    $color_black = new BCGColor(0, 0, 0);
		    $color_white = new BCGColor(255, 255, 255);

		    $code_generated = new BCGcode128();

		    if (function_exists('baseCustomSetup')) {
		        baseCustomSetup($code_generated, $_GET);
		    }

		    if (function_exists('customSetup')) {
		        customSetup($code_generated, $_GET);
		    }

		    $code_generated->setScale(max(1, min(4, $_GET['scale'])));
		    $code_generated->setBackgroundColor($color_white);
		    $code_generated->setForegroundColor($color_black);

		    if ($_GET['text'] !== '') {
		        $text = convertText($_GET['text']);
		        $code_generated->parse($text);
		    }
		} catch(Exception $exception) {
		    $drawException = $exception;
		}

		$drawing = new BCGDrawing('', $color_white);
		if($drawException) {
		    $drawing->drawException($drawException);
		} else {
		    $drawing->setBarcode($code_generated);
		    $drawing->setRotationAngle($_GET['rotation']);
		    $drawing->setDPI($_GET['dpi'] === 'NULL' ? null : max(72, min(300, intval($_GET['dpi']))));
		    $drawing->draw();
		}

		switch ($_GET['filetype']) {
		    case 'PNG':
		        header('Content-Type: image/png');
		        break;
		    case 'JPEG':
		        header('Content-Type: image/jpeg');
		        break;
		    case 'GIF':
		        header('Content-Type: image/gif');
		        break;
		}

		$drawing->finish($filetypes[$_GET['filetype']]);

	}


}
