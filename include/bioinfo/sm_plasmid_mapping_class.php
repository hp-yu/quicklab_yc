<?php
class PlasmidMapDraw {
	public $mName;
	public $mSize;
	public $mRes;
	public $mFea;
	public $mDiameter;

	private $mImage;
	private $mCentralX;
	private $mCentralY;
	private $mOffsetX;
	private $mOffsetY;
	private $mCanvasHeight;
	private $mCanvasWidth;
	private $mCanvasMargin;
	private $mCanvasLeft;
	private $mCanvasRight;
	private $mCanvasTop;
	private $mCanvasBottom;
	//private $mCanvasRatio;
	//private $mDiameterCanvasWidthRatio;
	//private $mFirstAngle;
	//private $mResAreaAngle;
	//private $mResArea;
	private $mResLineLength;
	private $mFeaHeight;
	//private $mFeaArrowAngle;
	private $mFeaArrowLength;//new
	private $mMaxDiameter;
	private $mFeaResSpacing;
	private $mFeaNameFontSize;
	private $mFeaNameFont;
	private $mNumFeaLayers;
	private $mPlasmidNameFontSize;
	private $mPlasmidNameFont;
	private $mPlasmidNameSizeSpacing;
	private $mFont;

	function __construct() {
		//putenv("GDFONTPATH=C:\WINNT\Fonts");
		//putenv("CFG_TTF_PATH=c:\winnt\fonts");
		//putenv("CFG_IMG_PATH=c:\winnt\fonts");
		//$this->mCanvasRatio = 3/4;
		//$this->mDiameterCanvasWidthRatio = 2/3;
		$this->mResLineLength = 20;
		$this->mFeaHeight = 10;
		//$this->mFeaArrowAngle = 3;
		$this->mFeaArrowLength = 8;
		$this->mCanvasMargin = 20;
		$this->mFeaResSpacing = 5;
		$this->mFeaNameFontSize = 10;
		//USE equal-width FONT
		$this->mFeaNameFont = "cour";
		$this->mPlasmidNameFontSize = 10;
		$this->mPlasmidNameFont = "cour";
		$this->mPlasmidNameSizeSpacing = 5;
		$this->mFont="cour";
	}

	private function AllocateColor($rgb) {
		if (strstr($rgb,"rgb")) $rgb = eregi_replace('[rgb()]','',$rgb);
		$rgb = split(",",$rgb);
		$r = $rgb[0];
		$g = $rgb[1];
		$b = $rgb[2];
		return  imagecolorallocate($this->mImage,$r,$g,$b);
	}

	public function OutputPng () {
		$this -> mCentralX = $this->mDiameter/2;
		$this -> mCentralY = $this->mDiameter/2;
		//arc angles and number of layers
		$this -> CalFeatures();
		$this -> CalRestrictionSites();
		$this -> CalCanvas();
		$this -> DrawCanvas();
		$this -> DrawFeatures();
		$this -> DrawRestrictionSites();
		$this -> DrawName();
		$this -> DrawLogo();
		imagepng($this->mImage);
		imagedestroy($this->mImage);
	}

	private function CalCanvas () {
		$this -> mCanvasWidth = $this->mCanvasRight - $this->mCanvasLeft + 2*$this->mCanvasMargin;
		$this -> mCanvasHeight = $this->mCanvasBottom - $this->mCanvasTop + 2*$this->mCanvasMargin;
		$this -> mOffsetX = abs($this->mCanvasLeft) + $this->mCanvasMargin;
		$this -> mOffsetY = abs($this->mCanvasTop) + $this->mCanvasMargin;
		$this -> mCentralX = $this->mDiameter/2 + $this -> mOffsetX;
		$this -> mCentralY = $this->mDiameter/2 + $this -> mOffsetY;
	}

	private function DrawCanvas () {
		$this -> mImage = imagecreatetruecolor($this->mCanvasWidth,$this->mCanvasHeight);
		imagefill($this->mImage,0,0,$this -> AllocateColor("255,255,255"));
	}

	function DrawOutline () {
		$this->imageSmoothArc ($this->mImage, $this -> mCentralX, $this -> mCentralY, $this -> mDiameter, $this -> mDiameter, array(0,0,0,0), 0, 2 * pi());
		$this->imageSmoothArc ($this->mImage, $this -> mCentralX, $this -> mCentralY, $this -> mDiameter-2, $this -> mDiameter-2, array(255,255,255,0), 0, 2 * pi());
	}

	private function DrawName () {
		$name_str = $this->mName;
		$size_str = "(".$this->mSize." bp)";
		$name_bbox = imagettfbbox($this->mPlasmidNameFontSize,0,$this->mPlasmidNameFont,$name_str);
		$size_bbox = imagettfbbox($this->mPlasmidNameFontSize,0,$this->mPlasmidNameFont,$size_str);
		$name_x = $this -> mCentralX - $name_bbox[2]/2;
		$name_y = $this -> mCentralY - $name_bbox[1] - ($this -> mPlasmidNameSizeSpacing/2);
		$size_x = $this -> mCentralX - $size_bbox[2]/2;
		$size_y = $this -> mCentralY - $size_bbox[7] + ($this -> mPlasmidNameSizeSpacing/2);
	imagettftext($this->mImage,$this->mPlasmidNameFontSize,0,$name_x,$name_y,$this->AllocateColor("0,0,0"),$this->mPlasmidNameFont,$name_str);
		imagettftext($this->mImage,$this->mPlasmidNameFontSize,0,$size_x,$size_y,$this->AllocateColor("0,0,0"),$this->mPlasmidNameFont,$size_str);
	}

	private function DrawLogo () {
		$logo = "Powered by Quicklab";
		$logo_bbox = imagettfbbox(8,0,$this->mFont,$logo);
		$logo_width = abs ($logo_bbox[0] - $logo_bbox[2]);
		$logo_x = $this->mCanvasWidth - $logo_width - 5;
		$logo_y = $this->mCanvasHeight - $logo_bbox[1];
		imagettftext($this->mImage,8,0,$logo_x,$logo_y,$this->AllocateColor("0,0,0"),$this->mFont,$logo);
	}

	private function CalFeatures () {
		if (count($this -> mFea)>0) {
			$fea = $this -> mFea;
			//sort the features by start site
			$fea = $this -> SortArray($fea,"start","SORT_ASC","SORT_REGULAR");
			$fea_height = $this->mFeaHeight;
			$fea_cha_bbox = imagettfbbox($this->mFeaNameFontSize,0,$this->mFeaNameFont,"A");
			$fea_cha_width = abs($fea_cha_bbox[0]-$fea_cha_bbox[2]);
			$fea_layer = array();
			for ($i=0;$i<count($fea);$i++) {
				$fea_name_bbox = imagettfbbox($this->mFeaNameFontSize,0,$this->mFeaNameFont,"Ap");
				$fea_name_height = abs($fea_name_bbox[1]-$fea_name_bbox[7]);
				//calculate feature arc angles
				$fea[$i]['arc_angle_start'] = ($fea[$i]['start']/($this ->mSize)) * 360;
				$fea[$i]['arc_angle_end'] = ($fea[$i]['end']/($this ->mSize)) * 360;
				$fea[$i]['arc_angle'] = $fea[$i]['arc_angle_end'] - $fea[$i]['arc_angle_start'];
				$fea[$i]['arc_angle_middle'] = $fea[$i]['arc_angle_start'] + $fea[$i]['arc_angle'] / 2;
				//calculate feature angles
				$fea[$i]['angle_start'] = $fea[$i]['arc_angle_start'];
				$fea[$i]['angle_end'] = $fea[$i]['arc_angle_end'];
				$p=0;
				do {
					//calculate feature name angles
					$fea[$i]['diameter'] = $this -> mDiameter + $p * (4 * $fea_height + 2 * $fea_name_height);
					//calculate the arrow angle
					$FeaArrowAngle=($this->mFeaArrowLength/($fea[$i]['diameter']*pi()))*360;
					//the unexpected gap between feature arc and arrow, 1 pixel
					$FeaGapAngle=(1/($fea[$i]['diameter']*pi()))*360;
					$fea[$i]['layer'] = $p;
					$fea_angle_unit = rad2deg(asin(($fea_cha_width / 2)/ (($fea[$i]['diameter'] + 2 * $fea_height) / 2))) * 2;
					$fea[$i]['name_angle'] = $fea_angle_unit * strlen($fea[$i]['name']);
					$fea[$i]['name_angle_start'] = $fea[$i]['arc_angle_middle'] - $fea[$i]['name_angle'] / 2;
					$fea[$i]['name_angle_end'] = $fea[$i]['arc_angle_middle'] + $fea[$i]['name_angle'] / 2;
					//if the name angle is large than the arc angle
					if ($fea[$i]['name_angle'] > $fea[$i]['arc_angle']) {
						$fea[$i]['angle_start'] = $fea[$i]['name_angle_start'];
						$fea[$i]['angle_end'] = $fea[$i]['name_angle_end'];
					} else {
						switch ($fea[$i]['ori']) {
							case 1:
								$fea[$i]['angle_start'] = $fea[$i]['arc_angle_start'];
								$fea[$i]['angle_end'] = $fea[$i]['arc_angle_end']+$FeaArrowAngle;
								break;
							case 2:
								$fea[$i]['angle_start'] = $fea[$i]['arc_angle_start']-$FeaArrowAngle;
								$fea[$i]['angle_end'] = $fea[$i]['arc_angle_end'];
								break;
							default:
								$fea[$i]['angle_start'] = $fea[$i]['arc_angle_start'];
								$fea[$i]['angle_end'] = $fea[$i]['arc_angle_end'];
								break;
						}
					}
					$p++;
				}
				while (($fea_layer[$p]) >= $fea[$i]['angle_start']);
				$fea_layer[$p] = $fea[$i]['angle_end'];

				//calculate the arrow coordinates
				switch ($fea[$i]['ori']) {
					case 1:
						$fea[$i]['arrow_x_1'] = $this -> mCentralX + ($fea[$i]['diameter'] - 2 * $fea_height) / 2 * sin(deg2rad($fea[$i]['arc_angle_end']-$FeaGapAngle));
						$fea[$i]['arrow_y_1'] = $this -> mCentralY - ($fea[$i]['diameter'] - 2 * $fea_height) / 2 * cos(deg2rad($fea[$i]['arc_angle_end']-$FeaGapAngle));
						$fea[$i]['arrow_x_2'] = $this -> mCentralX + ($fea[$i]['diameter'] + 2 * $fea_height) / 2 * sin(deg2rad($fea[$i]['arc_angle_end']-$FeaGapAngle));
						$fea[$i]['arrow_y_2'] = $this -> mCentralY - ($fea[$i]['diameter'] + 2 * $fea_height) / 2 * cos(deg2rad($fea[$i]['arc_angle_end']-$FeaGapAngle));
						$fea[$i]['arrow_x_3'] = $this -> mCentralX + $fea[$i]['diameter'] / 2 * sin(deg2rad($fea[$i]['arc_angle_end']+$FeaArrowAngle));
						$fea[$i]['arrow_y_3'] = $this -> mCentralY - $fea[$i]['diameter'] / 2 * cos(deg2rad($fea[$i]['arc_angle_end']+$FeaArrowAngle));
						$fea[$i]['arrow'] = 1;
						break;
					case 2:
						$fea[$i]['arrow_x_1'] = $this -> mCentralX + ($fea[$i]['diameter'] - 2 * $fea_height) / 2 * sin(deg2rad($fea[$i]['arc_angle_start']+$FeaGapAngle));
						$fea[$i]['arrow_y_1'] = $this -> mCentralY - ($fea[$i]['diameter'] - 2 * $fea_height) / 2 * cos(deg2rad($fea[$i]['arc_angle_start']+$FeaGapAngle));
						$fea[$i]['arrow_x_2'] = $this -> mCentralX + ($fea[$i]['diameter'] + 2 * $fea_height) / 2 * sin(deg2rad($fea[$i]['arc_angle_start']+$FeaGapAngle));
						$fea[$i]['arrow_y_2'] = $this -> mCentralY - ($fea[$i]['diameter'] + 2 * $fea_height) / 2 * cos(deg2rad($fea[$i]['arc_angle_start']+$FeaGapAngle));
						$fea[$i]['arrow_x_3'] = $this -> mCentralX + $fea[$i]['diameter'] / 2 * sin(deg2rad($fea[$i]['arc_angle_start']-$FeaArrowAngle));
						$fea[$i]['arrow_y_3'] = $this -> mCentralY - $fea[$i]['diameter'] / 2 * cos(deg2rad($fea[$i]['arc_angle_start']-$FeaArrowAngle));
						$fea[$i]['arrow'] = 1;
						break;
					default:
						$fea[$i]['arrow'] = 0;
						break;
				}
			}
			$this -> mMaxDiameter = $this -> mDiameter + (count ($fea_layer) - 1)* (4 * $fea_height + 2 * $fea_name_height) + 2 * $fea_height + 2 * $fea_name_height;
			$this -> mNumFeaLayers = count($fea_layer);
			$this -> mFea = $fea;
		} else {
			$this -> mMaxDiameter = $this -> mDiameter;
		}
	}

	private function DrawFeatures () {
		if (count($this -> mFea)>0) {
			$offset_x = $this -> mOffsetX;
			$offset_y = $this -> mOffsetY;
			$fea_height = $this->mFeaHeight;
			$fea_cha_bbox = imagettfbbox($this->mFeaNameFontSize,0,$this->mFeaNameFont,"A");
			$fea_cha_width = abs($fea_cha_bbox[0]-$fea_cha_bbox[2]);
			$fea_cha_bbox = imagettfbbox($this->mFeaNameFontSize,0,$this->mFeaNameFont,"p");
			$fea = $this -> mFea;
			$fea = $this -> SortArray($fea,"layer","SORT_DESC","SORT_REGULAR");
			//draw the feature arc
			if ($fea[0]['layer'] == 0) {
				$this->DrawOutline();
			}
			for ($i=0;$i<count($fea);$i++) {
				$fea[$i]['color'] = eregi_replace('[rgb()]','',$fea[$i]['color']);
				$color = split(",",$fea[$i]['color']);
				$r = $color[0];
				$g = $color[1];
				$b = $color[2];
				$this->imageSmoothArc ($this->mImage, $this -> mCentralX, $this -> mCentralY, $fea[$i]['diameter'] + $fea_height, $fea[$i]['diameter'] + $fea_height, array($r,$g,$b,0), deg2rad(90-$fea[$i]['arc_angle_end']), deg2rad(90-$fea[$i]['arc_angle_start']));
				if ($fea[$i+1]['layer'] != $fea[$i]['layer']) {
					$this->imageSmoothArc ($this->mImage, $this -> mCentralX, $this -> mCentralY, $fea[$i]['diameter'] - $fea_height, $fea[$i]['diameter'] - $fea_height, array(255,255,255,0), 0, 2 * pi());
					if ($fea[$i+1]['layer'] == 0) {
						$this->DrawOutline();
					}
				}
			}
			$this->imageSmoothArc ($this->mImage, $this -> mCentralX, $this -> mCentralY, $this->mDiameter - $fea_height, $this->mDiameter - $fea_height, array(255,255,255,0), 0, 2 * pi());
			//draw the feature name character by character
			for ($i=0;$i<count($fea);$i++) {
				$fea_angle_unit = rad2deg(asin(($fea_cha_width / 2)/ ($fea[$i]['diameter']/2 + $fea_height))) * 2;
				for ($m=0;$m<strlen($fea[$i]['name']);$m++) {
					$fea[$i]['cha_angle'] = $m * $fea_angle_unit;
					$fea[$i]['name_x_1'] = $this -> mCentralX + ($fea[$i]['diameter']/2 + $fea_height + $fea_cha_bbox[1]) * sin(deg2rad($fea[$i]['name_angle_start']+$fea[$i]['cha_angle']));
					$fea[$i]['name_y_1'] = $this -> mCentralY - ($fea[$i]['diameter']/2 + $fea_height + $fea_cha_bbox[1]) * cos(deg2rad($fea[$i]['name_angle_start']+$fea[$i]['cha_angle']));
					imagettftext($this->mImage,$this->mFeaNameFontSize,(360 - $fea[$i]['name_angle_start'] - $fea[$i]['cha_angle']),$fea[$i]['name_x_1'],$fea[$i]['name_y_1'],$this->AllocateColor($fea[$i]['color']),$this->mFeaNameFont,substr ($fea[$i]['name'],$m,1));
				}
			}
			//draw the feature arrow
			imageantialias($this->mImage,true);
			for ($i=0;$i<count($fea);$i++) {
				if ($fea[$i]['arrow'] == 1) {
					$values = array(($fea[$i]['arrow_x_1'] + $offset_x),($fea[$i]['arrow_y_1'] + $offset_y),($fea[$i]['arrow_x_2'] + $offset_x),($fea[$i]['arrow_y_2'] + $offset_y),($fea[$i]['arrow_x_3'] + $offset_x),($fea[$i]['arrow_y_3'] + $offset_y));
					imagefilledpolygon($this->mImage,$values,3,$this->AllocateColor($fea[$i]['color']));
					//for antialias purpose
					imagepolygon($this->mImage,$values,3,$this->AllocateColor($fea[$i]['color']));
				}
			}
			imageantialias($this->mImage,false);
		} else {
			$this->DrawOutline();
		}
	}

	private function CalRestrictionSites () {
		$extend = ($this -> mMaxDiameter - $this -> mDiameter)/2;
		$canvas_left = - ($extend);
		$canvas_right = $this -> mDiameter + $extend;
		$canvas_top = - ($extend);
		$canvas_bottom = $this -> mDiameter + $extend;
		if (count($this -> mRes)>0) {
			$res = $this -> mRes;
			//sort the restriction sites by site position
			$res = $this -> SortArray($res,"site","SORT_ASC","SORT_REGULAR");
			$radius = $this -> mMaxDiameter/2 + $this->mFeaResSpacing;
			$radius_2 = $radius + $this->mResLineLength;
			//stat the number of res sites in four areas
			$num_res_1 = 0;
			$num_res_2 = 0;
			$num_res_3 = 0;
			$num_res_4 = 0;
			for ($i=0;$i<count($res);$i++) {
				$res[$i]['angle'] = ($res[$i]['site']/($this ->mSize)) * 360;
				if ($res[$i]['angle']<=360) $num_res_4++;
				if ($res[$i]['angle']<=270) $num_res_3++;
				if ($res[$i]['angle']<=180) $num_res_2++;
				if ($res[$i]['angle']<=90) $num_res_1++;
				$res[$i]['name'] = " ".$res[$i]['name']." (".$res[$i]['site'].") ";
			}
			//calculate the coordinate of the res sites in the 1st area
			for ($i=0;$i<$num_res_1;$i++) {
				$res[$i]['area'] = 1;
				$res_name_bbox = imagettfbbox(8,0,$this->mFont,$res[$i]['name']);
				$res_name_width = abs($res_name_bbox[0]-$res_name_bbox[2]);
				$res_name_height = abs($res_name_bbox[1]-$res_name_bbox[7]);
				$res[$i]['angle'] = ($res[$i]['site']/($this ->mSize)) * 360;
				$res[$i]['x_1'] = $this -> mCentralX + $radius * sin(deg2rad($res[$i]['angle']));
				$res[$i]['y_1'] = $this -> mCentralY - $radius * cos(deg2rad($res[$i]['angle']));
				$res[$i]['x_2'] = $this -> mCentralX + $radius_2 * sin(deg2rad($res[$i]['angle']));
				$res[$i]['y_2'] = $this -> mCentralY - $radius_2 * cos(deg2rad($res[$i]['angle']));
				$res[$i]['x_3'] = $res[$i]['x_2'] + $res_name_width;
				$res[$i]['y_3'] = $res[$i]['y_2'];
				$res[$i]['x_4'] = $res[$i]['x_2'] + $res_name_width;
				$res[$i]['y_4'] = $res[$i]['y_2'] - $res_name_height;
				$res[$i]['x_5'] = $res[$i]['x_2'];
				$res[$i]['y_5'] = $res[$i]['y_2'] - $res_name_height;
				$m = $i;
				while (($res[$m]['x_5']<$res[$m-1]['x_3'] && $res[$m]['y_5']<$res[$m-1]['y_3']) && $m>0) {
					while ($res[$m]['y_5']<$res[$m-1]['y_3']) {
						$res[$m-1]['y_2'] --;
						$res[$m-1]['y_3'] --;
						$res[$m-1]['y_4'] --;
						$res[$m-1]['y_5'] --;
					}
					$m--;
				}
			}
			//calculate the coordinate of the res sites in the 2nd area
			for ($i=($num_res_2-1);$i>=$num_res_1;$i--) {
				$res[$i]['area'] = 2;
				$res_name_bbox = imagettfbbox(8,0,$this->mFont,$res[$i]['name']);
				$res_name_width = abs($res_name_bbox[0]-$res_name_bbox[2]);
				$res_name_height = abs($res_name_bbox[1]-$res_name_bbox[7]);
				$res[$i]['angle'] = ($res[$i]['site']/($this ->mSize)) * 360;
				$res[$i]['x_1'] = $this -> mCentralX + $radius * sin(deg2rad($res[$i]['angle']));
				$res[$i]['y_1'] = $this -> mCentralY - $radius * cos(deg2rad($res[$i]['angle']));
				$res[$i]['x_2'] = $this -> mCentralX + $radius_2 * sin(deg2rad($res[$i]['angle']));
				$res[$i]['y_2'] = $this -> mCentralY - $radius_2 * cos(deg2rad($res[$i]['angle']));
				$res[$i]['x_3'] = $res[$i]['x_2'];
				$res[$i]['y_3'] = $res[$i]['y_2'] + $res_name_height;
				$res[$i]['x_4'] = $res[$i]['x_2'] + $res_name_width;
				$res[$i]['y_4'] = $res[$i]['y_2'] + $res_name_height;
				$res[$i]['x_5'] = $res[$i]['x_2'] + $res_name_width;
				$res[$i]['y_5'] = $res[$i]['y_2'];
				$m = $i;
				while (($res[$m]['x_3']<$res[$m+1]['x_5'] && $res[$m]['y_3']>$res[$m+1]['y_5']) && $m<$num_res_2) {
					while ($res[$m]['y_3']>$res[$m+1]['y_5']) {
						$res[$m+1]['y_2'] ++;
						$res[$m+1]['y_3'] ++;
						$res[$m+1]['y_4'] ++;
						$res[$m+1]['y_5'] ++;
					}
					$m++;
				}
			}
			//calculate the coordinate of the res sites in the 3rd area
			for ($i=$num_res_2;$i<$num_res_3;$i++) {
				$res[$i]['area'] = 3;
				$res_name_bbox = imagettfbbox(8,0,$this->mFont,$res[$i]['name']);
				$res_name_width = abs($res_name_bbox[0]-$res_name_bbox[2]);
				$res_name_height = abs($res_name_bbox[1]-$res_name_bbox[7]);
				$res[$i]['angle'] = ($res[$i]['site']/($this ->mSize)) * 360;
				$res[$i]['x_1'] = $this -> mCentralX + $radius * sin(deg2rad($res[$i]['angle']));
				$res[$i]['y_1'] = $this -> mCentralY - $radius * cos(deg2rad($res[$i]['angle']));
				$res[$i]['x_2'] = $this -> mCentralX + $radius_2 * sin(deg2rad($res[$i]['angle']));
				$res[$i]['y_2'] = $this -> mCentralY - $radius_2 * cos(deg2rad($res[$i]['angle']));
				$res[$i]['x_3'] = $res[$i]['x_2'] - $res_name_width;
				$res[$i]['y_3'] = $res[$i]['y_2'];
				$res[$i]['x_4'] = $res[$i]['x_2'] - $res_name_width;
				$res[$i]['y_4'] = $res[$i]['y_2'] + $res_name_height;
				$res[$i]['x_5'] = $res[$i]['x_2'];
				$res[$i]['y_5'] = $res[$i]['y_2'] + $res_name_height;
				$m = $i;
				while (($res[$m]['x_5']>$res[$m-1]['x_3'] && $res[$m]['y_5']>$res[$m-1]['y_3']) && $m>$num_res_2) {
					while ($res[$m]['y_5']>$res[$m-1]['y_3']) {
						$res[$m-1]['y_2'] ++;
						$res[$m-1]['y_3'] ++;
						$res[$m-1]['y_4'] ++;
						$res[$m-1]['y_5'] ++;
					}
					$m--;
				}
			}
			//calculate the coordinate of the res sites in the 4th area
			for ($i=($num_res_4-1);$i>=$num_res_3;$i--) {
				$res[$i]['area'] = 4;
				$res_name_bbox = imagettfbbox(8,0,$this->mFont,$res[$i]['name']);
				$res_name_width = abs($res_name_bbox[0]-$res_name_bbox[2]);
				$res_name_height = abs($res_name_bbox[7]);
				$res[$i]['angle'] = ($res[$i]['site']/($this ->mSize)) * 360;
				$res[$i]['x_1'] = $this -> mCentralX + $radius * sin(deg2rad($res[$i]['angle']));
				$res[$i]['y_1'] = $this -> mCentralY - $radius * cos(deg2rad($res[$i]['angle']));
				$res[$i]['x_2'] = $this -> mCentralX + $radius_2 * sin(deg2rad($res[$i]['angle']));
				$res[$i]['y_2'] = $this -> mCentralY - $radius_2 * cos(deg2rad($res[$i]['angle']));
				$res[$i]['x_3'] = $res[$i]['x_2'];
				$res[$i]['y_3'] = $res[$i]['y_2'] - $res_name_height;
				$res[$i]['x_4'] = $res[$i]['x_2']	- $res_name_width;
				$res[$i]['y_4'] = $res[$i]['y_2'] - $res_name_height;
				$res[$i]['x_5'] = $res[$i]['x_2'] - $res_name_width;
				$res[$i]['y_5'] = $res[$i]['y_2'];
				$m = $i;
				while (($res[$m]['x_3']>$res[$m+1]['x_5'] && $res[$m]['y_3']<$res[$m+1]['y_5']) && $m<$num_res_4) {
					while ($res[$m]['y_3']<$res[$m+1]['y_5']) {
						$res[$m+1]['y_2'] --;
						$res[$m+1]['y_3'] --;
						$res[$m+1]['y_4'] --;
						$res[$m+1]['y_5'] --;
					}
					$m++;
				}
			}

			for ($i=0;$i<count($res);$i++) {
				if ($res[$i]['x_4']<$canvas_left) $canvas_left = $res[$i]['x_4'];
				if ($res[$i]['x_4']>$canvas_right) $canvas_right = $res[$i]['x_4'];
				if ($res[$i]['y_4']<$canvas_top) $canvas_top = $res[$i]['y_4'];
				if ($res[$i]['y_4']>$canvas_bottom) $canvas_bottom = $res[$i]['y_4'];
			}
			$this -> mRes = $res;
		}
		$this -> mCanvasLeft = $canvas_left;
		$this -> mCanvasRight = $canvas_right;
		$this -> mCanvasTop = $canvas_top;
		$this -> mCanvasBottom = $canvas_bottom;
	}

	private function DrawRestrictionSites () {
		//draw the res sites
		$offset_x = $this -> mOffsetX;
		$offset_y = $this -> mOffsetY;
		imageantialias($this->mImage,true);
		if (count($this -> mRes)>0) {
			$res = $this -> mRes;
			for ($i=0;$i<count($res);$i++) {
				imageline($this -> mImage, ($res[$i]['x_1'] + $offset_x), ($res[$i]['y_1'] + $offset_y), ($res[$i]['x_2'] + $offset_x), ($res[$i]['y_2'] + $offset_y), $this->AllocateColor("0,0,0"));
				switch ($res[$i]['area']) {
					case 1:					imagettftext($this->mImage,8,0,($res[$i]['x_2'] + $offset_x),($res[$i]['y_2'] + $offset_y),$this->AllocateColor("0,0,0"),$this->mFont,$res[$i]['name']);
					break;
					case 2:					imagettftext($this->mImage,8,0,($res[$i]['x_3'] + $offset_x),($res[$i]['y_3'] + $offset_y),$this->AllocateColor("0,0,0"),$this->mFont,$res[$i]['name']);
					break;
					case 3:					imagettftext($this->mImage,8,0,($res[$i]['x_4'] + $offset_x),($res[$i]['y_4'] + $offset_y),$this->AllocateColor("0,0,0"),$this->mFont,$res[$i]['name']);
					break;
					case 4:					imagettftext($this->mImage,8,0,($res[$i]['x_5'] + $offset_x),($res[$i]['y_5'] + $offset_y),$this->AllocateColor("0,0,0"),$this->mFont,$res[$i]['name']);
					break;
				}
			}
		}
	}

	//$str=mb_convert_encoding("qiuzf �����? q_zefeng@21cn.com","UTF-8","GB2312");
	//imagettftext($this->mImage,$font_size,90,190,210,$black,$font_name,$str);

	// 说�??�?PHP�?�?维�?��????????�???��??
	// ??��??�?http://www.CodeBit.cn
	/**
* @package BugFree
* @version $Id: FunctionsMain.inc.php,v 1.32 2005/09/24 11:38:37 wwccss Exp $
* Sort an two-dimension array by some level two items use array_multisort() function.
* sysSortArray($Array,"Key1","SORT_ASC","SORT_RETULAR","Key2"??????)
* @author Chunsheng Wang <wwccss@263.net>
* @param array $ArrayData the array to sort.
* @param string $KeyName1 the first item to sort by.
* @param string $SortOrder1 the order to sort by("SORT_ASC"|"SORT_DESC")
* @param string $SortType1 the sort type("SORT_REGULAR"|"SORT_NUMERIC"|"SORT_STRING")
* @return array sorted array.
*/
	private function SortArray($ArrayData,$KeyName1,$SortOrder1 = "SORT_ASC",$SortType1 = "SORT_REGULAR")
	{
		if(!is_array($ArrayData))
		{
			return $ArrayData;
		}
		// Get args number.
		$ArgCount = func_num_args();
		// Get keys to sort by and put them to SortRule array.
		for($I = 1;$I < $ArgCount;$I ++)
		{
			$Arg = func_get_arg($I);
			if(!eregi("SORT",$Arg))
			{
				$KeyNameList[] = $Arg;
				$SortRule[] = '$'.$Arg;
			}
			else
			{
				$SortRule[] = $Arg;
			}
		}
		// Get the values according to the keys and put them to array.
		foreach($ArrayData AS $Key => $Info)
		{
			foreach($KeyNameList AS $KeyName)
			{
				${$KeyName}[$Key] = $Info[$KeyName];
			}
		}
		// Create the eval string and eval it.
		$EvalString = 'array_multisort('.join(",",$SortRule).',$ArrayData);';
		eval ($EvalString);
		return $ArrayData;
	}
	private function ImageEllipseAA( &$img, $x, $y, $w, $h,$color,$segments=70)
	{
		$w=$w/2;
		$h=$h/2;
		$jump=2*M_PI/$segments;
		$oldx=$x+sin(-$jump)*$w;
		$oldy=$y+cos(-$jump)*$h;
		for($i=0;$i<2*(M_PI);$i+=$jump)
		{
			$newx=$x+sin($i)*$w;
			$newy=$y+cos($i)*$h;
			ImageLine($img,$newx,$newy,$oldx,$oldy,$color);
			$oldx=$newx;
			$oldy=$newy;
		}
	}

	private function imageSmoothCircle( &$img, $cx, $cy, $cr, $color ) {
		$ir = $cr;
		$ix = 0;
		$iy = $ir;
		$ig = 2 * $ir - 3;
		$idgr = -6;
		$idgd = 4 * $ir - 10;
		$fill = imageColorExactAlpha( $img, $color[ 'R' ], $color[ 'G' ], $color[ 'B' ], 0 );
		imageLine( $img, $cx + $cr - 1, $cy, $cx, $cy, $fill );
		imageLine( $img, $cx - $cr + 1, $cy, $cx - 1, $cy, $fill );
		imageLine( $img, $cx, $cy + $cr - 1, $cx, $cy + 1, $fill );
		imageLine( $img, $cx, $cy - $cr + 1, $cx, $cy - 1, $fill );
		$draw = imageColorExactAlpha( $img, $color[ 'R' ], $color[ 'G' ], $color[ 'B' ], 42 );
		imageSetPixel( $img, $cx + $cr, $cy, $draw );
		imageSetPixel( $img, $cx - $cr, $cy, $draw );
		imageSetPixel( $img, $cx, $cy + $cr, $draw );
		imageSetPixel( $img, $cx, $cy - $cr, $draw );
		while ( $ix <= $iy - 2 ) {
			if ( $ig < 0 ) {
				$ig += $idgd;
				$idgd -= 8;
				$iy--;
			} else {
				$ig += $idgr;
				$idgd -= 4;
			}
			$idgr -= 4;
			$ix++;
			imageLine( $img, $cx + $ix, $cy + $iy - 1, $cx + $ix, $cy + $ix, $fill );
			imageLine( $img, $cx + $ix, $cy - $iy + 1, $cx + $ix, $cy - $ix, $fill );
			imageLine( $img, $cx - $ix, $cy + $iy - 1, $cx - $ix, $cy + $ix, $fill );
			imageLine( $img, $cx - $ix, $cy - $iy + 1, $cx - $ix, $cy - $ix, $fill );
			imageLine( $img, $cx + $iy - 1, $cy + $ix, $cx + $ix, $cy + $ix, $fill );
			imageLine( $img, $cx + $iy - 1, $cy - $ix, $cx + $ix, $cy - $ix, $fill );
			imageLine( $img, $cx - $iy + 1, $cy + $ix, $cx - $ix, $cy + $ix, $fill );
			imageLine( $img, $cx - $iy + 1, $cy - $ix, $cx - $ix, $cy - $ix, $fill );
			$filled = 0;
			for ( $xx = $ix - 0.45; $xx < $ix + 0.5; $xx += 0.2 ) {
				for ( $yy = $iy - 0.45; $yy < $iy + 0.5; $yy += 0.2 ) {
					if ( sqrt( pow( $xx, 2 ) + pow( $yy, 2 ) ) < $cr ) $filled += 4;
				}
			}
			$draw = imageColorExactAlpha( $img, $color[ 'R' ], $color[ 'G' ], $color[ 'B' ], ( 100 - $filled ) );
			imageSetPixel( $img, $cx + $ix, $cy + $iy, $draw );
			imageSetPixel( $img, $cx + $ix, $cy - $iy, $draw );
			imageSetPixel( $img, $cx - $ix, $cy + $iy, $draw );
			imageSetPixel( $img, $cx - $ix, $cy - $iy, $draw );
			imageSetPixel( $img, $cx + $iy, $cy + $ix, $draw );
			imageSetPixel( $img, $cx + $iy, $cy - $ix, $draw );
			imageSetPixel( $img, $cx - $iy, $cy + $ix, $draw );
			imageSetPixel( $img, $cx - $iy, $cy - $ix, $draw );
		}
	}

	/*

	Copyright (c) 2006-2008 Ulrich Mierendorff

	Permission is hereby granted, free of charge, to any person obtaining a
	copy of this software and associated documentation files (the "Software"),
	to deal in the Software without restriction, including without limitation
	the rights to use, copy, modify, merge, publish, distribute, sublicense,
	and/or sell copies of the Software, and to permit persons to whom the
	Software is furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
	THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
	FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
	DEALINGS IN THE SOFTWARE.

	Changelog:
	version 1.1
	- improved the rendering speed by ~20%

	- Thanks to Matthias Mächler for fixing some small errors:
	* uninitialized variables
	* deprecated passing of $img reference in imageSmoothArc ()

	version 1.0
	Release of rewritten script

	*/

	private function imageSmoothArcDrawSegment (&$img, $cx, $cy, $a, $b, $aaAngleX, $aaAngleY, $color, $start, $stop, $seg)
	{
		// Originally written from scratch by Ulrich Mierendorff, 06/2006
		// Rewritten and improved, 04/2007, 07/2007

		// Please do not use THIS function directly. Scroll down to imageSmoothArc(...).

		$fillColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], $color[3] );

		$xStart = abs($a * cos($start));
		$yStart = abs($b * sin($start));
		$xStop  = abs($a * cos($stop));
		$yStop  = abs($b * sin($stop));
		$dxStart = 0;
		$dyStart = 0;
		$dxStop = 0;
		$dyStop = 0;
		if ($xStart != 0)
		$dyStart = $yStart/$xStart;
		if ($xStop != 0)
		$dyStop = $yStop/$xStop;
		if ($yStart != 0)
		$dxStart = $xStart/$yStart;
		if ($yStop != 0)
		$dxStop = $xStop/$yStop;
		if (abs($xStart) >= abs($yStart)) {
			$aaStartX = true;
		} else {
			$aaStartX = false;
		}
		if ($xStop >= $yStop) {
			$aaStopX = true;
		} else {
			$aaStopX = false;
		}
		//$xp = +1; $yp = -1; $xa = +1; $ya = 0;
		for ( $x = 0; $x < $a; $x += 1 ) {
			/*$y = $b * sqrt( 1 - ($x*$x)/($a*$a) );

			$error = $y - (int)($y);
			$y = (int)($y);

			$diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );*/

			$_y1 = $dyStop*$x;
			$_y2 = $dyStart*$x;
			if ($xStart > $xStop)
			{
				$error1 = $_y1 - (int)($_y1);
				$error2 = 1 - $_y2 + (int)$_y2;
				$_y1 = $_y1-$error1;
				$_y2 = $_y2+$error2;
			}
			else
			{
				$error1 = 1 - $_y1 + (int)$_y1;
				$error2 = $_y2 - (int)($_y2);
				$_y1 = $_y1+$error1;
				$_y2 = $_y2-$error2;
			}
			/*
			if ($aaStopX)
			$diffColor1 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error1 );
			if ($aaStartX)
			$diffColor2 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error2 );
			*/

			if ($seg == 0 || $seg == 2)
			{
				$i = $seg;
				if (!($start > $i*M_PI/2 && $x > $xStart)) {
					if ($i == 0) {
						$xp = +1; $yp = -1; $xa = +1; $ya = 0;
					} else {
						$xp = -1; $yp = +1; $xa = 0; $ya = +1;
					}
					if ( $stop < ($i+1)*(M_PI/2) && $x <= $xStop ) {
						$diffColor1 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error1 );
						$y1 = $_y1; if ($aaStopX) imageSetPixel($img, $cx+$xp*($x)+$xa, $cy+$yp*($y1+1)+$ya, $diffColor1);

					} else {
						$y = $b * sqrt( 1 - ($x*$x)/($a*$a) );
						$error = $y - (int)($y);
						$y = (int)($y);
						$diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
						$y1 = $y; if ($x < $aaAngleX ) imageSetPixel($img, $cx+$xp*$x+$xa, $cy+$yp*($y1+1)+$ya, $diffColor);
					}
					if ($start > $i*M_PI/2 && $x <= $xStart) {
						$diffColor2 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error2 );
						$y2 = $_y2; if ($aaStartX) imageSetPixel($img, $cx+$xp*$x+$xa, $cy+$yp*($y2-1)+$ya, $diffColor2);
					} else {
						$y2 = 0;
					}
					if ($y2 <= $y1) imageLine($img, $cx+$xp*$x+$xa, $cy+$yp*$y1+$ya , $cx+$xp*$x+$xa, $cy+$yp*$y2+$ya, $fillColor);
				}
			}

			if ($seg == 1 || $seg == 3)
			{
				$i = $seg;
				if (!($stop < ($i+1)*M_PI/2 && $x > $xStop)) {
					if ($i == 1) {
						$xp = -1; $yp = -1; $xa = 0; $ya = 0;
					} else {
						$xp = +1; $yp = +1; $xa = 1; $ya = 1;
					}
					if ( $start > $i*M_PI/2 && $x < $xStart ) {
						$diffColor2 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error2 );
						$y1 = $_y2; if ($aaStartX) imageSetPixel($img, $cx+$xp*$x+$xa, $cy+$yp*($y1+1)+$ya, $diffColor2);

					} else {
						$y = $b * sqrt( 1 - ($x*$x)/($a*$a) );
						$error = $y - (int)($y);
						$y = (int) $y;
						$diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
						$y1 = $y; if ($x < $aaAngleX ) imageSetPixel($img, $cx+$xp*$x+$xa, $cy+$yp*($y1+1)+$ya, $diffColor);
					}
					if ($stop < ($i+1)*M_PI/2 && $x <= $xStop) {
						$diffColor1 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error1 );
						$y2 = $_y1; if ($aaStopX)  imageSetPixel($img, $cx+$xp*$x+$xa, $cy+$yp*($y2-1)+$ya, $diffColor1);
					} else {
						$y2 = 0;
					}
					if ($y2 <= $y1) imageLine($img, $cx+$xp*$x+$xa, $cy+$yp*$y1+$ya, $cx+$xp*$x+$xa, $cy+$yp*$y2+$ya, $fillColor);
				}
			}
		}

		///YYYYY

		for ( $y = 0; $y < $b; $y += 1 ) {
			/*$x = $a * sqrt( 1 - ($y*$y)/($b*$b) );

			$error = $x - (int)($x);
			$x = (int)($x);

			$diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
			*/
			$_x1 = $dxStop*$y;
			$_x2 = $dxStart*$y;
			if ($yStart > $yStop)
			{
				$error1 = $_x1 - (int)($_x1);
				$error2 = 1 - $_x2 + (int)$_x2;
				$_x1 = $_x1-$error1;
				$_x2 = $_x2+$error2;
			}
			else
			{
				$error1 = 1 - $_x1 + (int)$_x1;
				$error2 = $_x2 - (int)($_x2);
				$_x1 = $_x1+$error1;
				$_x2 = $_x2-$error2;
			}
			/*
			if (!$aaStopX)
			$diffColor1 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error1 );
			if (!$aaStartX)
			$diffColor2 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error2 );
			*/

			if ($seg == 0 || $seg == 2)
			{
				$i = $seg;
				if (!($start > $i*M_PI/2 && $y > $yStop)) {
					if ($i == 0) {
						$xp = +1; $yp = -1; $xa = 1; $ya = 0;
					} else {
						$xp = -1; $yp = +1; $xa = 0; $ya = 1;
					}
					if ( $stop < ($i+1)*(M_PI/2) && $y <= $yStop ) {
						$diffColor1 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error1 );
						$x1 = $_x1; if (!$aaStopX) imageSetPixel($img, $cx+$xp*($x1-1)+$xa, $cy+$yp*($y)+$ya, $diffColor1);
					}
					if ($start > $i*M_PI/2 && $y < $yStart) {
						$diffColor2 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error2 );
						$x2 = $_x2; if (!$aaStartX) imageSetPixel($img, $cx+$xp*($x2+1)+$xa, $cy+$yp*($y)+$ya, $diffColor2);
					} else {
						$x = $a * sqrt( 1 - ($y*$y)/($b*$b) );
						$error = $x - (int)($x);
						$x = (int)($x);
						$diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
						$x1 = $x; if ($y < $aaAngleY && $y <= $yStop ) imageSetPixel($img, $cx+$xp*($x1+1)+$xa, $cy+$yp*$y+$ya, $diffColor);
					}
				}
			}

			if ($seg == 1 || $seg == 3)
			{
				$i = $seg;
				if (!($stop < ($i+1)*M_PI/2 && $y > $yStart)) {
					if ($i == 1) {
						$xp = -1; $yp = -1; $xa = 0; $ya = 0;
					} else {
						$xp = +1; $yp = +1; $xa = 1; $ya = 1;
					}
					if ( $start > $i*M_PI/2 && $y < $yStart ) {
						$diffColor2 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error2 );
						$x1 = $_x2; if (!$aaStartX) imageSetPixel($img, $cx+$xp*($x1-1)+$xa, $cy+$yp*$y+$ya,  $diffColor2);
					}
					if ($stop < ($i+1)*M_PI/2 && $y <= $yStop) {
						$diffColor1 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error1 );
						$x2 = $_x1; if (!$aaStopX)  imageSetPixel($img, $cx+$xp*($x2+1)+$xa, $cy+$yp*$y+$ya, $diffColor1);
					} else {
						$x = $a * sqrt( 1 - ($y*$y)/($b*$b) );
						$error = $x - (int)($x);
						$x = (int)($x);
						$diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
						$x1 = $x; if ($y < $aaAngleY  && $y < $yStart) imageSetPixel($img,$cx+$xp*($x1+1)+$xa,  $cy+$yp*$y+$ya, $diffColor);
					}
				}
			}
		}
	}


	private function imageSmoothArc ( &$img, $cx, $cy, $w, $h, $color, $start, $stop)
	{
		// Originally written from scratch by Ulrich Mierendorff, 06/2006
		// Rewritten and improved, 04/2007, 07/2007
		// compared to old version:
		// + Support for transparency added
		// + Improved quality of edges & antialiasing

		// note: This function does not represent the fastest way to draw elliptical
		// arcs. It was written without reading any papers on that subject. Better
		// algorithms may be twice as fast or even more.

		// what it cannot do: It does not support outlined arcs, only filled

		// Parameters:
		// $cx      - Center of ellipse, X-coord
		// $cy      - Center of ellipse, Y-coord
		// $w       - Width of ellipse ($w >= 2)
		// $h       - Height of ellipse ($h >= 2 )
		// $color   - Color of ellipse as a four component array with RGBA
		// $start   - Starting angle of the arc, no limited range!
		// $stop    - Stop     angle of the arc, no limited range!
		// $start _can_ be greater than $stop!
		// If any value is not in the given range, results are undefined!

		// This script does not use any special algorithms, everything is completely
		// written from scratch; see http://de.wikipedia.org/wiki/Ellipse for formulas.

		while ($start < 0)
		$start += 2*M_PI;
		while ($stop < 0)
		$stop += 2*M_PI;

		while ($start > 2*M_PI)
		$start -= 2*M_PI;

		while ($stop > 2*M_PI)
		$stop -= 2*M_PI;


		if ($start > $stop)
		{
			$this -> imageSmoothArc ( $img, $cx, $cy, $w, $h, $color, $start, 2*M_PI);
			$this -> imageSmoothArc ( $img, $cx, $cy, $w, $h, $color, 0, $stop);
			return;
		}

		$a = 1.0*round ($w/2);
		$b = 1.0*round ($h/2);
		$cx = 1.0*round ($cx);
		$cy = 1.0*round ($cy);

		$aaAngle = atan(($b*$b)/($a*$a)*tan(0.25*M_PI));
		$aaAngleX = $a*cos($aaAngle);
		$aaAngleY = $b*sin($aaAngle);

		$a -= 0.5; // looks better...
		$b -= 0.5;

		for ($i=0; $i<4;$i++)
		{
			if ($start < ($i+1)*M_PI/2)
			{
				if ($start > $i*M_PI/2)
				{
					if ($stop > ($i+1)*M_PI/2)
					{
						$this -> imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $aaAngleX, $aaAngleY , $color, $start, ($i+1)*M_PI/2, $i);
					}
					else
					{
						$this -> imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $aaAngleX, $aaAngleY, $color, $start, $stop, $i);
						break;
					}
				}
				else
				{
					if ($stop > ($i+1)*M_PI/2)
					{
						$this -> imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $aaAngleX, $aaAngleY, $color, $i*M_PI/2, ($i+1)*M_PI/2, $i);
					}
					else
					{
						$this -> imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $aaAngleX, $aaAngleY, $color, $i*M_PI/2, $stop, $i);
						break;
					}
				}
			}
		}
	}

	/*

	Copyright (c) 2006-2008 Ulrich Mierendorff

	Permission is hereby granted, free of charge, to any person obtaining a
	copy of this software and associated documentation files (the "Software"),
	to deal in the Software without restriction, including without limitation
	the rights to use, copy, modify, merge, publish, distribute, sublicense,
	and/or sell copies of the Software, and to permit persons to whom the
	Software is furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
	THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
	FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
	DEALINGS IN THE SOFTWARE.

	*/

	private function imageSmoothArcDrawSegmentOptimized (&$img, $cx, $cy, $a, $b, $color, $start, $stop, $seg)
	{
		// Originally written from scratch by Ulrich Mierendorff, 06/2006
		// Rewritten and improved, 04/2007, 07/2007
		// Optimized circle version: 03/2008

		// Please do not use THIS function directly. Scroll down to imageSmoothArc(...).

		$fillColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], $color[3] );
		switch ($seg)
		{
			case 0: $xp = +1; $yp = -1; $xa = 1; $ya = -1; break;
			case 1: $xp = -1; $yp = -1; $xa = 0; $ya = -1; break;
			case 2: $xp = -1; $yp = +1; $xa = 0; $ya = 0; break;
			case 3: $xp = +1; $yp = +1; $xa = 1; $ya = 0; break;
		}
		for ( $x = 0; $x <= $a; $x += 1 ) {
			$y = $b * sqrt( 1 - ($x*$x)/($a*$a) );
			$error = $y - (int)($y);
			$y = (int)($y);
			$diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
			imageSetPixel($img, $cx+$xp*$x+$xa, $cy+$yp*($y+1)+$ya, $diffColor);
			imageLine($img, $cx+$xp*$x+$xa, $cy+$yp*$y+$ya , $cx+$xp*$x+$xa, $cy+$ya, $fillColor);
		}
		for ( $y = 0; $y < $b; $y += 1 ) {
			$x = $a * sqrt( 1 - ($y*$y)/($b*$b) );
			$error = $x - (int)($x);
			$x = (int)($x);
			$diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
			imageSetPixel($img, $cx+$xp*($x+1)+$xa, $cy+$yp*$y+$ya, $diffColor);
		}
	}


	private function imageSmoothArcOptimized ( &$img, $cx, $cy, $w, $h, $color, $start, $stop)
	{
		// Originally written from scratch by Ulrich Mierendorff, 06/2006
		// Rewritten and improved, 04/2007, 07/2007
		// Optimized circle version: 03/2008
		// compared to old version:
		// + Support for transparency added
		// + Improved quality of edges & antialiasing

		// note: This function does not represent the fastest way to draw elliptical
		// arcs. It was written without reading any papers on that subject. Better
		// algorithms may be twice as fast or even more.

		// Parameters:
		// $cx      - Center of ellipse, X-coord
		// $cy      - Center of ellipse, Y-coord
		// $w       - Width of ellipse ($w >= 2)
		// $h       - Height of ellipse ($h >= 2 )
		// $color   - Color of ellipse as a four component array with RGBA
		// $start   - Starting angle of the arc: 0, PI/2, PI, PI/2*3, 2*PI,... (0,90°,180°,270°,360°,...)
		// $stop    - Stop     angle of the arc: 0, PI/2, PI, PI/2*3, 2*PI,... (0,90°,180°,270°,360°,...)
		// $start _can_ be greater than $stop!
		// If any value is not in the given range, results are undefined!

		// This script does not use any special algorithms, everything is completely
		// written from scratch; see http://de.wikipedia.org/wiki/Ellipse for formulas.

		while ($start < 0)
		$start += 2*M_PI;
		while ($stop < 0)
		$stop += 2*M_PI;

		while ($start > 2*M_PI)
		$start -= 2*M_PI;

		while ($stop > 2*M_PI)
		$stop -= 2*M_PI;


		if ($start > $stop)
		{
			$this -> imageSmoothArcOptimized ( &$img, $cx, $cy, $w, $h, $color, $start, 2*M_PI);
			$this -> imageSmoothArcOptimized ( &$img, $cx, $cy, $w, $h, $color, 0, $stop);
			return;
		}

		$a = 1.0*round ($w/2);
		$b = 1.0*round ($h/2);
		$cx = 1.0*round ($cx);
		$cy = 1.0*round ($cy);

		for ($i=0; $i<4;$i++)
		{
			if ($start < ($i+1)*M_PI/2)
			{
				if ($start > $i*M_PI/2)
				{
					if ($stop > ($i+1)*M_PI/2)
					{
						$this -> imageSmoothArcDrawSegmentOptimized($img, $cx, $cy, $a, $b, $color, $start, ($i+1)*M_PI/2, $i);
					}
					else
					{
						$this -> imageSmoothArcDrawSegmentOptimized($img, $cx, $cy, $a, $b, $color, $start, $stop, $i);
						break;
					}
				}
				else
				{
					if ($stop > ($i+1)*M_PI/2)
					{
						$this -> imageSmoothArcDrawSegmentOptimized($img, $cx, $cy, $a, $b, $color, $i*M_PI/2, ($i+1)*M_PI/2, $i);
					}
					else
					{
						$this -> imageSmoothArcDrawSegmentOptimized($img, $cx, $cy, $a, $b, $color, $i*M_PI/2, $stop, $i);
						break;
					}
				}
			}
		}
	}
}
?>
